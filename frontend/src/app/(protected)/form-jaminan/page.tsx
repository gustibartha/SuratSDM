"use client";

import { useCallback, useEffect, useState } from "react";
import Link from "next/link";
import { apiFetch, ApiError } from "@/lib/api";
import type { FormJaminan, PaginatedResponse } from "@/lib/types";

const API_URL = process.env.NEXT_PUBLIC_API_URL;

function statusColor(status: string) {
  if (status.startsWith("Sudah Disetujui")) return "bg-emerald-100 text-emerald-700";
  if (status.startsWith("Menunggu")) return "bg-amber-100 text-amber-700";
  return "bg-zinc-100 text-zinc-600";
}

export default function FormJaminanListPage() {
  const [statusKaryawan, setStatusKaryawan] = useState<"karyawan_tetap" | "pensiunan">("karyawan_tetap");
  const [jenisSurat, setJenisSurat] = useState<"personal" | "keluarga">("personal");
  const [page, setPage] = useState(1);
  const [result, setResult] = useState<PaginatedResponse<FormJaminan> | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [busyId, setBusyId] = useState<number | null>(null);

  const load = useCallback(() => {
    setError(null);
    apiFetch<PaginatedResponse<FormJaminan>>(
      `/api/form-jaminan?status_karyawan=${statusKaryawan}&jenis_surat=${jenisSurat}&page=${page}`
    )
      .then(setResult)
      .catch(() => setError("Gagal memuat data surat jaminan."));
  }, [statusKaryawan, jenisSurat, page]);

  useEffect(() => {
    load();
  }, [load]);

  async function handleDelete(id: number) {
    if (!confirm("Hapus surat jaminan ini?")) return;
    setBusyId(id);
    try {
      await apiFetch(`/api/form-jaminan/${id}`, { method: "DELETE" });
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
        <h1 className="text-lg font-semibold text-zinc-800">Form Jaminan</h1>
        <Link
          href="/form-jaminan/new"
          className="rounded bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-500"
        >
          + Buat Surat Jaminan
        </Link>
      </div>

      <div className="flex flex-wrap gap-2 text-sm">
        {(["karyawan_tetap", "pensiunan"] as const).map((s) => (
          <button
            key={s}
            onClick={() => {
              setStatusKaryawan(s);
              setPage(1);
            }}
            className={`rounded px-3 py-1.5 ${
              statusKaryawan === s ? "bg-cyan-600 text-white" : "bg-white text-zinc-600 hover:bg-zinc-100"
            }`}
          >
            {s === "karyawan_tetap" ? "Karyawan Tetap" : "Pensiunan"}
          </button>
        ))}
        <span className="mx-1 self-center text-zinc-300">|</span>
        {(["personal", "keluarga"] as const).map((j) => (
          <button
            key={j}
            onClick={() => {
              setJenisSurat(j);
              setPage(1);
            }}
            className={`rounded px-3 py-1.5 capitalize ${
              jenisSurat === j ? "bg-amber-500 text-white" : "bg-white text-zinc-600 hover:bg-zinc-100"
            }`}
          >
            {j}
          </button>
        ))}
      </div>

      {error && <p className="text-sm text-red-600">{error}</p>}

      <div className="overflow-x-auto rounded-lg bg-white shadow-sm">
        <table className="w-full text-left text-sm">
          <thead className="border-b border-zinc-200 text-xs uppercase text-zinc-500">
            <tr>
              <th className="px-4 py-3">Nomor Surat</th>
              <th className="px-4 py-3">Karyawan</th>
              <th className="px-4 py-3">Pasien</th>
              <th className="px-4 py-3">Rumah Sakit</th>
              <th className="px-4 py-3">Status</th>
              <th className="px-4 py-3 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-zinc-100">
            {result?.data.map((f) => (
              <tr key={f.id}>
                <td className="px-4 py-3 font-medium text-zinc-800">{f.nomor_surat}</td>
                <td className="px-4 py-3 text-zinc-600">{f.karyawan?.nama_karyawan}</td>
                <td className="px-4 py-3 text-zinc-600">
                  {f.nama_pasien}
                  <span className="ml-1 text-xs text-zinc-400">({f.hubungan_keluarga})</span>
                </td>
                <td className="px-4 py-3 text-zinc-600">{f.rumah_sakit?.nama_rumah_sakit}</td>
                <td className="px-4 py-3">
                  <span className={`rounded px-2 py-1 text-xs font-medium ${statusColor(f.status_pengajuan)}`}>
                    {f.status_pengajuan}
                  </span>
                </td>
                <td className="px-4 py-3 text-right">
                  <div className="flex flex-wrap justify-end gap-2">
                    {f.file_pdf && (
                      <a
                        href={`${API_URL}/generate-pdf/${f.file_pdf}`}
                        target="_blank"
                        rel="noreferrer"
                        className="rounded bg-emerald-50 px-3 py-1 text-emerald-700 hover:bg-emerald-100"
                      >
                        PDF
                      </a>
                    )}
                    <Link
                      href={`/form-jaminan/${f.id}/edit`}
                      className="rounded bg-zinc-100 px-3 py-1 text-zinc-700 hover:bg-zinc-200"
                    >
                      Edit
                    </Link>
                    <button
                      onClick={() => handleDelete(f.id)}
                      disabled={busyId === f.id}
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
