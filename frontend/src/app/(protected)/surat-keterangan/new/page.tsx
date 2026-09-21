import SuratKeteranganForm from "@/components/surat-keterangan-form";

export default function NewSuratKeteranganPage() {
  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Buat Surat Keterangan</h1>
      <SuratKeteranganForm />
    </div>
  );
}
