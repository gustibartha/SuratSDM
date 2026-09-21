import MonitoringTagihanForm from "@/components/monitoring-tagihan-form";

export default async function EditMonitoringTagihanPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;

  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Edit Monitoring Tagihan</h1>
      <MonitoringTagihanForm monitoringTagihanId={Number(id)} />
    </div>
  );
}
