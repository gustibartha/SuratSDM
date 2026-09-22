<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetUser;
    public $resetUrl;

    public function __construct(User $user, string $resetUrl)
    {
        $this->resetUser = $user;
        $this->resetUrl = $resetUrl;
    }

    public function build()
    {
        return $this->subject('Reset Password - Aplikasi Surat SDM')
            ->view('emails.reset-password');
    }
}
