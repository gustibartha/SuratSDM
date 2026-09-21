"use client";

import { useCallback, useEffect, useState } from "react";
import Link from "next/link";
import { motion } from "motion/react";
import { Plus, Receipt, RefreshCw } from "lucide-react";
import { apiFetch, ApiError } from "@/lib/api";
import type { Kuitansi, PaginatedResponse } from "@/lib/types";

interface SyncSummary {
  imported: number;
  updated: number;
  unchanged: number;
  skipped: number;
  skipped_reasons: string[];
}

const API_URL = process.env.NEXT_PUBLIC_API_URL;

const STATUS_FILTERS = ["Semua", "Diajukan", "Diproses", "Lengkap", "Tidak Lengkap"] as const;

function statusColor(status: string) {
  switch (status) {
    case "Lengkap":
      return "bg-emerald-100 text-emerald-700";
    case "Diproses":
      return "bg-amber-100 text-amber-700";
    case "Tidak Lengkap":
      return "bg-red-100 text-red-700";
    default:
      return "bg-zinc-100 text-zinc-600";
  }
}

function formatRupiah(value: number | null) {
  if (value == null) return "-";
  return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", maximumFractionDigits: 0 }).format(
    value
  );
}

export default function KuitansiListPage() {
  const [status, setStatus] = useState<(typeof STATUS_FILTERS)[number]>("Semua");
  const [page, setPage] = useState(1);
  const [result, setResult] = useState<PaginatedResponse<Kuitansi> | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [syncing, setSyncing] = useState(false);
  const [syncSummary, setSyncSummary] = useState<SyncSummary | null>(null);

  const load = useCallback(() => {
    setError(null);
    const query = status === "Semua" ? "" : `&status=${encodeURIComponent(status)}`;
    apiFetch<PaginatedResponse<Kuitansi>>(`/api/kuitansi?page=${page}${query}`)
      .then(setResult)
      .catch(() => setError("Gagal memuat data kuitansi."));
  }, [page, status]);

  useEffect(() => {
    load();
  }, [load]);

  async function handleSync() {
    setSyncing(true);
    setSyncSummary(null);
    try {
      const res = await apiFetch<SyncSummary>("/api/kuitansi-sync", { method: "POST" });
      setSyncSummary(res);
      load();
    } catch (err) {
      alert(err instanceof ApiError ? err.message : "Gagal sinkronisasi.");
    } finally {
      setSyncing(false);
    }
  }

  return (
    <div className="flex flex-col gap-4">
      <div className="flex items-center justify-between">
        <h1 className="flex items-center gap-2 text-lg font-semibold text-zinc-800">
          <Receipt className="text-cyan-600" size={20} />
          Kwitansi
        </h1>
        <div className="flex gap-2">
          <motion.button
            whileHover={{ scale: 1.03 }}
            whileTap={{ scale: 0.97 }}
            onClick={handleSync}
            disabled={syncing}
            className="flex items-center gap-1.5 rounded-lg bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow ring-1 ring-black/5 hover:bg-zinc-50 disabled:opacity-50"
          >
            <RefreshCw size={16} className={syncing ? "animate-spin" : ""} />
            {syncing ? "Sinkronisasi..." : "Sync dari Spreadsheet"}
          </motion.button>
          <Link href="/kuitansi/new">
            <motion.span
              whileHover={{ scale: 1.03 }}
              whileTap={{ scale: 0.97 }}
              className="flex items-center gap-1.5 rounded-lg bg-cyan-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-cyan-500"
            >
              <Plus size={16} />
              Ajukan Kuitansi
            </motion.span>
          </Link>
        </div>
      </div>

      {syncSummary && (
        <motion.div
          initial={{ opacity: 0, y: -8 }}
          animate={{ opacity: 1, y: 0 }}
          className="rounded-lg bg-cyan-50 px-4 py-3 text-sm text-cyan-900 ring-1 ring-cyan-200"
        >
          Sync selesai: <strong>{syncSummary.imported}</strong> baru,{" "}
          <strong>{syncSummary.updated}</strong> diperbarui, {syncSummary.unchanged} tidak berubah,{" "}
          {syncSummary.skipped} dilewati (tidak ketemu data karyawan/tanggal tidak valid).
        </motion.div>
      )}

      <div className="flex flex-wrap gap-2 text-sm">
        {STATUS_FILTERS.map((s) => (
          <button
            key={s}
            onClick={() => {
              setStatus(s);
              setPage(1);
            }}
            className={`rounded-full px-3 py-1.5 transition ${
              status === s ? "bg-cyan-600 text-white" : "bg-white text-zinc-600 shadow-sm hover:bg-zinc-100"
            }`}
          >
            {s}
          </button>
        ))}
      </div>

      {error && <p className="text-sm text-red-600">{error}</p>}

      <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
        {result?.data.map((k, i) => (
          <motion.div
            key={k.id}
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.25, delay: i * 0.03 }}
          >
            <Link
              href={`/kuitansi/${k.id}`}
              className="flex gap-3 rounded-xl bg-white p-3 shadow-sm ring-1 ring-black/5 transition hover:shadow-md"
            >
              {k.foto_path ? (
                // eslint-disable-next-line @next/next/no-img-element
                <img
                  src={`${API_URL}/kuitansi-foto/${k.foto_path}`}
                  alt="Kuitansi"
                  className="h-20 w-20 shrink-0 rounded-lg object-cover"
                />
              ) : (
                <div className="flex h-20 w-20 shrink-0 items-center justify-center rounded-lg bg-zinc-100 text-zinc-400">
                  <Receipt size={24} />
                </div>
              )}
              <div className="min-w-0 flex-1">
                <p className="truncate text-sm font-semibold text-zinc-800">{k.nama_pasien}</p>
                <p className="truncate text-xs text-zinc-500">{k.rumah_sakit?.nama_rumah_sakit}</p>
                <p className="mt-1 text-sm font-medium text-zinc-700">{formatRupiah(k.nominal)}</p>
                <span
                  className={`mt-1 inline-block rounded px-2 py-0.5 text-xs font-medium ${statusColor(k.status)}`}
                >
                  {k.status}
                </span>
              </div>
            </Link>
          </motion.div>
        ))}
        {result && result.data.length === 0 && (
          <p className="col-span-full py-10 text-center text-sm text-zinc-400">
            Belum ada kuitansi untuk status ini.
          </p>
        )}
      </div>

      {result && result.last_page > 1 && (
        <div className="flex items-center justify-between text-sm text-zinc-600">
          <span>
            Halaman {result.current_page} dari {result.last_page} ({result.total} data)
          </span>
          <div className="flex gap-2">
            <button
              onClick={() => setPage((p) => Math.max(1, p - 1))}
              disabled={result.current_page <= 1}
              className="rounded bg-white px-3 py-1 shadow-sm disabled:opacity-40"
            >
              Sebelumnya
            </button>
            <button
              onClick={() => setPage((p) => Math.min(result.last_page, p + 1))}
              disabled={result.current_page >= result.last_page}
              className="rounded bg-white px-3 py-1 shadow-sm disabled:opacity-40"
            >
              Berikutnya
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
