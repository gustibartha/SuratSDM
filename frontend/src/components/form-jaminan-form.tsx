"use client";

import { useEffect, useMemo, useState, type FormEvent } from "react";
import { useRouter } from "next/navigation";
import { apiFetch, ApiError } from "@/lib/api";
import SearchableSelect from "./searchable-select";
import type {
  FormJaminan,
  JenisPemeriksaan,
  KaryawanOption,
  RumahSakit,
} from "@/lib/types";

const FAMILY_OPTIONS: Array<{ key: "istri" | "anak_1" | "anak_2" | "anak_3"; hubungan: string }> = [
  { key: "istri", hubungan: "Istri / Suami" },
  { key: "anak_1", hubungan: "Anak Ke 1" },
  { key: "anak_2", hubungan: "Anak Ke 2" },
  { key: "anak_3", hubungan: "Anak Ke 3" },
];

export default function FormJaminanForm({
  formJaminanId,
}: {
  formJaminanId?: number;
}) {
  const router = useRouter();
  const isEdit = Boolean(formJaminanId);

  const [loading, setLoading] = useState(isEdit);
  const [submitting, setSubmitting] = useState(false);
  const [errors, setErrors] = useState<Record<string, string[]>>({});

  const [statusKaryawan, setStatusKaryawan] = useState<"karyawan_tetap" | "pensiunan">("karyawan_tetap");
  const [jenisSurat, setJenisSurat] = useState<"personal" | "keluarga">("personal");
  const [karyawanOptions, setKaryawanOptions] = useState<KaryawanOption[]>([]);
  const [rumahSakitList, setRumahSakitList] = useState<RumahSakit[]>([]);
  const [jenisPemeriksaanList, setJenisPemeriksaanList] = useState<JenisPemeriksaan[]>([]);

  const [idKaryawan, setIdKaryawan] = useState<number | null>(null);
  const [namaPasien, setNamaPasien] = useState("");
  const [hubunganKeluarga, setHubunganKeluarga] = useState("");
  const [idJenisPemeriksaan, setIdJenisPemeriksaan] = useState<number | null>(null);
  const [idRumahSakit, setIdRumahSakit] = useState<number | null>(null);
  const [biayaRumahSakit, setBiayaRumahSakit] = useState<string>("");

  useEffect(() => {
    apiFetch<{ data: RumahSakit[] }>("/api/rumah-sakit").then((r) => setRumahSakitList(r.data));
    apiFetch<{ data: JenisPemeriksaan[] }>("/api/jenis-pemeriksaan").then((r) =>
      setJenisPemeriksaanList(r.data)
    );
  }, []);

  useEffect(() => {
    apiFetch<{ data: KaryawanOption[] }>(
      `/api/karyawan-options?status_karyawan=${statusKaryawan}`
    ).then((r) => setKaryawanOptions(r.data));
  }, [statusKaryawan]);

  useEffect(() => {
    if (!formJaminanId) return;
    apiFetch<{ form_jaminan: FormJaminan }>(`/api/form-jaminan/${formJaminanId}`)
      .then(({ form_jaminan }) => {
        setJenisSurat(form_jaminan.jenis_surat);
        setStatusKaryawan(form_jaminan.karyawan?.status_karyawan ?? "karyawan_tetap");
        setIdKaryawan(form_jaminan.id_karyawan);
        setNamaPasien(form_jaminan.nama_pasien);
        setHubunganKeluarga(form_jaminan.hubungan_keluarga);
        setIdJenisPemeriksaan(form_jaminan.id_jenis_pemeriksaan);
        setIdRumahSakit(form_jaminan.id_rumah_sakit);
        setBiayaRumahSakit(form_jaminan.biaya_rumah_sakit?.toString() ?? "");
      })
      .finally(() => setLoading(false));
  }, [formJaminanId]);

  const selectedKaryawan = useMemo(
    () => karyawanOptions.find((k) => k.id === idKaryawan) ?? null,
    [karyawanOptions, idKaryawan]
  );

  // Auto-fill nama_pasien for "personal" once a karyawan is chosen.
  useEffect(() => {
    if (jenisSurat === "personal" && selectedKaryawan) {
      setNamaPasien(selectedKaryawan.nama_karyawan);
      setHubunganKeluarga("Ybs");
    }
  }, [jenisSurat, selectedKaryawan]);

  const karyawanSelectOptions = karyawanOptions.map((k) => ({
    value: k.id,
    label: k.nama_karyawan,
    sublabel: k.nid,
  }));

  const familyChoices = selectedKaryawan
    ? FAMILY_OPTIONS.filter((f) => selectedKaryawan[f.key])
    : [];

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setErrors({});

    if (!idKaryawan || !idJenisPemeriksaan || !idRumahSakit) {
      alert("Lengkapi semua field yang wajib diisi.");
      return;
    }

    setSubmitting(true);
    const payload = {
      id_karyawan: idKaryawan,
      jenis_surat: jenisSurat,
      nama_pasien: namaPasien,
      hubungan_keluarga: hubunganKeluarga,
      id_jenis_pemeriksaan: idJenisPemeriksaan,
      id_rumah_sakit: idRumahSakit,
      biaya_rumah_sakit: biayaRumahSakit ? Number(biayaRumahSakit) : null,
    };

    try {
      if (formJaminanId) {
        await apiFetch(`/api/form-jaminan/${formJaminanId}`, {
          method: "PUT",
          body: JSON.stringify(payload),
        });
      } else {
        await apiFetch("/api/form-jaminan", {
          method: "POST",
          body: JSON.stringify(payload),
        });
      }
      router.push("/form-jaminan");
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
      {!isEdit && (
        <div className="flex gap-2 text-sm">
          {(["karyawan_tetap", "pensiunan"] as const).map((s) => (
            <button
              key={s}
              type="button"
              onClick={() => {
                setStatusKaryawan(s);
                setIdKaryawan(null);
              }}
              className={`rounded px-3 py-1.5 ${
                statusKaryawan === s ? "bg-cyan-600 text-white" : "bg-zinc-100 text-zinc-600"
              }`}
            >
              {s === "karyawan_tetap" ? "Karyawan Tetap" : "Pensiunan"}
            </button>
          ))}
          <span className="mx-1 self-center text-zinc-300">|</span>
          {(["personal", "keluarga"] as const).map((j) => (
            <button
              key={j}
              type="button"
              onClick={() => setJenisSurat(j)}
              className={`rounded px-3 py-1.5 capitalize ${
                jenisSurat === j ? "bg-amber-500 text-white" : "bg-zinc-100 text-zinc-600"
              }`}
            >
              {j}
            </button>
          ))}
        </div>
      )}

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <Field label="Karyawan" error={errors.id_karyawan}>
          <SearchableSelect
            options={karyawanSelectOptions}
            value={idKaryawan}
            onChange={setIdKaryawan}
            placeholder="Cari nama karyawan..."
          />
        </Field>

        <Field label="Kelas Rawat Inap">
          <input
            readOnly
            value={selectedKaryawan?.kelas_rawat_inap?.jenis_kelas ?? ""}
            className="input bg-zinc-50 text-zinc-500"
          />
        </Field>

        {jenisSurat === "personal" ? (
          <Field label="Nama Pasien">
            <input readOnly value={namaPasien} className="input bg-zinc-50 text-zinc-500" />
          </Field>
        ) : (
          <Field label="Nama Pasien (Keluarga)" error={errors.nama_pasien}>
            <select
              required
              value={
                familyChoices.find((f) => selectedKaryawan?.[f.key] === namaPasien)?.key ?? ""
              }
              onChange={(e) => {
                const choice = FAMILY_OPTIONS.find((f) => f.key === e.target.value);
                if (choice && selectedKaryawan) {
                  setNamaPasien(selectedKaryawan[choice.key] ?? "");
                  setHubunganKeluarga(choice.hubungan);
                }
              }}
              className="input"
              disabled={!selectedKaryawan}
            >
              <option value="" disabled>
                - Pilih anggota keluarga -
              </option>
              {familyChoices.map((f) => (
                <option key={f.key} value={f.key}>
                  {f.hubungan} — {selectedKaryawan?.[f.key]}
                </option>
              ))}
            </select>
          </Field>
        )}

        <Field label="Jenis Pemeriksaan" error={errors.id_jenis_pemeriksaan}>
          <select
            required
            value={idJenisPemeriksaan ?? ""}
            onChange={(e) => setIdJenisPemeriksaan(Number(e.target.value))}
            className="input"
          >
            <option value="" disabled>
              - Pilih -
            </option>
            {jenisPemeriksaanList.map((j) => (
              <option key={j.id} value={j.id}>
                {j.jenis_pemeriksaan}
              </option>
            ))}
          </select>
        </Field>

        <Field label="Rumah Sakit" error={errors.id_rumah_sakit}>
          <SearchableSelect
            options={rumahSakitList.map((r) => ({ value: r.id, label: r.nama_rumah_sakit }))}
            value={idRumahSakit}
            onChange={setIdRumahSakit}
            placeholder="Cari rumah sakit..."
          />
        </Field>

        <Field label="Biaya Rumah Sakit" error={errors.biaya_rumah_sakit}>
          <input
            type="number"
            value={biayaRumahSakit}
            onChange={(e) => setBiayaRumahSakit(e.target.value)}
            className="input"
          />
        </Field>
      </div>

      <div className="flex justify-end gap-2">
        <button
          type="button"
          onClick={() => router.push("/form-jaminan")}
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
