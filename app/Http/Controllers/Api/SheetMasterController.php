<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;

/**
 * CSV of active karyawan/pensiunan and their family, pulled into the
 * kuitansi Google Sheet with =IMPORTDATA() to feed its dropdowns, so names
 * there are always spelled exactly as in Master Data. Google fetches it
 * from its own servers without a login, so it's guarded by a shared key
 * (SHEET_MASTER_KEY) instead of a session.
 */
class SheetMasterController extends Controller
{
    private const PLACEHOLDERS = ['-', '0', '.', 'X'];

    public function show(Request $request)
    {
        $key = (string) config('services.sheet_master.key');
        if ($key === '' || ! hash_equals($key, (string) $request->query('key'))) {
            abort(404);
        }

        $status = $request->query('status');
        if (! in_array($status, ['karyawan_tetap', 'pensiunan'], true)) {
            abort(404);
        }

        $people = Karyawan::where('status_karyawan', $status)
            ->orderBy('nama_karyawan')
            ->get(['nama_karyawan', 'nid', 'istri', 'anak_1', 'anak_2', 'anak_3']);

        $out = fopen('php://temp', 'r+');
        fputcsv($out, ['NAMA', 'NID', 'ISTRI/SUAMI', 'ANAK 1', 'ANAK 2', 'ANAK 3']);
        foreach ($people as $p) {
            $nama = $this->clean($p->nama_karyawan);
            if ($nama === '') {
                continue;
            }
            fputcsv($out, [
                $nama,
                $this->clean($p->nid),
                $this->clean($p->istri),
                $this->clean($p->anak_1),
                $this->clean($p->anak_2),
                $this->clean($p->anak_3),
            ]);
        }
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store',
        ]);
    }

    private function clean($value): string
    {
        $value = trim(preg_replace('/\s+/', ' ', (string) $value));

        return in_array(strtoupper($value), self::PLACEHOLDERS, true) ? '' : $value;
    }
}
