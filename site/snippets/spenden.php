<?php
$supporterHeadlines = [
  'Gold-Support' => 'Unsere Gold-Supporter',
  'Silber-Support' => 'Unsere Silber-Supporter',
  'Bronze-Support' => 'Unsere Bronze-Supporter',
];

$tierRank = [
  'Bronze-Support' => 1,
  'Silber-Support' => 2,
  'Gold-Support' => 3,
];

$parseBenefits = function ($field): array {
  if (!$field || $field->isEmpty()) {
    return [];
  }

  $lines = preg_split('/\R/u', $field->value()) ?: [];
  $benefits = [];

  foreach ($lines as $line) {
    $line = trim($line);
    if ($line !== '') {
      $benefits[] = $line;
    }
  }

  return $benefits;
};
?>

<section id="<?= $data->title()->lower() ?>" class="grid-2clm align-top
<?php if ($bgColor = $data->bgColor()->lower()) : ?>
<?= $bgColor ?>
<?php endif ?>">

  <article class="max-width">
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p class="subheadline"><?= $data->subheadline() ?></p>

    <?php if ($data->textBefore()->isNotEmpty()) : ?>
      <?= $data->textBefore()->kirbytext() ?>
    <?php endif ?>
  </article>

  <?php if ($tiers = $data->sponsorshipTiers()->toStructure()) : ?>
    <?php
    $tiersSorted = $tiers->sortBy(
      fn ($tier) => $tierRank[$tier->tierName()->value()] ?? 99,
      'asc'
    );

    $previousBenefits = [];
    $previousName = null;
    ?>
    <div class="sponsorship-slider" aria-label="Supportstufen">
      <div class="sponsorship-grid">
        <?php foreach ($tiersSorted as $tier) : ?>
          <?php
          $tierName = $tier->tierName()->value();
          $allBenefits = $parseBenefits($tier->benefits());
          $addedBenefits = array_values(array_filter(
            $allBenefits,
            fn ($benefit) => !in_array($benefit, $previousBenefits, true)
          ));
          $includesLabel = $previousName
            ? 'Alles aus ' . $previousName . ', plus:'
            : 'Enthaltene Leistungen:';
          ?>
          <div class="sponsorship-tier">
            <div class="sponsorship-tier-header">
              <h3><?= $tier->tierName() ?></h3>
              <p class="sponsorship-price"><?= $tier->priceRange() ?></p>
            </div>

            <div class="sponsorship-tier-content">
              <p class="sponsorship-includes"><?= esc($includesLabel) ?></p>

              <?php if ($addedBenefits) : ?>
                <ul>
                  <?php foreach ($addedBenefits as $benefit) : ?>
                    <li><?= esc($benefit) ?></li>
                  <?php endforeach ?>
                </ul>
              <?php endif ?>

              <?php if ($tier->supporters()->isNotEmpty() && ($headline = $supporterHeadlines[$tierName] ?? null)) : ?>
                <div class="sponsorship-supporters">
                  <h4><?= $headline ?>:</h4>
                  <ul class="supporter-list">
                    <?php foreach ($tier->supporters()->toStructure() as $supporter) : ?>
                      <?php if ($logo = $supporter->logo()->toFile()) : ?>
                        <li>
                          <?php if ($supporter->link()->isNotEmpty()) : ?>
                            <a href="<?= $supporter->link() ?>" target="_blank" rel="noopener noreferrer">
                              <img src="<?= $logo->resize(240)->url() ?>" alt="<?= $logo->alt()->or($headline) ?>">
                            </a>
                          <?php else : ?>
                            <img src="<?= $logo->resize(240)->url() ?>" alt="<?= $logo->alt()->or($headline) ?>">
                          <?php endif ?>
                        </li>
                      <?php elseif ($supporter->link()->isNotEmpty()) : ?>
                        <li>
                          <a href="<?= $supporter->link() ?>" target="_blank" rel="noopener noreferrer"><?= $supporter->link() ?></a>
                        </li>
                      <?php endif ?>
                    <?php endforeach ?>
                  </ul>
                </div>
              <?php endif ?>
            </div>
          </div>
          <?php
          $previousBenefits = $allBenefits;
          $previousName = $tierName;
          ?>
        <?php endforeach ?>
      </div>
    </div>
  <?php endif ?>

  <?php if ($data->textAfter()->isNotEmpty()) : ?>
    <article class="max-width">
      <?= $data->textAfter()->kirbytext() ?>
    </article>
  <?php endif ?>


</section>
