<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Mkad_Mail extends Mailable
{
    use Queueable, SerializesModels;
    public $mkad;
    public $formjaminan;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mkad,$formjaminan)
    {
        $this->mkad = $mkad;
        $this->formjaminan = $formjaminan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Aplikasi Surat Jaminan Kesehatan')
        ->view('admin/template/email_mkad');
    }
}
