const API_URL = process.env.NEXT_PUBLIC_API_URL ?? "";

export class ApiError extends Error {
  status: number;
  errors?: Record<string, string[]>;

  constructor(status: number, message: string, errors?: Record<string, string[]>) {
    super(message);
    this.status = status;
    this.errors = errors;
  }
}

function readCookie(name: string): string | null {
  if (typeof document === "undefined") return null;
  const match = document.cookie
    .split("; ")
    .find((row) => row.startsWith(`${name}=`));
  if (!match) return null;
  return decodeURIComponent(match.split("=").slice(1).join("="));
}

/**
 * Frontend and API live on different domains in production (vercel.app vs
 * fly.dev), so document.cookie on the frontend's origin can never see the
 * XSRF-TOKEN cookie the backend sets — it's a cross-site cookie, invisible
 * to JS on the other domain. The backend also returns the same encrypted
 * token in the response body (see AuthController::csrfCookie) specifically
 * so we can cache it here instead of reading it back from the cookie.
 */
let csrfToken: string | null = null;

export async function ensureCsrfCookie(): Promise<void> {
  if (csrfToken || readCookie("XSRF-TOKEN")) return;
  const res = await fetch(`${API_URL}/api/csrf-cookie`, { credentials: "include" });
  const body = await res.json().catch(() => null);
  if (body?.csrf_token) {
    csrfToken = body.csrf_token;
  }
}

const MUTATING_METHODS = new Set(["POST", "PUT", "PATCH", "DELETE"]);

export async function apiFetch<T>(path: string, init: RequestInit = {}): Promise<T> {
  const method = (init.method ?? "GET").toUpperCase();

  if (MUTATING_METHODS.has(method)) {
    await ensureCsrfCookie();
  }

  const headers = new Headers(init.headers);
  headers.set("Accept", "application/json");
  if (!(init.body instanceof FormData)) {
    headers.set("Content-Type", "application/json");
  }

  const xsrfToken = csrfToken ?? readCookie("XSRF-TOKEN");
  if (xsrfToken && MUTATING_METHODS.has(method)) {
    headers.set("X-XSRF-TOKEN", xsrfToken);
  }

  const res = await fetch(`${API_URL}${path}`, {
    ...init,
    method,
    headers,
    credentials: "include",
  });

  if (!res.ok) {
    const body = await res.json().catch(() => null);
    throw new ApiError(res.status, body?.message ?? res.statusText, body?.errors);
  }

  if (res.status === 204) {
    return undefined as T;
  }

  return res.json() as Promise<T>;
}
