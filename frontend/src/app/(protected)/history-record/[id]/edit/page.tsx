import HistoryRecordForm from "@/components/history-record-form";

export default async function EditHistoryRecordPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;

  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Edit History Record</h1>
      <HistoryRecordForm recordId={Number(id)} />
    </div>
  );
}
