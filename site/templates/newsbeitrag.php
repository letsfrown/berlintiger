<?php snippet('header') ?>


<main>
  <section>
    <article class="max-width">
      <p class="date"><?= $page->date()->toDate('d.m.Y') ?></p>

      <h1 name="<?= $page->title() ?>"><?= $page->title() ?></h1>
      <p class="subheadline"><?= $page->intro() ?></p>

      <?php if ($cover = $page->cover()->toFile()) :
        echo $cover->resize(null, 600);
      endif ?>

      <?= $page->text()->kirbytext() ?>
    </article>

    <nav class="pagination">
      <?php if ($prev = $page->prev(collection('news'))) : ?>
        <a href="<?= $prev->url() ?>">&larr; vorherhiger Beitrag</a>
      <?php else : ?>
        <span>&larr; vorherhiger Beitrag</span>
      <?php endif ?>

      <a href="<?= $page->parent()->url() ?>">News</a>

      <?php if ($next = $page->next(collection('news'))) : ?>
        <a href="<?= $next->url() ?>">nächster Beitrag &rarr;</a>
      <?php else : ?>
        <span>nächster Beitrag &rarr;</span>
      <?php endif ?>
    </nav>

  </section>


</main>

<?php snippet('footer') ?>