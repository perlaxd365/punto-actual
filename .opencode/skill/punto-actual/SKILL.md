---
name: punto-actual
description: Mapa del proyecto Punto Actual (portal peruano de noticias y convocatorias laborales, Laravel + Livewire + Volt + Tailwind). Úsala al inicio de cualquier tarea sobre noticias, vacantes/convocatorias, entidades, panel admin, rutas, modelos, migración o SEO de este repositorio para ubicar archivos rápido y no re-explorar el código.
---

# Punto Actual — mapa del proyecto

Portal peruano de **noticias** y **empleos/convocatorias**. Proyecto Laravel pequeño,
rediseñado con estética premium rojo/negro y utilidades de componente en `app.css`. Este
skill es el mapa autoritativo: **no re-explores** todo el repo; lee solo lo necesario.

Para rediseñar o crear UI usa además el skill `apple-ui` (`.opencode/skill/apple-ui`).

## Stack declarado

- PHP 8.3+, Laravel `^13.0` (composer.json), MySQL/MariaDB en producción.
- Livewire `^3.8`, Livewire Volt `^1.7`, Livewire Flux `^2.0`, `spatie/laravel-permission ^6`.
- Imágenes: `cloudinary/cloudinary_php ^3.3` (cuenta compartida con Bravera).
- Indexación: Google Indexing API vía service account (HTTP + JWT, sin SDK extra).
- Frontend: Vite 7 + `laravel-vite-plugin` 2 + Tailwind CSS 4 (`@tailwindcss/vite`).
- Vistas Blade clásicas (no hay componentes Livewire/Volt propios aún).

## Integraciones y funcionalidades añadidas

- **Cloudinary**: `app/Services/CloudinaryImageService.php`. Raíz `punto-actual`,
  subcarpetas `punto-actual/noticias` y `punto-actual/entidades`. Los formularios admin
  aceptan archivo (`image_file` en noticias, `logo_file` en entidades) y guardan el
  `secure_url` en `news.image` / `entities.logo`; al reemplazar se borra el asset anterior.
  Credenciales `CLOUDINARY_*` (mismas de Bravera) en `.env`. Probado: sube y elimina.
- **Google Indexing API**: `app/Services/GoogleIndexingService.php`,
  `app/Jobs/SubmitUrlToGoogle.php`, `app/Observers/NewsObserver.php` (registrado en
  `AppServiceProvider`). Al guardar una noticia publicada (o borrarla) se ejecuta
  `dispatchAfterResponse` → `URL_UPDATED`/`URL_DELETED`; no requiere worker. Se activa con
  `GOOGLE_INDEXING_ENABLED=true` y `GOOGLE_APPLICATION_CREDENTIALS=/ruta/service-account.json`.
  Si está desactivado o sin credenciales, solo registra warning.
- **SEO**: sitemap con namespace Google News para noticias de las últimas 48 h;
  JSON-LD `NewsArticle` en noticias y `JobPosting` en convocatorias; OG/Twitter/canonical
  en el layout; RSS y `robots.txt`.
- **Redes sociales**: `config/puntoactual.php` + `SOCIAL_*` en `.env`; iconos en
  `partials/social.blade.php` (header, footer y banda de portada). Vacíos no se muestran.
- **Diseño**: base blanca con acentos rojos y negro solo para texto/divisiones; paleta
  `brand-*` e `ink-*` definida con `@theme`; clases reutilizables `.container-page`,
  `.btn-primary`, `.btn-ghost`, `.card`, `.input`, `.label`, `.badge-red`, `.badge-soft`,
  `.section-label`, `.article-body`, `.admin-table`, `.surface-soft`.
- **Paginación clara**: `resources/views/vendor/pagination/tailwind.blade.php`.
- **Analítica**: `config/analytics.php`, partials `partials/analytics-head|body.blade.php`
  (GA4, GTM, Clarity, Meta Pixel, TikTok Pixel) y `resources/js/analytics.js` (eventos
  `news_view`, `job_view`, `apply_click`, `share`, `search`, `social_click`, `cover_click`,
  `read_depth`). IDs por `.env`: `GOOGLE_ANALYTICS_ID`, `GOOGLE_TAG_MANAGER_ID`,
  `MICROSOFT_CLARITY_ID`, `META_PIXEL_ID`, `TIKTOK_PIXEL_ID`; verificación con
  `GOOGLE_SITE_VERIFICATION`/`BING_SITE_VERIFICATION`. El panel admin muestra el estado y
  enlaces a cada herramienta.
