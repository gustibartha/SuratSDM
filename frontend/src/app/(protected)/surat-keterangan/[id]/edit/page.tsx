import SuratKeteranganForm from "@/components/surat-keterangan-form";

export default async function EditSuratKeteranganPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;

  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Edit Surat Keterangan</h1>
      <SuratKeteranganForm suratKeteranganId={Number(id)} />
    </div>
  );
}
