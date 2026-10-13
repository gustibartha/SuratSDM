<?php

namespace App\Http\Controllers\Api;

use App\Models\Karyawan;
use App\Models\Kuitansi;
use App\Models\KuitansiNameAlias;
use App\Models\RumahSakit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KuitansiSyncController extends ApiController
{
    private const SHEET_ID = '1h3HuONW0D67hfXPo0gD70aA7x9Jxm2jZ20Ugm1LDR04';

    /**
     * gid per tab in the shared "Monitoring Kuitansi Kesehatan" Google Sheet.
     * karyawan_tetap rows carry a NID column; pensiunan rows don't, so those
     * are matched by name only.
     */
    private const TABS = [
        ['gid' => 0, 'status_karyawan' => 'karyawan_tetap'],
        ['gid' => 1023311066, 'status_karyawan' => 'pensiunan'],
    ];

    private const STATUS_MAP = [
        'LENGKAP' => 'Lengkap',
        'TIDAK LENGKAP' => 'Tidak Lengkap',
    ];

    /**
     * The sheet has ~9,500 data rows combined. Doing per-row queries would
     * mean tens of thousands of round-trips, so everything is preloaded
     * into memory once and written back with a handful of bulk queries.
     */
    public function sync()
    {
        $this->authUser();
        set_time_limit(180);

        $summary = ['imported' => 0, 'updated' => 0, 'unchanged' => 0, 'skipped' => 0, 'skipped_reasons' => []];

        $allRows = [];
        foreach (self::TABS as $tab) {
            $rows = $this->fetchSheetRows($tab['gid']);
            if ($rows === null) {
                $summary['skipped_reasons'][] = "Gagal mengambil data untuk gid={$tab['gid']}.";
                continue;
            }
            foreach ($rows as $row) {
                $allRows[] = [$row, $tab['status_karyawan']];
            }
        }

        $karyawanAll = Karyawan::get(['id', 'nid', 'nama_karyawan', 'status_karyawan']);

        $karyawanByNid = $karyawanAll->filter(function ($k) {
            return $k->nid;
        })->keyBy(function ($k) {
            return $k->status_karyawan . '|' . $k->nid;
        });

        // Someone who has since retired has an archived karyawan_tetap row
        // and an active pensiunan row with the same NID, while their older
        // claims still sit in the KARYAWAN tab. NID identifies the person,
        // so fall back to it regardless of status (only if it's unique).
        $karyawanByNidAnyStatus = $karyawanAll->filter(function ($k) {
            return $k->nid;
        })->groupBy('nid')->filter(function ($group) {
            return $group->count() === 1;
        })->map(function ($group) {
            return $group->first();
        });

        // Names in the sheet are typed by hand ("M KAMAL" vs "M. KAMAL",
        // "ALM.CHORIB", "MURTI TUSLI" vs "MURTITUSLI"), so match on a key
        // that ignores titles, punctuation and spacing. A key shared by two
        // different people is dropped rather than guessed between.
        $karyawanByName = $karyawanAll->filter(function ($k) {
            return self::matchKey((string) $k->nama_karyawan) !== '';
        })->groupBy(function ($k) {
            return $k->status_karyawan . '|' . self::matchKey((string) $k->nama_karyawan);
        })->filter(function ($group) {
            return $group->count() === 1;
        })->map(function ($group) {
            return $group->first();
        });

        $karyawanById = $karyawanAll->keyBy('id');
        $aliasTarget = KuitansiNameAlias::get(['status_karyawan', 'alias_key', 'karyawan_id'])
            ->mapWithKeys(function ($a) {
                return [$a->status_karyawan . '|' . $a->alias_key => $a->karyawan_id];
            });

        $unmatched = [];

        $rumahSakitByName = RumahSakit::get(['id', 'nama_rumah_sakit'])
            ->keyBy(function ($r) {
                return strtoupper(trim($r->nama_rumah_sakit));
            });

        // Pass 1: figure out which institutions from the sheet don't exist
        // yet, and bulk-create them so pass 2 can resolve every id in memory.
        $missingInstansi = [];
        foreach ($allRows as [$row, $statusKaryawan]) {
            $instansi = trim($row['NAMA INSTANSI'] ?? '');
            if ($instansi !== '' && ! $rumahSakitByName->has(strtoupper($instansi))) {
                $missingInstansi[strtoupper($instansi)] = $instansi;
            }
        }
        if (! empty($missingInstansi)) {
            $now = now();
            $insertRows = array_map(function ($nama) use ($now) {
                return ['nama_rumah_sakit' => $nama, 'created_at' => $now, 'updated_at' => $now];
            }, array_values($missingInstansi));
            foreach (array_chunk($insertRows, 500) as $chunk) {
                DB::table('rumah_sakits')->insert($chunk);
            }
            $rumahSakitByName = RumahSakit::get(['id', 'nama_rumah_sakit'])
                ->keyBy(function ($r) {
                    return strtoupper(trim($r->nama_rumah_sakit));
                });
        }

        $existingKuitansi = Kuitansi::get(['id', 'karyawan_id', 'nama_pasien', 'tanggal_kuitansi', 'id_rumah_sakit', 'status', 'catatan'])
            ->keyBy(function ($k) {
                return $k->karyawan_id . '|' . $k->nama_pasien . '|' . $k->tanggal_kuitansi . '|' . $k->id_rumah_sakit;
            });

        $resolved = [];
        $now = now();

        // Pass 2: resolve every row against the in-memory maps only. The
        // sheet has occasional duplicate rows for the same person/date/
        // hospital with a different status (e.g. corrected later) — keyed
        // assignment below means whichever occurs LAST in the sheet wins,
        // deterministically, the same way on every run.
        foreach ($allRows as [$row, $statusKaryawan]) {
            $namaPasienRaw = $row['NAMA PASIEN'] ?? '';
            $namaKaryawanRaw = $row[$statusKaryawan === 'pensiunan' ? 'NAMA PENSIUNAN' : 'NAMA KARYAWAN'] ?? '';
            $tanggalRaw = $row['TANGGAL KUITANSI'] ?? '';
            $instansi = trim($row['NAMA INSTANSI'] ?? '');

            if ($namaKaryawanRaw === '' || $tanggalRaw === '') {
                continue;
            }

            $matchKey = self::matchKey($namaKaryawanRaw);
            $nameKey = $statusKaryawan . '|' . $matchKey;
            $nid = trim($row['NID'] ?? '');
            if (strpos($nid, '#') === 0) {
                $nid = ''; // spreadsheet formula errors like "#N/A"
            }

            $karyawan = $nid !== ''
                ? ($karyawanByNid[$statusKaryawan . '|' . $nid] ?? $karyawanByNidAnyStatus[$nid] ?? null)
                : null;

            if (! $karyawan && $matchKey !== '') {
                $karyawan = $karyawanByName[$nameKey] ?? null;
            }

            if (! $karyawan && $matchKey !== '' && isset($aliasTarget[$nameKey])) {
                $karyawan = $karyawanById[$aliasTarget[$nameKey]] ?? null;
            }

            if (! $karyawan) {
                $summary['skipped']++;
                if (! isset($unmatched[$nameKey])) {
                    $unmatched[$nameKey] = [
                        'name' => $this->stripTitle($namaKaryawanRaw),
                        'status_karyawan' => $statusKaryawan,
                        'nid' => $nid !== '' ? $nid : null,
                        'rows' => 0,
                    ];
                }
                $unmatched[$nameKey]['rows']++;
                continue;
            }

            $tanggal = $this->parseTanggal($tanggalRaw);
            if (! $tanggal) {
                $summary['skipped']++;
                $summary['skipped_reasons'][] = "Tanggal tidak valid untuk {$namaKaryawanRaw}: {$tanggalRaw}";
                continue;
            }

            $namaPasien = $this->stripTitle($namaPasienRaw !== '' ? $namaPasienRaw : $namaKaryawanRaw);
            $isSelf = $this->stripTitle($namaKaryawanRaw) === $namaPasien;
            $idRumahSakit = $instansi !== '' ? ($rumahSakitByName[strtoupper($instansi)]->id ?? null) : null;
            $statusLabel = self::STATUS_MAP[strtoupper(trim($row['STATUS'] ?? ''))] ?? 'Diajukan';
            $catatan = trim($row['KETERANGAN'] ?? '') ?: null;

            $key = $karyawan->id . '|' . $namaPasien . '|' . $tanggal . '|' . $idRumahSakit;

            $resolved[$key] = [
                'karyawan_id' => $karyawan->id,
                'nama_pasien' => $namaPasien,
                'hubungan_keluarga' => $isSelf ? 'Ybs' : 'Keluarga',
                'id_rumah_sakit' => $idRumahSakit,
                'tanggal_kuitansi' => $tanggal,
                'status' => $statusLabel,
                'catatan' => $catatan,
            ];
        }

        // Pass 3: diff the final resolved value per key against the DB once
        // — this is what makes repeated syncs idempotent even when the
        // sheet itself contains conflicting duplicate rows for a key.
        $toInsert = [];
        foreach ($resolved as $key => $attributes) {
            $existing = $existingKuitansi->get($key);

            if (! $existing) {
                $toInsert[] = $attributes + ['created_at' => $now, 'updated_at' => $now];
                continue;
            }

            if ($existing->status !== $attributes['status'] || $existing->catatan !== $attributes['catatan']) {
                Kuitansi::where('id', $existing->id)->update([
                    'status' => $attributes['status'],
                    'catatan' => $attributes['catatan'],
                    'updated_at' => $now,
                ]);
                $summary['updated']++;
            } else {
                $summary['unchanged']++;
            }
        }

        foreach (array_chunk($toInsert, 500) as $chunk) {
            DB::table('kuitansis')->insert($chunk);
        }
        $summary['imported'] = count($toInsert);
        $summary['skipped_reasons'] = array_slice(array_unique($summary['skipped_reasons']), 0, 30);
        $summary['unmatched'] = $this->withSuggestions(array_values($unmatched), $karyawanAll);

        return response()->json($summary);
    }

    /**
     * Ranks the closest karyawan for each unmatched sheet name, across both
     * statuses (a claim in the KARYAWAN tab may belong to someone who has
     * since retired). Same-status candidates win ties. These are only
     * suggestions: an admin confirms each one, since near-identical names
     * ("SUMARTO" / "SUHARTO") can be different people.
     */
    private function withSuggestions(array $unmatched, $karyawanAll): array
    {
        foreach ($unmatched as &$item) {
            $needle = self::matchKey($item['name']);
            $status = $item['status_karyawan'];
            $item['suggestions'] = $karyawanAll
                ->map(function ($k) use ($needle, $status) {
                    $distance = levenshtein($needle, self::matchKey((string) $k->nama_karyawan));

                    return [
                        'id' => $k->id,
                        'nama_karyawan' => $k->nama_karyawan,
                        'nid' => $k->nid,
                        'status_karyawan' => $k->status_karyawan,
                        'distance' => $distance,
                        'rank' => $distance * 2 + ($k->status_karyawan === $status ? 0 : 1),
                    ];
                })
                ->sortBy('rank')
                ->take(3)
                ->values()
                ->all();
        }
        unset($item);

        usort($unmatched, function ($a, $b) {
            return $b['rows'] <=> $a['rows'];
        });

        return $unmatched;
    }

    /**
     * Key for matching a hand-typed sheet name to a karyawan record: leading
     * titles removed, then everything but letters dropped, so spacing and
     * punctuation differences don't matter.
     */
    public static function matchKey(string $name): string
    {
        $name = strtoupper(trim($name));
        do {
            $before = $name;
            $name = preg_replace('/^(NY|TN|SDR|SDRI|ALM|ALMH|ALMARHUM|ALMARHUMAH|IR|DRS|DRA|DR|HJ|H)\b\.?\s*/', '', $name);
        } while ($name !== $before);

        return preg_replace('/[^A-Z]/', '', $name);
    }

    /**
     * Downloads the tab as CSV (the sheet is shared as "anyone with the
     * link can view", so no OAuth/service account is needed) and returns
     * the data rows as associative arrays keyed by the sheet's own headers.
     */
    private function fetchSheetRows(int $gid): ?array
    {
        $url = 'https://docs.google.com/spreadsheets/d/' . self::SHEET_ID . '/export?format=csv&gid=' . $gid;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
        ]);
        $csv = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (! $csv || $httpCode >= 400) {
            return null;
        }

        // Some headers wrap onto a second physical line inside a quoted
        // CSV field (e.g. "STATUS\n(LENGKAP/ TIDAK LENGKAP)"). Splitting on
        // "\n" first (like explode()) would break those rows apart, so this
        // reads the CSV through fgetcsv() on an in-memory stream, which
        // correctly treats a quoted embedded newline as part of one field.
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $csv);
        rewind($stream);

        $lines = [];
        while (($line = fgetcsv($stream)) !== false) {
            $lines[] = $line;
        }
        fclose($stream);

        // The sheet has a couple of title/instruction rows before the real
        // header row — find it by looking for the "NO" column.
        $headerIndex = null;
        foreach ($lines as $i => $line) {
            if (isset($line[0]) && trim($line[0]) === 'NO') {
                $headerIndex = $i;
                break;
            }
        }

        if ($headerIndex === null) {
            return null;
        }

        $headers = array_map(function ($h) {
            $h = trim(preg_replace('/\s+/', ' ', $h));
            // A few headers wrap onto a second line in the sheet itself
            // (e.g. "STATUS\n(LENGKAP/ TIDAK LENGKAP)") — collapse those
            // back to their first word so lookups by plain key still work.
            if (preg_match('/^STATUS\b/i', $h)) {
                return 'STATUS';
            }
            return $h;
        }, $lines[$headerIndex]);

        $rows = [];
        for ($i = $headerIndex + 1; $i < count($lines); $i++) {
            $line = $lines[$i];
            if (empty($line[0]) && empty($line[1])) {
                continue;
            }
            $row = [];
            foreach ($headers as $col => $name) {
                if ($name === '') {
                    continue;
                }
                $row[$name] = trim($line[$col] ?? '');
            }
            $rows[] = $row;
        }

        return $rows;
    }

    private function stripTitle(string $name): string
    {
        $name = preg_replace('/^(TN\.|NY\.|SDR\.|SDRI\.)\s*/i', '', trim($name));

        return trim($name);
    }

    private function parseTanggal(string $value): ?string
    {
        $value = trim($value);
        foreach (['d/m/Y', 'd-m-Y', 'Y-m-d'] as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
                if ($date) {
                    return $date->format('Y-m-d');
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return null;
    }
}
