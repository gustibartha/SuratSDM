"use client";

import { useCallback, useEffect, useState } from "react";
import Link from "next/link";
import { apiFetch, ApiError } from "@/lib/api";
import type { MonitoringTagihan, PaginatedResponse } from "@/lib/types";

function formatRupiah(value: number) {
  return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", maximumFractionDigits: 0 }).format(
    value
  );
}

export default function MonitoringTagihanListPage() {
  const [page, setPage] = useState(1);
  const [result, setResult] = useState<PaginatedResponse<MonitoringTagihan> | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [busyId, setBusyId] = useState<number | null>(null);

  const load = useCallback(() => {
    setError(null);
    apiFetch<PaginatedResponse<MonitoringTagihan>>(`/api/monitoring-tagihan?page=${page}`)
      .then(setResult)
      .catch(() => setError("Gagal memuat data monitoring tagihan."));
  }, [page]);

  useEffect(() => {
    load();
  }, [load]);

  async function handleDelete(id: number) {
    if (!confirm("Hapus data monitoring tagihan ini?")) return;
    setBusyId(id);
    try {
      await apiFetch(`/api/monitoring-tagihan/${id}`, { method: "DELETE" });
      load();
    } catch (err) {
      alert(err instanceof ApiError ? err.message : "Gagal menghapus data.");
    } finally {
      setBusyId(null);
    }
  }

  return (
    <div className="flex flex-col gap-4">
      <div className="flex items-center justify-between">
        <h1 className="text-lg font-semibold text-zinc-800">Monitoring Tagihan</h1>
        <Link
          href="/monitoring-tagihan/new"
          className="rounded bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-500"
        >
          + Tambah Tagihan
        </Link>
      </div>

      {error && <p className="text-sm text-red-600">{error}</p>}

      <div className="overflow-x-auto rounded-lg bg-white shadow-sm">
        <table className="w-full text-left text-sm">
          <thead className="border-b border-zinc-200 text-xs uppercase text-zinc-500">
            <tr>
              <th className="px-4 py-3">Nomor Surat</th>
              <th className="px-4 py-3">Karyawan</th>
              <th className="px-4 py-3">No Tagihan</th>
              <th className="px-4 py-3">Jumlah</th>
              <th className="px-4 py-3">Status</th>
              <th className="px-4 py-3 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-zinc-100">
            {result?.data.map((m) => (
              <tr key={m.id}>
                <td className="px-4 py-3 font-medium text-zinc-800">
                  {m.form_jaminan?.nomor_surat}
                </td>
                <td className="px-4 py-3 text-zinc-600">{m.form_jaminan?.karyawan?.nama_karyawan}</td>
                <td className="px-4 py-3 text-zinc-600">{m.no_tagihan ?? "-"}</td>
                <td className="px-4 py-3 text-zinc-600">{formatRupiah(m.jumlah)}</td>
                <td className="px-4 py-3">
                  <span
                    className={`rounded px-2 py-1 text-xs font-medium ${
                      m.status_pembayaran === "Sudah Di Bayar"
                        ? "bg-emerald-100 text-emerald-700"
                        : "bg-amber-100 text-amber-700"
                    }`}
                  >
                    {m.status_pembayaran}
                  </span>
                </td>
                <td className="px-4 py-3 text-right">
                  <div className="flex justify-end gap-2">
                    <Link
                      href={`/monitoring-tagihan/${m.id}/edit`}
                      className="rounded bg-zinc-100 px-3 py-1 text-zinc-700 hover:bg-zinc-200"
                    >
                      Edit
                    </Link>
                    <button
                      onClick={() => handleDelete(m.id)}
                      disabled={busyId === m.id}
                      className="rounded bg-red-50 px-3 py-1 text-red-600 hover:bg-red-100 disabled:opacity-50"
                    >
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            ))}
            {result && result.data.length === 0 && (
              <tr>
                <td colSpan={6} className="px-4 py-6 text-center text-zinc-400">
                  Tidak ada data.
                </td>
              </tr>
            )}
          </tbody>
        </table>
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
