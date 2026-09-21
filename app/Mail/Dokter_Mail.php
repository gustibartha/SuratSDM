<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Dokter_Mail extends Mailable
{
    use Queueable, SerializesModels;
    public $dokter;
    public $data_karyawan;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dokter,$data_karyawan)
    {
        $this->dokter = $dokter;
        $this->data_karyawan = $data_karyawan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Aplikasi Surat Jaminan Kesehatan')
        ->view('admin/template/email_dokter');
    }
}