- **API para publicar**: `routes/api.php` + `app/Http/Controllers/Api/{News,Vacancy}Controller.php`
  protegidos por `api.token` (`app/Http/Middleware/AuthenticateApiToken.php`). Auth con
  `Authorization: Bearer <API_TOKEN>` (o `X-Api-Key`); el token vive en `.env` como
  `API_TOKEN` y `config('puntoactual.api_token')`. Endpoints: `GET/POST /api/news` y
  `GET/POST /api/vacancies`. El POST de noticias acepta categoría por nombre/slug (la crea
  si no existe) y aplica la lógica de portada; el de convocatorias acepta `entity_id` o
  `entity_name` (crea la entidad si falta). Imágenes por URL en `image`. Requiere
  `api: ...routes/api.php` en `bootstrap/app.php` y el alias de middleware.
- **Portadas y carrusel**: las portadas son **noticias** con `in_cover = true` (máx. 4,
  `News::cover()` ordena por `cover_at` desc). El home recibe `$coverNews` y muestra un
  carrusel con fade de 1 s y rotación cada 5 s (`data-carousel`, `resources/js/app.js`,
  `.carousel-*` en `app.css`). En admin se marca con el check "Poner en portada"; al marcar
  una nueva se pone primera y se recorta a 4 (`enforceCoverLimit`). Fallback: noticias
  destacadas con imagen y luego portada de muestra.
- **Imágenes de muestra**: `public/images/samples/{cover-1..4,news-1,vacancy-1,entity-cover-1}.jpg`
  generadas localmente y usadas por el seeder. Los uploads reales siguen yendo a Cloudinary.
- **Portadas por entidad**: noticias usan `news.image`; convocatorias `vacancies.image`
  (subida `image_file`, carpeta `punto-actual/convocatorias`); entidades
  `entities.cover_image` (subida `cover_file`, carpeta `punto-actual/entidades`), además de `logo`.

## Estado del repo (ya restaurado y funcionando)

El proyecto fue completado con un esqueleto Laravel 13 limpio: existen `artisan`,
`bootstrap/`, `config/`, `public/index.php`, `tests/`, `vendor/`, `node_modules/`,
`composer.lock`, `app/Models/User.php`, `app/Http/Controllers/Controller.php`,
`app/Providers/AppServiceProvider.php` y las migraciones base (users/cache/jobs).
También se corrigieron los dobles backslashes que rompían `routes/web.php` y
`database/seeders/DatabaseSeeder.php`.

- `.env` local usa **SQLite** (`database/database.sqlite`) con sesión/caché/cola sobre
  `database`. `.env.example` sigue siendo la plantilla de producción (MySQL): no la
  sobrescribas con la local.
- Hay login mínimo en `GET/POST /login` (`routes/web.php` + `views/auth/login.blade.php`),
  logout en `POST /logout`, y enlace "Panel/Salir" en el layout. Credenciales del seeder:
  `admin@puntoactual.pe` / `Cambiar123!` (cambiar en producción).
- `/admin` y los CRUD responden correctamente tras iniciar sesión.
- Servidor local verificado en `http://127.0.0.1:8000` (todas las rutas públicas 200).

## Comandos (verificados)

```bash
composer install
php artisan migrate:fresh --seed --force
npm install && npm run build
php artisan serve                    # http://127.0.0.1:8000
# dev: npm run dev   |  tests: php artisan test   |  estilo: ./vendor/bin/pint
```

`deploy/install.sh` automatiza install + keygen; `deploy/nginx-puntoactual.conf` sirve
`/var/www/punto-actual/public`, PHP-FPM 8.3, `client_max_body_size 20M`.

## Mapa de archivos (todos los archivos fuente)

