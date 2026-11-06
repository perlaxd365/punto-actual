<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $mailSubject ?? 'Boletín Punto Actual' }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f1ee;font-family:Arial,Helvetica,sans-serif;color:#27272a;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ee;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e4e4e7;">
                    <tr>
                        <td style="padding:28px 28px 8px;">
                            <div style="font-size:22px;font-weight:900;letter-spacing:-0.5px;">
                                <span style="color:#0a0a0a;">PUNTO</span> <span style="color:#e11d2e;">ACTUAL</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 28px 28px;font-size:15px;line-height:1.75;color:#3f3f46;">
                            {!! nl2br(e($body)) !!}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;border-top:1px solid #f4f4f5;font-size:11px;line-height:1.6;color:#71717a;">
                            Recibes este correo porque te suscribiste en Punto Actual.
                            <a href="{{ $unsubscribeUrl }}" style="color:#71717a;">Cancelar suscripción</a>.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
