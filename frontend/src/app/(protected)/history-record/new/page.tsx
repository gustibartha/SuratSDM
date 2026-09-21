import HistoryRecordForm from "@/components/history-record-form";

export default function NewHistoryRecordPage() {
  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Tambah History Record</h1>
      <HistoryRecordForm />
    </div>
  );
}
