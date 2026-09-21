<?php

namespace App\Http\Controllers\Api;

use App\Models\Karyawan;
use App\Models\Kuitansi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KuitansiController extends ApiController
{
    private const STATUSES = ['Diajukan', 'Diproses', 'Lengkap', 'Tidak Lengkap'];

    /**
     * Public name search to help someone find themselves when they don't
     * remember their exact NID. Returns a short list with a masked NID
     * (for self-recognition only) — never the full NID or any kuitansi
     * data — the real lookup still happens through publicStatus().
     */
    public function publicSearchByName(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|min:3|max:255',
            'status_karyawan' => 'required|in:karyawan_tetap,pensiunan',
        ]);

        // LIKE's case-sensitivity depends on the DB driver/collation (MySQL:
        // insensitive by default, Postgres: sensitive) — LOWER() on both
        // sides keeps this search behaving the same on either database.
        $matches = Karyawan::where('status_karyawan', $data['status_karyawan'])
            ->whereRaw('LOWER(nama_karyawan) LIKE ?', ['%' . strtolower($data['nama']) . '%'])
            ->orderBy('nama_karyawan')
            ->limit(8)
            ->get(['id', 'nama_karyawan', 'jabatan', 'nid']);

        return response()->json([
            'data' => $matches->map(function ($k) {
                return [
                    'id' => $k->id,
                    'nama_karyawan' => $k->nama_karyawan,
                    'jabatan' => $k->jabatan,
                    'nid_masked' => self::maskNid($k->nid),
                ];
            }),
        ]);
    }

    private static function maskNid(?string $nid): string
    {
        if (! $nid || strlen($nid) <= 4) {
            return str_repeat('*', strlen($nid ?? ''));
        }

        return substr($nid, 0, 2) . str_repeat('*', strlen($nid) - 4) . substr($nid, -2);
    }

    /**
     * Public, unauthenticated status lookup for an employee/pensioner
     * checking their own kuitansi — matches by NID, or by karyawan_id after
     * they picked themselves from publicSearchByName(). Never accepts a
     * bare name here, since that would skip the self-recognition step.
     */
    public function publicStatus(Request $request)
    {
        $data = $request->validate([
            'nid' => 'nullable|string|max:255',
            'karyawan_id' => 'nullable|integer',
            'status_karyawan' => 'required|in:karyawan_tetap,pensiunan',
        ]);

        if (empty($data['nid']) && empty($data['karyawan_id'])) {
            return response()->json(['message' => 'NID wajib diisi.'], 422);
        }

        $karyawan = ! empty($data['karyawan_id'])
            ? Karyawan::where('id', $data['karyawan_id'])
                ->where('status_karyawan', $data['status_karyawan'])
                ->first()
            : Karyawan::where('nid', $data['nid'])
                ->where('status_karyawan', $data['status_karyawan'])
                ->first();

        if (! $karyawan) {
            $label = $data['status_karyawan'] === 'pensiunan' ? 'pensiunan' : 'karyawan';
            return response()->json(['message' => "NID tidak ditemukan pada data {$label}."], 404);
        }

        $kuitansi = Kuitansi::with('rumahSakit')
            ->where('karyawan_id', $karyawan->id)
            ->latest()
            ->get([
                'id', 'nama_pasien', 'hubungan_keluarga', 'id_rumah_sakit',
                'nominal', 'tanggal_kuitansi', 'status', 'catatan', 'created_at',
            ]);

        return response()->json([
            'karyawan' => [
                'nama_karyawan' => $karyawan->nama_karyawan,
                'status_karyawan' => $karyawan->status_karyawan,
            ],
            'data' => $kuitansi,
        ]);
    }

    public function index(Request $request)
    {
        $this->authUser();

        $query = Kuitansi::with(['karyawan', 'rumahSakit', 'creator']);

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        return response()->json($query->latest()->paginate(25));
    }

    public function show($id)
    {
        $this->authUser();

        $kuitansi = Kuitansi::with(['karyawan', 'rumahSakit', 'creator'])->findOrFail($id);

        return response()->json(['kuitansi' => $kuitansi]);
    }

    /**
     * Read a just-selected receipt photo and ask Claude vision to pull out
     * the total amount and date, without persisting anything yet. The
     * frontend shows these as editable suggestions before the real submit.
     */
    public function extract(Request $request)
    {
        $this->authUser();

        $request->validate([
            'foto' => 'required|image|max:8192',
        ]);

        $file = $request->file('foto');
        $apiKey = config('services.anthropic.key');

        if (! $apiKey) {
            return response()->json([
                'message' => 'Fitur baca otomatis belum dikonfigurasi (API key belum diset).',
            ], 503);
        }

        $base64 = base64_encode(file_get_contents($file->getRealPath()));
        $mimeType = $file->getMimeType() ?: 'image/jpeg';

        $payload = [
            'model' => 'claude-haiku-4-5-20251001',
            'max_tokens' => 300,
            'messages' => [[
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'image',
                        'source' => [
                            'type' => 'base64',
                            'media_type' => $mimeType,
                            'data' => $base64,
                        ],
                    ],
                    [
                        'type' => 'text',
                        'text' => 'Ini adalah foto kuitansi/struk berobat berbahasa Indonesia. Baca nominal total '
                            . 'pembayaran dan tanggal pada kuitansi ini. Balas HANYA dengan JSON tanpa teks lain, '
                            . 'format persis: {"nominal": <angka rupiah tanpa simbol atau titik/koma, atau null '
                            . 'jika tidak terbaca>, "tanggal": "<YYYY-MM-DD, atau null jika tidak terbaca>"}',
                    ],
                ],
            ]],
        ];

        $ch = curl_init('https://api.anthropic.com/v1/messages');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'x-api-key: ' . $apiKey,
                'anthropic-version: 2023-06-01',
                'content-type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 30,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError || $httpCode >= 400) {
            return response()->json([
                'message' => 'Gagal membaca foto kuitansi. Silakan isi manual.',
                'detail' => $curlError ?: $response,
            ], 502);
        }

        $decoded = json_decode($response, true);
        $text = $decoded['content'][0]['text'] ?? '';

        if (! preg_match('/\{.*\}/s', $text, $matches)) {
            return response()->json([
                'message' => 'Tidak bisa membaca data dari foto. Silakan isi manual.',
            ], 422);
        }

        $extracted = json_decode($matches[0], true);

        return response()->json([
            'nominal' => $extracted['nominal'] ?? null,
            'tanggal_kuitansi' => $extracted['tanggal'] ?? null,
        ]);
    }

    public function store(Request $request)
    {
        $user = $this->authUser();

        $data = $request->validate([
            'karyawan_id' => 'required|integer|exists:karyawans,id',
            'nama_pasien' => 'required|string|max:255',
            'hubungan_keluarga' => 'required|string|max:255',
            'id_rumah_sakit' => 'required|integer|exists:rumah_sakits,id',
            'nominal' => 'required|integer|min:0',
            'tanggal_kuitansi' => 'required|date',
            'diagnosa' => 'nullable|string',
            'foto' => 'required|image|max:8192',
        ]);

        $file = $request->file('foto');
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('kuitansi-foto'), $filename);

        unset($data['foto']);

        $kuitansi = Kuitansi::create(array_merge($data, [
            'foto_path' => $filename,
            'created_by' => $user->id,
            'status' => 'Diajukan',
        ]));

        return response()->json([
            'kuitansi' => $kuitansi->fresh()->load(['karyawan', 'rumahSakit', 'creator']),
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $this->authUser();

        $kuitansi = Kuitansi::findOrFail($id);

        $data = $request->validate([
            'status' => 'required|in:' . implode(',', self::STATUSES),
            'catatan' => 'nullable|string',
        ]);

        $kuitansi->update($data);

        return response()->json([
            'kuitansi' => $kuitansi->load(['karyawan', 'rumahSakit', 'creator']),
        ]);
    }

    public function destroy($id)
    {
        $this->authUser();

        $kuitansi = Kuitansi::findOrFail($id);

        if ($kuitansi->foto_path) {
            @unlink(public_path('kuitansi-foto/' . $kuitansi->foto_path));
        }

        $kuitansi->delete();

        return response()->json(['message' => 'deleted']);
    }
}
