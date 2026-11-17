<?php
$member = $data;
$firstName = explode(' ', trim($member->title()->value()))[0];
$includeFacts = $includeFacts ?? true;
$role = $role ?? ($member->jobs()->isNotEmpty() ? $member->jobs() : null);
$tag = $tag ?? 'li';
$hasContact = $member->email()->isNotEmpty();
$hasFacts = $includeFacts && $member->facts()->isNotEmpty();
$hasExtras = $hasContact || $hasFacts;
$panelId = 'member-' . $member->slug() . '-panel';
?>
<<?= $tag ?> class="team-card<?= $hasExtras ? ' has-extras' : '' ?>">
  <div class="team-card-inner">
    <?php if ($hasExtras) : ?>
      <div class="team-card-panel" id="<?= $panelId ?>" aria-hidden="true">
        <div class="team-card-panel-body<?= $hasContact && !$hasFacts ? ' team-card-panel-body--contact' : '' ?>">
          <?php if ($hasContact) : ?>
            <a class="button sm team-mail" href="mailto:<?= esc($member->email(), 'attr') ?>">
              <svg class="team-mail-icon" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
                <path fill="currentColor" d="M1.5 3.5A1.5 1.5 0 0 1 3 2h10a1.5 1.5 0 0 1 1.5 1.5v9A1.5 1.5 0 0 1 13 14H3a1.5 1.5 0 0 1-1.5-1.5v-9Zm1.5 0v.4l5 3.2 5-3.2V3.5H3Zm10 1.77-4.66 3a.75.75 0 0 1-.68 0L3 5.27V12.5h10V5.27Z"/>
              </svg>
              <span><?= esc($firstName) ?> kontaktieren</span>
            </a>
          <?php endif ?>

          <?php if ($hasFacts) : ?>
            <div class="team-card-facts">
              <?= $member->facts()->kt() ?>
            </div>
          <?php endif ?>
        </div>
      </div>
    <?php endif ?>

    <div class="team-card-front">
      <?php if ($img = $member->headshot()->toFile()) : ?>
        <div class="team-card-media">
          <?= $img->crop(450, 450) ?>
        </div>
      <?php else : ?>
        <div class="team-card-media">
          <div class="img-placeholder" aria-hidden="true"></div>
        </div>
      <?php endif ?>

      <div class="team-card-meta">
        <h3 class="team-card-name"><?= $member->title() ?></h3>
        <?php if ($role) : ?>
          <p class="team-card-role"><?= $role ?></p>
        <?php endif ?>
      </div>
    </div>

    <?php if ($hasExtras) : ?>
      <button
        type="button"
        class="team-card-toggle"
        aria-expanded="false"
        aria-controls="<?= $panelId ?>"
        aria-label="<?= $hasFacts ? 'Mehr über' : 'Kontakt zu' ?> <?= esc($member->title(), 'attr') ?>"
      >
        <span class="team-card-toggle-icon" aria-hidden="true"></span>
      </button>
    <?php endif ?>
  </div>
</<?= $tag ?>>
