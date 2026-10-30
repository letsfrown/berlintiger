<?php snippet('header') ?>
<main>

  <!-- HERO -->
  <section class="hero" style="background-image: url(
      <?php
      if ($image = $page->cover()->toFile()) {
        echo $image->resize(1440)->url();
      } ?>
   )">
    <h1><?= $page->headline() ?></h1>
    <p class=" subheadline"><?= $page->subheadline() ?></p>

    <?php if ($quote) : ?>
      <img src="<?= $quote->url() ?>" alt="<?= $quote->alt()->escape() ?>" style="width:<?= $quote->width() ?>px; height:<?= $quote->height() ?>px">
    <?php endif ?>
    <a class="button mobileVisible" href="#spielplan">↓ zum Spielplan </a>
  </section>

  <!-- MAIN CONTENT -->
  <section class="with-sidebar nopadding">
    <article class="main-content">

      <!-- Aktuelle News -->
      <?php foreach ($homeNews as $article) : ?>
        <section class="news" id="news">
          <a href="<?= $article->url() ?>">
            <article>
              <p>News</p>
              <h2><?= $article->title(); ?></h2>

              <p>
                <?= $article->intro()
                  ->or($article->text()->excerpt(160, false, '…')) ?>
                <span class="readmore">&rarr;&nbsp;mehr&nbsp;lesen</span>
              </p>

            </article>
            <?php if ($item = $article->image()->crop(300)) : ?>
              <img src="<?= $item->url() ?>" alt="<?= $item->alt() ?>" class="rounded">
            <?php endif ?>

          </a>
        </section>
      <?php endforeach ?>

      <!-- Tiger-Promovideo -->
      <div class="video-container" id="video" data-youtube-id="OzLZWb5CgZ0">
        <button type="button" class="video-poster" aria-label="Video abspielen: Wir sind Berlin Tiger">
          <img
            src="<?= url('assets/images/promo-video-poster.jpg') ?>"
            alt="Berlin Tiger beim Tip-off"
            width="1280"
            height="616"
            loading="lazy"
            decoding="async">
          <span class="video-play" aria-hidden="true"></span>
        </button>
      </div>

      <!-- Karte -->
      <section class="map hellgrau " id="map">
        <p>Basketball in Kreuzberg</p>
        <h2>Wo findet ihr uns überall?</h2>

        <?php if ($data = $site->find('angebote/ags')) : ?>
          <?php snippet('map', ['data' => $data]) ?>
        <?php endif ?>
      </section>

    </article>


    <!-- SPIELPLAN -->
    <?php
    $showDbvSpielplan = $page->showDbvSpielplan()->toBool();
    $dbv = $showDbvSpielplan ? $site->find('dtb') : null;
    $dbvSchedules = $dbv ? $dbv->schedules()->toStructure() : null;
    $hasDbvSpielplan = $dbvSchedules && $dbvSchedules->isNotEmpty();
    ?>
    <aside class="sidebar" id="spielplan">

      <article>
        <h2>Spielplan</h2>
        <?php if ($widgetNumber = $page->widgetNumber()) : ?>
          <?= snippet('spielplan-verein', [
            'widgetNumber' => $widgetNumber,
            // Match team pages with two schedules (iframeHeight 600)
            'iframeHeight' => $hasDbvSpielplan ? 600 : 1200,
          ]); ?>
        <?php endif ?>
      </article>

      <?php if ($hasDbvSpielplan) : ?>
        <?php foreach ($dbvSchedules as $schedule) : ?>
          <article class="dbv-spielplan">
            <h2 class="padding-sm">Spielplan der <?= $dbv->title() ?> <?= $schedule->league() ?></h2>
            <?= snippet('spielplan-mannschaft', [
              'schedule' => $schedule,
              'titleBgColor' => '242c53',
              'tapBgColorActive' => '242c53',
              'tapBorderColorActive' => '242c53',
            ]); ?>
          </article>
        <?php endforeach ?>
      <?php endif ?>

    </aside>


  </section>

</main>
<?php snippet('footer') ?>