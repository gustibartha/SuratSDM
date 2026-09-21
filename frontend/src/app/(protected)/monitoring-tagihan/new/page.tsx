import MonitoringTagihanForm from "@/components/monitoring-tagihan-form";

export default function NewMonitoringTagihanPage() {
  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Tambah Monitoring Tagihan</h1>
      <MonitoringTagihanForm />
    </div>
  );
}
