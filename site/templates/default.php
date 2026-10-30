<?php snippet('header') ?>


<main>
  <section>
    <article class="max-width">
      <h1><?= $page->headline()->or($page->title()) ?></h1>
      <?php if ($subheadline = $page->subheadline()) : ?>
        <h2><?= $subheadline ?></h2>
      <?php endif ?>
      <p><?= $page->text()->kirbytext() ?></p>
    </article>
    </secion>
</main>

<?php snippet('footer') ?>