"use client";

import { useEffect, useState, type FormEvent } from "react";
import { useRouter } from "next/navigation";
import { apiFetch, ApiError } from "@/lib/api";
import SearchableSelect from "./searchable-select";
import type { HistoryRecord, KaryawanOption } from "@/lib/types";

export default function HistoryRecordForm({ recordId }: { recordId?: number }) {
  const router = useRouter();
  const isEdit = Boolean(recordId);

  const [loading, setLoading] = useState(isEdit);
  const [submitting, setSubmitting] = useState(false);
  const [errors, setErrors] = useState<Record<string, string[]>>({});

  const [karyawanOptions, setKaryawanOptions] = useState<KaryawanOption[]>([]);
  const [karyawanId, setKaryawanId] = useState<number | null>(null);

  const [riwayatPenyakit, setRiwayatPenyakit] = useState("");
  const [jenisPengobatan, setJenisPengobatan] = useState("");
  const [riwayatObat, setRiwayatObat] = useState("");
  const [resumeMedis, setResumeMedis] = useState("");

  useEffect(() => {
    apiFetch<{ data: KaryawanOption[] }>("/api/karyawan-options?status_karyawan=all").then((r) =>
      setKaryawanOptions(r.data)
    );
  }, []);

  useEffect(() => {
    if (!recordId) return;
    apiFetch<{ record: HistoryRecord }>(`/api/history-record/${recordId}`)
      .then(({ record: r }) => {
        setKaryawanId(r.karyawan_id);
        setRiwayatPenyakit(r.riwayat_penyakit ?? "");
        setJenisPengobatan(r.jenis_pengobatan ?? "");
        setRiwayatObat(r.riwayat_obat ?? "");
        setResumeMedis(r.resume_medis ?? "");
      })
      .finally(() => setLoading(false));
  }, [recordId]);

  const karyawanSelectOptions = karyawanOptions.map((k) => ({
    value: k.id,
    label: k.nama_karyawan,
    sublabel: k.nid,
  }));

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setErrors({});

    if (!karyawanId) {
      alert("Pilih karyawan terlebih dahulu.");
      return;
    }

    setSubmitting(true);
    const payload = {
      karyawan_id: karyawanId,
      riwayat_penyakit: riwayatPenyakit || null,
      jenis_pengobatan: jenisPengobatan || null,
      riwayat_obat: riwayatObat || null,
      resume_medis: resumeMedis || null,
    };

    try {
      if (recordId) {
        await apiFetch(`/api/history-record/${recordId}`, {
          method: "PUT",
          body: JSON.stringify(payload),
        });
      } else {
        await apiFetch("/api/history-record", {
          method: "POST",
          body: JSON.stringify(payload),
        });
      }
      router.push("/history-record");
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
      <Field label="Karyawan" error={errors.karyawan_id}>
        <SearchableSelect
          options={karyawanSelectOptions}
          value={karyawanId}
          onChange={setKaryawanId}
          placeholder="Cari nama karyawan..."
        />
      </Field>

      <Field label="Riwayat Penyakit" error={errors.riwayat_penyakit}>
        <textarea
          value={riwayatPenyakit}
          onChange={(e) => setRiwayatPenyakit(e.target.value)}
          className="input"
          rows={3}
        />
      </Field>

      <Field label="Jenis Pengobatan / Tindakan" error={errors.jenis_pengobatan}>
        <textarea
          value={jenisPengobatan}
          onChange={(e) => setJenisPengobatan(e.target.value)}
          className="input"
          rows={3}
        />
      </Field>

      <Field label="Riwayat Obat" error={errors.riwayat_obat}>
        <textarea
          value={riwayatObat}
          onChange={(e) => setRiwayatObat(e.target.value)}
          className="input"
          rows={3}
        />
      </Field>

      <Field label="Resume Medis" error={errors.resume_medis}>
        <textarea
          value={resumeMedis}
          onChange={(e) => setResumeMedis(e.target.value)}
          className="input"
          rows={3}
        />
      </Field>

      <div className="flex justify-end gap-2">
        <button
          type="button"
          onClick={() => router.push("/history-record")}
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
