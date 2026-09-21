"use client";

import { useState, type FormEvent } from "react";
import { apiFetch, ApiError } from "@/lib/api";

export default function UbahPasswordPage() {
  const [passwordLama, setPasswordLama] = useState("");
  const [password, setPassword] = useState("");
  const [konfirmasi, setKonfirmasi] = useState("");
  const [message, setMessage] = useState<{ type: "success" | "error"; text: string } | null>(null);
  const [submitting, setSubmitting] = useState(false);

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setMessage(null);

    if (password !== konfirmasi) {
      setMessage({ type: "error", text: "Konfirmasi password tidak sama." });
      return;
    }

    setSubmitting(true);
    try {
      await apiFetch("/api/change-password", {
        method: "POST",
        body: JSON.stringify({ password_lama: passwordLama, password }),
      });
      setMessage({ type: "success", text: "Berhasil mengubah password." });
      setPasswordLama("");
      setPassword("");
      setKonfirmasi("");
    } catch (err) {
      setMessage({
        type: "error",
        text: err instanceof ApiError ? err.message : "Gagal mengubah password.",
      });
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Ubah Password</h1>

      <form
        onSubmit={handleSubmit}
        className="flex max-w-md flex-col gap-4 rounded-lg bg-white p-6 shadow-sm"
      >
        <label className="flex flex-col gap-1 text-sm">
          <span className="font-medium text-zinc-700">Password Lama</span>
          <input
            type="password"
            required
            value={passwordLama}
            onChange={(e) => setPasswordLama(e.target.value)}
            className="input"
          />
        </label>

        <label className="flex flex-col gap-1 text-sm">
          <span className="font-medium text-zinc-700">Password Baru</span>
          <input
            type="password"
            required
            minLength={6}
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            className="input"
          />
        </label>

        <label className="flex flex-col gap-1 text-sm">
          <span className="font-medium text-zinc-700">Konfirmasi Password Baru</span>
          <input
            type="password"
            required
            value={konfirmasi}
            onChange={(e) => setKonfirmasi(e.target.value)}
            className="input"
          />
        </label>

        {message && (
          <p
            className={`rounded px-3 py-2 text-sm ${
              message.type === "success"
                ? "bg-emerald-50 text-emerald-700"
                : "bg-red-50 text-red-600"
            }`}
          >
            {message.text}
          </p>
        )}

        <button
          type="submit"
          disabled={submitting}
          className="rounded bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-500 disabled:opacity-50"
        >
          {submitting ? "Menyimpan..." : "Simpan"}
        </button>
      </form>
    </div>
  );
}
