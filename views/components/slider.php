<?php
$current_page = 'sliders';
$title = 'Pestañas corredisas';
$breadcrumb = 'vamos a ver que sale';
?>

<div class="page-header">
    <h1 class="page-title">Gestión de Roles y Permisos</h1>
    <div>
        <button type="button" class="btn btn-primary" onclick="openRoleModal()">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            nuevo slider
        </button>
    </div>
</div>
  <div class="container">
    <h3 class="mb-3">Pantallas laterales dinámicas (demo)</h3>

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

    <!-- Ejemplo de botones fuera de la capa para demostración -->
    <div class="mt-3">
      <small class="text-muted">En tu app, puedes disparar <code>addNextScreen()</code> desde cualquier botón.</small>
    </div>
  </div>

  <!-- Bootstrap JS (Popper incluido) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    /**
     * Implementación:
     * - track: contenedor flex con ancho variable (cada .pantalla = 100%)
     * - currentIndex: índice visible (0-based)
     * - addNextScreen() añade la pantalla y la carga desde fetchContent(index)
     * - loadContent simula (o realiza) fetch; reemplaza por tu propia API
     * - maxKeep: cantidad máxima de pantallas en DOM para no crecer infinito (opcional)
     */

    const track = document.getElementById('track');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const pageIndicator = document.getElementById('pageIndicator');

    let currentIndex = -1;         // índice actual visible
    let totalScreens = 0;          // total creadas
    const maxKeep = 5;             // mantiene últimas N pantallas en DOM (ajustable)

    // Helper: actualiza transform para mostrar pantalla actual
    function updateTranslate() {
      const tx = -currentIndex * 100;
      track.style.transform = `translateX(${tx}%)`;
      pageIndicator.textContent = `${currentIndex + 1} / ${totalScreens}`;
      prevBtn.disabled = currentIndex <= 0;
      // si no hay más contenido por cargar podrías desactivar nextBtn
    }

    // Simula fetch; reemplaza con tu API real:
    // Ejemplo real: return fetch(`/api/panel?id=${page}`).then(r=>r.json())
    function fetchContentMock(page) {
      return new Promise((resolve) => {
        // Simula retraso de red
        setTimeout(() => {
          resolve({
            title: `Pantalla #${page}`,
            subtitle: `Contenido cargado dinámicamente (id ${page})`,
            body: `<p>Este es un contenido de ejemplo para la pantalla <strong>#${page}</strong>. Aquí puedes renderizar formularios, imágenes, PDFs incrustados, listas, tablas, gráficos, etc.</p>
                   <p>Fecha: ${new Date().toLocaleString()}</p>`,
            meta: {
              author: 'Sistema',
              updated: new Date().toISOString()
            }
          });
        }, 650); // ~650ms
      });
    }

    // Crea la estructura DOM de una pantalla vacía y devuelve el elemento
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

      // Delegación de eventos dentro de la pantalla (ej: refresh)
      screen.addEventListener('click', (e) => {
        const action = e.target.closest('[data-action]');
        if (!action) return;
        const a = action.dataset.action;
        if (a === 'refresh') {
          // recargar contenido
          loadContentIntoScreen(parseInt(screen.dataset.index, 10));
        } else if (a === 'openPDF') {
          // ejemplo: abrir PDF dentro de la misma capa o modal
          alert('Abrir PDF (ejemplo). Reemplaza por tu lógica.');
        } else if (a === 'openImage') {
          alert('Abrir imagen (ejemplo). Reemplaza por tu lógica.');
        }
      });

      return screen;
    }

    // Carga contenido (fetch) y lo pone dentro del shell
    async function loadContentIntoScreen(index) {
      const shell = track.querySelector(`.pantalla[data-index="${index}"]`);
      if (!shell) return;

      // Opcional: muestra loader interno
      const titleEl = shell.querySelector('[data-slot="title"]');
      const subtitleEl = shell.querySelector('[data-slot="subtitle"]');
      const bodyEl = shell.querySelector('[data-slot="body"]');
      const metaEl = shell.querySelector('[data-slot="meta"]');

      titleEl.textContent = 'Cargando…';
      subtitleEl.textContent = 'Obteniendo contenido...';
      bodyEl.innerHTML = `<div class="d-flex h-100 w-100 align-items-center justify-content-center"><div class="text-center text-muted"><i class="bi bi-hourglass-split fs-2"></i><div>Cargando contenido...</div></div></div>`;
      metaEl.textContent = '—';

      try {
        // Aquí usa tu fetch real. Por ahora usamos mock:
        const data = await fetchContentMock(index + 1);

        // Render limpio (puedes usar template engines o innerHTML con cuidado)
        titleEl.textContent = data.title || 'Sin título';
        subtitleEl.textContent = data.subtitle || '';
        bodyEl.innerHTML = data.body || '<div>No hay contenido</div>';
        metaEl.textContent = `Última actualización: ${new Date(data.meta.updated).toLocaleString()}`;

      } catch (err) {
        titleEl.textContent = 'Error al cargar';
        subtitleEl.textContent = '';
        bodyEl.innerHTML = `<div class="alert alert-danger small mb-0">No se pudo cargar el contenido. ${err.message || ''}</div>`;
        metaEl.textContent = `Error`;
        console.error(err);
      }
    }

    // Añade la siguiente pantalla, la carga y hace slide
    async function addNextScreen() {
      // Añadimos nueva pantalla al track
      totalScreens++;
      const newIndex = totalScreens - 1;
      const shell = createScreenShell(newIndex);
      track.appendChild(shell);

      // Mantenemos sólo las últimas 'maxKeep' pantallas en DOM (opcional)
      if (track.children.length > maxKeep) {
        // Borra la pantalla más antigua (left)
        track.removeChild(track.children[0]);
        // Al remover una del inicio, los data-index cambian visualmente -> REINDEXAMOS
        reindexScreens();
      }

      // Actualizamos índice visible al final
      currentIndex = Math.min(totalScreens - 1, currentIndex + 1);
      updateTranslate();

      // Cargamos contenido en la nueva pantalla
      await loadContentIntoScreen(newIndex);

      // Después de cargar, opcionalmente pre-cargar la siguiente (optimización)
      // preloadNext(newIndex + 1);
    }

    // Reasigna data-index y totalScreens si hicimos limpieza del DOM
    function reindexScreens() {
      const shells = Array.from(track.children);
      shells.forEach((el, i) => {
        el.dataset.index = i;
        el.setAttribute('aria-label', `Pantalla ${i + 1}`);
      });
      totalScreens = shells.length;
      // Ajustar currentIndex si hacía referencia a índices anteriores
      if (currentIndex >= totalScreens) currentIndex = totalScreens - 1;
      updateTranslate();
    }

    // Botones
    nextBtn.addEventListener('click', () => addNextScreen());
    prevBtn.addEventListener('click', () => {
      if (currentIndex <= 0) return;
      currentIndex--;
      updateTranslate();
    });

    // Soporte teclado (flechas) y swipe básico
    document.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowRight') nextBtn.click();
      if (e.key === 'ArrowLeft') prevBtn.click();
    });

    // Swipe touch (simple)
    (function addTouch() {
      let startX = 0, endX = 0;
      const el = document.getElementById('pantallas-capa');
      el.addEventListener('touchstart', e => startX = e.changedTouches[0].clientX);
      el.addEventListener('touchend', e => {
        endX = e.changedTouches[0].clientX;
        const dx = endX - startX;
        if (Math.abs(dx) < 40) return;
        if (dx < 0) nextBtn.click(); else prevBtn.click();
      });
    })();

    // Inicial: añadimos la primera pantalla
    (async function init() {
      // Si quieres precargar varias al inicio, llama addNextScreen varias veces
      await addNextScreen();
    })();

    // Export / API pública simple (para que la integres en tu app)
    window.pantallas = {
      addNextScreen,
      goTo: (index) => {
        if (index < 0 || index >= totalScreens) return;
        currentIndex = index;
        updateTranslate();
      },
      getCurrentIndex: () => currentIndex,
      getTotal: () => totalScreens,
      // Puedes añadir métodos para recargar, reemplazar contenido, insertar en posición, etc.
    };

  </script>