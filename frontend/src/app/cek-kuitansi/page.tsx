"use client";

import { useEffect, useRef, useState, type FormEvent } from "react";
import Link from "next/link";
import { motion, AnimatePresence } from "motion/react";
import { Receipt, Search, ArrowLeft, User, Briefcase, Wallet } from "lucide-react";
import { apiFetch, ApiError } from "@/lib/api";

const API_URL = process.env.NEXT_PUBLIC_API_URL;

interface PublicKuitansiItem {
  id: number;
  nama_pasien: string;
  hubungan_keluarga: string;
  nominal: number | null;
  tanggal_kuitansi: string | null;
  status: "Diajukan" | "Diproses" | "Lengkap" | "Tidak Lengkap";
  catatan: string | null;
  rumah_sakit: { nama_rumah_sakit: string } | null;
}

interface PublicStatusResponse {
  karyawan: { nama_karyawan: string; status_karyawan: string };
  data: PublicKuitansiItem[];
}

interface NameMatch {
  id: number;
  nama_karyawan: string;
  jabatan: string | null;
  nid_masked: string;
}

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

export default function CekKuitansiPage() {
  const [statusKaryawan, setStatusKaryawan] = useState<"karyawan_tetap" | "pensiunan">("karyawan_tetap");
  const [mode, setMode] = useState<"nid" | "nama">("nid");
  const [nid, setNid] = useState("");
  const [nama, setNama] = useState("");
  const [matches, setMatches] = useState<NameMatch[]>([]);
  const [searchingName, setSearchingName] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [result, setResult] = useState<PublicStatusResponse | null>(null);
  const debounceRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  useEffect(() => {
    if (mode !== "nama" || nama.trim().length < 3) {
      setMatches([]);
      return;
    }
    if (debounceRef.current) clearTimeout(debounceRef.current);
    debounceRef.current = setTimeout(async () => {
      setSearchingName(true);
      try {
        const res = await apiFetch<{ data: NameMatch[] }>(
          `/api/public/kuitansi-search?nama=${encodeURIComponent(nama.trim())}&status_karyawan=${statusKaryawan}`
        );
        setMatches(res.data);
      } catch {
        setMatches([]);
      } finally {
        setSearchingName(false);
      }
    }, 400);
    return () => {
      if (debounceRef.current) clearTimeout(debounceRef.current);
    };
  }, [nama, mode, statusKaryawan]);

  async function fetchStatus(params: string) {
    setError(null);
    setResult(null);
    setLoading(true);
    try {
      const res = await apiFetch<PublicStatusResponse>(
        `/api/public/kuitansi-status?${params}&status_karyawan=${statusKaryawan}`
      );
      setResult(res);
      setMatches([]);
    } catch (err) {
      setError(err instanceof ApiError ? err.message : "Terjadi kesalahan, coba lagi.");
    } finally {
      setLoading(false);
    }
  }

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    if (mode === "nid") {
      fetchStatus(`nid=${encodeURIComponent(nid.trim())}`);
    }
  }

  function resetSearch() {
    setError(null);
    setResult(null);
    setMatches([]);
    setNid("");
    setNama("");
  }

  return (
    <div
      className="flex flex-1 flex-col items-center bg-teal-700 bg-cover bg-center px-4 py-12"
      style={{ backgroundImage: `url(${API_URL}/vendor/lakers/img/bg-suratjaminan.jpg)` }}
    >
      <div className="w-full max-w-lg">
        <Link
          href="/login"
          className="mb-4 inline-flex items-center gap-1.5 text-sm text-white/80 hover:text-white"
        >
          <ArrowLeft size={16} />
          Kembali ke Login
        </Link>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.4 }}
          className="rounded-xl bg-black/70 p-8 text-white shadow-2xl ring-1 ring-white/10 backdrop-blur"
        >
          <div className="mb-6 text-center">
            {/* eslint-disable-next-line @next/next/no-img-element */}
            <img
              src={`${API_URL}/vendor/lakers/img/logo-pjb.png`}
              alt="PLN Nusantara Power"
              className="mx-auto mb-4 w-40"
            />
            <h1 className="flex items-center justify-center gap-2 text-lg font-semibold">
              <Receipt size={20} className="text-cyan-400" />
              Cek Status Kuitansi
            </h1>
          </div>

          <div className="mb-4 grid grid-cols-2 gap-2">
            {(
              [
                { value: "karyawan_tetap", label: "Karyawan", icon: Briefcase },
                { value: "pensiunan", label: "Pensiunan", icon: Wallet },
              ] as const
            ).map((opt) => (
              <button
                key={opt.value}
                type="button"
                onClick={() => {
                  setStatusKaryawan(opt.value);
                  resetSearch();
                }}
                className={`flex items-center justify-center gap-2 rounded-lg py-2.5 text-sm font-medium transition ${
                  statusKaryawan === opt.value
                    ? "bg-cyan-500 text-white shadow-lg shadow-cyan-500/20"
                    : "bg-white/10 text-white/70 hover:bg-white/20"
                }`}
              >
                <opt.icon size={16} />
                {opt.label}
              </button>
            ))}
          </div>

          <div className="mb-4 flex justify-center gap-2 text-sm">
            {(["nid", "nama"] as const).map((m) => (
              <button
                key={m}
                type="button"
                onClick={() => {
                  setMode(m);
                  resetSearch();
                }}
                className={`rounded-full px-4 py-1.5 transition ${
                  mode === m ? "bg-cyan-500 text-white" : "bg-white/10 text-white/70 hover:bg-white/20"
                }`}
              >
                {m === "nid" ? "Cari dengan NID" : "Cari dengan Nama"}
              </button>
            ))}
          </div>

          {mode === "nid" ? (
            <>
              <p className="mb-3 text-center text-sm text-white/70">
                Masukkan NID (Nomor Induk) untuk melihat status pengajuan kuitansi Anda.
              </p>
              <form onSubmit={handleSubmit} className="flex gap-2">
                <input
                  type="text"
                  required
                  placeholder="Contoh: 7292115K3"
                  value={nid}
                  onChange={(e) => setNid(e.target.value)}
                  className="flex-1 rounded border border-white/20 bg-white/10 px-4 py-2 text-sm placeholder-white/50 outline-none transition focus:border-cyan-400 focus:bg-white/15"
                />
                <motion.button
                  whileHover={{ scale: 1.04 }}
                  whileTap={{ scale: 0.96 }}
                  type="submit"
                  disabled={loading}
                  className="flex items-center gap-1.5 rounded bg-cyan-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-cyan-500/20 transition hover:bg-cyan-400 disabled:opacity-50"
                >
                  <Search size={16} />
                  {loading ? "..." : "Cek"}
                </motion.button>
              </form>
            </>
          ) : (
            <div className="relative">
              <p className="mb-3 text-center text-sm text-white/70">
                Ketik nama Anda, lalu pilih nama Anda dari daftar yang muncul.
              </p>
              <input
                type="text"
                placeholder="Ketik minimal 3 huruf nama Anda..."
                value={nama}
                onChange={(e) => setNama(e.target.value)}
                className="w-full rounded border border-white/20 bg-white/10 px-4 py-2 text-sm placeholder-white/50 outline-none transition focus:border-cyan-400 focus:bg-white/15"
              />

              <AnimatePresence>
                {(matches.length > 0 || searchingName) && (
                  <motion.ul
                    initial={{ opacity: 0, y: -6 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0 }}
                    className="mt-2 divide-y divide-white/10 overflow-hidden rounded-lg bg-white/10"
                  >
                    {searchingName && (
                      <li className="px-4 py-2 text-sm text-white/50">Mencari...</li>
                    )}
                    {matches.map((m) => (
                      <li key={m.id}>
                        <button
                          type="button"
                          onClick={() => fetchStatus(`karyawan_id=${m.id}`)}
                          className="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm transition hover:bg-white/10"
                        >
                          <User size={16} className="shrink-0 text-cyan-300" />
                          <span className="min-w-0 flex-1">
                            <span className="block truncate font-medium">{m.nama_karyawan}</span>
                            <span className="block truncate text-xs text-white/50">
                              {m.jabatan} · NID {m.nid_masked}
                            </span>
                          </span>
                        </button>
                      </li>
                    ))}
                  </motion.ul>
                )}
              </AnimatePresence>
            </div>
          )}

          <AnimatePresence mode="wait">
            {error && (
              <motion.p
                initial={{ opacity: 0, height: 0 }}
                animate={{ opacity: 1, height: "auto" }}
                exit={{ opacity: 0, height: 0 }}
                className="mt-4 rounded bg-red-500/20 px-3 py-2 text-sm text-red-200"
              >
                {error}
              </motion.p>
            )}

            {result && (
              <motion.div
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0 }}
                className="mt-5"
              >
                <p className="mb-3 text-sm text-white/80">
                  Halo, <span className="font-semibold text-white">{result.karyawan.nama_karyawan}</span>
                </p>

                {result.data.length === 0 ? (
                  <p className="rounded bg-white/5 px-4 py-6 text-center text-sm text-white/60">
                    Belum ada kuitansi yang diajukan.
                  </p>
                ) : (
                  <div className="flex flex-col gap-2">
                    {result.data.map((item) => (
                      <div key={item.id} className="rounded-lg bg-white/10 p-3">
                        <div className="flex items-start justify-between gap-2">
                          <div className="min-w-0">
                            <p className="truncate text-sm font-medium">{item.nama_pasien}</p>
                            <p className="truncate text-xs text-white/60">
                              {item.rumah_sakit?.nama_rumah_sakit} · {item.tanggal_kuitansi}
                            </p>
                          </div>
                          <span
                            className={`shrink-0 rounded px-2 py-0.5 text-xs font-medium ${statusColor(item.status)}`}
                          >
                            {item.status}
                          </span>
                        </div>
                        <p className="mt-1 text-sm font-semibold text-cyan-300">
                          {formatRupiah(item.nominal)}
                        </p>
                        {item.catatan && (
                          <p className="mt-1 text-xs text-amber-300/90">Catatan: {item.catatan}</p>
                        )}
                      </div>
                    ))}
                  </div>
                )}
              </motion.div>
            )}
          </AnimatePresence>
        </motion.div>
      </div>
    </div>
  );
}
