"use client";

import { useCallback, useEffect, useState } from "react";
import { apiFetch, ApiError } from "@/lib/api";
import type { FormJaminan, SuratKeterangan, Visa } from "@/lib/types";

interface ApprovalsResponse {
  form_jaminan: FormJaminan[];
  surat_keterangan: SuratKeterangan[];
  visa: Visa[];
}

type Tab = "form_jaminan" | "surat_keterangan" | "visa";

const TAB_LABELS: Record<Tab, string> = {
  form_jaminan: "Form Jaminan",
  surat_keterangan: "Surat Keterangan",
  visa: "Visa",
};

export default function ApprovalsPage() {
  const [data, setData] = useState<ApprovalsResponse | null>(null);
  const [tab, setTab] = useState<Tab>("form_jaminan");
  const [error, setError] = useState<string | null>(null);
  const [busyId, setBusyId] = useState<number | null>(null);

  const load = useCallback(() => {
    setError(null);
    apiFetch<ApprovalsResponse>("/api/approvals")
      .then(setData)
      .catch(() => setError("Gagal memuat data persetujuan."));
  }, []);

  useEffect(() => {
    load();
  }, [load]);

  async function decide(endpoint: string, id: number, action: "approve" | "reject") {
    if (action === "reject" && !confirm("Tolak pengajuan ini?")) return;
    setBusyId(id);
    try {
      await apiFetch(`/api/${endpoint}/${id}/${action}`, { method: "POST" });
      load();
    } catch (err) {
      alert(err instanceof ApiError ? err.message : "Gagal memproses.");
    } finally {
      setBusyId(null);
    }
  }

  if (error) return <p className="text-sm text-red-600">{error}</p>;
  if (!data) return <p className="text-sm text-zinc-500">Memuat...</p>;

  const counts: Record<Tab, number> = {
    form_jaminan: data.form_jaminan.length,
    surat_keterangan: data.surat_keterangan.length,
    visa: data.visa.length,
  };

  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Persetujuan</h1>

      <div className="flex gap-2 text-sm">
        {(Object.keys(TAB_LABELS) as Tab[]).map((t) => (
          <button
            key={t}
            onClick={() => setTab(t)}
            className={`rounded px-3 py-1.5 ${
              tab === t ? "bg-cyan-600 text-white" : "bg-white text-zinc-600 hover:bg-zinc-100"
            }`}
          >
            {TAB_LABELS[t]} ({counts[t]})
          </button>
        ))}
      </div>

      {tab === "form_jaminan" && (
        <div className="overflow-x-auto rounded-lg bg-white shadow-sm">
          <table className="w-full text-left text-sm">
            <thead className="border-b border-zinc-200 text-xs uppercase text-zinc-500">
              <tr>
                <th className="px-4 py-3">Nomor Surat</th>
                <th className="px-4 py-3">Karyawan</th>
                <th className="px-4 py-3">Pasien</th>
                <th className="px-4 py-3">Rumah Sakit</th>
                <th className="px-4 py-3 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-zinc-100">
              {data.form_jaminan.map((f) => (
                <tr key={f.id}>
                  <td className="px-4 py-3 font-medium text-zinc-800">{f.nomor_surat}</td>
                  <td className="px-4 py-3 text-zinc-600">{f.karyawan?.nama_karyawan}</td>
                  <td className="px-4 py-3 text-zinc-600">
                    {f.nama_pasien} <span className="text-xs text-zinc-400">({f.hubungan_keluarga})</span>
                  </td>
                  <td className="px-4 py-3 text-zinc-600">{f.rumah_sakit?.nama_rumah_sakit}</td>
                  <td className="px-4 py-3 text-right">
                    <ApproveRejectButtons
                      disabled={busyId === f.id}
                      onApprove={() => decide("form-jaminan", f.id, "approve")}
                      onReject={() => decide("form-jaminan", f.id, "reject")}
                    />
                  </td>
                </tr>
              ))}
              {data.form_jaminan.length === 0 && <EmptyRow colSpan={5} />}
            </tbody>
          </table>
        </div>
      )}

      {tab === "surat_keterangan" && (
        <div className="overflow-x-auto rounded-lg bg-white shadow-sm">
          <table className="w-full text-left text-sm">
            <thead className="border-b border-zinc-200 text-xs uppercase text-zinc-500">
              <tr>
                <th className="px-4 py-3">Nomor Surat</th>
                <th className="px-4 py-3">Karyawan</th>
                <th className="px-4 py-3">Penerima</th>
                <th className="px-4 py-3">Keperluan</th>
                <th className="px-4 py-3 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-zinc-100">
              {data.surat_keterangan.map((s) => (
                <tr key={s.id}>
                  <td className="px-4 py-3 font-medium text-zinc-800">{s.nomor_surat}</td>
                  <td className="px-4 py-3 text-zinc-600">{s.karyawan?.nama_karyawan}</td>
                  <td className="px-4 py-3 text-zinc-600">{s.penerima ?? "-"}</td>
                  <td className="px-4 py-3 text-zinc-600">{s.keperluan ?? "-"}</td>
                  <td className="px-4 py-3 text-right">
                    <ApproveRejectButtons
                      disabled={busyId === s.id}
                      onApprove={() => decide("surat-keterangan", s.id, "approve")}
                      onReject={() => decide("surat-keterangan", s.id, "reject")}
                    />
                  </td>
                </tr>
              ))}
              {data.surat_keterangan.length === 0 && <EmptyRow colSpan={5} />}
            </tbody>
          </table>
        </div>
      )}

      {tab === "visa" && (
        <div className="overflow-x-auto rounded-lg bg-white shadow-sm">
          <table className="w-full text-left text-sm">
            <thead className="border-b border-zinc-200 text-xs uppercase text-zinc-500">
              <tr>
                <th className="px-4 py-3">Nomor Surat</th>
                <th className="px-4 py-3">Karyawan</th>
                <th className="px-4 py-3">Negara Tujuan</th>
                <th className="px-4 py-3">Keluarga</th>
                <th className="px-4 py-3 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-zinc-100">
              {data.visa.map((v) => (
                <tr key={v.id}>
                  <td className="px-4 py-3 font-medium text-zinc-800">{v.nomor_surat}</td>
                  <td className="px-4 py-3 text-zinc-600">{v.karyawan?.nama_karyawan}</td>
                  <td className="px-4 py-3 text-zinc-600">{v.negara_tujuan ?? "-"}</td>
                  <td className="px-4 py-3 text-zinc-600">
                    {v.keluarga.length > 0 ? `${v.keluarga.length} orang` : "-"}
                  </td>
                  <td className="px-4 py-3 text-right">
                    <ApproveRejectButtons
                      disabled={busyId === v.id}
                      onApprove={() => decide("visa", v.id, "approve")}
                      onReject={() => decide("visa", v.id, "reject")}
                    />
                  </td>
                </tr>
              ))}
              {data.visa.length === 0 && <EmptyRow colSpan={5} />}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}

function ApproveRejectButtons({
  disabled,
  onApprove,
  onReject,
}: {
  disabled: boolean;
  onApprove: () => void;
  onReject: () => void;
}) {
  return (
    <div className="flex justify-end gap-2">
      <button
        onClick={onApprove}
        disabled={disabled}
        className="rounded bg-emerald-50 px-3 py-1 text-emerald-700 hover:bg-emerald-100 disabled:opacity-50"
      >
        Setujui
      </button>
      <button
        onClick={onReject}
        disabled={disabled}
        className="rounded bg-red-50 px-3 py-1 text-red-600 hover:bg-red-100 disabled:opacity-50"
      >
        Tolak
      </button>
    </div>
  );
}

function EmptyRow({ colSpan }: { colSpan: number }) {
  return (
    <tr>
      <td colSpan={colSpan} className="px-4 py-6 text-center text-zinc-400">
        Tidak ada yang menunggu persetujuan.
      </td>
    </tr>
  );
}
