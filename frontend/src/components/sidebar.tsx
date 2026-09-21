"use client";

import { useState } from "react";
import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import { motion, AnimatePresence } from "motion/react";
import {
  LayoutDashboard,
  Users,
  FileText,
  Receipt,
  FileCheck2,
  Plane,
  Stethoscope,
  Download,
  ClipboardCheck,
  KeyRound,
  LogOut,
  Menu,
  X,
  Camera,
} from "lucide-react";
import { useAuth } from "@/lib/auth";
import type { User } from "@/lib/types";

const API_URL = process.env.NEXT_PUBLIC_API_URL;

const ADMIN_NAV_ITEMS = [
  { href: "/dashboard", label: "Dashboard", icon: LayoutDashboard },
  { href: "/karyawan", label: "Master Data", icon: Users },
  { href: "/form-jaminan", label: "Form Jaminan", icon: FileText },
  { href: "/kuitansi", label: "Kwitansi", icon: Camera },
  { href: "/monitoring-tagihan", label: "Monitoring Tagihan", icon: Receipt },
  { href: "/surat-keterangan", label: "Surat Keterangan", icon: FileCheck2 },
  { href: "/visa", label: "Visa", icon: Plane },
  { href: "/history-record", label: "History Record", icon: Stethoscope },
  { href: "/export", label: "Export", icon: Download },
];

const APPROVER_NAV_ITEMS = [
  { href: "/approvals", label: "Persetujuan", icon: ClipboardCheck },
  { href: "/kuitansi", label: "Kwitansi", icon: Camera },
  { href: "/ubah-password", label: "Ubah Password", icon: KeyRound },
];

export default function Sidebar({ user }: { user: User }) {
  const pathname = usePathname();
  const router = useRouter();
  const { logout } = useAuth();
  const [mobileOpen, setMobileOpen] = useState(false);
  const navItems = user.role === "admin" ? ADMIN_NAV_ITEMS : APPROVER_NAV_ITEMS;

  async function handleLogout() {
    await logout();
    router.replace("/login");
  }

  const isActive = (href: string) => pathname === href || pathname?.startsWith(`${href}/`);

  return (
    <>
      {/* Mobile top bar */}
      <div className="flex items-center justify-between border-b border-zinc-200 bg-white px-4 py-3 lg:hidden">
        <div className="flex items-center gap-2">
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img src={`${API_URL}/vendor/lakers/img/logo-pjb.png`} alt="PLN" className="h-7" />
        </div>
        <button
          onClick={() => setMobileOpen((v) => !v)}
          className="rounded p-2 text-zinc-600 hover:bg-zinc-100"
          aria-label="Menu"
        >
          {mobileOpen ? <X size={20} /> : <Menu size={20} />}
        </button>
      </div>

      {/* Mobile drawer */}
      <AnimatePresence>
        {mobileOpen && (
          <>
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              onClick={() => setMobileOpen(false)}
              className="fixed inset-0 z-30 bg-black/30 lg:hidden"
            />
            <motion.aside
              initial={{ x: -280 }}
              animate={{ x: 0 }}
              exit={{ x: -280 }}
              transition={{ type: "spring", stiffness: 320, damping: 32 }}
              className="fixed inset-y-0 left-0 z-40 w-64 lg:hidden"
            >
              <SidebarContent
                user={user}
                navItems={navItems}
                isActive={isActive}
                onNavigate={() => setMobileOpen(false)}
                onLogout={handleLogout}
              />
            </motion.aside>
          </>
        )}
      </AnimatePresence>

      {/* Desktop sidebar */}
      <aside className="hidden lg:block lg:w-64 lg:shrink-0">
        <div className="fixed inset-y-0 left-0 w-64">
          <SidebarContent
            user={user}
            navItems={navItems}
            isActive={isActive}
            onNavigate={() => {}}
            onLogout={handleLogout}
          />
        </div>
      </aside>
    </>
  );
}

function SidebarContent({
  user,
  navItems,
  isActive,
  onNavigate,
  onLogout,
}: {
  user: User;
  navItems: { href: string; label: string; icon: React.ComponentType<{ size?: number; className?: string }> }[];
  isActive: (href: string) => boolean;
  onNavigate: () => void;
  onLogout: () => void;
}) {
  return (
    <div className="flex h-full flex-col bg-gradient-to-b from-teal-800 to-teal-900 text-white">
      <div className="flex items-center gap-3 border-b border-white/10 px-5 py-5">
        {/* eslint-disable-next-line @next/next/no-img-element */}
        <img
          src={`${API_URL}/vendor/lakers/img/logo-pjb.png`}
          alt="PLN Nusantara Power"
          className="h-9 w-auto rounded bg-white/95 px-1.5 py-1"
        />
        <div className="leading-tight">
          <p className="text-sm font-semibold">Surat SDM</p>
          <p className="text-xs text-teal-200">Nusantara Power</p>
        </div>
      </div>

      <nav className="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        {navItems.map((item) => {
          const active = isActive(item.href);
          const Icon = item.icon;
          return (
            <Link
              key={item.href}
              href={item.href}
              onClick={onNavigate}
              className={`relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors ${
                active ? "text-white" : "text-teal-100/80 hover:bg-white/5 hover:text-white"
              }`}
            >
              {active && (
                <motion.div
                  layoutId="active-nav-pill"
                  className="absolute inset-0 rounded-lg bg-cyan-500/90 shadow-md"
                  transition={{ type: "spring", stiffness: 400, damping: 32 }}
                />
              )}
              <Icon size={18} className="relative z-10 shrink-0" />
              <span className="relative z-10">{item.label}</span>
            </Link>
          );
        })}
      </nav>

      <div className="border-t border-white/10 px-4 py-4">
        <div className="mb-3 flex items-center gap-2">
          <div className="flex h-8 w-8 items-center justify-center rounded-full bg-cyan-500/90 text-xs font-semibold uppercase">
            {user.name.slice(0, 1)}
          </div>
          <div className="min-w-0 leading-tight">
            <p className="truncate text-sm font-medium">{user.name}</p>
            <p className="text-xs uppercase text-teal-300">{user.role}</p>
          </div>
        </div>
        <button
          onClick={onLogout}
          className="flex w-full items-center justify-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-sm text-white transition hover:bg-white/20"
        >
          <LogOut size={16} />
          Logout
        </button>
      </div>
    </div>
  );
}
