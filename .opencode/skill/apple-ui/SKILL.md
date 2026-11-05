---
name: apple-ui
description: Diseño e implementación de UI minimalista, premium e inspirada en Apple para Punto Actual (portal de noticias y empleos en Laravel 13 + Blade + Tailwind CSS 4). Úsala al crear o rediseñar vistas, componentes, tarjetas, formularios y páginas del portal, especialmente con la paleta rojo/negro.
---

# Apple UI — Punto Actual

Diseño minimalista, premium y de alta legibilidad inspirado en las Human
Interface Guidelines de Apple, **sin copiar interfaces propietarias**. El portal
es editorial: debe sentirse rápido, sobrio y confiable.

## Principios

- Claridad, deferencia, profundidad, consistencia y simplicidad.
- Mobile-first; todos los componentes funcionan en móvil, tablet y escritorio.
- Jerarquía tipográfica fuerte, mucho aire y contraste alto.
- Movimiento discreto (150–300 ms, solo opacity/transform/scale/blur).
- Accesibilidad: HTML semántico, foco visible, etiquetas, contraste suficiente.

## Stack del proyecto

- Laravel 13, Blade clásico, Vite 7, Tailwind CSS 4.
- No hay componentes Vue/React ni Livewire propios: usa Blade + clases Tailwind.
- Estilos globales en `resources/css/app.css`; tokens con `@theme`.
- Vistas en `resources/views/`; layout único `layouts/app.blade.php`.

## Paleta obligatoria: blanco, rojo y negro

Base **blanca**; el **negro** se reserva para texto, bordes y líneas de división
(no para fondos extensos). El **rojo** es el único acento, alternando tonos.
Usa los tokens de `resources/css/app.css` (familia `brand` = rojos, `ink` = negros).

```
brand-50   #FFF1F1   (fondo de badges suaves)
brand-100  #FFE0E0
brand-200  #FFC4C4
brand-300  #FF9A9A
brand-400  #FF5C5C
brand-500  #FF2D2D   (acento/hover)
brand-600  #E11D2E   (acción/CTA principal)
brand-700  #B3121F   (texto rojo sobre blanco)
brand-800  #8B0E18
brand-900  #650A12
brand-950  #3D060B

ink-950    #0A0A0C   (texto principal)
ink-900    #18181B
ink-800    #27272A
ink-700    #3F3F46
ink-600    #52525B
```

Reglas de uso:

- Fondo general `bg-white`; superficies suaves `bg-zinc-50` o `bg-black/[0.02]`.
- Texto principal `text-ink-950`; secundario `text-zinc-500/600`.
- Bordes y divisiones en `border-black/5`, `border-black/10` o `border-black/15`.
- Acento y CTA en `brand-600` (hover `brand-500`); texto rojo legible `brand-700`.
- El rojo se usa con moderación: un foco por sección. Nunca fondos negros grandes.

## Superficies y profundidad

- Radios: tarjetas `rounded-2xl`, contenedores `rounded-3xl`, botones `rounded-full`.
- Sombras suaves y difusas: `shadow-[0_1px_2px_rgba(0,0,0,.04),0_22px_50px_-32px_rgba(0,0,0,.35)]`.
- Separación por línea divisoria negra tenue (`border-black/10`) + cambio de tono,
  no por sombras excesivas.
- Header sticky blanco translúcido: `bg-white/85 backdrop-blur-xl border-b border-black/10`.
- Gradientes permitidos: halos rojos muy suaves sobre blanco (`from-brand-50`,
  `rgba(225,29,46,0.15)`) y `bg-gradient-to-br`; nunca arcoíris.
- Acento superior fino rojo (`h-0.5 bg-gradient-to-r from-brand-700 via-brand-500 to-brand-700`).

## Tipografía

- Inter (ya cargada). Títulos `font-black tracking-tight`, cuerpo `leading-relaxed`.
- H1 hero `text-4xl md:text-6xl`; H2 sección `text-3xl`; tarjeta `text-lg`.
- Etiquetas de sección: `text-xs font-bold uppercase tracking-[0.2em] text-brand-600`.
- Cuerpo de noticia: usa `.article-body` (definido en app.css) en vez de clases
  `prose` (el plugin typography no está instalado).

## Componentes

- **Header**: sticky blanco con división negra inferior y acento rojo superior, logo
  "PUNTO ACTUAL" (ACTUAL en rojo), nav en `text-zinc-600`, iconos sociales discretos,
  CTA "Buscar empleo" rojo.
- **Card noticia**: imagen con `aspect-[16/10]`, contenido sobre blanco, categoría en
  rojo, título `text-ink-950`, hover con elevación mínima y borde rojo tenue.
- **Card empleo**: superficie blanca, badge de régimen rojo suave, salario en badge
  neutro, fecha de cierre visible; toda la tarjeta es enlace.
- **Botones**: primario rojo (`.btn-primary`), secundario blanco con borde
  (`.btn-ghost`), ambos `rounded-full`.
- **Formularios admin**: `.input` blanco con borde `border-black/15`, foco rojo;
  etiquetas `.label`; errores en `text-brand-700`.
- **Estados vacíos**: texto sobrio + CTA, nunca una caja vacía.
- **Badges**: `.badge-red` (rojo suave) y `.badge-soft` (negro 4%).

## Reglas de implementación

1. Reutiliza el layout y las clases existentes antes de crear variantes.
2. Mantén rutas, nombres de campos y lógica de negocio intactos por cambios visuales.
3. Imágenes siempre con `object-cover`, `loading="lazy"` y `alt` descriptivo.
4. No introduzcas frameworks CSS ni JS adicionales.
5. No uses emojis en la UI salvo que se pidan.
6. Conserva el responsive con `max-w-7xl`, grids `md:`/`lg:` y nav móvil usable.
7. Verifica contraste y foco de teclado en cada componente interactivo.

## Animación

- Preferir transiciones de `opacity`, `transform`, `scale` y `blur`.
- Evitar rebotes, parallax y animaciones largas.
- Hover de tarjetas: `transition duration-300 hover:-translate-y-1`.
