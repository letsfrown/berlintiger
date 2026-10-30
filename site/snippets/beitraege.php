<section id="<?= $data->title()->lower() ?>" class="grid-2clm align-top
<?php if ($bgColor = $data->bgColor()->lower()) : ?> 
<?= $bgColor ?> 
<?php endif ?>">

  <article>
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p class="subheadline"><?= $data->subheadline() ?></p>
    <?= $data->text()->kirbytext() ?>
    <form action=""></form>
  </article>

  <article class="price-list">
    <?php if ($beitraege = $data->beitraege()->toStructure()->groupBy('ageGroup')) : ?>
      <?php
      $categoryOrder = array_flip($data->blueprint()->field('beitraege')['fields']['category']['options'] ?? []);
      ?>
      <?php foreach ($beitraege as $ageGroup => $beitraegePerAgeGroup) : ?>

        <h3><?= str::ucfirst($ageGroup) ?></h3>
        <?php foreach ($beitraegePerAgeGroup->sortBy(fn ($beitrag) => $categoryOrder[(string) $beitrag->category()] ?? PHP_INT_MAX) as $beitrag) : ?>
          <div class="flex">
            <div>
              <h4><?= $beitrag->category() ?></h4>
              <?php if ($comment = $beitrag->comment()) : ?>
                <span class="comment"><?= $beitrag->comment() ?></span>
              <?php endif ?>
            </div>
            <div class="price-wrapper">
              <span class="price"><?= $beitrag->priceMonthly() ?></span>
              <span class="time-period"> / Monat</span>
            </div>
          </div>
        <?php endforeach ?>

      <?php endforeach ?>
    <?php endif ?>
  </article>

</section>