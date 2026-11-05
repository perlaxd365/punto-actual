function pushEvent(event, params = {}) {
    if (window.dataLayer) {
        window.dataLayer.push({ event, ...params });
    }

    if (typeof window.gtag === 'function') {
        window.gtag('event', event, params);
    }

    if (typeof window.fbq === 'function') {
        window.fbq('trackCustom', event, params);
    }

    if (window.ttq && typeof window.ttq.track === 'function') {
        window.ttq.track(event, params);
    }
}

const page = window.PA_PAGE || null;

if (page && page.type === 'news') {
    pushEvent('news_view', {
        item_id: page.id,
        item_name: page.title,
        item_category: page.category || undefined,
        city: page.city || undefined,
        country: page.country || undefined,
    });
}

if (page && page.type === 'job') {
    pushEvent('job_view', {
        item_id: page.id,
        item_name: page.title,
        item_category: page.regime || undefined,
        department: page.department || undefined,
    });
}

document.addEventListener('click', (event) => {
    const element = event.target.closest('[data-track]');

    if (!element) {
        return;
    }

    const params = {};

    if (element.dataset.trackLabel) params.label = element.dataset.trackLabel;
    if (element.dataset.trackNetwork) params.network = element.dataset.trackNetwork;
    if (element.dataset.trackId) params.item_id = element.dataset.trackId;
    if (element.dataset.trackType) params.item_type = element.dataset.trackType;

    pushEvent(element.dataset.track, params);
});

document.addEventListener('submit', (event) => {
    const form = event.target.closest('form[data-track]');

    if (!form) {
        return;
    }

    const field = form.querySelector('[name="q"]');

    pushEvent(form.dataset.track, {
        search_term: field ? field.value : undefined,
    });
});

if (page && page.type === 'news') {
    const marks = new Set();

    const onScroll = () => {
        const height = document.documentElement.scrollHeight - window.innerHeight;

        if (height <= 0) {
            return;
        }

        const percent = Math.round((window.scrollY / height) * 100);

        [50, 90].forEach((threshold) => {
            if (percent >= threshold && !marks.has(threshold)) {
                marks.add(threshold);
                pushEvent('read_depth', { percent: threshold, item_id: page.id });
            }
        });
    };

    window.addEventListener('scroll', onScroll, { passive: true });
}
