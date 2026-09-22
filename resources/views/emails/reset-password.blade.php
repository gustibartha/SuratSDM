<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f5; padding: 24px; margin: 0;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background: #ffffff; border-radius: 8px; padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #0f172a; margin-top: 0;">Reset Password</h2>
                            <p style="color: #334155; font-size: 14px; line-height: 1.6;">
                                Halo {{ $resetUser->name }},
                            </p>
                            <p style="color: #334155; font-size: 14px; line-height: 1.6;">
                                Kami menerima permintaan untuk mereset password akun Anda di Aplikasi Surat SDM.
                                Klik tombol di bawah ini untuk membuat password baru. Link ini berlaku selama 60 menit.
                            </p>
                            <p style="text-align: center; margin: 32px 0;">
                                <a href="{{ $resetUrl }}" style="background: #0891b2; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-size: 14px; font-weight: 600;">
                                    Reset Password
                                </a>
                            </p>
                            <p style="color: #64748b; font-size: 12px; line-height: 1.6;">
                                Jika Anda tidak meminta reset password, abaikan email ini — password Anda tidak akan berubah.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
