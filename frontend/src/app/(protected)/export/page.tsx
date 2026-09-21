"use client";

import { useState, type FormEvent } from "react";
import * as XLSX from "xlsx";
import { apiFetch } from "@/lib/api";
import type { FormJaminan, HistoryRecord } from "@/lib/types";

type Tab = "form_jaminan" | "history_record";

export default function ExportPage() {
  const [tab, setTab] = useState<Tab>("form_jaminan");
  const [karyawan, setKaryawan] = useState<"karyawan_tetap" | "pensiunan">("karyawan_tetap");
  const [tanggalMulai, setTanggalMulai] = useState("");
  const [tanggalSelesai, setTanggalSelesai] = useState("");

  const [forms, setForms] = useState<FormJaminan[] | null>(null);
  const [records, setRecords] = useState<HistoryRecord[] | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  async function applyFilter(e: FormEvent) {
    e.preventDefault();
    if (!tanggalMulai || !tanggalSelesai) {
      alert("Isi tanggal mulai dan tanggal selesai.");
      return;
    }
    setLoading(true);
    setError(null);
    const query = `karyawan=${karyawan}&tanggal_mulai=${tanggalMulai}&tanggal_selesai=${tanggalSelesai}`;
    try {
      if (tab === "form_jaminan") {
        const res = await apiFetch<{ data: FormJaminan[] }>(`/api/export/form-jaminan?${query}`);
        setForms(res.data);
      } else {
        const res = await apiFetch<{ data: HistoryRecord[] }>(`/api/export/history-record?${query}`);
        setRecords(res.data);
      }
    } catch {
      setError("Gagal memuat data export.");
    } finally {
      setLoading(false);
    }
  }

  function downloadExcel() {
    let rows: Record<string, unknown>[] = [];
    let filename = "";

    if (tab === "form_jaminan" && forms) {
      rows = forms.map((f, i) => ({
        No: i + 1,
        "Nomor Surat": f.nomor_surat,
        "Jenis Surat": f.jenis_surat,
        "Nama Karyawan": f.karyawan?.nama_karyawan ?? "",
        NID: f.karyawan?.nid ?? "",
        "Nama Pasien": f.nama_pasien,
        "Jenis Pemeriksaan": f.jenis_pemeriksaan?.jenis_pemeriksaan ?? "",
        "Rumah Sakit": f.rumah_sakit?.nama_rumah_sakit ?? "",
        "Kelas Rawat Inap": f.karyawan?.kelas_rawat_inap?.jenis_kelas ?? "",
        "Status Pengajuan": f.status_pengajuan,
      }));
      filename = `Export Data-${tanggalMulai}-${tanggalSelesai}.xlsx`;
    } else if (tab === "history_record" && records) {
      rows = records.map((r, i) => ({
        No: i + 1,
        "Nama Karyawan": r.karyawan?.nama_karyawan ?? "",
        Nid: r.karyawan?.nid ?? "",
        "Riwayat Penyakit": r.riwayat_penyakit ?? "",
        "Jenis Pengobatan / Tindakan": r.jenis_pengobatan ?? "",
        "Riwayat Obat": r.riwayat_obat ?? "",
        "Resume Medis": r.resume_medis ?? "",
      }));
      filename = `Export-history-records-Data-${tanggalMulai}-${tanggalSelesai}.xlsx`;
    }

    if (rows.length === 0) {
      alert("Tidak ada data untuk diexport.");
      return;
    }

    const ws = XLSX.utils.json_to_sheet(rows);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "sheet1");
    XLSX.writeFile(wb, filename);
  }

  const currentRows = tab === "form_jaminan" ? forms : records;

  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Export</h1>

      <div className="flex gap-2 text-sm">
        {(["form_jaminan", "history_record"] as Tab[]).map((t) => (
          <button
            key={t}
            onClick={() => {
              setTab(t);
              setForms(null);
              setRecords(null);
            }}
            className={`rounded px-3 py-1.5 ${
              tab === t ? "bg-cyan-600 text-white" : "bg-white text-zinc-600 hover:bg-zinc-100"
            }`}
          >
            {t === "form_jaminan" ? "Export Data Surat Jaminan" : "Export Data History"}
          </button>
        ))}
      </div>

      <form onSubmit={applyFilter} className="flex flex-wrap items-end gap-3 rounded-lg bg-white p-4 shadow-sm">
        <label className="flex flex-col gap-1 text-sm">
          <span className="font-medium text-zinc-700">Jenis Karyawan</span>
          <select
            value={karyawan}
            onChange={(e) => setKaryawan(e.target.value as typeof karyawan)}
            className="input"
          >
            <option value="karyawan_tetap">Karyawan Tetap</option>
            <option value="pensiunan">Karyawan Pensiun</option>
          </select>
        </label>

        <label className="flex flex-col gap-1 text-sm">
          <span className="font-medium text-zinc-700">Tanggal Mulai</span>
          <input
            type="date"
            required
            value={tanggalMulai}
            onChange={(e) => setTanggalMulai(e.target.value)}
            className="input"
          />
        </label>

        <label className="flex flex-col gap-1 text-sm">
          <span className="font-medium text-zinc-700">Tanggal Selesai</span>
          <input
            type="date"
            required
            value={tanggalSelesai}
            onChange={(e) => setTanggalSelesai(e.target.value)}
            className="input"
          />
        </label>

        <button
          type="submit"
          disabled={loading}
          className="rounded bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-500 disabled:opacity-50"
        >
          {loading ? "Memuat..." : "Apply Filter"}
        </button>
      </form>

      {error && <p className="text-sm text-red-600">{error}</p>}

      {currentRows && (
        <>
          <div className="overflow-x-auto rounded-lg bg-white shadow-sm">
            {tab === "form_jaminan" ? (
              <table className="w-full text-left text-sm">
                <thead className="border-b border-zinc-200 text-xs uppercase text-zinc-500">
                  <tr>
                    <th className="px-3 py-2">No</th>
                    <th className="px-3 py-2">Nomor Surat</th>
                    <th className="px-3 py-2">Nama Karyawan</th>
                    <th className="px-3 py-2">Nama Pasien</th>
                    <th className="px-3 py-2">Rumah Sakit</th>
                    <th className="px-3 py-2">Status</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-zinc-100">
                  {(forms ?? []).map((f, i) => (
                    <tr key={f.id}>
                      <td className="px-3 py-2">{i + 1}</td>
                      <td className="px-3 py-2">{f.nomor_surat}</td>
                      <td className="px-3 py-2">{f.karyawan?.nama_karyawan}</td>
                      <td className="px-3 py-2">{f.nama_pasien}</td>
                      <td className="px-3 py-2">{f.rumah_sakit?.nama_rumah_sakit}</td>
                      <td className="px-3 py-2">{f.status_pengajuan}</td>
                    </tr>
                  ))}
                  {forms?.length === 0 && (
                    <tr>
                      <td colSpan={6} className="px-3 py-6 text-center text-zinc-400">
                        Data masih kosong.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            ) : (
              <table className="w-full text-left text-sm">
                <thead className="border-b border-zinc-200 text-xs uppercase text-zinc-500">
                  <tr>
                    <th className="px-3 py-2">No</th>
                    <th className="px-3 py-2">Nama Karyawan</th>
                    <th className="px-3 py-2">Riwayat Penyakit</th>
                    <th className="px-3 py-2">Jenis Pengobatan</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-zinc-100">
                  {(records ?? []).map((r, i) => (
                    <tr key={r.id}>
                      <td className="px-3 py-2">{i + 1}</td>
                      <td className="px-3 py-2">{r.karyawan?.nama_karyawan}</td>
                      <td className="px-3 py-2">{r.riwayat_penyakit}</td>
                      <td className="px-3 py-2">{r.jenis_pengobatan}</td>
                    </tr>
                  ))}
                  {records?.length === 0 && (
                    <tr>
                      <td colSpan={4} className="px-3 py-6 text-center text-zinc-400">
                        Data masih kosong.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            )}
          </div>

          <div className="text-center">
            <button
              onClick={downloadExcel}
              className="rounded bg-emerald-600 px-6 py-2 text-sm font-medium text-white hover:bg-emerald-500"
            >
              Download Report
            </button>
          </div>
        </>
      )}
    </div>
  );
}
