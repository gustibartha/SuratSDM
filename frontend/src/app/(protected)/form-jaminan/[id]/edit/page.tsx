import FormJaminanForm from "@/components/form-jaminan-form";

export default async function EditFormJaminanPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;

  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Edit Surat Jaminan</h1>
      <FormJaminanForm formJaminanId={Number(id)} />
    </div>
  );
}
