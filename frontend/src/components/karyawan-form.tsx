"use client";

import { useEffect, useState, type FormEvent } from "react";
import { useRouter } from "next/navigation";
import { apiFetch, ApiError } from "@/lib/api";
import type { Karyawan, KelasRawatInap } from "@/lib/types";

type FormState = Partial<Karyawan>;

const EMPTY_FORM: FormState = {
  nama_karyawan: "",
  nid: "",
  jabatan: "",
  jenjang_jabatan: "",
  alamat: "",
  tanggal_lahir: "",
  istri: "",
  anak_1: "",
  anak_2: "",
  anak_3: "",
  status_karyawan: "karyawan_tetap",
  id_kelas_rawat_inap: null,
  email: "",
  tanggal_masuk_karyawan: "",
};

export default function KaryawanForm({
  karyawanId,
}: {
  karyawanId?: number;
}) {
  const router = useRouter();
  const [form, setForm] = useState<FormState>(EMPTY_FORM);
  const [kelasList, setKelasList] = useState<KelasRawatInap[]>([]);
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [loading, setLoading] = useState(Boolean(karyawanId));
  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    apiFetch<{ data: KelasRawatInap[] }>("/api/kelas-rawat-inap")
      .then((res) => setKelasList(res.data))
      .catch(() => setKelasList([]));
  }, []);

  useEffect(() => {
    if (!karyawanId) return;
    apiFetch<{ karyawan: Karyawan }>(`/api/karyawan/${karyawanId}`)
      .then((res) => setForm(res.karyawan))
      .finally(() => setLoading(false));
  }, [karyawanId]);

  function update<K extends keyof FormState>(key: K, value: FormState[K]) {
    setForm((prev) => ({ ...prev, [key]: value }));
  }

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setErrors({});
    setSubmitting(true);
    try {
      if (karyawanId) {
        await apiFetch(`/api/karyawan/${karyawanId}`, {
          method: "PUT",
          body: JSON.stringify(form),
        });
      } else {
        await apiFetch("/api/karyawan", {
          method: "POST",
          body: JSON.stringify(form),
        });
      }
      router.push("/karyawan");
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
        <Field label="Nama Karyawan" error={errors.nama_karyawan}>
          <input
            required
            value={form.nama_karyawan ?? ""}
            onChange={(e) => update("nama_karyawan", e.target.value)}
            className="input"
          />
        </Field>

        <Field label="NID" error={errors.nid}>
          <input
            required
            value={form.nid ?? ""}
            onChange={(e) => update("nid", e.target.value)}
            className="input"
          />
        </Field>

        <Field label="Jabatan" error={errors.jabatan}>
          <input
            value={form.jabatan ?? ""}
            onChange={(e) => update("jabatan", e.target.value)}
            className="input"
          />
        </Field>

        <Field label="Jenjang Jabatan" error={errors.jenjang_jabatan}>
          <input
            value={form.jenjang_jabatan ?? ""}
            onChange={(e) => update("jenjang_jabatan", e.target.value)}
            className="input"
          />
        </Field>

        <Field label="Status Karyawan" error={errors.status_karyawan}>
          <select
            required
            value={form.status_karyawan ?? "karyawan_tetap"}
            onChange={(e) =>
              update("status_karyawan", e.target.value as Karyawan["status_karyawan"])
            }
            className="input"
          >
            <option value="karyawan_tetap">Karyawan Tetap</option>
            <option value="pensiunan">Pensiunan</option>
          </select>
        </Field>

        <Field label="Kelas Rawat Inap" error={errors.id_kelas_rawat_inap}>
          <select
            required
            value={form.id_kelas_rawat_inap ?? ""}
            onChange={(e) =>
              update(
                "id_kelas_rawat_inap",
                e.target.value ? Number(e.target.value) : null
              )
            }
            className="input"
          >
            <option value="" disabled>
              - Pilih -
            </option>
            {kelasList.map((k) => (
              <option key={k.id} value={k.id}>
                {k.jenis_kelas}
              </option>
            ))}
          </select>
        </Field>

        <Field label="Tanggal Lahir" error={errors.tanggal_lahir}>
          <input
            type="date"
            value={form.tanggal_lahir ?? ""}
            onChange={(e) => update("tanggal_lahir", e.target.value)}
            className="input"
          />
        </Field>

        <Field label="Tanggal Masuk Karyawan" error={errors.tanggal_masuk_karyawan}>
          <input
            type="date"
            value={form.tanggal_masuk_karyawan ?? ""}
            onChange={(e) => update("tanggal_masuk_karyawan", e.target.value)}
            className="input"
          />
        </Field>

        <Field label="Email" error={errors.email}>
          <input
            type="email"
            value={form.email ?? ""}
            onChange={(e) => update("email", e.target.value)}
            className="input"
          />
        </Field>

        <Field label="Nama Istri" error={errors.istri}>
          <input
            value={form.istri ?? ""}
            onChange={(e) => update("istri", e.target.value)}
            className="input"
          />
        </Field>
      </div>

      <Field label="Alamat" error={errors.alamat}>
        <textarea
          value={form.alamat ?? ""}
          onChange={(e) => update("alamat", e.target.value)}
          className="input"
          rows={3}
        />
      </Field>

      <div className="flex justify-end gap-2">
        <button
          type="button"
          onClick={() => router.push("/karyawan")}
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
