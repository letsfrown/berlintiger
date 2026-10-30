<?php snippet('header') ?>

<?php
$schedules = $page->schedules()->toStructure();
$cover = $page->cover()->toFile();
$banner = $page->drawings()->isNotEmpty() ? $page->drawings()->toFiles()->shuffle()->first() : null;
?>


<main class="with-sidebar">
  <section class="main-content nopadding">

    <!-- HERO -->
    <article class="hero">
      <div class="hero-title">
        <h1><?= $page->headline()->or($page->title()) ?></h1>
        <a class="button" href="#spielplan">↓ zum Spielplan</a>
      </div>

      <?php if ($cover || $banner) : ?>
        <div class="hero-image">
          <?php if ($banner) : ?>
            <img src="<?= $banner->crop(1440, 960)->url() ?>" class="banner" alt="<?= $banner->alt() ?>">
          <?php endif ?>

          <?php if ($cover) : ?>
            <img src="<?= $cover->crop(1440, 960)->url() ?>" class="cover" alt="<?= $cover->alt() ?>">
          <?php endif ?>
        </div>
      <?php endif ?>
    </article>

    <!-- COACHES -->
    <?php if ($page->coaches()->isNotEmpty()) : ?>
      <article class="container">
        <h2>Coaches der <?= $page->title() ?></h2>
        <div class="team-card-grid">
          <?php foreach ($page->coaches()->toStructure() as $coach) : ?>
            <div class="team-card">
              <?php if ($coach->coach()->toPage()->headshot()->isNotEmpty()) : ?>
                <?= $coach->coach()->toPage()->headshot()->toFile()->crop(400) ?>
              <?php else : ?>
                <div class="placeholder"></div>
              <?php endif ?>
              <h3><?= $coach->coach()->toPage()->title() ?></h3>
              <p><?= $coach->position() ?></p>
            </div>
          <?php endforeach ?>
        </div>

      </article>
    <?php endif ?>



  </section>


  <!-- SIDEBAR SPIELPLÄNE -->
  <aside class="sidebar" id="spielplan">

    <?php if ($schedules->isNotEmpty());
    foreach ($schedules as $schedule) : ?>

      <article>
        <h2 class="padding-sm">Spielplan der <?= $page->title() ?> <?= $schedule->league() ?></h2>
          <?= snippet('spielplan-mannschaft', ['schedule' => $schedule]); ?>
      </article>
    <?php endforeach ?>

    <?php if ($schedules->count() < 2) :  ?>
      <div class="decoration"></div>
    <?php endif ?>



  </aside>


</main>

<?php snippet('footer') ?>