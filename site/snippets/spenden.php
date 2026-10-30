<?php
$supporterHeadlines = [
  'Gold-Support' => 'Unsere Gold-Supporter',
  'Silber-Support' => 'Unsere Silber-Supporter',
  'Bronze-Support' => 'Unsere Bronze-Supporter',
];
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
    <div class="sponsorship-grid">
      <?php foreach ($tiers as $tier) : ?>
        <div class="sponsorship-tier">
          <input type="checkbox" id="tier-<?= $tier->id() ?>" class="sponsorship-toggle" aria-hidden="true">
          <label for="tier-<?= $tier->id() ?>" class="sponsorship-tier-header">
            <h3><?= $tier->tierName() ?></h3>
            <p class="sponsorship-price"><?= $tier->priceRange() ?></p>
            <span class="sponsorship-toggle-icon">▼</span>
          </label>
          <div class="sponsorship-tier-content">
            <?php if ($benefits = $tier->benefits()) : ?>
              <ul>
                <?php foreach (explode("\n", $benefits->value()) as $benefit) : ?>
                  <?php if (trim($benefit)) : ?>
                    <li><?= trim($benefit) ?></li>
                  <?php endif ?>
                <?php endforeach ?>
              </ul>
            <?php endif ?>

            <?php if ($tier->supporters()->isNotEmpty() && ($headline = $supporterHeadlines[$tier->tierName()->value()] ?? null)) : ?>
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
      <?php endforeach ?>
    </div>
  <?php endif ?>

  <?php if ($data->textAfter()->isNotEmpty()) : ?>
    <article class="max-width">
      <?= $data->textAfter()->kirbytext() ?>
    </article>
  <?php endif ?>


</section>
