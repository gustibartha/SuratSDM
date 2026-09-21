import KuitansiForm from "@/components/kuitansi-form";

export default function NewKuitansiPage() {
  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Ajukan Kuitansi</h1>
      <KuitansiForm />
    </div>
  );
}
