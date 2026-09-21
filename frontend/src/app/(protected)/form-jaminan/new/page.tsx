import FormJaminanForm from "@/components/form-jaminan-form";

export default function NewFormJaminanPage() {
  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Buat Surat Jaminan</h1>
      <FormJaminanForm />
    </div>
  );
}
