"use client";

import { useState, type FormEvent } from "react";
import Link from "next/link";
import { motion } from "motion/react";
import { ArrowLeft } from "lucide-react";
import { apiFetch, ApiError } from "@/lib/api";

const API_URL = process.env.NEXT_PUBLIC_API_URL;

export default function ForgotPasswordPage() {
  const [email, setEmail] = useState("");
  const [message, setMessage] = useState<{ type: "success" | "error"; text: string } | null>(null);
  const [submitting, setSubmitting] = useState(false);

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setMessage(null);
    setSubmitting(true);
    try {
      const res = await apiFetch<{ message: string }>("/api/forgot-password", {
        method: "POST",
        body: JSON.stringify({ email }),
      });
      setMessage({ type: "success", text: res.message });
    } catch (err) {
      setMessage({
        type: "error",
        text: err instanceof ApiError ? err.message : "Terjadi kesalahan, coba lagi.",
      });
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
        <div className="mb-6 text-center">
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img
            src={`${API_URL}/vendor/lakers/img/logo-pjb.png`}
            alt="PLN Nusantara Power"
            className="mx-auto mb-4 w-48"
          />
          <p className="mt-2 text-sm text-white/70">
            Lupa Password
            <br />
            Masukkan email Anda untuk menerima link reset password
          </p>
        </div>

        <form onSubmit={handleSubmit} className="flex flex-col gap-4">
          <input
            type="email"
            required
            placeholder="Silahkan Masukkan Email Anda"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            className="rounded border border-white/20 bg-white/10 px-4 py-2 text-sm placeholder-white/50 outline-none transition focus:border-cyan-400 focus:bg-white/15"
          />

          {message && (
            <motion.p
              initial={{ opacity: 0, height: 0 }}
              animate={{ opacity: 1, height: "auto" }}
              className={`rounded px-3 py-2 text-sm ${
                message.type === "success" ? "bg-emerald-500/20 text-emerald-200" : "bg-red-500/20 text-red-200"
              }`}
            >
              {message.text}
            </motion.p>
          )}

          <motion.button
            whileHover={{ scale: 1.02 }}
            whileTap={{ scale: 0.98 }}
            type="submit"
            disabled={submitting}
            className="mt-2 rounded bg-cyan-500 py-2 text-sm font-semibold text-white shadow-lg shadow-cyan-500/20 transition hover:bg-cyan-400 disabled:opacity-50"
          >
            {submitting ? "Mengirim..." : "Kirim Link Reset"}
          </motion.button>
        </form>

        <Link
          href="/login"
          className="mt-5 flex items-center justify-center gap-1.5 text-sm text-white/70 transition hover:text-white"
        >
          <ArrowLeft size={15} />
          Kembali ke Login
        </Link>
      </motion.div>
    </div>
  );
}
