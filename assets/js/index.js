window.addEventListener('load', function() {
  function updateHeaderOffset() {
    var header = document.querySelector('body > header');
    if (!header) return;

    var headerHeight = header.offsetHeight;
    document.documentElement.style.setProperty('--header-height', headerHeight + 'px');

    document.querySelectorAll('header, section, aside').forEach(function(element) {
      element.style.scrollMarginTop = headerHeight + 'px';
    });
  }

  updateHeaderOffset();
  window.addEventListener('resize', updateHeaderOffset);

  // Subnav: underline the section currently in view.
  var subnav = document.querySelector('.subnavigation');
  if (subnav) {
    var links = Array.from(subnav.querySelectorAll('a[href^="#"]'));
    var sections = links
      .map(function(link) {
        var id = link.getAttribute('href').slice(1);
        var section = id ? document.getElementById(id) : null;
        return section ? { link: link, section: section } : null;
      })
      .filter(Boolean);

    function setActive(activeLink) {
      links.forEach(function(link) {
        link.classList.toggle('active', link === activeLink);
      });
    }

    function syncFromHash() {
      var hash = window.location.hash.slice(1);
      var match = sections.find(function(item) {
        return item.section.id === hash;
      });
      if (match) setActive(match.link);
    }

    if (sections.length && 'IntersectionObserver' in window) {
      var visible = new Map();
      var observer = new IntersectionObserver(
        function(entries) {
          entries.forEach(function(entry) {
            visible.set(entry.target, entry.isIntersecting ? entry.intersectionRatio : 0);
          });

          var best = null;
          var bestRatio = 0;
          sections.forEach(function(item) {
            var ratio = visible.get(item.section) || 0;
            if (ratio > bestRatio) {
              bestRatio = ratio;
              best = item;
            }
          });

          if (best) setActive(best.link);
        },
        {
          rootMargin: '-20% 0px -55% 0px',
          threshold: [0, 0.1, 0.25, 0.5, 0.75, 1]
        }
      );

      sections.forEach(function(item) {
        observer.observe(item.section);
      });
    }

    syncFromHash();
    window.addEventListener('hashchange', syncFromHash);
  }
});

(function () {
  var cards = Array.prototype.slice.call(document.querySelectorAll('.team-card.has-extras'));
  if (!cards.length) return;

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
    if (card.classList.contains('is-open')) {
      closeCard(card);
    } else {
      openCard(card);
    }
  }

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
    if (event.key !== 'Escape') return;
    cards.forEach(closeCard);
  });
})();

(function () {
  var lightbox = document.getElementById('lightbox');
  if (!lightbox) return;

  var lightboxImage = document.getElementById('lightbox-image');
  var closeBtn = lightbox.querySelector('.lightbox-close');
  var prevBtn = lightbox.querySelector('.lightbox-prev');
  var nextBtn = lightbox.querySelector('.lightbox-next');
  var galleries = document.querySelectorAll('.gallery');
  if (!lightboxImage || !galleries.length) return;

  var currentIndex = 0;
  var images = [];

  function updateLightboxImage() {
    var thumb = images[currentIndex];
    if (!thumb) return;
    var img = thumb.querySelector('img');
    lightboxImage.src = thumb.dataset.fullsize || '';
    lightboxImage.alt = img ? img.alt : '';
  }

  function openLightbox(thumbs, index) {
    images = thumbs;
    currentIndex = index;
    updateLightboxImage();
    lightbox.setAttribute('aria-hidden', 'false');
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    lightbox.setAttribute('aria-hidden', 'true');
    lightbox.classList.remove('active');
    document.body.style.overflow = '';
  }

  function prevImage() {
    if (!images.length) return;
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    updateLightboxImage();
  }

  function nextImage() {
    if (!images.length) return;
    currentIndex = (currentIndex + 1) % images.length;
    updateLightboxImage();
  }

  galleries.forEach(function (gallery) {
    var thumbs = Array.prototype.slice.call(gallery.querySelectorAll('.gallery-thumb'));
    thumbs.forEach(function (thumb, index) {
      thumb.addEventListener('click', function () {
        openLightbox(thumbs, index);
      });
    });
  });

  if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
  if (prevBtn) prevBtn.addEventListener('click', prevImage);
  if (nextBtn) nextBtn.addEventListener('click', nextImage);

  lightbox.addEventListener('click', function (event) {
    if (event.target === lightbox) closeLightbox();
  });

  document.addEventListener('keydown', function (event) {
    if (!lightbox.classList.contains('active')) return;
    if (event.key === 'Escape') closeLightbox();
    if (event.key === 'ArrowLeft') prevImage();
    if (event.key === 'ArrowRight') nextImage();
  });
})();

document.querySelectorAll('.video-container[data-youtube-id]').forEach(function(container) {
  var poster = container.querySelector('.video-poster');
  if (!poster) return;

  poster.addEventListener('click', function() {
    var id = container.getAttribute('data-youtube-id');
    if (!id) return;

    var iframe = document.createElement('iframe');
    iframe.src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0&modestbranding=1';
    iframe.title = poster.getAttribute('aria-label') || 'YouTube video';
    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
    iframe.allowFullscreen = true;
    iframe.setAttribute('frameborder', '0');

    container.innerHTML = '';
    container.appendChild(iframe);
  });
});






// Karten-Marker: per Klick, Tap oder Tastatur (Enter/Leertaste) öffnen.
(function () {
  var dots = Array.prototype.slice.call(document.querySelectorAll('.annotated .dot'));
  if (!dots.length) return;

  function setOpen(dot, open) {
    dot.classList.toggle('hover', open);
    dot.setAttribute('aria-expanded', open ? 'true' : 'false');
  }

  function toggle(dot) {
    var open = !dot.classList.contains('hover');
    dots.forEach(function (other) { setOpen(other, other === dot ? open : false); });
  }

  dots.forEach(function (dot) {
    dot.addEventListener('click', function (event) {
      event.stopPropagation();
      toggle(dot);
    });
    dot.addEventListener('keydown', function (event) {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        toggle(dot);
      } else if (event.key === 'Escape') {
        setOpen(dot, false);
      }
    });
  });

  document.addEventListener('click', function () {
    dots.forEach(function (dot) { setOpen(dot, false); });
  });
})();
