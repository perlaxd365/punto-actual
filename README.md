# PUNTO ACTUAL
Portal peruano de noticias y convocatorias laborales. Base de proyecto Laravel 13 + Livewire 3 + Volt + Tailwind CSS 4.

## Requisitos
- PHP 8.3+, Composer 2
- MySQL 8 / MariaDB
- Node.js 20+ y npm
- Extensiones PHP habituales de Laravel: ctype, curl, dom, fileinfo, filter, hash, mbstring, openssl, pcre, PDO, session, tokenizer, xml, pdo_mysql, gd o imagick.

## Instalación rápida
Este repositorio contiene el código de la aplicación y sus módulos, no incluye `vendor/` ni `node_modules/`.
1. Copiar el proyecto al servidor.
2. Ejecutar `bash deploy/install.sh` (o seguir los pasos de abajo).
3. Configurar `.env` con las credenciales de MySQL y el dominio.
4. Ejecutar `php artisan storage:link`.
5. Configurar el document root del dominio a `public/`.

Pasos manuales:
```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
# Editar .env: APP_URL, DB_*, MAIL_*
php artisan migrate --seed --force
npm install
npm run build
php artisan storage:link
php artisan optimize
```

## Acceso inicial
El seeder crea `admin@puntoactual.pe` con contraseña temporal `Cambiar123!`.
Cambiarla inmediatamente después del primer ingreso. En producción, no habilitar registro público de usuarios.

## Módulos incluidos
- Portada editorial responsive, noticias y categorías.
- Directorio de entidades.
- Convocatorias con régimen, remuneración, plazas, ubicación, formación, experiencia general y específica, funciones, requisitos, cronograma, documentos y URL oficial.
- Estados de convocatoria: borrador, vigente, por vencer, finalizada, cancelada.
- Panel protegido para gestión de noticias, convocatorias y entidades.
- Búsqueda y filtros de convocatorias.
- Sitemap XML, RSS básico, robots.txt y metadatos SEO.
- Seeders de demostración (contenido ficticio, claramente marcado como demostración).

## Importante
- Los datos de demostración no son convocatorias reales.
- El sistema no representa al Estado peruano ni garantiza la autenticidad de ofertas. El editor debe comprobar fuente, fecha y enlace oficial antes de publicar.
- La importación automatizada está preparada como siguiente integración; no publica scraping ni contenido de terceros automáticamente. Respetar términos, robots.txt, derechos de autor y fuentes oficiales.
- La base entregada es un starter funcional de aplicación. La instalación final debe probarse en el VPS objetivo y completar SMTP, backups, HTTPS, CDN y monitoreo.
