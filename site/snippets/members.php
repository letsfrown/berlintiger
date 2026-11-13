<section id="<?= $data->title()->lower() ?>" <?php if ($bgColor = $data->bgColor()->lower()) : ?> class="<?= $bgColor ?>" <?php endif ?>>
  <h2><?= $data->headline()->or($data->title()) ?></h2>
  <p class="subheadline"><?= $data->subheadline() ?></p>
</section>

<?php $members = $data->children()->listed(); ?>
<?php if ($members->isNotEmpty()) : ?>
<section class="team-slider" aria-label="Team">
  <div class="team-slider-chrome">
    <button type="button" class="team-slider-btn team-slider-prev" aria-label="Vorherige Mitglieder">
      <span aria-hidden="true">‹</span>
    </button>

    <div class="team-slider-viewport">
      <ul class="team-slider-track">
        <?php foreach ($members as $member) : ?>
          <?php snippet('member', ['data' => $member]); ?>
        <?php endforeach ?>
      </ul>
    </div>

    <button type="button" class="team-slider-btn team-slider-next" aria-label="Nächste Mitglieder">
      <span aria-hidden="true">›</span>
    </button>
  </div>
</section>
<?php endif ?>
