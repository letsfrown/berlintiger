<?php snippet('header') ?>

<?php $schedules = $page->schedules()->toStructure(); ?>

<main class="with-sidebar">
  <section class="main-content nopadding">

    <article class="hero">
      <div class="hero-title">
        <h1><?= $page->headline()->or($page->title()) ?></h1>
        <?php if ($schedules->isNotEmpty()) : ?>
          <a class="button" href="#spielplan">↓ zum Spielplan</a>
        <?php endif ?>
      </div>

      <?php if ($cover = $page->cover()->toFile()) : ?>
        <div class="hero-image" style="background-image: url('<?= $cover->crop(1440, 960)->url() ?>')"></div>
      <?php endif ?>
    </article>

    <?php if ($page->subheadline()->isNotEmpty() || $page->introtext()->isNotEmpty() || $page->text()->isNotEmpty()) : ?>
      <article class="content-block">
        <?php if ($page->date()->isNotEmpty()) : ?>
          <p class="date"><?= $page->date()->toDate('d.m.Y') ?></p>
        <?php endif ?>

        <?php if ($page->subheadline()->isNotEmpty()) : ?>
          <p class="subheadline"><?= $page->subheadline() ?></p>
        <?php endif ?>

        <?php if ($page->introtext()->isNotEmpty()) : ?>
          <p class="introtext"><?= $page->introtext()->kirbytext() ?></p>
        <?php endif ?>

        <?= $page->text()->kirbytext() ?>
      </article>
    <?php endif ?>

    <?php if ($page->gallery()->isNotEmpty()) : ?>
      <article class="gallery-block">
        <ul class="gallery">
          <?php foreach ($page->gallery()->toFiles() as $image) : ?>
            <li>
              <img
                src="<?= $image->crop(400, 400)->url() ?>"
                alt="<?= $image->alt() ?>"
                data-fullsize="<?= $image->url() ?>"
                class="gallery-thumb"
                loading="lazy">
            </li>
          <?php endforeach ?>
        </ul>
      </article>

      <div id="lightbox" class="lightbox" aria-hidden="true">
        <button class="lightbox-close" aria-label="Close lightbox">&times;</button>
        <button class="lightbox-prev" aria-label="Previous image">&#8249;</button>
        <button class="lightbox-next" aria-label="Next image">&#8250;</button>
        <div class="lightbox-content">
          <img id="lightbox-image" src="" alt="">
        </div>
      </div>

      <script>
        (function() {
          const gallery = document.querySelector('.gallery');
          const lightbox = document.getElementById('lightbox');
          const lightboxImage = document.getElementById('lightbox-image');
          const closeBtn = document.querySelector('.lightbox-close');
          const prevBtn = document.querySelector('.lightbox-prev');
          const nextBtn = document.querySelector('.lightbox-next');
          let currentIndex = 0;
          let images = [];

          if (!gallery || !lightbox) return;

          images = Array.from(gallery.querySelectorAll('.gallery-thumb'));

          function openLightbox(index) {
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

          function updateLightboxImage() {
            if (images[currentIndex]) {
              lightboxImage.src = images[currentIndex].dataset.fullsize;
              lightboxImage.alt = images[currentIndex].alt;
            }
          }

          function prevImage() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateLightboxImage();
          }

          function nextImage() {
            currentIndex = (currentIndex + 1) % images.length;
            updateLightboxImage();
          }

          images.forEach((img, index) => {
            img.addEventListener('click', () => openLightbox(index));
          });

          closeBtn.addEventListener('click', closeLightbox);
          prevBtn.addEventListener('click', prevImage);
          nextBtn.addEventListener('click', nextImage);

          lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
              closeLightbox();
            }
          });

          document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('active')) return;

            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
          });
        })();
      </script>
    <?php endif ?>

  </section>

  <?php if ($schedules->isNotEmpty()) : ?>
    <aside class="sidebar" id="spielplan">
      <?php foreach ($schedules as $schedule) : ?>
        <article>
          <h2 class="padding-sm">Spielplan der <?= $page->title() ?> <?= $schedule->league() ?></h2>
          <?= snippet('spielplan-mannschaft', [
            'schedule' => $schedule,
            'titleBgColor' => '242c53',
            'tapBgColorActive' => '242c53',
            'tapBorderColorActive' => '242c53',
          ]); ?>
        </article>
      <?php endforeach ?>

      <?php if ($schedules->count() < 2) : ?>
        <div class="decoration"></div>
      <?php endif ?>
    </aside>
  <?php endif ?>
</main>

<?php snippet('footer') ?>