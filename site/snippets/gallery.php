<?php
/**
 * Square image gallery with shared lightbox.
 *
 * @var \Kirby\Cms\Files|\Kirby\Cms\Field|null $images
 * @var string|null $wrapperClass Optional wrapper element class (e.g. gallery-block)
 */
$files = $images ?? null;

if (is_object($files) && method_exists($files, 'toFiles')) {
  $files = $files->toFiles();
}

if (!$files || $files->isEmpty()) {
  return;
}

$wrapperClass = $wrapperClass ?? null;
?>

<?php if ($wrapperClass) : ?>
  <article class="<?= esc($wrapperClass, 'attr') ?>">
<?php endif ?>

  <ul class="gallery">
    <?php foreach ($files as $image) : ?>
      <li>
        <button
          type="button"
          class="gallery-thumb"
          data-fullsize="<?= $image->url() ?>"
          aria-label="<?= esc($image->alt()->or('Bild vergrößern'), 'attr') ?>"
        >
          <img
            src="<?= $image->crop(400, 400)->url() ?>"
            alt="<?= $image->alt() ?>"
            loading="lazy">
        </button>
      </li>
    <?php endforeach ?>
  </ul>

<?php if ($wrapperClass) : ?>
  </article>
<?php endif ?>

<?php if (!defined('GALLERY_LIGHTBOX')) : define('GALLERY_LIGHTBOX', true); ?>
  <div id="lightbox" class="lightbox" aria-hidden="true">
    <button type="button" class="lightbox-close" aria-label="Lightbox schließen">&times;</button>
    <button type="button" class="lightbox-prev" aria-label="Vorheriges Bild">&#8249;</button>
    <button type="button" class="lightbox-next" aria-label="Nächstes Bild">&#8250;</button>
    <div class="lightbox-content">
      <img id="lightbox-image" src="" alt="">
    </div>
  </div>
<?php endif ?>
