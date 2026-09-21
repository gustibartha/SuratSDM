"use client";

import { useEffect, useState, useCallback } from "react";
import Link from "next/link";
import { apiFetch, ApiError } from "@/lib/api";
import type { Karyawan, PaginatedResponse } from "@/lib/types";

export default function KaryawanListPage() {
  const [status, setStatus] = useState<"karyawan_tetap" | "pensiunan">("karyawan_tetap");
  const [page, setPage] = useState(1);
  const [result, setResult] = useState<PaginatedResponse<Karyawan> | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [busyId, setBusyId] = useState<number | null>(null);

  const load = useCallback(() => {
    setError(null);
    apiFetch<PaginatedResponse<Karyawan>>(
      `/api/karyawan?status_karyawan=${status}&page=${page}`
    )
      .then(setResult)
      .catch(() => setError("Gagal memuat data karyawan."));
  }, [status, page]);

  useEffect(() => {
    load();
  }, [load]);

  async function handleDelete(id: number) {
    if (!confirm("Hapus data karyawan ini?")) return;
    setBusyId(id);
    try {
      await apiFetch(`/api/karyawan/${id}`, { method: "DELETE" });
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
        <h1 className="text-lg font-semibold text-zinc-800">Master Data Karyawan</h1>
        <Link
          href="/karyawan/new"
          className="rounded bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-500"
        >
          + Tambah Karyawan
        </Link>
      </div>

      <div className="flex gap-2 text-sm">
        {(["karyawan_tetap", "pensiunan"] as const).map((s) => (
          <button
            key={s}
            onClick={() => {
              setStatus(s);
              setPage(1);
            }}
            className={`rounded px-3 py-1.5 ${
              status === s
                ? "bg-cyan-600 text-white"
                : "bg-white text-zinc-600 hover:bg-zinc-100"
            }`}
          >
            {s === "karyawan_tetap" ? "Karyawan Tetap" : "Pensiunan"}
          </button>
        ))}
      </div>

      {error && <p className="text-sm text-red-600">{error}</p>}

      <div className="overflow-x-auto rounded-lg bg-white shadow-sm">
        <table className="w-full text-left text-sm">
          <thead className="border-b border-zinc-200 text-xs uppercase text-zinc-500">
            <tr>
              <th className="px-4 py-3">Nama</th>
              <th className="px-4 py-3">NID</th>
              <th className="px-4 py-3">Jabatan</th>
              <th className="px-4 py-3">Kelas Rawat Inap</th>
              <th className="px-4 py-3 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-zinc-100">
            {result?.data.map((k) => (
              <tr key={k.id}>
                <td className="px-4 py-3 font-medium text-zinc-800">
                  {k.nama_karyawan}
                </td>
                <td className="px-4 py-3 text-zinc-600">{k.nid}</td>
                <td className="px-4 py-3 text-zinc-600">{k.jabatan}</td>
                <td className="px-4 py-3 text-zinc-600">
                  {k.kelas_rawat_inap?.jenis_kelas ?? "-"}
                </td>
                <td className="px-4 py-3 text-right">
                  <div className="flex justify-end gap-2">
                    <Link
                      href={`/karyawan/${k.id}/edit`}
                      className="rounded bg-zinc-100 px-3 py-1 text-zinc-700 hover:bg-zinc-200"
                    >
                      Edit
                    </Link>
                    <button
                      onClick={() => handleDelete(k.id)}
                      disabled={busyId === k.id}
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
                <td colSpan={5} className="px-4 py-6 text-center text-zinc-400">
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
