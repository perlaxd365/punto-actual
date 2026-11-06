@php($analytics = config('analytics'))
@if($analytics['enabled'] && request()->cookie('pa_consent') === 'all')
    @if($analytics['gtm_id'])
        <script>
            (function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l !== 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{ $analytics['gtm_id'] }}');
        </script>
    @elseif($analytics['ga4_id'])
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $analytics['ga4_id'] }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', '{{ $analytics['ga4_id'] }}', { anonymize_ip: true });
        </script>
    @endif

    @if($analytics['clarity_id'])
        <script>
            (function (c, l, a, r, i, t, y) {
                c[a] = c[a] || function () { (c[a].q = c[a].q || []).push(arguments); };
                t = l.createElement(r);
                t.async = 1;
                t.src = 'https://www.clarity.ms/tag/' + i;
                y = l.getElementsByTagName(r)[0];
                y.parentNode.insertBefore(t, y);
            })(window, document, 'clarity', 'script', '{{ $analytics['clarity_id'] }}');
        </script>
    @endif

    @if($analytics['meta_pixel_id'])
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return; n = f.fbq = function () {
                    n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                };
                if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0'; n.queue = [];
                t = b.createElement(e); t.async = !0; t.src = v;
                s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s);
            }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $analytics['meta_pixel_id'] }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" alt=""
                 src="https://www.facebook.com/tr?id={{ $analytics['meta_pixel_id'] }}&ev=PageView&noscript=1">
        </noscript>
    @endif

    @if($analytics['tiktok_pixel_id'])
        <script>
            !function (w, d, t) {
                w.TiktokAnalyticsObject = t;
                var ttq = w[t] = w[t] || [];
                ttq.methods = ['page', 'track', 'identify', 'instances', 'debug', 'on', 'off', 'once', 'ready', 'alias', 'group', 'enableCookie', 'disableCookie'];
                ttq.setAndDefer = function (t, e) { t[e] = function () { t.push([e].concat(Array.prototype.slice.call(arguments, 0))); }; };
                for (var i = 0; i < ttq.methods.length; i++) ttq.setAndDefer(ttq, ttq.methods[i]);
                ttq.load = function (e, n) {
                    var i = 'https://analytics.tiktok.com/i18n/pixel/events.js';
                    ttq._i = ttq._i || {}; ttq._i[e] = []; ttq._i[e]._u = i;
                    ttq._t = ttq._t || {}; ttq._t[e] = +new Date; ttq._o = ttq._o || {}; ttq._o[e] = n || {};
                    var o = d.createElement('script'); o.type = 'text/javascript'; o.async = !0;
                    o.src = i + '?sdkid=' + e + '&lib=' + t;
                    var a = d.getElementsByTagName('script')[0]; a.parentNode.insertBefore(o, a);
                };
                ttq.load('{{ $analytics['tiktok_pixel_id'] }}');
                ttq.page();
            }(window, document, 'ttq');
        </script>
    @endif
@endif
