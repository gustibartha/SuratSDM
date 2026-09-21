"use client";

import { useMemo, useState } from "react";

interface Option {
  value: number;
  label: string;
  sublabel?: string;
}

export default function SearchableSelect({
  options,
  value,
  onChange,
  placeholder = "Cari...",
}: {
  options: Option[];
  value: number | null;
  onChange: (value: number) => void;
  placeholder?: string;
}) {
  const [query, setQuery] = useState("");
  const [open, setOpen] = useState(false);

  const selected = options.find((o) => o.value === value);

  const filtered = useMemo(() => {
    if (!query) return options.slice(0, 50);
    const q = query.toLowerCase();
    return options
      .filter(
        (o) =>
          o.label.toLowerCase().includes(q) || o.sublabel?.toLowerCase().includes(q)
      )
      .slice(0, 50);
  }, [options, query]);

  return (
    <div className="relative">
      <input
        type="text"
        value={open ? query : (selected?.label ?? "")}
        onChange={(e) => {
          setQuery(e.target.value);
          setOpen(true);
        }}
        onFocus={() => {
          setQuery("");
          setOpen(true);
        }}
        onBlur={() => setTimeout(() => setOpen(false), 150)}
        placeholder={placeholder}
        className="input"
      />
      {open && (
        <ul className="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded border border-zinc-200 bg-white text-sm shadow-lg">
          {filtered.length === 0 && (
            <li className="px-3 py-2 text-zinc-400">Tidak ditemukan.</li>
          )}
          {filtered.map((o) => (
            <li key={o.value}>
              <button
                type="button"
                onMouseDown={(e) => e.preventDefault()}
                onClick={() => {
                  onChange(o.value);
                  setOpen(false);
                  setQuery("");
                }}
                className="block w-full px-3 py-2 text-left hover:bg-cyan-50"
              >
                {o.label}
                {o.sublabel && (
                  <span className="ml-2 text-xs text-zinc-400">{o.sublabel}</span>
                )}
              </button>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
