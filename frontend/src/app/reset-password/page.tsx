"use client";

import { Suspense, useState, type FormEvent } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import Link from "next/link";
import { motion } from "motion/react";
import { ArrowLeft, Eye, EyeOff } from "lucide-react";
import { apiFetch, ApiError } from "@/lib/api";

const API_URL = process.env.NEXT_PUBLIC_API_URL;

function ResetPasswordForm() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const token = searchParams.get("token") ?? "";
  const email = searchParams.get("email") ?? "";

  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [message, setMessage] = useState<{ type: "success" | "error"; text: string } | null>(null);
  const [submitting, setSubmitting] = useState(false);

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setMessage(null);

    if (password !== passwordConfirmation) {
      setMessage({ type: "error", text: "Konfirmasi password tidak sama." });
      return;
    }

    setSubmitting(true);
    try {
      const res = await apiFetch<{ message: string }>("/api/reset-password", {
        method: "POST",
        body: JSON.stringify({
          email,
          token,
          password,
          password_confirmation: passwordConfirmation,
        }),
      });
      setMessage({ type: "success", text: res.message });
      setTimeout(() => router.replace("/login"), 1500);
    } catch (err) {
      setMessage({
        type: "error",
        text: err instanceof ApiError ? err.message : "Terjadi kesalahan, coba lagi.",
      });
    } finally {
      setSubmitting(false);
    }
  }

  const linkMissing = !token || !email;

  return (
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
          Buat Password Baru
          <br />
          {email && <span className="text-white/90">{email}</span>}
        </p>
      </div>

      {linkMissing ? (
        <p className="rounded bg-red-500/20 px-3 py-2 text-sm text-red-200">
          Link reset password tidak valid. Silakan minta link baru.
        </p>
      ) : (
        <form onSubmit={handleSubmit} className="flex flex-col gap-4">
          <div className="relative">
            <input
              type={showPassword ? "text" : "password"}
              required
              minLength={6}
              placeholder="Password Baru"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full rounded border border-white/20 bg-white/10 px-4 py-2 pr-10 text-sm placeholder-white/50 outline-none transition focus:border-cyan-400 focus:bg-white/15"
            />
            <button
              type="button"
              onClick={() => setShowPassword((v) => !v)}
              aria-label={showPassword ? "Sembunyikan password" : "Tampilkan password"}
              className="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 transition hover:text-white/90"
            >
              {showPassword ? <EyeOff size={16} /> : <Eye size={16} />}
            </button>
          </div>

          <input
            type={showPassword ? "text" : "password"}
            required
            placeholder="Konfirmasi Password Baru"
            value={passwordConfirmation}
            onChange={(e) => setPasswordConfirmation(e.target.value)}
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
            {submitting ? "Menyimpan..." : "Simpan Password Baru"}
          </motion.button>
        </form>
      )}

      <Link
        href="/login"
        className="mt-5 flex items-center justify-center gap-1.5 text-sm text-white/70 transition hover:text-white"
      >
        <ArrowLeft size={15} />
        Kembali ke Login
      </Link>
    </motion.div>
  );
}

export default function ResetPasswordPage() {
  return (
    <div
      className="flex flex-1 items-center justify-center bg-teal-700 bg-cover bg-center px-4 py-12"
      style={{ backgroundImage: `url(${API_URL}/vendor/lakers/img/bg-suratjaminan.jpg)` }}
    >
      <Suspense fallback={null}>
        <ResetPasswordForm />
      </Suspense>
    </div>
  );
}
