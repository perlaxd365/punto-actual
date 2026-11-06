@php($slotId = config('ads.slots.'.($adSlot ?? '')))
@if(config('ads.enabled') && config('ads.client') && $slotId && request()->cookie('pa_consent') === 'all')
    <div class="my-8 rounded-2xl border border-black/5 bg-black/[0.015] p-3">
        <p class="mb-1 text-[10px] font-bold uppercase tracking-wider text-zinc-400">Publicidad</p>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="{{ config('ads.client') }}"
             data-ad-slot="{{ $slotId }}"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>
@endif
