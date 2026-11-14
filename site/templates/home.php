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
          <?php $quote = $quoteFile->resize(null, 500); /* nur für width/height */ ?>
          <img
            class="hero-quote"
            <?= $i === 0 ? 'src' : 'data-src' ?>="<?= $quoteFile->inkUrl(500) ?>"
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
      <a class="button button--go hero-cta" href="<?= url('anmeldung') ?>#mitgliedwerden">Zum Probetraining</a>
      <a class="button button--down hero-cta hero-cta--secondary mobileVisible" href="#spielplan">Zum Spielplan</a>
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
    $widgetNumber = $page->widgetNumber();
    $hasVereinSpielplan = $widgetNumber->isNotEmpty();

    $panels = [];
    if ($hasVereinSpielplan) {
      $panels[] = [
        'label'   => 'Berlin Tiger',
        'variant' => 'tiger',
        'html'    => snippet('spielplan-verein', [
          'widgetNumber' => $widgetNumber,
          'iframeHeight' => 1200,
        ], true),
      ];
    }
    if ($hasDbvSpielplan) {
      foreach ($dbvSchedules as $schedule) {
        $label = $schedule->league()->isNotEmpty()
          ? $schedule->league()->value()
          : $dbv->title()->value();
        $panels[] = [
          'label'   => $label,
          'variant' => 'dbv',
          'html'    => snippet('spielplan-mannschaft', [
            'schedule' => $schedule,
            'titleBgColor' => '242c53',
            'tapBgColorActive' => '242c53',
            'tapBorderColorActive' => '242c53',
            'iframeHeight' => 1200,
          ], true),
        ];
      }
    }
    ?>
    <?php if (count($panels) > 0) : ?>
      <aside class="sidebar" id="spielplan">
        <?php snippet('spielplan-tabs', [
          'heading'   => 'Spielplan',
          'ariaLabel' => 'Spielpläne',
          'variant'   => $hasVereinSpielplan ? 'tiger' : 'dbv',
          'panels'    => $panels,
        ]); ?>
      </aside>
    <?php endif ?>


  </section>

</main>
<?php snippet('footer') ?>