<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Bienvenido a Punto Actual</title>
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
                            <p style="margin:0 0 12px;">Hola{{ $subscriber->name ? ' '.$subscriber->name : '' }},</p>
                            <p style="margin:0 0 12px;">Gracias por suscribirte a <strong>Punto Actual</strong>. Desde ahora recibirás lo más importante de la actualidad nacional e internacional y las convocatorias laborales más relevantes.</p>
                            <p style="margin:0 0 12px;">También puedes visitar el portal cuando quieras y revisar las oportunidades vigentes con su carrera, requisitos y fecha de cierre.</p>
                            <p style="margin:20px 0 0;">
                                <a href="{{ url('/') }}" style="display:inline-block;background:#e11d2e;color:#ffffff;text-decoration:none;font-weight:700;padding:12px 20px;border-radius:999px;">Visitar Punto Actual</a>
                            </p>
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
