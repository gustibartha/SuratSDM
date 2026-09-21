import VisaForm from "@/components/visa-form";

export default function NewVisaPage() {
  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold text-zinc-800">Buat Surat Visa</h1>
      <VisaForm />
    </div>
  );
}
