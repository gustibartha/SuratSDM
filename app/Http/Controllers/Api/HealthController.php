<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Pinged on a schedule (.github/workflows/keepalive.yml) so the free-tier
 * InsForge database and the scale-to-zero backend see regular activity.
 * Runs a real query, so it fails (503) when the database is unreachable.
 */
class HealthController extends Controller
{
    public function show()
    {
        try {
            DB::select('SELECT 1');
        } catch (\Throwable $e) {
            return response()->json(['status' => 'db_unreachable'], 503);
        }

        return response()->json(['status' => 'ok']);
    }
}
