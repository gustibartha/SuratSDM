import KaryawanForm from "@/components/karyawan-form";

export default async function EditKaryawanPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;

  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Edit Karyawan</h1>
      <KaryawanForm karyawanId={Number(id)} />
    </div>
  );
}
