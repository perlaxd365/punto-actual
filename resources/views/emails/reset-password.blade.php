<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Restablece tu contraseña</title>
</head>
<body style="margin:0;padding:0;background:#f4f1ee;font-family:Arial,Helvetica,sans-serif;color:#27272a;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ee;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e4e4e7;">
                    <tr>
                        <td style="padding:28px 28px 8px;">
                            <div style="font-size:22px;font-weight:900;letter-spacing:-0.5px;">
                                <span style="color:#0a0a0a;">PUNTO</span> <span style="color:#e11d2e;">ACTUAL</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 28px 28px;font-size:15px;line-height:1.7;color:#3f3f46;">
                            <p style="margin:0 0 12px;">Hola,</p>
                            <p style="margin:0 0 12px;">Recibimos una solicitud para restablecer la contraseña de la cuenta <strong>{{ $email }}</strong> en Punto Actual.</p>
                            <p style="margin:20px 0;">
                                <a href="{{ $resetUrl }}" style="display:inline-block;background:#e11d2e;color:#ffffff;text-decoration:none;font-weight:700;padding:12px 20px;border-radius:999px;">Restablecer contraseña</a>
                            </p>
                            <p style="margin:0 0 12px;">Este enlace vencerá en {{ $expires }} minutos. Si el botón no funciona, copia y pega esta dirección en tu navegador:</p>
                            <p style="margin:0 0 12px;font-size:12px;line-height:1.6;word-break:break-all;color:#71717a;">{{ $resetUrl }}</p>
                            <p style="margin:0;">Si no solicitaste este cambio, puedes ignorar este correo. Tu contraseña actual seguirá funcionando.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;border-top:1px solid #f4f4f5;font-size:11px;line-height:1.6;color:#71717a;">
                            Por seguridad, nunca compartas este enlace con nadie. Este mensaje fue enviado automáticamente por Punto Actual.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
