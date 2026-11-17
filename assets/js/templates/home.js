// Startseite: Kinder-Zitate im Hero nacheinander per Überblendung zeigen.
// Bilder ab dem zweiten werden erst kurz vor dem Einblenden geladen.
(function () {
  var box = document.querySelector('[data-hero-quotes]');
  if (!box) return;

  var quotes = Array.prototype.slice.call(box.querySelectorAll('.hero-quote'));
  if (!quotes.length) return;

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var current = 0;
  var timer = null;
  var DURATION = 6000;

  function load(img) {
    if (img && img.dataset.src) {
      img.src = img.dataset.src;
      img.removeAttribute('data-src');
    }
  }

  function show(index) {
    var prev = quotes[current];
    var next = quotes[index];
    prev.classList.remove('is-in');
    prev.setAttribute('aria-hidden', 'true');
    next.classList.add('is-in');
    next.removeAttribute('aria-hidden');
    current = index;
    load(quotes[(index + 1) % quotes.length]);
  }

  function advance() {
    var index = (current + 1) % quotes.length;
    var img = quotes[index];
    load(img);
    if (img.complete) {
      show(index);
    } else {
      img.addEventListener('load', function () { show(index); }, { once: true });
    }
  }

  function start() {
    if (quotes.length < 2) return;
    clearInterval(timer);
    timer = setInterval(advance, DURATION);
  }

  // Erstes Zitat blendet als Teil des Seitenaufbaus ein
  window.setTimeout(function () {
    quotes[0].classList.add('is-in');
    load(quotes[1]);
  }, reduce ? 0 : 1300);

  window.setTimeout(start, reduce ? 0 : 3500);
})();
