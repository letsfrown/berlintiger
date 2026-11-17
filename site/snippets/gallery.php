<?php
/**
 * Square image gallery with shared lightbox.
 *
 * @var \Kirby\Cms\Files|\Kirby\Content\Field|null $images
 * @var string|null $wrapperClass Optional wrapper element class (default: gallery-block)
 */
$files = $images ?? null;

// Field::toFiles() is registered via __call — method_exists() is always false.
// Accept either a Field or an already-resolved Files collection (Kirby 4).
if ($files instanceof \Kirby\Content\Field) {
  $files = $files->toFiles();
}

if (!$files instanceof \Kirby\Cms\Files || $files->isEmpty()) {
  return;
}

$wrapperClass = $wrapperClass ?? 'gallery-block';
?>

<article class="<?= esc($wrapperClass, 'attr') ?>">
  <ul class="gallery">
    <?php foreach ($files as $image) : ?>
      <?php if ($image instanceof \Kirby\Cms\File === false) continue ?>
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
</article>

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
