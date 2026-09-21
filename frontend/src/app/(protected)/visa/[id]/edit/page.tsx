import VisaForm from "@/components/visa-form";

export default async function EditVisaPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;

  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Edit Surat Visa</h1>
      <VisaForm visaId={Number(id)} />
    </div>
  );
}
