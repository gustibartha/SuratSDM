"use client";

import { useEffect, useRef, useState, type FormEvent } from "react";
import { useRouter } from "next/navigation";
import { motion } from "motion/react";
import { Camera, ScanLine, Loader2 } from "lucide-react";
import { apiFetch, ApiError } from "@/lib/api";
import SearchableSelect from "./searchable-select";
import type { KaryawanOption, RumahSakit } from "@/lib/types";

const FAMILY_OPTIONS: Array<{ key: "istri" | "anak_1" | "anak_2" | "anak_3"; hubungan: string }> = [
  { key: "istri", hubungan: "Istri / Suami" },
  { key: "anak_1", hubungan: "Anak Ke 1" },
  { key: "anak_2", hubungan: "Anak Ke 2" },
  { key: "anak_3", hubungan: "Anak Ke 3" },
];

export default function KuitansiForm() {
  const router = useRouter();
  const fileInputRef = useRef<HTMLInputElement>(null);

  const [karyawanOptions, setKaryawanOptions] = useState<KaryawanOption[]>([]);
  const [rumahSakitList, setRumahSakitList] = useState<RumahSakit[]>([]);

  const [karyawanId, setKaryawanId] = useState<number | null>(null);
  const [namaPasien, setNamaPasien] = useState("");
  const [hubunganKeluarga, setHubunganKeluarga] = useState("Ybs");
  const [idRumahSakit, setIdRumahSakit] = useState<number | null>(null);
  const [nominal, setNominal] = useState("");
  const [tanggalKuitansi, setTanggalKuitansi] = useState("");
  const [diagnosa, setDiagnosa] = useState("");

  const [photo, setPhoto] = useState<File | null>(null);
  const [photoPreview, setPhotoPreview] = useState<string | null>(null);
  const [extracting, setExtracting] = useState(false);
  const [extractError, setExtractError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);
  const [errors, setErrors] = useState<Record<string, string[]>>({});

  useEffect(() => {
    apiFetch<{ data: KaryawanOption[] }>("/api/karyawan-options?status_karyawan=all").then((r) =>
      setKaryawanOptions(r.data)
    );
    apiFetch<{ data: RumahSakit[] }>("/api/rumah-sakit").then((r) => setRumahSakitList(r.data));
  }, []);

  const selectedKaryawan = karyawanOptions.find((k) => k.id === karyawanId) ?? null;

  useEffect(() => {
    if (hubunganKeluarga === "Ybs" && selectedKaryawan) {
      setNamaPasien(selectedKaryawan.nama_karyawan);
    }
  }, [hubunganKeluarga, selectedKaryawan]);

  const familyChoices = selectedKaryawan
    ? FAMILY_OPTIONS.filter((f) => selectedKaryawan[f.key])
    : [];

  function handlePhotoChange(file: File | null) {
    setPhoto(file);
    setExtractError(null);
    if (photoPreview) URL.revokeObjectURL(photoPreview);
    setPhotoPreview(file ? URL.createObjectURL(file) : null);
  }

  async function handleExtract() {
    if (!photo) return;
    setExtracting(true);
    setExtractError(null);
    try {
      const form = new FormData();
      form.append("foto", photo);
      const res = await apiFetch<{ nominal: number | null; tanggal_kuitansi: string | null }>(
        "/api/kuitansi/extract",
        { method: "POST", body: form }
      );
      if (res.nominal) setNominal(String(res.nominal));
      if (res.tanggal_kuitansi) setTanggalKuitansi(res.tanggal_kuitansi);
      if (!res.nominal && !res.tanggal_kuitansi) {
        setExtractError("Tidak ada data yang terbaca, silakan isi manual.");
      }
    } catch (err) {
      setExtractError(err instanceof ApiError ? err.message : "Gagal membaca foto.");
    } finally {
      setExtracting(false);
    }
  }

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setErrors({});

    if (!karyawanId || !idRumahSakit || !photo) {
      alert("Lengkapi karyawan, rumah sakit, dan foto kuitansi.");
      return;
    }

    setSubmitting(true);
    const form = new FormData();
    form.append("karyawan_id", String(karyawanId));
    form.append("nama_pasien", namaPasien);
    form.append("hubungan_keluarga", hubunganKeluarga);
    form.append("id_rumah_sakit", String(idRumahSakit));
    form.append("nominal", nominal);
    form.append("tanggal_kuitansi", tanggalKuitansi);
    form.append("diagnosa", diagnosa);
    form.append("foto", photo);

    try {
      await apiFetch("/api/kuitansi", { method: "POST", body: form });
      router.push("/kuitansi");
    } catch (err) {
      if (err instanceof ApiError && err.errors) {
        setErrors(err.errors);
      } else {
        alert(err instanceof ApiError ? err.message : "Gagal menyimpan kuitansi.");
      }
    } finally {
      setSubmitting(false);
    }
  }

  const karyawanSelectOptions = karyawanOptions.map((k) => ({
    value: k.id,
    label: k.nama_karyawan,
    sublabel: k.nid,
  }));

  return (
    <form onSubmit={handleSubmit} className="flex flex-col gap-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5">
      <div>
        <span className="mb-2 block text-sm font-medium text-zinc-700">Foto Kuitansi</span>
        <input
          ref={fileInputRef}
          type="file"
          accept="image/*"
          capture="environment"
          className="hidden"
          onChange={(e) => handlePhotoChange(e.target.files?.[0] ?? null)}
        />

        {!photoPreview ? (
          <button
            type="button"
            onClick={() => fileInputRef.current?.click()}
            className="flex w-full flex-col items-center gap-2 rounded-xl border-2 border-dashed border-zinc-300 bg-zinc-50 py-10 text-zinc-500 transition hover:border-cyan-400 hover:bg-cyan-50/50 hover:text-cyan-600"
          >
            <Camera size={28} />
            <span className="text-sm font-medium">Ambil / Unggah Foto Kuitansi</span>
          </button>
        ) : (
          <div className="flex flex-col items-center gap-3 sm:flex-row sm:items-start">
            {/* eslint-disable-next-line @next/next/no-img-element */}
            <img
              src={photoPreview}
              alt="Preview kuitansi"
              className="h-48 w-full rounded-lg object-cover ring-1 ring-black/10 sm:w-40"
            />
            <div className="flex flex-1 flex-col gap-2">
              <motion.button
                whileHover={{ scale: 1.02 }}
                whileTap={{ scale: 0.98 }}
                type="button"
                onClick={handleExtract}
                disabled={extracting}
                className="flex items-center justify-center gap-2 rounded-lg bg-cyan-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-cyan-500 disabled:opacity-50"
              >
                {extracting ? (
                  <Loader2 size={16} className="animate-spin" />
                ) : (
                  <ScanLine size={16} />
                )}
                {extracting ? "Membaca foto..." : "Baca Otomatis"}
              </motion.button>
              <button
                type="button"
                onClick={() => fileInputRef.current?.click()}
                className="text-xs text-zinc-500 underline hover:text-zinc-700"
              >
                Ganti foto
              </button>
              {extractError && <p className="text-xs text-amber-600">{extractError}</p>}
            </div>
          </div>
        )}
      </div>

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <Field label="Karyawan" error={errors.karyawan_id}>
          <SearchableSelect
            options={karyawanSelectOptions}
            value={karyawanId}
            onChange={setKaryawanId}
            placeholder="Cari nama karyawan..."
          />
        </Field>

        <Field label="Pasien">
          <select
            value={hubunganKeluarga === "Ybs" ? "Ybs" : namaPasien}
            onChange={(e) => {
              if (e.target.value === "Ybs") {
                setHubunganKeluarga("Ybs");
                setNamaPasien(selectedKaryawan?.nama_karyawan ?? "");
              } else {
                const choice = FAMILY_OPTIONS.find((f) => selectedKaryawan?.[f.key] === e.target.value);
                if (choice) {
                  setHubunganKeluarga(choice.hubungan);
                  setNamaPasien(e.target.value);
                }
              }
            }}
            className="input"
            disabled={!selectedKaryawan}
          >
            <option value="Ybs">{selectedKaryawan?.nama_karyawan ?? "Karyawan sendiri"}</option>
            {familyChoices.map((f) => (
              <option key={f.key} value={selectedKaryawan?.[f.key] ?? ""}>
                {selectedKaryawan?.[f.key]} ({f.hubungan})
              </option>
            ))}
          </select>
        </Field>

        <Field label="Rumah Sakit / Instansi" error={errors.id_rumah_sakit}>
          <SearchableSelect
            options={rumahSakitList.map((r) => ({ value: r.id, label: r.nama_rumah_sakit }))}
            value={idRumahSakit}
            onChange={setIdRumahSakit}
            placeholder="Cari rumah sakit..."
          />
        </Field>

        <Field label="Nominal (Rp)" error={errors.nominal}>
          <input
            type="number"
            required
            value={nominal}
            onChange={(e) => setNominal(e.target.value)}
            className="input"
          />
        </Field>

        <Field label="Tanggal Kuitansi" error={errors.tanggal_kuitansi}>
          <input
            type="date"
            required
            value={tanggalKuitansi}
            onChange={(e) => setTanggalKuitansi(e.target.value)}
            className="input"
          />
        </Field>
      </div>

      <Field label="Diagnosa / Keterangan (opsional)" error={errors.diagnosa}>
        <textarea value={diagnosa} onChange={(e) => setDiagnosa(e.target.value)} className="input" rows={3} />
      </Field>

      <div className="flex justify-end gap-2">
        <button
          type="button"
          onClick={() => router.push("/kuitansi")}
          className="rounded bg-zinc-100 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-200"
        >
          Batal
        </button>
        <motion.button
          whileHover={{ scale: 1.02 }}
          whileTap={{ scale: 0.98 }}
          type="submit"
          disabled={submitting}
          className="rounded bg-cyan-600 px-5 py-2 text-sm font-medium text-white shadow hover:bg-cyan-500 disabled:opacity-50"
        >
          {submitting ? "Mengirim..." : "Kirim Kuitansi"}
        </motion.button>
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
