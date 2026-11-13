(function () {
  var slider = document.querySelector('.team-slider');
  if (!slider) return;

  var viewport = slider.querySelector('.team-slider-viewport');
  var track = slider.querySelector('.team-slider-track');
  var prevBtn = slider.querySelector('.team-slider-prev');
  var nextBtn = slider.querySelector('.team-slider-next');
  var cards = Array.prototype.slice.call(slider.querySelectorAll('.team-card'));

  if (!viewport || !track || !cards.length) return;

  viewport.setAttribute('tabindex', '0');

  function cardStep() {
    var card = cards[0];
    if (!card) return 350;
    return card.getBoundingClientRect().width;
  }

  function updateArrowState() {
    var maxScroll = Math.max(0, viewport.scrollWidth - viewport.clientWidth);
    var left = viewport.scrollLeft;
    if (prevBtn) prevBtn.disabled = left <= 4;
    if (nextBtn) nextBtn.disabled = left >= maxScroll - 4;
  }

  function scrollByDir(dir) {
    viewport.scrollBy({ left: dir * cardStep(), behavior: 'smooth' });
  }

  function closeCard(card) {
    var panel = card.querySelector('.team-card-panel');
    var toggle = card.querySelector('.team-card-toggle');
    if (!panel) return;
    card.classList.remove('is-open');
    panel.setAttribute('aria-hidden', 'true');
    if (toggle) toggle.setAttribute('aria-expanded', 'false');
  }

  function openCard(card) {
    cards.forEach(function (other) {
      if (other !== card) closeCard(other);
    });

    var panel = card.querySelector('.team-card-panel');
    var toggle = card.querySelector('.team-card-toggle');
    if (!panel) return;
    card.classList.add('is-open');
    panel.setAttribute('aria-hidden', 'false');
    if (toggle) toggle.setAttribute('aria-expanded', 'true');
  }

  function toggleCard(card) {
    if (!card.classList.contains('has-extras')) return;
    if (card.classList.contains('is-open')) {
      closeCard(card);
    } else {
      openCard(card);
    }
  }

  function sliderIsActive() {
    var active = document.activeElement;
    return slider.contains(active) || slider.matches(':hover');
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function () {
      scrollByDir(-1);
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', function () {
      scrollByDir(1);
    });
  }

  viewport.addEventListener('scroll', function () {
    window.requestAnimationFrame(updateArrowState);
  }, { passive: true });

  cards.forEach(function (card) {
    var toggle = card.querySelector('.team-card-toggle');
    if (!toggle) return;

    toggle.addEventListener('click', function (event) {
      event.preventDefault();
      event.stopPropagation();
      toggleCard(card);
    });

    card.addEventListener('click', function (event) {
      if (event.target.closest('a, button')) return;
      toggleCard(card);
    });
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'ArrowLeft') {
      if (!sliderIsActive()) return;
      event.preventDefault();
      scrollByDir(-1);
    } else if (event.key === 'ArrowRight') {
      if (!sliderIsActive()) return;
      event.preventDefault();
      scrollByDir(1);
    } else if (event.key === 'Escape') {
      if (!slider.contains(document.activeElement) && !slider.matches(':hover')) return;
      cards.forEach(closeCard);
    }
  });

  updateArrowState();
  window.addEventListener('resize', updateArrowState);
})();
