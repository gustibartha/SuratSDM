<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Sm_Mail extends Mailable
{
    use Queueable, SerializesModels;
    public $sm;
    public $data_karyawan;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sm,$data_karyawan)
    {
        $this->sm = $sm;
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
        ->view('admin/template/email_sm');
    }
}