```
routes/web.php                         rutas públicas, login/logout y grupo admin
routes/api.php                         API token: GET/POST /news y /vacancies
app/Models/{News,Vacancy,Category,Entity}.php + User.php
app/Http/Controllers/
  NewsController.php  VacancyController.php  EntityController.php  SeoController.php
  Admin/{Dashboard,News,Vacancy,Entity}Controller.php
  Api/{News,Vacancy}Controller.php
app/Http/Middleware/AuthenticateApiToken.php
bootstrap/app.php                      registra api routes + alias api.token
app/Services/CloudinaryImageService.php
app/Services/GoogleIndexingService.php
app/Jobs/SubmitUrlToGoogle.php
app/Observers/NewsObserver.php        indexado automático al guardar/borrar noticia
app/Providers/AppServiceProvider.php  registra NewsObserver
config/services.php                   cloudinary + google.indexing
config/puntoactual.php                redes sociales, nombre y sitio
config/analytics.php                  GA4/GTM/Clarity/Meta/TikTok + verificaciones
database/migrations/0001_01_01_* (users/cache/jobs) + 2026_11_05_000001_create_portal_tables.php
  + 2026_11_06_000001_add_covers_and_images.php (vacancies.image, entities.cover_image)
  + 2026_11_06_000002_add_location_to_news.php (city, country)
  + 2026_11_06_000003_cover_from_news.php (news.in_cover/cover_at; elimina tabla covers)
database/seeders/DatabaseSeeder.php
resources/views/auth/login.blade.php  login mínimo del panel
resources/views/
  layouts/app.blade.php                layout único (SEO/OG, header, footer, @stack head)
  partials/social.blade.php            iconos Facebook/Instagram/TikTok/X/YouTube
  partials/analytics-head.blade.php    scripts GA4/GTM/Clarity/Meta/TikTok (condicional)
  partials/analytics-body.blade.php    noscript de GTM
  home.blade.php  news/{index,show,international}  vacancies/{index,show}  entities/show
  admin/{dashboard, news/{index,form}, vacancies/{index,form}, entities/{index,form}}
  seo/{sitemap,feed}
  vendor/pagination/tailwind.blade.php paginación clara
resources/css/app.css                  Tailwind 4, @theme rojo/negro y clases componentes
resources/js/{app,bootstrap,analytics}.js  carruseles, sliders y tracking de eventos
public/robots.txt                      Disallow /admin + sitemap
docs/ROADMAP.md                        fases 1–4 y lineamientos de seguridad
.opencode/skill/apple-ui/SKILL.md      guía de diseño premium rojo/negro
```

## Modelo de datos

- `categories`: name, slug(unique), description, type (default `news`).
- `entities`: name, slug(unique), entity_type, logo, cover_image, description, website, official_jobs_url, region.
- `news`: category_id→categories(nullOnDelete), user_id→users(nullOnDelete), title(220),
  slug(unique), excerpt(500), body(longText), image, city, country, source_name/url,
  status(`draft`/`published`), is_featured, in_cover, cover_at, published_at,
  seo_title, seo_description(320). `scopeCover()` = in_cover con orden cover_at desc.
  Índice (status, published_at). `News::place_label` devuelve el país si la categoría es
  `internacional` y si no la ciudad; se muestra como badge en tarjetas y ficha.
- `vacancies`: entity_id→entities(cascade), title, slug(unique), image, reference_code,
  employment_regime, employment_type, description/functions/requirements,
  education_level, professional_career, general_experience, specific_experience,
  courses_training, knowledge, salary_min/max (decimal), salary_currency(`PEN`),
  vacancies_count, department/province/district/workplace/work_mode,
  published_at, application_start_at, application_end_at, schedule,
  official_url (requerido), bases_url, attachments(json), status
  (`draft`|`published`|`expiring`|`finished`|`cancelled`), is_featured,
  editorial_verified_at. Índices (status, application_end_at), (department, employment_regime).

Scopes clave:

- `News::published()` = status published + published_at ≤ ahora. `featured()` = is_featured.
- `Vacancy::published()` = status en `published|expiring` **y** (`application_end_at` nulo o
  ≥ hoy). `Vacancy::salary_label` (accessor) formatea "S/ x – S/ y" o "No especificada".

Relaciones: News belongsTo Category y User (`author`); Vacancy belongsTo Entity;
Entity hasMany Vacancy; Category hasMany News.

## Rutas y parámetros (nombres exactos)

Públicas:
- `GET /` → `home` (inline closure en web.php).
- `GET /noticias` → `news.index`; `GET /noticias/{news:slug}` → `news.show`.
- `GET /internacional` → `news.international` (solo noticias de la categoría `internacional`).
- `GET /empleos` → `vacancies.index`; `GET /empleos/{vacancy:slug}` → `vacancies.show`.
- `GET /entidades/{entity:slug}` → `entities.show`.
- `GET /categoria/{category:slug}` → `categories.show` (usa vista `news.index`).
- `GET /sitemap.xml` → `sitemap`; `GET /feed.xml` → `feed`.

