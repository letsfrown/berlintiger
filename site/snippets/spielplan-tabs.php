<?php
/**
 * Spielplan with optional pills: one visible widget at a time.
 * Single panel → headline only (no lonely pill). Multi → headline + pills.
 *
 * @param array $panels Each: label (string), html (string), variant? ('tiger'|'dbv')
 * @param string|null $heading Black chrome title (default: Spielplan)
 * @param string|null $ariaLabel Accessible name for the tablist
 * @param string|null $variant Heading accent: tiger (orange) | dbv (blue)
 */
$panels = array_values(array_filter($panels ?? [], function ($panel) {
  return is_array($panel) && !empty($panel['html']);
}));

if (count($panels) === 0) {
  return;
}

$heading = $heading ?? 'Spielplan';
$ariaLabel = $ariaLabel ?? 'Spielpläne';
$variant = ($variant ?? 'tiger') === 'dbv' ? 'dbv' : 'tiger';
$showTabs = count($panels) > 1;
$uid = 'sp-' . substr(uniqid('tabs', true), -8);
$articleClass = 'spielplan-tabs' . ($variant === 'dbv' ? ' dbv-spielplan' : '');
?>
<article class="<?= $articleClass ?>"<?php if ($showTabs) : ?> data-spielplan-tabs<?php endif ?>>
  <h2 class="spielplan-heading"><?= esc($heading) ?></h2>

  <?php if ($showTabs) : ?>
    <div
      class="spielplan-tabs__list"
      role="tablist"
      aria-label="<?= esc($ariaLabel, 'attr') ?>"
    >
      <?php foreach ($panels as $i => $panel) : ?>
        <?php
        $tabId = $uid . '-tab-' . $i;
        $panelId = $uid . '-panel-' . $i;
        $tabVariant = ($panel['variant'] ?? $variant) === 'dbv' ? 'dbv' : 'tiger';
        $selected = $i === 0;
        ?>
        <button
          type="button"
          class="spielplan-tabs__tab spielplan-tabs__tab--<?= $tabVariant ?>"
          role="tab"
          id="<?= esc($tabId, 'attr') ?>"
          aria-controls="<?= esc($panelId, 'attr') ?>"
          aria-selected="<?= $selected ? 'true' : 'false' ?>"
          tabindex="<?= $selected ? '0' : '-1' ?>"
          data-spielplan-tab
        >
          <span class="spielplan-tabs__dot" aria-hidden="true"></span>
          <?= esc($panel['label']) ?>
        </button>
      <?php endforeach ?>
    </div>
  <?php endif ?>

  <?php foreach ($panels as $i => $panel) : ?>
    <?php
    $tabId = $uid . '-tab-' . $i;
    $panelId = $uid . '-panel-' . $i;
    $selected = $i === 0;
    ?>
    <div
      class="spielplan-tabs__panel"
      <?php if ($showTabs) : ?>
        role="tabpanel"
        id="<?= esc($panelId, 'attr') ?>"
        aria-labelledby="<?= esc($tabId, 'attr') ?>"
        data-spielplan-panel
        <?php if (!$selected) : ?>hidden<?php endif ?>
      <?php endif ?>
    >
      <?= $panel['html'] ?>
    </div>
  <?php endforeach ?>
</article>
