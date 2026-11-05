# Hoja de ruta de producción

## Fase 1 — Base editorial y laboral
Incluida en este starter: portada, noticias, categorías, directorio de entidades, fichas de convocatoria, filtros básicos y CRUD inicial.

## Fase 2 — Operación editorial
- Roles y permisos granulares (administrador, editor, periodista, verificador).
- Flujo borrador → revisión → publicación.
- Carga de imágenes y PDF con validación MIME, antivirus y límites.
- Historial de cambios, trazabilidad de fuentes y registro de quién verificó.
- Editor enriquecido sanitizado; no permitir HTML arbitrario a roles no confiables.

## Fase 3 — Empleabilidad
- Alertas por correo y notificaciones web por profesión, entidad y región.
- Favoritos y búsquedas guardadas.
- Comparador de convocatorias.
- Importador asistido de fuentes oficiales, deduplicación y cola de revisión humana.
- Detección de fechas vencidas y cambio automático de estado mediante Scheduler.

## Fase 4 — Audiencia y monetización
- Analítica editorial y de búsqueda.
- Espacios publicitarios etiquetados.
- Newsletter, RSS ampliado y API pública de lectura.
- CDN de imágenes, caché y búsqueda indexada (Meilisearch / Typesense).

## Seguridad y cumplimiento
- HTTPS, backups diarios y prueba de restauración.
- MFA para administradores, rate limiting y logs.
- Política de privacidad, cookies y tratamiento de datos personales conforme a la normativa peruana.
- No republicar íntegramente contenidos protegidos; resumir con atribución y enlazar fuentes.
- Nunca mostrar una convocatoria como vigente si su fecha de cierre pasó o la fuente no fue validada.
