<?php
$current_page = 'sliders';
$title = 'Pestañas corredisas';
$breadcrumb = 'SLIDERS';
?>

<div class="page-header">
    <h1 class="page-title">Modulo en pantalla</h1>
    <div>
        <button class="btn btn-primary" data-pantallas="next" data-url="/views/dashboard.php" onclick="pantallas.addNextScreenWithUrl(this.dataset.url)">
            Siguiente → (page 5)
        </button>        
        
    </div>
</div>
  <div class="container-fluid">
    <!-- Capa donde se cargará todo -->
    <div id="pantallas-capa" aria-live="polite" aria-atomic="true">
      <div id="track" class="pantallas-track">
        <!-- Las pantallas se agregarán dinámicamente aquí -->
      </div>

      <!-- Indicador de página -->
      <div id="pageIndicator" class="page-indicator">0 / 0</div>

      <!-- Controles -->
      <div class="controls d-flex gap-2">
        <button id="prevBtn" class="btn btn-ghost btn-sm" title="Anterior" disabled>
          <i class="bi bi-chevron-left"></i>
        </button>
        <button id="nextBtn" class="btn btn-primary btn-sm">
          <i class="bi bi-chevron-right"></i> Siguiente
        </button>
      </div>
    </div>

<script>
/* Script completo: ahora el servidor devuelve HTML (text/html).
   Extrae nextUrl/prevUrl/url desde headers HTTP o <meta name="..."> dentro del HTML.
*/

const track = document.getElementById('track');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const pageIndicator = document.getElementById('pageIndicator');

let currentIndex = -1;
let totalScreens = 0;
const maxKeep = 6;
const screensMeta = [];

/* Ajustá a tu endpoint real si querés */
function defaultUrlFor(index) {
  return `/views/components/demo_slider.php?page=${index + 1}`;
}

function updateTranslate() {
  const tx = -currentIndex * 100;
  track.style.transform = `translateX(${tx}%)`;
  pageIndicator.textContent = `${currentIndex + 1} / ${totalScreens}`;
  prevBtn.disabled = currentIndex <= 0;

  const meta = screensMeta[currentIndex] || {};
  nextBtn.disabled = (meta.nextUrl === null);

  // sincronizar data-url interno
  if (meta.nextUrl !== undefined) {
    if (meta.nextUrl === null) nextBtn.removeAttribute('data-url');
    else nextBtn.dataset.url = meta.nextUrl;
  }
  if (meta.prevUrl !== undefined) {
    if (meta.prevUrl === null) prevBtn.removeAttribute('data-url');
    else prevBtn.dataset.url = meta.prevUrl;
  }

  // sincronizar externos con data-pantallas
  document.querySelectorAll('[data-pantallas="next"]').forEach(b => {
    if (meta.nextUrl === null) { b.removeAttribute('data-url'); b.disabled = true; }
    else if (meta.nextUrl !== undefined) { b.dataset.url = meta.nextUrl; b.disabled = false; }
  });
  document.querySelectorAll('[data-pantallas="prev"]').forEach(b => {
    if (meta.prevUrl === null) { b.removeAttribute('data-url'); b.disabled = true; }
    else if (meta.prevUrl !== undefined) { b.dataset.url = meta.prevUrl; b.disabled = false; }
  });
}

