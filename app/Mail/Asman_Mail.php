<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Asman_Mail extends Mailable
{
    use Queueable, SerializesModels;
    public $mkad;
    public $data_karyawan;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mkad,$data_karyawan)
    {
        $this->mkad = $mkad;
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
        ->view('admin/template/email_asman');
    }
}
