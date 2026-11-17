<?php snippet('header') ?>

<main>

    <h1 class="visually-hidden"><?= $page->title() ?></h1>

    <?php foreach ($page->children()->listed() as $section) {
      snippet(('turnier'), ['data' => $section]);
    } ?>
</main>

<?php snippet('footer') ?>