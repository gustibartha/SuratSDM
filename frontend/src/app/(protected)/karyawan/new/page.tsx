import KaryawanForm from "@/components/karyawan-form";

export default function NewKaryawanPage() {
  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Tambah Karyawan</h1>
      <KaryawanForm />
    </div>
  );
}