Admin (`auth`, prefijo `/admin`, nombre `admin.`): `dashboard`, resource
`noticias`→`admin.news.*`, `convocatorias`→`admin.vacancies.*`, `entidades`→`admin.entities.*`
(sin `show`). **Ojo con los binding por nombre de parámetro**:
`Route::resource('noticias', ...)` genera `{noticia}`, `convocatorias`→`{convocatoria}`,
`entidades`→`{entidad}`; por eso los controladores admin tipan `News $noticia`,
`Vacancy $convocatoria`, `Entity $entidad` (el nombre de la variable debe coincidir con el
parámetro de ruta). En público se usa binding explícito por `slug`.

## Convenciones del código

- `Admin\NewsController`/`Admin\EntityController` y las vistas fueron reescritos legibles;
  `VacancyController`, `SeoController` y el seeder siguen comprimidos en una línea. Respeta
  el estilo de cada archivo. Una clase por archivo, namespace `App\...`.
- Vistas: `@extends('layouts.app')`, secciones `@section('title'|'description'|'content')`.
  Usa `@push('head')` para meta/JSON-LD por página.
- Tailwind inline + clases de componente de `app.css`. Paleta: fondo blanco, superficies
  suaves `bg-zinc-50`/`bg-black/[0.02]`, texto `text-ink-950` y `text-zinc-500/600`, bordes
  `border-black/5|10|15` y acento rojo `brand-500/600/700`. El negro es para texto y
  divisiones, **no** para fondos extensos. Tipografía Inter. Contenedor `container-page`.
- Botones/enlaces: `.btn-primary` (rojo), `.btn-ghost` (blanco con borde), `.link-red`;
  inputs `.input` y etiquetas `.label`. No uses `slate`/`amber`/azul marino.
- Slug manual con `Str::slug(...).'-'.Str::lower(Str::random(5))` en store (News/Vacancy);
  Entity usa random(4). No hay `slug` automático en model.
- Texto de UI en español, tono editorial peruano. Validaciones inline en cada controlador
  (`private function validated`) usando `$request->validate([...])`; no hay FormRequests.
- Imágenes: subida opcional a Cloudinary (`image_file`/`logo_file`) o URL manual
  (`image`/`logo`). La URL de Cloudinary se guarda como string; el asset anterior se elimina
  al reemplazar. Validación: `image|mimes:jpg,jpeg,png,webp,avif|max:5120`.
- La noticia demo del seeder queda `published`; la convocatoria demo queda `draft` a propósito.
- No hay tests ni factories propias. Si agregas tests usa `php artisan test`.

## Tareas típicas → dónde tocar

- Portada: `routes/web.php` (closure `home`) + `resources/views/home.blade.php`.
- Listado/búsqueda noticias: `NewsController::index` + `views/news/index.blade.php`
  (filtro `q` sobre title/excerpt).
- Ficha noticia y SEO: `NewsController::show` + `views/news/show.blade.php` (usa
  `seo_title`/`seo_description`, renderiza `body` con `{!! !!}` sin sanitizar).
  `show()` carga `$similar` (misma categoría), `$recommended` (otras categorías) y
  `$vacancies` (convocatorias recientes). La ficha usa un layout 70/30
  (`lg:grid-cols-[minmax(0,7fr)_minmax(0,3fr)]`): artículo a la izquierda y sidebar
  sticky a la derecha con sliders de recomendaciones
  (`news/partials/sidebar-slider.blade.php`, `data-slider`, flechas y dots) y bloque de
  convocatorias. La lógica de sliders/carruseles está en `resources/js/app.js`.
- Listado/filtros empleos: `VacancyController::index` (`q`, `region`→department,
  `regime`→employment_regime, `entity`→slug) + `views/vacancies/index.blade.php`
  (lista de regiones hardcodeada en el controlador).
- Ficha convocatoria: `VacancyController::show` + `views/vacancies/show.blade.php`
  (bloques de texto por campo, enlaces `official_url`/`bases_url`). Usa layout 70/30 con
  sidebar sticky: `$recommended` (otro régimen) y `$similar` (mismo departamento/régimen)
  en sliders (`vacancies/partials/sidebar-slider.blade.php`), recomendadas primero, más
  una tarjeta de entidad convocante. El botón dice "Ir a postular".
