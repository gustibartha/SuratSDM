export type Role = "admin" | "sm" | "mkad" | "asman" | "dokter";

export function homeRouteForRole(role: Role): string {
  return role === "admin" ? "/dashboard" : "/approvals";
}

export interface User {
  id: number;
  name: string;
  email: string;
  role: Role;
  jabatan: string | null;
  nid: string | null;
}

export interface KelasRawatInap {
  id: number;
  jenis_kelas: string;
  harga: number;
}

export interface Karyawan {
  id: number;
  nama_karyawan: string;
  nid: string;
  jabatan: string | null;
  jenjang_jabatan: string | null;
  alamat: string | null;
  tanggal_lahir: string | null;
  istri: string | null;
  anak_1: string | null;
  anak_2: string | null;
  anak_3: string | null;
  status_karyawan: "karyawan_tetap" | "pensiunan";
  id_kelas_rawat_inap: number | null;
  tgl_lahir_istri: string | null;
  tgl_lahir_anak_1: string | null;
  tgl_lahir_anak_2: string | null;
  tgl_lahir_anak_3: string | null;
  email: string | null;
  tanggal_masuk_karyawan: string | null;
  kelas_rawat_inap: KelasRawatInap | null;
}

export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
}

export interface KaryawanOption {
  id: number;
  nama_karyawan: string;
  nid: string;
  istri: string | null;
  anak_1: string | null;
  anak_2: string | null;
  anak_3: string | null;
  id_kelas_rawat_inap: number | null;
  status_karyawan: "karyawan_tetap" | "pensiunan";
  kelas_rawat_inap: KelasRawatInap | null;
}

export interface RumahSakit {
  id: number;
  nama_rumah_sakit: string;
  alamat: string | null;
  no_telpon: string | null;
  email: string | null;
}

export interface JenisPemeriksaan {
  id: number;
  jenis_pemeriksaan: string;
  keterangan: string | null;
}

export interface FormJaminan {
  id: number;
  nomor_surat: string;
  jenis_surat: "personal" | "keluarga";
  id_karyawan: number;
  id_jenis_pemeriksaan: number;
  nama_pasien: string;
  hubungan_keluarga: string;
  id_rumah_sakit: number;
  biaya_rumah_sakit: number | null;
  status_pengajuan: string;
  status_email: boolean;
  file_pdf: string | null;
  is_rejected: boolean;
  karyawan: (KaryawanOption & { kelas_rawat_inap: KelasRawatInap | null }) | null;
  rumah_sakit: RumahSakit | null;
  jenis_pemeriksaan: JenisPemeriksaan | null;
}

export interface MonitoringTagihan {
  id: number;
  id_form_jaminan: number;
  tanggal_tagihan: string | null;
  no_tagihan: string | null;
  jumlah: number;
  tanggal_pembayaran: string | null;
  tanggal_realisasi_perawatan: string | null;
  tanggal_realisasi_perawatan_akhir: string | null;
  keterangan: string | null;
  status_pembayaran: "Belum Di Bayar" | "Sudah Di Bayar";
  form_jaminan: FormJaminan | null;
}

export interface SuratKeterangan {
  id: number;
  karyawan_id: number;
  nomor_surat: string;
  sifat: string | null;
  penerima: string | null;
  alamat_penerima: string | null;
  keperluan: string | null;
  status: string;
  file: string | null;
  tanggal_masuk_karyawan: string | null;
  is_rejected: boolean;
  karyawan: KaryawanOption | null;
}

export interface VisaKeluarga {
  id?: number;
  nama: string;
  hubungan: string;
  nomor_passport: string | null;
}

export interface Visa {
  id: number;
  karyawan_id: number;
  nomor_surat: string;
  jenis: string | null;
  tujuan: string | null;
  alamat: string | null;
  tanggal_mulai: string | null;
  tanggal_selesai: string | null;
  negara_tujuan: string | null;
  keperluan: string | null;
  status: string;
  file: string | null;
  is_rejected: boolean;
  karyawan: KaryawanOption | null;
  keluarga: VisaKeluarga[];
}

export interface HistoryRecord {
  id: number;
  karyawan_id: number;
  riwayat_penyakit: string | null;
  jenis_pengobatan: string | null;
  riwayat_obat: string | null;
  resume_medis: string | null;
  karyawan: KaryawanOption | null;
}

export interface Kuitansi {
  id: number;
  karyawan_id: number;
  created_by: number | null;
  nama_pasien: string;
  hubungan_keluarga: string;
  id_rumah_sakit: number;
  nominal: number | null;
  tanggal_kuitansi: string | null;
  diagnosa: string | null;
  foto_path: string | null;
  status: "Diajukan" | "Diproses" | "Lengkap" | "Tidak Lengkap";
  catatan: string | null;
  karyawan: KaryawanOption | null;
  rumah_sakit: RumahSakit | null;
  creator: { id: number; name: string } | null;
}

export interface DashboardStats {
  count_monitoring: number;
  count_sudah: number;
  count_keterangan: number;
  count_visa: number;
  monitoring: Array<Record<string, unknown>>;
  sudah: Array<{
    id: number;
    nomor_surat: string;
    nama_pasien: string;
    status_pengajuan: string;
  }>;
  keterangan: Array<{
    id: number;
    nomor_surat: string;
    status: string;
  }>;
  visa: Array<{
    id: number;
    nomor_surat: string;
    status: string;
  }>;
}
