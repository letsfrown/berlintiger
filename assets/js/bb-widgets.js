(function () {
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

  function initMannschaft(el) {
    if (!window.widget || typeof widget.mannschaftswidget !== 'function') return;
    if (el.dataset.bbReady === '1') return;

    var id = el.id;
    if (!id || !el.dataset.bbMannschaft) return;

    var height = parseInt(el.dataset.bbHeight || '600', 10);
    el.dataset.bbReady = '1';
    el.innerHTML = '';
    el.style.setProperty('--bb-iframe-height', height + 'px');

    widget.mannschaftswidget(id, {
      iframeWidth: containerWidth(el),
      iframeHeight: height,
      // Hide refresh control — page heading already identifies the plan
      showRefreshButton: false,
      titleColor: 'AAAAAA',
      titleBgColor: '000000',
      tapColor: 'AAAAAA',
      tapBgColor: '000000',
      tapBgColorActive: el.dataset.bbTapActive || 'FE5000',
      tapBorderColorActive: el.dataset.bbTapBorder || 'FE5000',
      colorMatchGroup: '000000',
      bgColorMatchGroup: 'F5F1F0',
      colorMatchListItem: '000000',
      bgColorMatchListItem: 'FFFFFF',
      showKuerzelInSpiele: false,
      mannschaftsId: el.dataset.bbMannschaft
    });
  }

  function initVerein(el) {
    if (!window.widget || typeof widget.vereinswidget !== 'function') return;
    if (el.dataset.bbReady === '1') return;

    var id = el.id;
    if (!id) return;

    var height = parseInt(el.dataset.bbHeight || '600', 10);
    el.dataset.bbReady = '1';
    el.innerHTML = '';
    el.style.setProperty('--bb-iframe-height', height + 'px');

    widget.vereinswidget(id, {
      iframeWidth: containerWidth(el),
      iframeHeight: height,
      showRefreshButton: false,
      titleColor: 'AAAAAA',
      titleBgColor: '000000',
      tapColor: 'AAAAAA',
      tapBgColor: '000000',
      tapBgColorActive: el.dataset.bbTapActive || 'FE5000',
      tapBorderColorActive: el.dataset.bbTapBorder || 'FE5000',
      colorMatchGroup: '000000',
      bgColorMatchGroup: 'F5F1F0',
      colorMatchListItem: '000000',
      bgColorMatchListItem: 'FFFFFF',
      showKuerzelInSpiele: false,
      vereinsId: el.dataset.bbVerein || '3986',
      homeMatchesOnlyInit: false,
      rangeDays: parseInt(el.dataset.bbRangeDays || '7', 10)
    });
  }

  function initAll() {
    document.querySelectorAll('.bb-widget[data-bb-type="mannschaft"]').forEach(initMannschaft);
    document.querySelectorAll('.bb-widget[data-bb-type="verein"]').forEach(initVerein);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }
})();