function createScreenShell(index) {
  const screen = document.createElement('section');
  screen.className = 'pantalla';
  screen.setAttribute('role', 'region');
  screen.setAttribute('aria-label', `Pantalla ${index + 1}`);
  screen.dataset.index = index;

  screen.innerHTML = `
    <div class="panel-card">
      <header class="d-flex align-items-start justify-content-between">
        <div>
          <h4 class="mb-1" data-slot="title">Cargando…</h4>
          <div class="text-muted small" data-slot="subtitle">Obteniendo contenido...</div>
        </div>
        <div class="text-end">
          <button class="btn btn-sm btn-outline-light" data-action="refresh" title="Recargar">
            <i class="bi bi-arrow-clockwise"></i>
          </button>
        </div>
      </header>

      <div class="flex-grow-1 overflow-auto mt-2" data-slot="body">
        <div class="d-flex h-100 w-100 align-items-center justify-content-center">
          <div class="text-center text-muted">
            <i class="bi bi-hourglass-split fs-2"></i>
            <div>Cargando contenido...</div>
          </div>
        </div>
      </div>

      <footer class="d-flex justify-content-between align-items-center mt-3 small text-muted">
        <div data-slot="meta">—</div>
        <div>
          <button class="btn btn-sm btn-outline-light" data-action="openPDF"><i class="bi bi-file-earmark-pdf"></i></button>
          <button class="btn btn-sm btn-outline-light" data-action="openImage"><i class="bi bi-images"></i></button>
        </div>
      </footer>
    </div>
  `;

  screen.addEventListener('click', (e) => {
    const actionEl = e.target.closest('[data-action]');
    if (!actionEl) return;
    const a = actionEl.dataset.action;
    const idx = parseInt(screen.dataset.index, 10);
    if (a === 'refresh') {
      fetchContentIntoScreen(idx, { forceReload: true });
    } else if (a === 'openPDF') {
      alert('Abrir PDF — reemplaza por tu lógica.');
    } else if (a === 'openImage') {
      alert('Abrir imagen — reemplaza por tu lógica.');
    }
  });

  return screen;
}

/* MOCK HTML para desarrollo si fetch falla */
function fetchContentMockHtml(page) {
  return new Promise((resolve) => {
    setTimeout(() => {
      const hasNext = page < 4;
      const html = `
        <!doctype html>
        <html>
          <head>
            <meta name="url" content="${defaultUrlFor(page-1)}">
            <meta name="next-url" content="${hasNext ? defaultUrlFor(page) : ''}">
            <meta name="prev-url" content="${page>1 ? defaultUrlFor(page-2) : ''}">
            <meta name="title" content="Pantalla #${page}">
            <meta name="subtitle" content="Contenido HTML de ejemplo (id ${page})">
            <title>Pantalla #${page}</title>
          </head>
          <body>
            <h2>Pantalla ${page}</h2>
            <p>Este es contenido de ejemplo en HTML para la pantalla <strong>#${page}</strong>.</p>
            <p>Fecha: ${new Date().toLocaleString()}</p>
          </body>
        </html>
      `;
      resolve({ html, headers: {
        'x-url': defaultUrlFor(page-1),
        'x-next-url': hasNext ? defaultUrlFor(page) : null,
        'x-prev-url': page>1 ? defaultUrlFor(page-2) : null,
        'x-title': `Pantalla #${page}`,
        'x-subtitle': `Contenido HTML de ejemplo (id ${page})`
      }});
    }, 450);
  });
}

/* fetch real: obtiene text(), intenta extraer headers y meta tags */
async function fetchContentFromServer(url) {
  try {
    const res = await fetch(url, { credentials: 'same-origin' });
    const text = await res.text();

    // recoger headers (case-insensitive)
    const getHeader = (name) => {
      const v = res.headers.get(name);
      return v === null ? undefined : v;
    };
    const headers = {
      url: getHeader('x-url') ?? getHeader('X-Url'),
      nextUrl: getHeader('x-next-url') ?? getHeader('X-Next-Url'),
      prevUrl: getHeader('x-prev-url') ?? getHeader('X-Prev-Url'),
      title: getHeader('x-title'),
      subtitle: getHeader('x-subtitle')
    };

    // parsear HTML (DOMParser en cliente)
    const parser = new DOMParser();
    const doc = parser.parseFromString(text, 'text/html');

    // Extraer meta tags si headers no dieron resultados
    const readMeta = (name) => {
      const m = doc.querySelector(`meta[name="${name}"]`);
      return m ? m.getAttribute('content') : undefined;
    };

    const data = {
      body: text,
      url: headers.url ?? readMeta('url'),
      nextUrl: (typeof headers.nextUrl !== 'undefined') ? (headers.nextUrl === 'null' ? null : headers.nextUrl) : (readMeta('next-url') || readMeta('nextUrl')),
      prevUrl: (typeof headers.prevUrl !== 'undefined') ? (headers.prevUrl === 'null' ? null : headers.prevUrl) : (readMeta('prev-url') || readMeta('prevUrl')),
      title: headers.title ?? readMeta('title') ?? (doc.querySelector('title') ? doc.querySelector('title').textContent.trim() : undefined),
      subtitle: headers.subtitle ?? readMeta('subtitle')
    };

    return data;

  } catch (err) {
    // fallback al mock HTML (dev)
    console.warn('fetch HTML fallo para', url, err);
    const m = (url || '').match(/[?&]page=(\d+)/);
    const page = m ? parseInt(m[1], 10) : (Math.floor(Math.random()*100)+1);
    return fetchContentMockHtml(page);
  }
}

