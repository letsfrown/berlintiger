<?php
/**
 * Meta-Description, Open Graph und Canonical.
 * Fallbacks: Seite → Intro/Text → Website-Standard.
 */
$description = $page->metaDescription()->isNotEmpty()
  ? $page->metaDescription()->value()
  : null;

if ($description === null && $page->intro()->isNotEmpty()) {
  $description = Str::unhtml($page->intro()->value());
}

if ($description === null && $page->text()->isNotEmpty()) {
  $description = $page->text()->excerpt(160, true, '…');
}

if ($description === null || trim($description) === '') {
  $description = $site->metaDescription()->value();
}

$description = Str::short(trim(preg_replace('/\s+/u', ' ', (string)$description)), 160, '…');

$shareImage = $page->ogImage()->toFile()
  ?? $page->cover()->toFile()
  ?? $site->ogImage()->toFile();

$title = $page->isHomePage() ? $site->title()->value() : $page->title()->value() . ' | ' . $site->title()->value();
?>
<?php if ($description !== '') : ?>
  <meta name="description" content="<?= esc($description, 'attr') ?>">
  <meta property="og:description" content="<?= esc($description, 'attr') ?>">
<?php endif ?>
  <link rel="canonical" href="<?= esc($page->url(), 'attr') ?>">
  <meta property="og:type" content="<?= $page->intendedTemplate()->name() === 'newsbeitrag' ? 'article' : 'website' ?>">
  <meta property="og:site_name" content="<?= esc($site->title(), 'attr') ?>">
  <meta property="og:title" content="<?= esc($title, 'attr') ?>">
  <meta property="og:url" content="<?= esc($page->url(), 'attr') ?>">
  <meta property="og:locale" content="de_DE">
<?php if ($shareImage) : $og = $shareImage->crop(1200, 630); ?>
  <meta property="og:image" content="<?= esc($og->url(), 'attr') ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta name="twitter:card" content="summary_large_image">
<?php endif ?>
