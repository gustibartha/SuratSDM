"use client";

import { useEffect, useState, type FormEvent } from "react";
import { useRouter } from "next/navigation";
import { apiFetch, ApiError } from "@/lib/api";
import SearchableSelect from "./searchable-select";
import type { KaryawanOption, SuratKeterangan } from "@/lib/types";

export default function SuratKeteranganForm({
  suratKeteranganId,
}: {
  suratKeteranganId?: number;
}) {
  const router = useRouter();
  const isEdit = Boolean(suratKeteranganId);

  const [loading, setLoading] = useState(isEdit);
  const [submitting, setSubmitting] = useState(false);
  const [errors, setErrors] = useState<Record<string, string[]>>({});

  const [karyawanOptions, setKaryawanOptions] = useState<KaryawanOption[]>([]);
  const [karyawanId, setKaryawanId] = useState<number | null>(null);
  const [lockedKaryawanLabel, setLockedKaryawanLabel] = useState<string | null>(null);

  const [sifat, setSifat] = useState("");
  const [penerima, setPenerima] = useState("");
  const [alamatPenerima, setAlamatPenerima] = useState("");
  const [keperluan, setKeperluan] = useState("");
  const [tanggalMasukKaryawan, setTanggalMasukKaryawan] = useState("");

  useEffect(() => {
    if (isEdit) return;
    apiFetch<{ data: KaryawanOption[] }>("/api/karyawan-options?status_karyawan=all").then((r) =>
      setKaryawanOptions(r.data)
    );
  }, [isEdit]);

  useEffect(() => {
    if (!suratKeteranganId) return;
    apiFetch<{ surat_keterangan: SuratKeterangan }>(`/api/surat-keterangan/${suratKeteranganId}`)
      .then(({ surat_keterangan: s }) => {
        setKaryawanId(s.karyawan_id);
        setLockedKaryawanLabel(s.karyawan?.nama_karyawan ?? "");
        setSifat(s.sifat ?? "");
        setPenerima(s.penerima ?? "");
        setAlamatPenerima(s.alamat_penerima ?? "");
        setKeperluan(s.keperluan ?? "");
        setTanggalMasukKaryawan(s.tanggal_masuk_karyawan ?? "");
      })
      .finally(() => setLoading(false));
  }, [suratKeteranganId]);

  const karyawanSelectOptions = karyawanOptions.map((k) => ({
    value: k.id,
    label: k.nama_karyawan,
    sublabel: k.nid,
  }));

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setErrors({});

    if (!isEdit && !karyawanId) {
      alert("Pilih karyawan terlebih dahulu.");
      return;
    }

    setSubmitting(true);
    const payload = {
      ...(isEdit ? {} : { karyawan_id: karyawanId }),
      sifat: sifat || null,
      penerima: penerima || null,
      alamat_penerima: alamatPenerima || null,
      keperluan: keperluan || null,
      tanggal_masuk_karyawan: tanggalMasukKaryawan || null,
    };

    try {
      if (suratKeteranganId) {
        await apiFetch(`/api/surat-keterangan/${suratKeteranganId}`, {
          method: "PUT",
          body: JSON.stringify(payload),
        });
      } else {
        await apiFetch("/api/surat-keterangan", {
          method: "POST",
          body: JSON.stringify(payload),
        });
      }
      router.push("/surat-keterangan");
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
        {isEdit ? (
          <input readOnly value={lockedKaryawanLabel ?? ""} className="input bg-zinc-50 text-zinc-500" />
        ) : (
          <SearchableSelect
            options={karyawanSelectOptions}
            value={karyawanId}
            onChange={setKaryawanId}
            placeholder="Cari nama karyawan..."
          />
        )}
      </Field>

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <Field label="Sifat" error={errors.sifat}>
          <input value={sifat} onChange={(e) => setSifat(e.target.value)} placeholder="ex. Biasa" className="input" />
        </Field>

        <Field label="Penerima" error={errors.penerima}>
          <input
            value={penerima}
            onChange={(e) => setPenerima(e.target.value)}
            placeholder="ex. Bank BNI"
            className="input"
          />
        </Field>

        <Field label="Alamat Penerima" error={errors.alamat_penerima}>
          <input
            value={alamatPenerima}
            onChange={(e) => setAlamatPenerima(e.target.value)}
            className="input"
          />
        </Field>

        <Field label="Tanggal Masuk Karyawan" error={errors.tanggal_masuk_karyawan}>
          <input
            type="date"
            value={tanggalMasukKaryawan}
            onChange={(e) => setTanggalMasukKaryawan(e.target.value)}
            className="input"
          />
        </Field>
      </div>

      <Field label="Keperluan" error={errors.keperluan}>
        <input
          value={keperluan}
          onChange={(e) => setKeperluan(e.target.value)}
          placeholder="ex. Administrasi KTA BNI Flexi"
          className="input"
        />
      </Field>

      <div className="flex justify-end gap-2">
        <button
          type="button"
          onClick={() => router.push("/surat-keterangan")}
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
