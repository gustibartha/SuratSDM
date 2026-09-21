"use client";

import { use, useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { motion } from "motion/react";
import { ArrowLeft, Trash2 } from "lucide-react";
import { apiFetch, ApiError } from "@/lib/api";
import type { Kuitansi } from "@/lib/types";

const API_URL = process.env.NEXT_PUBLIC_API_URL;

const STATUS_OPTIONS: Kuitansi["status"][] = ["Diajukan", "Diproses", "Lengkap", "Tidak Lengkap"];

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

export default function KuitansiDetailPage({ params }: { params: Promise<{ id: string }> }) {
  const { id } = use(params);
  const router = useRouter();

  const [kuitansi, setKuitansi] = useState<Kuitansi | null>(null);
  const [catatan, setCatatan] = useState("");
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    apiFetch<{ kuitansi: Kuitansi }>(`/api/kuitansi/${id}`)
      .then((r) => {
        setKuitansi(r.kuitansi);
        setCatatan(r.kuitansi.catatan ?? "");
      })
      .catch(() => setError("Gagal memuat data kuitansi."));
  }, [id]);

  async function updateStatus(status: Kuitansi["status"]) {
    setBusy(true);
    try {
      const res = await apiFetch<{ kuitansi: Kuitansi }>(`/api/kuitansi/${id}/status`, {
        method: "POST",
        body: JSON.stringify({ status, catatan: catatan || null }),
      });
      setKuitansi(res.kuitansi);
    } catch (err) {
      alert(err instanceof ApiError ? err.message : "Gagal mengubah status.");
    } finally {
      setBusy(false);
    }
  }

  async function handleDelete() {
    if (!confirm("Hapus kuitansi ini?")) return;
    setBusy(true);
    try {
      await apiFetch(`/api/kuitansi/${id}`, { method: "DELETE" });
      router.push("/kuitansi");
    } catch (err) {
      alert(err instanceof ApiError ? err.message : "Gagal menghapus.");
      setBusy(false);
    }
  }

  if (error) return <p className="text-sm text-red-600">{error}</p>;
  if (!kuitansi) return <p className="text-sm text-zinc-500">Memuat...</p>;

  return (
    <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="flex flex-col gap-4">
      <button
        onClick={() => router.push("/kuitansi")}
        className="flex w-fit items-center gap-1.5 text-sm text-zinc-500 hover:text-zinc-700"
      >
        <ArrowLeft size={16} />
        Kembali
      </button>

      <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div className="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-black/5">
          {kuitansi.foto_path ? (
            // eslint-disable-next-line @next/next/no-img-element
            <img
              src={`${API_URL}/kuitansi-foto/${kuitansi.foto_path}`}
              alt="Foto kuitansi"
              className="h-full w-full object-cover"
            />
          ) : (
            <div className="flex h-64 items-center justify-center text-zinc-400">Tidak ada foto</div>
          )}
        </div>

        <div className="flex flex-col gap-4 rounded-xl bg-white p-5 shadow-sm ring-1 ring-black/5">
          <div className="flex items-start justify-between">
            <div>
              <h1 className="text-lg font-semibold text-zinc-800">{kuitansi.nama_pasien}</h1>
              <p className="text-sm text-zinc-500">{kuitansi.hubungan_keluarga}</p>
            </div>
            <span className={`rounded px-2 py-1 text-xs font-medium ${statusColor(kuitansi.status)}`}>
              {kuitansi.status}
            </span>
          </div>

          <dl className="grid grid-cols-2 gap-3 text-sm">
            <div>
              <dt className="text-zinc-400">Karyawan</dt>
              <dd className="font-medium text-zinc-800">{kuitansi.karyawan?.nama_karyawan}</dd>
            </div>
            <div>
              <dt className="text-zinc-400">Rumah Sakit</dt>
              <dd className="font-medium text-zinc-800">{kuitansi.rumah_sakit?.nama_rumah_sakit}</dd>
            </div>
            <div>
              <dt className="text-zinc-400">Nominal</dt>
              <dd className="font-medium text-zinc-800">{formatRupiah(kuitansi.nominal)}</dd>
            </div>
            <div>
              <dt className="text-zinc-400">Tanggal Kuitansi</dt>
              <dd className="font-medium text-zinc-800">{kuitansi.tanggal_kuitansi ?? "-"}</dd>
            </div>
            {kuitansi.diagnosa && (
              <div className="col-span-2">
                <dt className="text-zinc-400">Diagnosa</dt>
                <dd className="text-zinc-700">{kuitansi.diagnosa}</dd>
              </div>
            )}
            <div className="col-span-2">
              <dt className="text-zinc-400">Diajukan oleh</dt>
              <dd className="text-zinc-700">{kuitansi.creator?.name ?? "-"}</dd>
            </div>
          </dl>

          <div className="border-t border-zinc-100 pt-4">
            <label className="mb-2 block text-sm font-medium text-zinc-700">Catatan</label>
            <textarea
              value={catatan}
              onChange={(e) => setCatatan(e.target.value)}
              rows={2}
              placeholder="Catatan (opsional, misalnya alasan tidak lengkap)"
              className="input mb-3"
            />
            <div className="flex flex-wrap gap-2">
              {STATUS_OPTIONS.filter((s) => s !== kuitansi.status).map((s) => (
                <button
                  key={s}
                  onClick={() => updateStatus(s)}
                  disabled={busy}
                  className={`rounded-lg px-3 py-1.5 text-sm font-medium transition disabled:opacity-50 ${statusColor(
                    s
                  )} hover:brightness-95`}
                >
                  Set: {s}
                </button>
              ))}
              <button
                onClick={handleDelete}
                disabled={busy}
                className="ml-auto flex items-center gap-1.5 rounded-lg bg-red-50 px-3 py-1.5 text-sm text-red-600 hover:bg-red-100 disabled:opacity-50"
              >
                <Trash2 size={14} />
                Hapus
              </button>
            </div>
          </div>
        </div>
      </div>
    </motion.div>
  );
}
