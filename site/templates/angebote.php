<?php snippet('header') ?>

<main>

  <h1 name="<?= $page->title() ?>"></h1>

  <?php foreach ($page->children()->listed() as $section) {
    snippet($section->uid(), ['data' => $section]);
  } ?>

</main>

<?php snippet('footer') ?>