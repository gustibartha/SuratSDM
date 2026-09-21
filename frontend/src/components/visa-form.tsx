"use client";

import { useEffect, useState, type FormEvent } from "react";
import { useRouter } from "next/navigation";
import { apiFetch, ApiError } from "@/lib/api";
import SearchableSelect from "./searchable-select";
import type { KaryawanOption, Visa, VisaKeluarga } from "@/lib/types";

const HUBUNGAN_OPTIONS = ["Husband", "Wife", "Son", "Daughter"];

export default function VisaForm({ visaId }: { visaId?: number }) {
  const router = useRouter();
  const isEdit = Boolean(visaId);

  const [loading, setLoading] = useState(isEdit);
  const [submitting, setSubmitting] = useState(false);
  const [errors, setErrors] = useState<Record<string, string[]>>({});

  const [karyawanOptions, setKaryawanOptions] = useState<KaryawanOption[]>([]);
  const [karyawanId, setKaryawanId] = useState<number | null>(null);

  const [jenis, setJenis] = useState("");
  const [tujuan, setTujuan] = useState("");
  const [alamat, setAlamat] = useState("");
  const [tanggalMulai, setTanggalMulai] = useState("");
  const [tanggalSelesai, setTanggalSelesai] = useState("");
  const [negaraTujuan, setNegaraTujuan] = useState("");
  const [keperluan, setKeperluan] = useState("");
  const [keluarga, setKeluarga] = useState<VisaKeluarga[]>([]);

  const [newNama, setNewNama] = useState("");
  const [newHubungan, setNewHubungan] = useState(HUBUNGAN_OPTIONS[0]);
  const [newPassport, setNewPassport] = useState("");

  useEffect(() => {
    apiFetch<{ data: KaryawanOption[] }>("/api/karyawan-options?status_karyawan=all").then((r) =>
      setKaryawanOptions(r.data)
    );
  }, []);

  useEffect(() => {
    if (!visaId) return;
    apiFetch<{ visa: Visa }>(`/api/visa/${visaId}`)
      .then(({ visa: v }) => {
        setKaryawanId(v.karyawan_id);
        setJenis(v.jenis ?? "");
        setTujuan(v.tujuan ?? "");
        setAlamat(v.alamat ?? "");
        setTanggalMulai(v.tanggal_mulai ?? "");
        setTanggalSelesai(v.tanggal_selesai ?? "");
        setNegaraTujuan(v.negara_tujuan ?? "");
        setKeperluan(v.keperluan ?? "");
        setKeluarga(v.keluarga ?? []);
      })
      .finally(() => setLoading(false));
  }, [visaId]);

  const selectedKaryawan = karyawanOptions.find((k) => k.id === karyawanId) ?? null;
  const familyNames = selectedKaryawan
    ? [selectedKaryawan.istri, selectedKaryawan.anak_1, selectedKaryawan.anak_2, selectedKaryawan.anak_3].filter(
        (v): v is string => Boolean(v)
      )
    : [];

  const karyawanSelectOptions = karyawanOptions.map((k) => ({
    value: k.id,
    label: k.nama_karyawan,
    sublabel: k.nid,
  }));

  function addKeluarga() {
    if (!newNama) {
      alert("Pilih nama anggota keluarga.");
      return;
    }
    setKeluarga((prev) => [...prev, { nama: newNama, hubungan: newHubungan, nomor_passport: newPassport || null }]);
    setNewNama("");
    setNewPassport("");
  }

  function removeKeluarga(index: number) {
    setKeluarga((prev) => prev.filter((_, i) => i !== index));
  }

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
      jenis: jenis || null,
      tujuan: tujuan || null,
      alamat: alamat || null,
      tanggal_mulai: tanggalMulai || null,
      tanggal_selesai: tanggalSelesai || null,
      negara_tujuan: negaraTujuan || null,
      keperluan: keperluan || null,
      keluarga,
    };

    try {
      if (visaId) {
        await apiFetch(`/api/visa/${visaId}`, {
          method: "PUT",
          body: JSON.stringify(payload),
        });
      } else {
        await apiFetch("/api/visa", {
          method: "POST",
          body: JSON.stringify(payload),
        });
      }
      router.push("/visa");
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

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <Field label="Jenis Surat" error={errors.jenis}>
          <input value={jenis} onChange={(e) => setJenis(e.target.value)} placeholder="ex. Regular" className="input" />
        </Field>

        <Field label="Tujuan" error={errors.tujuan}>
          <input
            value={tujuan}
            onChange={(e) => setTujuan(e.target.value)}
            placeholder="ex. The Embassy of Australia"
            className="input"
          />
        </Field>

        <Field label="Alamat" error={errors.alamat}>
          <input value={alamat} onChange={(e) => setAlamat(e.target.value)} className="input" />
        </Field>

        <Field label="Negara Tujuan" error={errors.negara_tujuan}>
          <input
            value={negaraTujuan}
            onChange={(e) => setNegaraTujuan(e.target.value)}
            placeholder="ex. Australia"
            className="input"
          />
        </Field>

        <Field label="Tanggal Mulai" error={errors.tanggal_mulai}>
          <input
            type="date"
            value={tanggalMulai}
            onChange={(e) => setTanggalMulai(e.target.value)}
            className="input"
          />
        </Field>

        <Field label="Tanggal Selesai" error={errors.tanggal_selesai}>
          <input
            type="date"
            value={tanggalSelesai}
            onChange={(e) => setTanggalSelesai(e.target.value)}
            className="input"
          />
        </Field>
      </div>

      <Field label="Keperluan" error={errors.keperluan}>
        <input
          value={keperluan}
          onChange={(e) => setKeperluan(e.target.value)}
          placeholder="ex. vacation"
          className="input"
        />
      </Field>

      <div className="rounded border border-zinc-200 p-4">
        <h3 className="mb-3 text-sm font-semibold text-zinc-700">Keluarga</h3>

        {keluarga.length > 0 && (
          <table className="mb-3 w-full text-left text-sm">
            <thead className="text-xs uppercase text-zinc-500">
              <tr>
                <th className="py-1">Nama</th>
                <th className="py-1">Hubungan</th>
                <th className="py-1">Nomor Passport</th>
                <th className="py-1"></th>
              </tr>
            </thead>
            <tbody className="divide-y divide-zinc-100">
              {keluarga.map((k, i) => (
                <tr key={i}>
                  <td className="py-1.5">{k.nama}</td>
                  <td className="py-1.5">{k.hubungan}</td>
                  <td className="py-1.5">{k.nomor_passport ?? "-"}</td>
                  <td className="py-1.5 text-right">
                    <button
                      type="button"
                      onClick={() => removeKeluarga(i)}
                      className="text-xs text-red-600 hover:underline"
                    >
                      Hapus
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}

        <div className="grid grid-cols-1 gap-2 sm:grid-cols-4">
          <select value={newNama} onChange={(e) => setNewNama(e.target.value)} className="input">
            <option value="">- Pilih nama -</option>
            {familyNames.map((name) => (
              <option key={name} value={name}>
                {name}
              </option>
            ))}
          </select>
          <select value={newHubungan} onChange={(e) => setNewHubungan(e.target.value)} className="input">
            {HUBUNGAN_OPTIONS.map((h) => (
              <option key={h} value={h}>
                {h}
              </option>
            ))}
          </select>
          <input
            value={newPassport}
            onChange={(e) => setNewPassport(e.target.value)}
            placeholder="Nomor Passport"
            className="input"
          />
          <button
            type="button"
            onClick={addKeluarga}
            className="rounded bg-zinc-100 px-3 py-2 text-sm text-zinc-700 hover:bg-zinc-200"
          >
            + Tambah
          </button>
        </div>
      </div>

      <div className="flex justify-end gap-2">
        <button
          type="button"
          onClick={() => router.push("/visa")}
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
