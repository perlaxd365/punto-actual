@if(! request()->cookie('pa_consent'))
    <div id="pa-cookie" class="fixed inset-x-3 bottom-3 z-[70] sm:inset-x-6 sm:bottom-6">
        <div class="mx-auto max-w-4xl rounded-2xl border border-black/10 bg-white/95 p-4 shadow-[0_20px_60px_-25px_rgba(0,0,0,0.45)] backdrop-blur-xl sm:p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm leading-relaxed text-zinc-600">
                    <span class="font-bold text-ink-950">Tu privacidad importa.</span>
                    Usamos cookies necesarias para el sitio y, con tu permiso, cookies de medición para mejorar la experiencia.
                    <a href="{{ route('pages.privacidad') }}" class="font-semibold text-brand-600 underline underline-offset-4">Política de privacidad</a>.
                </p>
                <div class="flex shrink-0 gap-2">
                    <button type="button" data-cookie="necessary" class="btn-ghost !px-4 !py-2">Solo necesarias</button>
                    <button type="button" data-cookie="all" class="btn-primary !px-4 !py-2">Aceptar todas</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function () {
            var el = document.getElementById('pa-cookie');
            if (!el) return;
            el.querySelectorAll('[data-cookie]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var choice = button.getAttribute('data-cookie');
                    document.cookie = 'pa_consent=' + choice + '; path=/; max-age=31536000; SameSite=Lax';
                    el.remove();
                    if (choice === 'all') {
                        window.location.reload();
                    }
                });
            });
        })();
    </script>
@endif
