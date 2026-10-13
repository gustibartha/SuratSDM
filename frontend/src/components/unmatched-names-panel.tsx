"use client";

import { useEffect, useMemo, useState } from "react";
import { Link2, Trash2, UserX } from "lucide-react";
import { apiFetch, ApiError } from "@/lib/api";
import SearchableSelect from "@/components/searchable-select";

type StatusKaryawan = "karyawan_tetap" | "pensiunan";

export interface UnmatchedName {
  name: string;
  status_karyawan: StatusKaryawan;
  nid: string | null;
  rows: number;
  suggestions: { id: number; nama_karyawan: string; nid: string | null; distance: number }[];
}

interface KaryawanOpt {
  id: number;
  nama_karyawan: string;
  nid: string | null;
}

interface Alias {
  id: number;
  alias_name: string;
  status_karyawan: StatusKaryawan;
  karyawan: { id: number; nama_karyawan: string; nid: string | null } | null;
}

const rowKey = (u: UnmatchedName) => `${u.status_karyawan}|${u.name}`;

const STATUS_LABEL: Record<StatusKaryawan, string> = {
  karyawan_tetap: "Karyawan",
  pensiunan: "Pensiunan",
};

export default function UnmatchedNamesPanel({
  items,
  onResync,
  resyncing,
}: {
  items: UnmatchedName[];
  onResync: () => void;
  resyncing: boolean;
}) {
  const [options, setOptions] = useState<Record<StatusKaryawan, KaryawanOpt[]>>({
    karyawan_tetap: [],
    pensiunan: [],
  });
  // The page remounts this panel (via key) for every new sync result, so
  // the default choices only need computing once per mount.
  const [choice, setChoice] = useState<Record<string, number | null>>(() =>
    Object.fromEntries(items.map((u) => [rowKey(u), u.suggestions[0]?.id ?? null]))
  );
  const [linked, setLinked] = useState<Set<string>>(new Set());
  const [busy, setBusy] = useState<string | null>(null);
  const [aliases, setAliases] = useState<Alias[]>([]);

  useEffect(() => {
    (["pensiunan", "karyawan_tetap"] as const).forEach((status) => {
      apiFetch<{ data: KaryawanOpt[] }>(`/api/karyawan-options?status_karyawan=${status}`)
        .then((res) => setOptions((prev) => ({ ...prev, [status]: res.data })))
        .catch(() => {});
    });
    loadAliases();
  }, []);

  function loadAliases() {
    apiFetch<{ data: Alias[] }>("/api/kuitansi-aliases")
      .then((res) => setAliases(res.data))
      .catch(() => {});
  }

  async function link(u: UnmatchedName) {
    const id = choice[rowKey(u)];
    const target = options[u.status_karyawan].find((o) => o.id === id) ?? u.suggestions.find((s) => s.id === id);
    if (!id || !target) return;

    const ok = window.confirm(
      `Hubungkan nama "${u.name}" di spreadsheet dengan ${target.nama_karyawan}${
        target.nid ? ` (NID ${target.nid})` : ""
      }?\n\nSemua ${u.rows} kuitansi atas nama ini akan masuk ke riwayat orang tersebut. Pastikan ini orang yang sama.`
    );
    if (!ok) return;

    setBusy(rowKey(u));
    try {
      await apiFetch("/api/kuitansi-aliases", {
        method: "POST",
        body: JSON.stringify({ alias_name: u.name, status_karyawan: u.status_karyawan, karyawan_id: id }),
      });
      setLinked((prev) => new Set(prev).add(rowKey(u)));
      loadAliases();
    } catch (err) {
      alert(err instanceof ApiError ? err.message : "Gagal menghubungkan nama.");
    } finally {
      setBusy(null);
    }
  }

  async function unlink(alias: Alias) {
    const ok = window.confirm(
      `Hapus hubungan "${alias.alias_name}" → ${alias.karyawan?.nama_karyawan}?\n\nKuitansi yang sudah masuk tidak ikut terhapus.`
    );
    if (!ok) return;
    try {
      await apiFetch(`/api/kuitansi-aliases/${alias.id}`, { method: "DELETE" });
      loadAliases();
    } catch (err) {
      alert(err instanceof ApiError ? err.message : "Gagal menghapus hubungan.");
    }
  }

  const selectOptions = useMemo(() => {
    const build = (u: UnmatchedName) => {
      const suggestedIds = new Set(u.suggestions.map((s) => s.id));
      const suggested = u.suggestions.map((s) => ({
        value: s.id,
        label: s.nama_karyawan,
        sublabel: `Saran${s.nid ? ` · NID ${s.nid}` : ""}`,
      }));
      const rest = options[u.status_karyawan]
        .filter((o) => !suggestedIds.has(o.id))
        .map((o) => ({ value: o.id, label: o.nama_karyawan, sublabel: o.nid ? `NID ${o.nid}` : undefined }));
      return [...suggested, ...rest];
    };
    return Object.fromEntries(items.map((u) => [rowKey(u), build(u)]));
  }, [items, options]);

  const totalRows = items.reduce((sum, u) => sum + u.rows, 0);

  return (
    <div className="flex flex-col gap-3 rounded-lg bg-amber-50 p-4 ring-1 ring-amber-200">
      {items.length > 0 && (
        <>
          <div className="flex flex-wrap items-start justify-between gap-2">
            <div>
              <h2 className="flex items-center gap-2 text-sm font-semibold text-amber-900">
                <UserX size={16} />
                {items.length} nama di spreadsheet belum cocok dengan data karyawan ({totalRows} kuitansi tidak masuk)
              </h2>
              <p className="mt-1 text-xs text-amber-800">
                Biasanya karena beda ejaan. Pilih orang yang benar lalu klik Hubungkan, kemudian Sync ulang.
              </p>
            </div>
            {linked.size > 0 && (
              <button
                onClick={onResync}
                disabled={resyncing}
                className="rounded-lg bg-cyan-600 px-3 py-1.5 text-xs font-medium text-white shadow hover:bg-cyan-500 disabled:opacity-50"
              >
                {resyncing ? "Sinkronisasi..." : `Sync ulang (${linked.size} nama dihubungkan)`}
              </button>
            )}
          </div>

          <div className="flex flex-col divide-y divide-amber-200 rounded-md bg-white ring-1 ring-amber-200">
            {items.map((u) => {
              const key = rowKey(u);
              const isLinked = linked.has(key);
              return (
                <div key={key} className="grid gap-2 p-3 sm:grid-cols-[1fr_1.4fr_auto] sm:items-center">
                  <div className="min-w-0">
                    <p className="truncate text-sm font-medium text-zinc-800">{u.name}</p>
                    <p className="text-xs text-zinc-500">
                      {STATUS_LABEL[u.status_karyawan]} · {u.rows} kuitansi{u.nid ? ` · NID ${u.nid}` : ""}
                    </p>
                  </div>
                  <SearchableSelect
                    options={selectOptions[key] ?? []}
                    value={choice[key] ?? null}
                    onChange={(v) => setChoice((prev) => ({ ...prev, [key]: v }))}
                    placeholder="Cari nama karyawan/pensiunan..."
                  />
                  <button
                    onClick={() => link(u)}
                    disabled={isLinked || busy === key || !choice[key]}
                    className={`flex items-center justify-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium ${
                      isLinked ? "bg-emerald-100 text-emerald-700" : "bg-cyan-600 text-white hover:bg-cyan-500 disabled:opacity-50"
                    }`}
                  >
                    <Link2 size={14} />
                    {isLinked ? "Terhubung" : busy === key ? "Menyimpan..." : "Hubungkan"}
                  </button>
                </div>
              );
            })}
          </div>
        </>
      )}

      {aliases.length > 0 && (
        <details className="text-xs text-zinc-700">
          <summary className="cursor-pointer font-medium text-amber-900">
            Nama yang sudah dihubungkan ({aliases.length})
          </summary>
          <ul className="mt-2 flex flex-col divide-y divide-amber-100 rounded-md bg-white ring-1 ring-amber-200">
            {aliases.map((a) => (
              <li key={a.id} className="flex items-center justify-between gap-2 px-3 py-2">
                <span className="min-w-0 truncate">
                  <span className="font-medium">{a.alias_name}</span> → {a.karyawan?.nama_karyawan ?? "?"}{" "}
                  <span className="text-zinc-400">({STATUS_LABEL[a.status_karyawan]})</span>
                </span>
                <button
                  onClick={() => unlink(a)}
                  aria-label={`Hapus hubungan ${a.alias_name}`}
                  className="shrink-0 text-zinc-400 hover:text-red-600"
                >
                  <Trash2 size={14} />
                </button>
              </li>
            ))}
          </ul>
        </details>
      )}
    </div>
  );
}
