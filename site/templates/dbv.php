<?php snippet('header') ?>

<?php $schedules = $page->schedules()->toStructure(); ?>

<main class="with-sidebar">
  <section class="main-content nopadding">

    <article class="hero">
      <div class="hero-title">
        <h1><?= $page->headline()->or($page->title()) ?></h1>
        <?php if ($schedules->isNotEmpty()) : ?>
          <a class="button button--down" href="#spielplan">Zum Spielplan</a>
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

    <?php snippet('gallery', [
      'images' => $page->gallery(),
      'wrapperClass' => 'gallery-block',
    ]) ?>

  </section>

  <?php if ($schedules->isNotEmpty()) : ?>
    <aside class="sidebar" id="spielplan">
      <?php
      $panels = [];
      foreach ($schedules as $schedule) {
        $label = $schedule->league()->isNotEmpty()
          ? $schedule->league()->value()
          : $page->title()->value();
        $panels[] = [
          'label'   => $label,
          'variant' => 'dbv',
          'html'    => snippet('spielplan-mannschaft', [
            'schedule' => $schedule,
            'titleBgColor' => '242c53',
            'tapBgColorActive' => '242c53',
            'tapBorderColorActive' => '242c53',
          ], true),
        ];
      }
      snippet('spielplan-tabs', [
        'heading'   => 'Spielplan',
        'ariaLabel' => 'Spielpläne der ' . $page->title(),
        'variant'   => 'dbv',
        'panels'    => $panels,
      ]);
      ?>
    </aside>
  <?php endif ?>
</main>

<?php snippet('footer') ?>
