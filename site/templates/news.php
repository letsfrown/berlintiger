<?php snippet('header') ?>
<h1 name="<?= $page->title() ?>"></h1>

<main class="news-grid">
  <?php foreach ($kirby->collection('news') as $section) {
    snippet(('newsbeitrag-preview'), ['data' => $section]);
  } ?>
</main>

<?php snippet('footer') ?>