<?php snippet('header') ?>
<main>

  <!-- HERO -->
  <?php $heroBg = $page->cover()->toFile(); ?>
  <section class="hero" <?php if ($heroBg) : ?>style="--hero-bg: url('<?= $heroBg->resize(1440)->url() ?>')"<?php endif ?>>
    <div class="hero-intro">
      <h1 aria-label="<?= $page->headline()->escape() ?>">
        <?php foreach (preg_split('/\s+/u', trim($page->headline()->value())) as $i => $word) : ?>
          <span class="hero-word" aria-hidden="true"><span style="--i: <?= $i ?>"><?= esc($word) ?></span></span>
        <?php endforeach ?>
      </h1>
      <p class="subheadline"><?= $page->subheadline() ?></p>
    </div>

    <?php if ($quotes->isNotEmpty()) : ?>
      <div class="hero-quotes" data-hero-quotes>
        <?php foreach ($quotes->values() as $i => $quoteFile) : ?>
          <?php $quote = $quoteFile->resize(null, 500); ?>
          <img
            class="hero-quote"
            <?= $i === 0 ? 'src' : 'data-src' ?>="<?= $quote->url() ?>"
            alt="<?= $quoteFile->alt()->or('Handgeschriebenes Zitat eines Kindes')->escape() ?>"
            width="<?= $quote->width() ?>"
            height="<?= $quote->height() ?>"
            decoding="async"
            <?= $i === 0 ? '' : 'aria-hidden="true"' ?>>
        <?php endforeach ?>
      </div>
      <noscript><style>.hero .hero-quote:first-child { opacity: 1; }</style></noscript>
    <?php endif ?>

    <div class="hero-actions">
      <a class="button button--go hero-cta" href="<?= url('anmeldung') ?>#mitgliedwerden">Kostenloses Probetraining</a>
      <a class="button button--down hero-cta hero-cta--secondary mobileVisible" href="#spielplan">zum Spielplan</a>
    </div>
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
                <span class="readmore">mehr&nbsp;lesen</span>
              </p>

            </article>
            <?php if ($newsImage = $article->cover()->toFile() ?? $article->image()) : $item = $newsImage->crop(300) ?>
              <img src="<?= $item->url() ?>" alt="<?= $newsImage->alt() ?>" class="rounded" loading="lazy">
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
        <h2 class="spielplan-heading">Spielplan der Berlin Tiger</h2>
        <?php if ($widgetNumber = $page->widgetNumber()) : ?>
          <?php snippet('spielplan-verein', [
            'widgetNumber' => $widgetNumber,
            // Match team pages with two schedules (iframeHeight 600)
            'iframeHeight' => $hasDbvSpielplan ? 600 : 1200,
          ]); ?>
        <?php endif ?>
      </article>

      <?php if ($hasDbvSpielplan) : ?>
        <?php foreach ($dbvSchedules as $schedule) : ?>
          <article class="dbv-spielplan">
            <h2 class="spielplan-heading">Spielplan der <?= $dbv->title() ?><?= $schedule->league()->isNotEmpty() ? ' ' . $schedule->league() : '' ?></h2>
            <?php snippet('spielplan-mannschaft', [
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