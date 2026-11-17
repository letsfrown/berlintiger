<?php snippet('header') ?>
<?php
$news       = $kirby->collection('news')->paginate(12);
$pagination = $news->pagination();
?>
<h1 class="visually-hidden"><?= $page->title() ?></h1>

<main class="news-grid" data-news-grid>
  <?php foreach ($news as $index => $article) {
    snippet('newsbeitrag-preview', ['data' => $article]);
  } ?>

  <?php if ($pagination->hasNextPage() || $pagination->hasPrevPage()) : ?>
    <nav class="news-more" aria-label="Weitere Beiträge">
      <?php if ($pagination->hasPrevPage()) : ?>
        <a class="button" href="<?= $pagination->prevPageUrl() ?>">Neuere Beiträge</a>
      <?php endif ?>
      <?php if ($pagination->hasNextPage()) : ?>
        <a class="button" href="<?= $pagination->nextPageUrl() ?>" data-news-more>Mehr laden</a>
      <?php endif ?>
    </nav>
  <?php endif ?>
</main>

<?php snippet('footer') ?>
