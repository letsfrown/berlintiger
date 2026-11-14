(function () {
  var EQUAL_PX = 16;
  // Several schedules stack at a capped height so the next heading can peek.
  var MULTI_WIDGET_MAX = 380;
  var LAYOUT_CLASSES = [
    'layout-equal',
    'layout-sidebar-sticky',
    'layout-main-sticky'
  ];

  function containerWidth(el) {
    var node = el;
    while (node && node !== document.body) {
      if (window.getComputedStyle(node).display !== 'none') {
        var width = Math.round(node.getBoundingClientRect().width);
        if (width > 0) return width;
      }
      node = node.parentElement;
    }
    return 400;
  }

  function titleCropPx(el) {
    var value = window.getComputedStyle(el).getPropertyValue('--bb-title-crop').trim();
    var amount = parseFloat(value);
    if (!amount) return 48;
    if (value.indexOf('rem') !== -1) {
      return Math.round(amount * parseFloat(window.getComputedStyle(document.documentElement).fontSize));
    }
    return Math.round(amount);
  }

  function configuredHeight(el) {
    return parseInt(el.dataset.bbHeight || '600', 10);
  }

  function visibleArticles(sidebar) {
    return Array.prototype.filter.call(
      sidebar.querySelectorAll(':scope > article'),
      function (article) {
        return window.getComputedStyle(article).display !== 'none';
      }
    );
  }

  function naturalHeight(el, sidebar) {
    var configured = configuredHeight(el);
    if (visibleArticles(sidebar).length > 1) {
      return Math.min(configured, MULTI_WIDGET_MAX);
    }
    return configured;
  }

  function headerOffset() {
    var raw = window.getComputedStyle(document.documentElement).getPropertyValue('--header-height').trim();
    var px = parseFloat(raw);
    if (px) return Math.round(px);
    var header = document.querySelector('body > header');
    return header ? header.offsetHeight : 0;
  }

  function stickyBudget() {
    return Math.max(280, Math.round(window.innerHeight - headerOffset()));
  }

  function isDesktop() {
    // Matches CSS --bp-mobile (680): sticky/fill modes only above mobile.
    return window.matchMedia('(min-width: 681px)').matches;
  }

  function contentBottomSpan(container) {
    if (!container) return 0;
    var top = Infinity;
    var bottom = 0;
    var found = false;
    Array.prototype.forEach.call(container.children, function (child) {
      if (window.getComputedStyle(child).display === 'none') return;
      var rect = child.getBoundingClientRect();
      if (rect.height <= 0 && rect.width <= 0) return;
      found = true;
      top = Math.min(top, rect.top);
      bottom = Math.max(bottom, rect.bottom);
    });
    return found ? Math.round(bottom - top) : 0;
  }

  function ensureMainPanel(main) {
    var panel = main.querySelector(':scope > .main-sticky-panel');
    if (panel) return panel;
    panel = document.createElement('div');
    panel.className = 'main-sticky-panel';
    while (main.firstChild) {
      panel.appendChild(main.firstChild);
    }
    main.appendChild(panel);
    return panel;
  }

  function clearInlineBox(el) {
    if (!el) return;
    el.style.removeProperty('height');
    el.style.removeProperty('max-height');
    el.style.removeProperty('min-height');
    el.style.removeProperty('top');
    el.style.removeProperty('position');
    el.style.removeProperty('overflow');
    el.style.removeProperty('overflow-x');
    el.style.removeProperty('overflow-y');
  }

  function enableScrollport(el, budgetH) {
    if (!el) return;
    el.style.maxHeight = budgetH + 'px';
    el.style.overflowX = 'hidden';
    el.style.overflowY = 'auto';
  }

  function clearLayout(row) {
    LAYOUT_CLASSES.forEach(function (name) {
      row.classList.remove(name);
    });
    var main = row.querySelector('.main-content');
    var sidebar = row.querySelector('.sidebar');
    clearInlineBox(main);
    clearInlineBox(sidebar);
    if (main) {
      clearInlineBox(main.querySelector(':scope > .main-sticky-panel'));
    }
  }

  function setLayout(row, mode) {
    LAYOUT_CLASSES.forEach(function (name) {
      row.classList.toggle(name, name === mode);
    });
  }

  function paint(el, iframeHeight) {
    if (!window.widget) return false;

    var id = el.id;
    if (!id) return false;

    // BB vereinswidget paints the checked home/away switch from titleBgColor
    // (not tapBgColorActive). Keep titleBgColor on the brand accent so the
    // active thumb stays visible on black tap chrome; the title row itself is
    // cropped via --bb-title-crop.
    var accent = el.dataset.bbTapActive || 'FE5000';
    var accentBorder = el.dataset.bbTapBorder || accent;

    var options = {
      iframeWidth: containerWidth(el),
      iframeHeight: iframeHeight,
      showRefreshButton: false,
      titleColor: el.dataset.bbTitleColor || 'FFFFFF',
      titleBgColor: accent,
      tapColor: 'FFFFFF',
      tapBgColor: '000000',
      tapBgColorActive: accent,
      tapBorderColorActive: accentBorder,
      colorMatchGroup: '000000',
      bgColorMatchGroup: 'F5F1F0',
      colorMatchListItem: '000000',
      bgColorMatchListItem: 'FFFFFF',
      showKuerzelInSpiele: false
    };

    if (el.dataset.bbType === 'mannschaft') {
      if (typeof widget.mannschaftswidget !== 'function' || !el.dataset.bbMannschaft) return false;
      options.mannschaftsId = el.dataset.bbMannschaft;
      el.innerHTML = '';
      widget.mannschaftswidget(id, options);
      return true;
    }

    if (el.dataset.bbType === 'verein') {
      if (typeof widget.vereinswidget !== 'function') return false;
      options.vereinsId = el.dataset.bbVerein || '3986';
      options.homeMatchesOnlyInit = false;
      options.rangeDays = parseInt(el.dataset.bbRangeDays || '7', 10);
      el.innerHTML = '';
      widget.vereinswidget(id, options);
      return true;
    }

    return false;
  }

  function applyWidgetHeight(el, visible) {
    var total = visible + titleCropPx(el);
    var applied = parseInt(el.dataset.bbApplied || '0', 10);
    if (Math.abs(applied - total) < 16 && el.querySelector('iframe')) {
      el.style.setProperty('--bb-iframe-height', visible + 'px');
      el.style.height = visible + 'px';
      return;
    }
    el.style.setProperty('--bb-iframe-height', visible + 'px');
    el.style.height = visible + 'px';
    if (!paint(el, total)) return;
    el.dataset.bbReady = '1';
    el.dataset.bbApplied = String(total);
  }

  function sizeWidgetsNatural(sidebar) {
    sidebar.querySelectorAll('.bb-widget[data-bb-type]').forEach(function (el) {
      if (window.getComputedStyle(el).display === 'none') return;
      applyWidgetHeight(el, naturalHeight(el, sidebar));
    });
  }

  // Single schedule: stretch the one widget so the sticky column has no gap.
  function sizeWidgetsToFill(sidebar, targetH) {
    var articles = visibleArticles(sidebar);
    if (!articles.length) return;

    var reserved = 0;
    var widgets = [];
    articles.forEach(function (article, index) {
      var heading = article.querySelector('h2');
      var widget = article.querySelector('.bb-widget[data-bb-type]');
      var headingH = heading ? Math.round(heading.getBoundingClientRect().height) : 0;
      var isLast = index === articles.length - 1;
      if (!widget || window.getComputedStyle(widget).display === 'none') {
        reserved += headingH;
        return;
      }
      if (!isLast) {
        var natural = naturalHeight(widget, sidebar);
        reserved += headingH + natural;
        widgets.push({ el: widget, visible: natural });
      } else {
        widgets.push({
          el: widget,
          visible: Math.max(200, targetH - reserved - headingH)
        });
      }
    });

    widgets.forEach(function (item) {
      applyWidgetHeight(item.el, item.visible);
    });
  }

  function measureMain(main) {
    var panel = main.querySelector(':scope > .main-sticky-panel');
    return contentBottomSpan(panel || main);
  }

  function hasHeroImage(main) {
    return !!main.querySelector('.hero-image');
  }

  /**
   * Stick the sidebar in the viewport.
   * - single widget + fill: lock height and stretch the widget (no gap under coaches)
   * - 2+ widgets: natural stack; if taller than budget, lock height + overflow-y so
   *   the column scrolls instead of painting into the footer
   */
  function stickSidebar(row, sidebar, budgetH, fill) {
    setLayout(row, 'layout-sidebar-sticky');
    enableScrollport(sidebar, budgetH);
    if (fill) {
      sidebar.style.height = budgetH + 'px';
      sizeWidgetsToFill(sidebar, budgetH);
      return;
    }
    sizeWidgetsNatural(sidebar);
    // max-height alone is not always a definite flex size — lock height when
    // content exceeds the sticky budget so overflow-y:auto forms a scrollport.
    if (contentBottomSpan(sidebar) > budgetH) {
      sidebar.style.height = budgetH + 'px';
    } else {
      sidebar.style.removeProperty('height');
    }
  }

  function stickMainPanel(row, main, budgetH) {
    setLayout(row, 'layout-main-sticky');
    var panel = ensureMainPanel(main);
    enableScrollport(panel, budgetH);
    panel.style.height = budgetH + 'px';
  }

  function layoutRow(row) {
    var main = row.querySelector('.main-content');
    var sidebar = row.querySelector('.sidebar');
    if (!main || !sidebar) return;

    ensureMainPanel(main);
    clearLayout(row);

    if (!isDesktop()) {
      sizeWidgetsNatural(sidebar);
      return;
    }

    sizeWidgetsNatural(sidebar);

    var V = stickyBudget();
    var M = measureMain(main);
    var S = contentBottomSpan(sidebar);
    var multi = visibleArticles(sidebar).length > 1;
    var noHero = !hasHeroImage(main);

    if (M < 80 || S < 80) {
      setLayout(row, 'layout-equal');
      return;
    }

    // No hero: coaches/main define the sticky budget.
    // Multi → scroll; single → fill the column.
    if (noHero) {
      stickSidebar(row, sidebar, Math.min(M, V), !multi);
      return;
    }

    // Roughly equal → no sticky
    if (Math.abs(M - S) <= EQUAL_PX) {
      setLayout(row, 'layout-equal');
      return;
    }

    // Main taller → sidebar sticks (fill only when one schedule)
    if (M > S) {
      stickSidebar(row, sidebar, Math.min(M, V), !multi);
      return;
    }

    // Sidebar taller + hero → main sticks; sidebar keeps natural height (page scrolls)
    stickMainPanel(row, main, V);
  }

  function initAll() {
    document.querySelectorAll('.with-sidebar').forEach(layoutRow);
  }

  function boot() {
    window.requestAnimationFrame(function () {
      window.requestAnimationFrame(initAll);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }

  window.addEventListener('load', initAll);

  var resizeTimer;
  window.addEventListener('resize', function () {
    window.clearTimeout(resizeTimer);
    resizeTimer = window.setTimeout(initAll, 150);
  });
})();