- Panel admin CRUD: controladores `app/Http/Controllers/Admin/*` y
  `resources/views/admin/**`. Formularios usan `@csrf` + `@method('PUT')` y `old(...)`.
- Entidades: `EntityController`/`Admin\EntityController` + `views/entities/show.blade.php`.
- SEO: `SeoController::sitemap/feed`, `views/seo/*`, `public/robots.txt`, metadatos OG en
  `layouts/app.blade.php`, JSON-LD en `news/show` y `vacancies/show`.
- Subida de imágenes: `Admin\NewsController` (`image_file`) / `Admin\EntityController`
  (`logo_file`) + `CloudinaryImageService`; formularios con `enctype="multipart/form-data"`.
- Indexación Google: `NewsObserver` (ya registrado) + `SubmitUrlToGoogle`. No dupliques el
  envío en los controladores.
- Redes sociales: edita `SOCIAL_*` en `.env` (o `config/puntoactual.php`); no toques el
  partial si solo cambian URLs.
- Nuevo campo en vacantes: migración nueva (no editar la existente), `$fillable` en
  `Vacancy`, validación en `Admin\VacancyController::validated`, textarea/input en
  `admin/vacancies/form.blade.php`, y bloque en `vacancies/show.blade.php`.
- Noticias internacionales: categoría `Internacional` (slug `internacional`), método
  `NewsController::international`, vista `news/international.blade.php` y sección
  "Internacional" en home (3 más recientes). El home recibe `$international` desde el
  closure de `routes/web.php`; enlaza en header y footer.
- Portadas/carrusel: `routes/web.php` (closure home carga `$coverNews` con
  `News::cover()`), carrusel en `home.blade.php`, JS en `resources/js/app.js`
  (`.carousel-*` en `app.css`). El check "Poner en portada" (`in_cover`) está en
  `admin/news/form`; el límite de 4 y el orden se ajustan en
  `Admin\NewsController` (`cover_at`, `enforceCoverLimit`).
- Ubicación de noticias: campos `city` y `country` en `admin/news/form`; el badge usa
  `$noticia->place_label` (país para Internacional, ciudad para el resto).
- Imagen de convocatoria/entidad: formularios `admin/vacancies/form` (`image_file`) y
  `admin/entities/form` (`cover_file`); se muestran en `vacancies/index|show` y `entities/show`.
  El botón público de la convocatoria dice **"Ir a postular"**.

## Deuda técnica / riesgos a tener en cuenta

- `{!! $news->body !!}` sin sanitizar → XSS si el editor no es confiable (ROADMAP Fase 2
  pide editor sanitizado y roles).
- `spatie/laravel-permission` está en composer pero sin roles/permisos implementados.
- `Vacancy::published()` llama `today()` en cada query; considera scheduler que pase
  `expiring→finished` (ROADMAP Fase 3).
- `SeoController` carga **todas** las noticias/vacantes para el sitemap (sin chunking).
- Admin no tiene eliminación en las vistas de listado (solo Editar) aunque el controlador
  sí implementa `destroy`. El auth es mínimo: no hay registro público, recuperación de
  contraseña, verificación de correo ni roles/permisos activos.
- Datos del seeder son ficticios y marcados como demostración; admin
  `admin@puntoactual.pe` / `Cambiar123!` (cambiar en producción). No hay registro público.
- Google Indexing API solo admite oficialmente `JobPosting` y `BroadcastEvent`; para noticias
  generales Google puede no procesar el aviso. Complementa con sitemap + Search Console y,
  si se requiere News, cumplir los requisitos de Google News. Además hay que dar de alta la
  service account como propietaria en Search Console.
- Al desplegar con MySQL cambia `.env` (el local usa SQLite) y ejecuta `php artisan config:clear`.
- Requisito legal del proyecto: nunca mostrar una convocatoria vencida como vigente; siempre
  enlazar y atribuir la fuente oficial.

## Eficiencia (regla para el agente)

1. Usa este mapa para ir directo al archivo. No hagas búsquedas globales ni leas
   `node_modules`/`vendor`.
2. Para cambios de una sola capa lee solo el controlador + su vista + el modelo/migración
   implicados, en una sola tanda de lecturas paralelas.
3. Mantén el estilo comprimido existente y no agregues comentarios salvo que se pidan.
4. Si el cambio toca datos, actualiza en conjunto: migración, `$fillable`, validación,
   formulario admin y vista pública.
