// News-Übersicht: „Mehr laden“ hängt die nächste Seite an, ohne neu zu laden.
// Ohne JS funktioniert der Link als normale Pagination.
(function () {
  var grid = document.querySelector('[data-news-grid]');
  if (!grid || !window.fetch || !window.DOMParser) return;

  grid.addEventListener('click', function (event) {
    var link = event.target.closest('[data-news-more]');
    if (!link) return;
    event.preventDefault();
    if (link.getAttribute('aria-busy') === 'true') return;

    var nav = link.closest('.news-more');
    var label = link.textContent;
    link.setAttribute('aria-busy', 'true');
    link.textContent = 'Lädt …';

    fetch(link.href, { credentials: 'same-origin' })
      .then(function (response) {
        if (!response.ok) throw new Error(response.status);
        return response.text();
      })
      .then(function (html) {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        var nextGrid = doc.querySelector('[data-news-grid]');
        if (!nextGrid) throw new Error('no grid');

        var items = Array.prototype.filter.call(nextGrid.children, function (el) {
          return !el.classList.contains('news-more');
        });
        var firstNew = null;
        items.forEach(function (item) {
          var node = document.importNode(item, true);
          grid.insertBefore(node, nav);
          if (!firstNew) firstNew = node;
        });

        var nextLink = nextGrid.querySelector('[data-news-more]');
        if (nextLink) {
          link.href = nextLink.getAttribute('href');
          link.textContent = label;
          link.removeAttribute('aria-busy');
        } else {
          nav.remove();
        }

        // Fokus auf den ersten neuen Beitrag (Tastatur/Screenreader)
        if (firstNew) {
          var focusTarget = firstNew.matches('a') ? firstNew : firstNew.querySelector('a');
          if (focusTarget) focusTarget.focus({ preventScroll: true });
        }
      })
      .catch(function () {
        // Fallback: normale Navigation zur nächsten Seite
        window.location.href = link.href;
      });
  });
})();