/* Inserta HTML en la pantalla dada */
async function fetchContentIntoScreen(index, options = {}) {
  const shell = track.querySelector(`.pantalla[data-index="${index}"]`);
  if (!shell) return;

  const titleEl = shell.querySelector('[data-slot="title"]');
  const subtitleEl = shell.querySelector('[data-slot="subtitle"]');
  const bodyEl = shell.querySelector('[data-slot="body"]');
  const metaEl = shell.querySelector('[data-slot="meta"]');

  titleEl.textContent = 'Cargando…';
  subtitleEl.textContent = 'Obteniendo contenido...';
  bodyEl.innerHTML = `<div class="d-flex h-100 w-100 align-items-center justify-content-center"><div class="text-center text-muted"><i class="bi bi-hourglass-split fs-2"></i><div>Cargando contenido...</div></div></div>`;
  metaEl.textContent = '—';

  if (!screensMeta[index]) screensMeta[index] = { url: null, nextUrl: undefined, prevUrl: null, loaded: false };

  const fetchUrl = (options.url ?? screensMeta[index].url) ?? defaultUrlFor(index);

  try {
    const data = await fetchContentFromServer(fetchUrl);

    // data: { body (HTML string), url, nextUrl, prevUrl, title, subtitle }
    // Guardar metadata (nextUrl puede ser null explícitamente)
    screensMeta[index].url = data.url ?? fetchUrl;
    screensMeta[index].nextUrl = ('nextUrl' in data) ? (data.nextUrl === '' ? null : data.nextUrl) : screensMeta[index].nextUrl;
    screensMeta[index].prevUrl = ('prevUrl' in data) ? (data.prevUrl === '' ? null : data.prevUrl) : screensMeta[index].prevUrl;
    screensMeta[index].loaded = true;

    // Render: inyectamos el HTML dentro del slot body
    titleEl.textContent = data.title ?? `Pantalla ${index + 1}`;
    subtitleEl.textContent = data.subtitle ?? '';
    // insertamos HTML tal cual vino (podés sanitizar si es necesario)
    bodyEl.innerHTML = data.body ?? '<div>No hay contenido</div>';
    metaEl.textContent = `Última actualización: ${new Date().toLocaleString()}`;

    const detail = { index, meta: screensMeta[index] };
    window.dispatchEvent(new CustomEvent('pantallas:meta', { detail }));

  } catch (err) {
    console.error('Error al cargar pantalla', index, err);
    titleEl.textContent = 'Error al cargar';
    subtitleEl.textContent = '';
    bodyEl.innerHTML = `<div class="alert alert-danger small mb-0">No se pudo cargar el contenido. ${err.message || ''}</div>`;
    metaEl.textContent = `Error`;
  } finally {
    updateTranslate();
  }
}

