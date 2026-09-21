"use client";

import { useEffect, useState } from "react";
import { motion } from "motion/react";
import { FileText, Receipt, FileCheck2, Plane } from "lucide-react";
import { apiFetch } from "@/lib/api";
import type { DashboardStats } from "@/lib/types";

const CARDS = [
  {
    key: "count_sudah",
    label: "Surat Jaminan",
    icon: FileText,
    gradient: "from-amber-400 to-orange-500",
  },
  {
    key: "count_monitoring",
    label: "Monitoring Tagihan",
    icon: Receipt,
    gradient: "from-slate-400 to-slate-600",
  },
  {
    key: "count_keterangan",
    label: "Surat Keterangan",
    icon: FileCheck2,
    gradient: "from-rose-400 to-pink-600",
  },
  {
    key: "count_visa",
    label: "Surat Visa",
    icon: Plane,
    gradient: "from-cyan-400 to-teal-600",
  },
] as const;

function AnimatedNumber({ value }: { value: number }) {
  const [display, setDisplay] = useState(0);

  useEffect(() => {
    let frame: number;
    const start = performance.now();
    const duration = 700;

    function tick(now: number) {
      const progress = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      setDisplay(Math.round(eased * value));
      if (progress < 1) frame = requestAnimationFrame(tick);
    }

    frame = requestAnimationFrame(tick);
    return () => cancelAnimationFrame(frame);
  }, [value]);

  return <>{display}</>;
}

export default function DashboardPage() {
  const [stats, setStats] = useState<DashboardStats | null>(null);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    apiFetch<DashboardStats>("/api/dashboard")
      .then(setStats)
      .catch(() => setError("Gagal memuat data dashboard."));
  }, []);

  if (error) {
    return <p className="text-sm text-red-600">{error}</p>;
  }

  if (!stats) {
    return <p className="text-sm text-zinc-500">Memuat dashboard...</p>;
  }

  return (
    <div className="flex flex-col gap-6">
      <h1 className="text-lg font-semibold text-zinc-800">Dashboard</h1>

      <div className="grid grid-cols-2 gap-4 sm:grid-cols-4">
        {CARDS.map((card, i) => {
          const Icon = card.icon;
          return (
            <motion.div
              key={card.key}
              initial={{ opacity: 0, y: 16 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.35, delay: i * 0.06 }}
              whileHover={{ y: -3 }}
              className="relative overflow-hidden rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5"
            >
              <div
                className={`absolute -right-4 -top-4 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br ${card.gradient} opacity-15`}
              />
              <div
                className={`mb-3 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br ${card.gradient} text-white shadow`}
              >
                <Icon size={18} />
              </div>
              <p className="text-xs font-medium text-zinc-500">{card.label}</p>
              <p className="mt-1 text-2xl font-bold text-zinc-800">
                <AnimatedNumber value={stats[card.key]} />
              </p>
            </motion.div>
          );
        })}
      </div>

      <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
        <motion.div
          initial={{ opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.35, delay: 0.25 }}
          className="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5"
        >
          <h2 className="mb-3 flex items-center gap-2 text-sm font-semibold text-zinc-700">
            <FileCheck2 size={16} className="text-rose-500" />
            Surat Keterangan Terbaru
          </h2>
          <ul className="divide-y divide-zinc-100 text-sm">
            {stats.keterangan.map((item) => (
              <li key={item.id} className="py-2">
                <p className="font-medium text-zinc-800">{item.nomor_surat}</p>
                <p className="text-zinc-500">{item.status}</p>
              </li>
            ))}
            {stats.keterangan.length === 0 && (
              <li className="py-2 text-zinc-400">Belum ada data.</li>
            )}
          </ul>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.35, delay: 0.3 }}
          className="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5"
        >
          <h2 className="mb-3 flex items-center gap-2 text-sm font-semibold text-zinc-700">
            <Plane size={16} className="text-cyan-500" />
            Surat Visa Terbaru
          </h2>
          <ul className="divide-y divide-zinc-100 text-sm">
            {stats.visa.map((item) => (
              <li key={item.id} className="py-2">
                <p className="font-medium text-zinc-800">{item.nomor_surat}</p>
                <p className="text-zinc-500">{item.status}</p>
              </li>
            ))}
            {stats.visa.length === 0 && (
              <li className="py-2 text-zinc-400">Belum ada data.</li>
            )}
          </ul>
        </motion.div>
      </div>
    </div>
  );
}
