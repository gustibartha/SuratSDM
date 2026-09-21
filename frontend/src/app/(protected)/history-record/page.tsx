"use client";

import { useCallback, useEffect, useState } from "react";
import Link from "next/link";
import { apiFetch, ApiError } from "@/lib/api";
import type { HistoryRecord, PaginatedResponse } from "@/lib/types";

export default function HistoryRecordListPage() {
  const [page, setPage] = useState(1);
  const [result, setResult] = useState<PaginatedResponse<HistoryRecord> | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [busyId, setBusyId] = useState<number | null>(null);

  const load = useCallback(() => {
    setError(null);
    apiFetch<PaginatedResponse<HistoryRecord>>(`/api/history-record?page=${page}`)
      .then(setResult)
      .catch(() => setError("Gagal memuat data history record."));
  }, [page]);

  useEffect(() => {
    load();
  }, [load]);

  async function handleDelete(id: number) {
    if (!confirm("Hapus history record ini?")) return;
    setBusyId(id);
    try {
      await apiFetch(`/api/history-record/${id}`, { method: "DELETE" });
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
        <h1 className="text-lg font-semibold text-zinc-800">History Record</h1>
        <Link
          href="/history-record/new"
          className="rounded bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-500"
        >
          + Tambah Record
        </Link>
      </div>

      {error && <p className="text-sm text-red-600">{error}</p>}

      <div className="overflow-x-auto rounded-lg bg-white shadow-sm">
        <table className="w-full text-left text-sm">
          <thead className="border-b border-zinc-200 text-xs uppercase text-zinc-500">
            <tr>
              <th className="px-4 py-3">Karyawan</th>
              <th className="px-4 py-3">Riwayat Penyakit</th>
              <th className="px-4 py-3">Jenis Pengobatan</th>
              <th className="px-4 py-3 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-zinc-100">
            {result?.data.map((r) => (
              <tr key={r.id}>
                <td className="px-4 py-3 font-medium text-zinc-800">{r.karyawan?.nama_karyawan}</td>
                <td className="max-w-xs truncate px-4 py-3 text-zinc-600">{r.riwayat_penyakit ?? "-"}</td>
                <td className="max-w-xs truncate px-4 py-3 text-zinc-600">{r.jenis_pengobatan ?? "-"}</td>
                <td className="px-4 py-3 text-right">
                  <div className="flex justify-end gap-2">
                    <Link
                      href={`/history-record/${r.id}/edit`}
                      className="rounded bg-zinc-100 px-3 py-1 text-zinc-700 hover:bg-zinc-200"
                    >
                      Edit
                    </Link>
                    <button
                      onClick={() => handleDelete(r.id)}
                      disabled={busyId === r.id}
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
                <td colSpan={4} className="px-4 py-6 text-center text-zinc-400">
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