async function addNextScreen(fetchUrlOverride) {
  if (nextBtn.disabled && typeof fetchUrlOverride === 'undefined') return;

  const newIndex = totalScreens;
  totalScreens++;
  const shell = createScreenShell(newIndex);
  track.appendChild(shell);
  screensMeta[newIndex] = screensMeta[newIndex] || { url: null, nextUrl: undefined, prevUrl: null, loaded: false };

  if (track.children.length > maxKeep) {
    track.removeChild(track.children[0]);
    reindexScreensAfterTrim();
  }

  let fetchUrl = null;
  if (typeof fetchUrlOverride !== 'undefined') {
    fetchUrl = fetchUrlOverride;
  } else if (newIndex - 1 >= 0 && screensMeta[newIndex - 1] && typeof screensMeta[newIndex - 1].nextUrl !== 'undefined') {
    fetchUrl = screensMeta[newIndex - 1].nextUrl;
  } else {
    fetchUrl = defaultUrlFor(newIndex);
  }

  if (fetchUrl === null) {
    totalScreens--;
    updateTranslate();
    return;
  }

  currentIndex = Math.min(totalScreens - 1, currentIndex + 1);
  updateTranslate();

  await fetchContentIntoScreen(newIndex, { url: fetchUrl });
}

function reindexScreensAfterTrim() {
  const shells = Array.from(track.children);
  const newMeta = [];
  shells.forEach((el, i) => {
    const oldIdx = parseInt(el.dataset.index, 10);
    el.dataset.index = i;
    el.setAttribute('aria-label', `Pantalla ${i + 1}`);
    newMeta[i] = screensMeta[oldIdx] || { url: null, nextUrl: undefined, prevUrl: null, loaded: false };
  });
  screensMeta.length = 0;
  Array.prototype.push.apply(screensMeta, newMeta);
  totalScreens = shells.length;
  if (currentIndex >= totalScreens) currentIndex = totalScreens - 1;
  updateTranslate();
}

async function goPreviousWithUrl(fetchUrlOverride) {
  if (currentIndex <= 0) return;
  const targetIndex = currentIndex - 1;

  let fetchUrl = screensMeta[targetIndex] && screensMeta[targetIndex].url ? screensMeta[targetIndex].url : defaultUrlFor(targetIndex);

  if (typeof fetchUrlOverride !== 'undefined') {
    fetchUrl = fetchUrlOverride;
  } else if (screensMeta[currentIndex] && typeof screensMeta[currentIndex].prevUrl !== 'undefined') {
    fetchUrl = screensMeta[currentIndex].prevUrl ?? fetchUrl;
  }

  currentIndex = targetIndex;
  updateTranslate();

  await fetchContentIntoScreen(targetIndex, { url: fetchUrl, forceReload: true });
}

/* Listeners de botones internos: usan data-url si existe */
nextBtn.addEventListener('click', () => {
  const url = nextBtn.dataset && nextBtn.dataset.url ? nextBtn.dataset.url : undefined;
  const normalized = (url === 'null') ? null : url;
  addNextScreen(normalized);
});

prevBtn.addEventListener('click', () => {
  const url = prevBtn.dataset && prevBtn.dataset.url ? prevBtn.dataset.url : undefined;
  const normalized = (url === 'null') ? null : url;
  goPreviousWithUrl(normalized);
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'ArrowRight') nextBtn.click();
  if (e.key === 'ArrowLeft') prevBtn.click();
});

(function addTouch() {
  let startX = 0, endX = 0;
  const el = document.getElementById('pantallas-capa');
  if (!el) return;
  el.addEventListener('touchstart', e => startX = e.changedTouches[0].clientX);
  el.addEventListener('touchend', e => {
    endX = e.changedTouches[0].clientX;
    const dx = endX - startX;
    if (Math.abs(dx) < 40) return;
    if (dx < 0) nextBtn.click(); else prevBtn.click();
  });
})();

(async function init() {
  await addNextScreen();
})();

window.pantallas = {
  addNextScreen,
  addNextScreenWithUrl: (url) => addNextScreen(url),
  goPrevious: () => goPreviousWithUrl(),
  goPreviousWithUrl,
  goTo: async (index) => {
    if (index < 0) return;
    while (index >= totalScreens) {
      await addNextScreen();
    }
    currentIndex = index;
    updateTranslate();
    await fetchContentIntoScreen(index, { forceReload: true });
  },
  getCurrentIndex: () => currentIndex,
  getTotal: () => totalScreens,
  meta: () => screensMeta
};
</script>

