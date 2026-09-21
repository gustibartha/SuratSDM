"use client";

import { useEffect, useState, type FormEvent } from "react";
import { useRouter } from "next/navigation";
import Link from "next/link";
import { motion } from "motion/react";
import { Receipt } from "lucide-react";
import { useAuth } from "@/lib/auth";
import { ApiError } from "@/lib/api";
import { homeRouteForRole } from "@/lib/types";

const API_URL = process.env.NEXT_PUBLIC_API_URL;

export default function LoginPage() {
  const router = useRouter();
  const { user, loading, login } = useAuth();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    if (!loading && user) {
      router.replace(homeRouteForRole(user.role));
    }
  }, [loading, user, router]);

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setError(null);
    setSubmitting(true);
    try {
      const loggedInUser = await login(email, password);
      router.replace(homeRouteForRole(loggedInUser.role));
    } catch (err) {
      setError(err instanceof ApiError ? err.message : "Terjadi kesalahan, coba lagi.");
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <div
      className="flex flex-1 items-center justify-center bg-teal-700 bg-cover bg-center px-4 py-12"
      style={{ backgroundImage: `url(${API_URL}/vendor/lakers/img/bg-suratjaminan.jpg)` }}
    >
      <motion.div
        initial={{ opacity: 0, y: 24, scale: 0.97 }}
        animate={{ opacity: 1, y: 0, scale: 1 }}
        transition={{ duration: 0.45, ease: "easeOut" }}
        className="w-full max-w-sm rounded-xl bg-black/70 p-8 text-white shadow-2xl ring-1 ring-white/10 backdrop-blur"
      >
        <motion.div
          initial={{ opacity: 0, y: -10 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.4, delay: 0.1 }}
          className="mb-6 text-center"
        >
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img
            src={`${API_URL}/vendor/lakers/img/logo-pjb.png`}
            alt="PLN Nusantara Power"
            className="mx-auto mb-4 w-48"
          />
          <p className="mt-2 text-sm text-white/70">
            Silahkan Login
            <br />
            Selamat Datang Di Aplikasi Surat SDM
          </p>
        </motion.div>

        <motion.form
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 0.4, delay: 0.2 }}
          onSubmit={handleSubmit}
          className="flex flex-col gap-4"
        >
          <input
            type="email"
            required
            placeholder="Silahkan Masukkan Email Anda"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            className="rounded border border-white/20 bg-white/10 px-4 py-2 text-sm placeholder-white/50 outline-none transition focus:border-cyan-400 focus:bg-white/15"
          />
          <input
            type="password"
            required
            placeholder="Silahkan Masukkan Password Anda"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            className="rounded border border-white/20 bg-white/10 px-4 py-2 text-sm placeholder-white/50 outline-none transition focus:border-cyan-400 focus:bg-white/15"
          />

          {error && (
            <motion.p
              initial={{ opacity: 0, height: 0 }}
              animate={{ opacity: 1, height: "auto" }}
              className="rounded bg-red-500/20 px-3 py-2 text-sm text-red-200"
            >
              {error}
            </motion.p>
          )}

          <motion.button
            whileHover={{ scale: 1.02 }}
            whileTap={{ scale: 0.98 }}
            type="submit"
            disabled={submitting}
            className="mt-2 rounded bg-cyan-500 py-2 text-sm font-semibold text-white shadow-lg shadow-cyan-500/20 transition hover:bg-cyan-400 disabled:opacity-50"
          >
            {submitting ? "Memproses..." : "Sign In"}
          </motion.button>
        </motion.form>

        <Link
          href="/cek-kuitansi"
          className="mt-5 flex items-center justify-center gap-1.5 text-sm text-white/70 transition hover:text-white"
        >
          <Receipt size={15} />
          Cek Status Kuitansi
        </Link>
      </motion.div>
    </div>
  );
}
