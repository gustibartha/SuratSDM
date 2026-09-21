"use client";

import { useEffect, useState, type FormEvent } from "react";
import { useRouter } from "next/navigation";
import { apiFetch, ApiError } from "@/lib/api";
import SearchableSelect from "./searchable-select";
import type { FormJaminan, MonitoringTagihan } from "@/lib/types";

export default function MonitoringTagihanForm({
  monitoringTagihanId,
}: {
  monitoringTagihanId?: number;
}) {
  const router = useRouter();
  const isEdit = Boolean(monitoringTagihanId);

  const [loading, setLoading] = useState(isEdit);
  const [submitting, setSubmitting] = useState(false);
  const [errors, setErrors] = useState<Record<string, string[]>>({});

  const [availableFormJaminan, setAvailableFormJaminan] = useState<FormJaminan[]>([]);
  const [idFormJaminan, setIdFormJaminan] = useState<number | null>(null);
  const [lockedFormJaminanLabel, setLockedFormJaminanLabel] = useState<string | null>(null);

  const [tanggalTagihan, setTanggalTagihan] = useState("");
  const [noTagihan, setNoTagihan] = useState("");
  const [jumlah, setJumlah] = useState("");
  const [tanggalPembayaran, setTanggalPembayaran] = useState("");
  const [tanggalRealisasiAwal, setTanggalRealisasiAwal] = useState("");
  const [tanggalRealisasiAkhir, setTanggalRealisasiAkhir] = useState("");
  const [keterangan, setKeterangan] = useState("");
  const [statusPembayaran, setStatusPembayaran] = useState<"Belum Di Bayar" | "Sudah Di Bayar">(
    "Belum Di Bayar"
  );

  useEffect(() => {
    if (isEdit) return;
    apiFetch<{ data: FormJaminan[] }>("/api/form-jaminan-available-for-monitoring").then((r) =>
      setAvailableFormJaminan(r.data)
    );
  }, [isEdit]);

  useEffect(() => {
    if (!monitoringTagihanId) return;
    apiFetch<{ monitoring_tagihan: MonitoringTagihan }>(
      `/api/monitoring-tagihan/${monitoringTagihanId}`
    )
      .then(({ monitoring_tagihan: m }) => {
        setIdFormJaminan(m.id_form_jaminan);
        setLockedFormJaminanLabel(
          `${m.form_jaminan?.nomor_surat ?? ""} — ${m.form_jaminan?.karyawan?.nama_karyawan ?? ""}`
        );
        setTanggalTagihan(m.tanggal_tagihan ?? "");
        setNoTagihan(m.no_tagihan ?? "");
        setJumlah(m.jumlah?.toString() ?? "");
        setTanggalPembayaran(m.tanggal_pembayaran ?? "");
        setTanggalRealisasiAwal(m.tanggal_realisasi_perawatan ?? "");
        setTanggalRealisasiAkhir(m.tanggal_realisasi_perawatan_akhir ?? "");
        setKeterangan(m.keterangan ?? "");
        setStatusPembayaran(m.status_pembayaran ?? "Belum Di Bayar");
      })
      .finally(() => setLoading(false));
  }, [monitoringTagihanId]);

  const formJaminanOptions = availableFormJaminan.map((f) => ({
    value: f.id,
    label: f.nomor_surat,
    sublabel: f.karyawan?.nama_karyawan,
  }));

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setErrors({});

    if (!isEdit && !idFormJaminan) {
      alert("Pilih nomor form jaminan terlebih dahulu.");
      return;
    }
    if (!jumlah) {
      alert("Jumlah wajib diisi.");
      return;
    }

    setSubmitting(true);
    const payload = {
      ...(isEdit ? {} : { id_form_jaminan: idFormJaminan }),
      tanggal_tagihan: tanggalTagihan || null,
      no_tagihan: noTagihan || null,
      jumlah: Number(jumlah),
      tanggal_pembayaran: tanggalPembayaran || null,
      tanggal_realisasi_perawatan: tanggalRealisasiAwal || null,
      tanggal_realisasi_perawatan_akhir: tanggalRealisasiAkhir || null,
      keterangan: keterangan || null,
      status_pembayaran: statusPembayaran,
    };

    try {
      if (monitoringTagihanId) {
        await apiFetch(`/api/monitoring-tagihan/${monitoringTagihanId}`, {
          method: "PUT",
          body: JSON.stringify(payload),
        });
      } else {
        await apiFetch("/api/monitoring-tagihan", {
          method: "POST",
          body: JSON.stringify(payload),
        });
      }
      router.push("/monitoring-tagihan");
    } catch (err) {
      if (err instanceof ApiError && err.errors) {
        setErrors(err.errors);
      } else {
        alert(err instanceof ApiError ? err.message : "Gagal menyimpan data.");
      }
    } finally {
      setSubmitting(false);
    }
  }

  if (loading) {
    return <p className="text-sm text-zinc-500">Memuat data...</p>;
  }

  return (
    <form onSubmit={handleSubmit} className="flex flex-col gap-4 rounded-lg bg-white p-6 shadow-sm">
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <Field label="Nomor Form Jaminan" error={errors.id_form_jaminan}>
          {isEdit ? (
            <input readOnly value={lockedFormJaminanLabel ?? ""} className="input bg-zinc-50 text-zinc-500" />
          ) : (
            <SearchableSelect
              options={formJaminanOptions}
              value={idFormJaminan}
              onChange={setIdFormJaminan}
              placeholder="Cari nomor surat / nama karyawan..."
            />
          )}
        </Field>

        <Field label="Status Pembayaran" error={errors.status_pembayaran}>
          <select
            value={statusPembayaran}
            onChange={(e) => setStatusPembayaran(e.target.value as typeof statusPembayaran)}
            className="input"
          >
            <option value="Belum Di Bayar">Belum Di Bayar</option>
            <option value="Sudah Di Bayar">Sudah Di Bayar</option>
          </select>
        </Field>

        <Field label="Tanggal Tagihan" error={errors.tanggal_tagihan}>
          <input
            type="date"
            value={tanggalTagihan}
            onChange={(e) => setTanggalTagihan(e.target.value)}
            className="input"
          />
        </Field>

        <Field label="No Tagihan" error={errors.no_tagihan}>
          <input value={noTagihan} onChange={(e) => setNoTagihan(e.target.value)} className="input" />
        </Field>

        <Field label="Jumlah" error={errors.jumlah}>
          <input
            type="number"
            required
            value={jumlah}
            onChange={(e) => setJumlah(e.target.value)}
            className="input"
          />
        </Field>

        <Field label="Tanggal Pembayaran" error={errors.tanggal_pembayaran}>
          <input
            type="date"
            value={tanggalPembayaran}
            onChange={(e) => setTanggalPembayaran(e.target.value)}
            className="input"
          />
        </Field>

        <Field label="Tanggal Realisasi Perawatan" error={errors.tanggal_realisasi_perawatan}>
          <input
            type="date"
            value={tanggalRealisasiAwal}
            onChange={(e) => setTanggalRealisasiAwal(e.target.value)}
            className="input"
          />
        </Field>

        <Field label="Tanggal Realisasi Perawatan Akhir" error={errors.tanggal_realisasi_perawatan_akhir}>
          <input
            type="date"
            value={tanggalRealisasiAkhir}
            onChange={(e) => setTanggalRealisasiAkhir(e.target.value)}
            className="input"
          />
        </Field>
      </div>

      <Field label="Keterangan" error={errors.keterangan}>
        <textarea
          value={keterangan}
          onChange={(e) => setKeterangan(e.target.value)}
          className="input"
          rows={3}
        />
      </Field>

      <div className="flex justify-end gap-2">
        <button
          type="button"
          onClick={() => router.push("/monitoring-tagihan")}
          className="rounded bg-zinc-100 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-200"
        >
          Batal
        </button>
        <button
          type="submit"
          disabled={submitting}
          className="rounded bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-500 disabled:opacity-50"
        >
          {submitting ? "Menyimpan..." : "Simpan"}
        </button>
      </div>
    </form>
  );
}

function Field({
  label,
  error,
  children,
}: {
  label: string;
  error?: string[];
  children: React.ReactNode;
}) {
  return (
    <label className="flex flex-col gap-1 text-sm">
      <span className="font-medium text-zinc-700">{label}</span>
      {children}
      {error && <span className="text-xs text-red-600">{error[0]}</span>}
    </label>
  );
}
