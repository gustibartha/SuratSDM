"use client";

import { useCallback, useEffect, useState } from "react";
import Link from "next/link";
import { apiFetch, ApiError } from "@/lib/api";
import type { PaginatedResponse, Visa } from "@/lib/types";

const API_URL = process.env.NEXT_PUBLIC_API_URL;

function statusColor(status: string) {
  if (status.startsWith("Sudah Disetujui")) return "bg-emerald-100 text-emerald-700";
  if (status.startsWith("Menunggu")) return "bg-amber-100 text-amber-700";
  return "bg-zinc-100 text-zinc-600";
}

export default function VisaListPage() {
  const [page, setPage] = useState(1);
  const [result, setResult] = useState<PaginatedResponse<Visa> | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [busyId, setBusyId] = useState<number | null>(null);

  const load = useCallback(() => {
    setError(null);
    apiFetch<PaginatedResponse<Visa>>(`/api/visa?page=${page}`)
      .then(setResult)
      .catch(() => setError("Gagal memuat data visa."));
  }, [page]);

  useEffect(() => {
    load();
  }, [load]);

  async function handleDelete(id: number) {
    if (!confirm("Hapus surat visa ini?")) return;
    setBusyId(id);
    try {
      await apiFetch(`/api/visa/${id}`, { method: "DELETE" });
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
        <h1 className="text-lg font-semibold text-zinc-800">Surat Visa</h1>
        <Link
          href="/visa/new"
          className="rounded bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-500"
        >
          + Buat Surat Visa
        </Link>
      </div>

      {error && <p className="text-sm text-red-600">{error}</p>}

      <div className="overflow-x-auto rounded-lg bg-white shadow-sm">
        <table className="w-full text-left text-sm">
          <thead className="border-b border-zinc-200 text-xs uppercase text-zinc-500">
            <tr>
              <th className="px-4 py-3">Nomor Surat</th>
              <th className="px-4 py-3">Karyawan</th>
              <th className="px-4 py-3">Negara Tujuan</th>
              <th className="px-4 py-3">Keluarga</th>
              <th className="px-4 py-3">Status</th>
              <th className="px-4 py-3 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-zinc-100">
            {result?.data.map((v) => (
              <tr key={v.id}>
                <td className="px-4 py-3 font-medium text-zinc-800">{v.nomor_surat}</td>
                <td className="px-4 py-3 text-zinc-600">{v.karyawan?.nama_karyawan}</td>
                <td className="px-4 py-3 text-zinc-600">{v.negara_tujuan ?? "-"}</td>
                <td className="px-4 py-3 text-zinc-600">
                  {v.keluarga.length > 0 ? `${v.keluarga.length} orang` : "-"}
                </td>
                <td className="px-4 py-3">
                  <span className={`rounded px-2 py-1 text-xs font-medium ${statusColor(v.status)}`}>
                    {v.status}
                  </span>
                </td>
                <td className="px-4 py-3 text-right">
                  <div className="flex flex-wrap justify-end gap-2">
                    {v.file && (
                      <a
                        href={`${API_URL}/surat-visa-pdf/${v.file}`}
                        target="_blank"
                        rel="noreferrer"
                        className="rounded bg-emerald-50 px-3 py-1 text-emerald-700 hover:bg-emerald-100"
                      >
                        PDF
                      </a>
                    )}
                    <Link
                      href={`/visa/${v.id}/edit`}
                      className="rounded bg-zinc-100 px-3 py-1 text-zinc-700 hover:bg-zinc-200"
                    >
                      Edit
                    </Link>
                    <button
                      onClick={() => handleDelete(v.id)}
                      disabled={busyId === v.id}
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
