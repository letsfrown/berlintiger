<?php
$titleColor = $titleColor ?? 'FFFFFF';
$tapBgColorActive = $tapBgColorActive ?? 'FE5000';
$tapBorderColorActive = $tapBorderColorActive ?? 'FE5000';
// BB uses titleBgColor for the checked switch thumb; keep it on the accent.
$titleBgColor = $titleBgColor ?? $tapBgColorActive;
$iframeHeight = (int)($iframeHeight ?? 600);
$widgetDomId = 'widget_' . $schedule->widgetNumber();
?>
<div
  id="<?= esc($widgetDomId, 'attr') ?>"
  class="bb-widget"
  data-bb-type="mannschaft"
  data-bb-mannschaft="<?= esc($schedule->widgetId(), 'attr') ?>"
  data-bb-height="<?= $iframeHeight ?>"
  data-bb-title-color="<?= esc($titleColor, 'attr') ?>"
  data-bb-title-bg="<?= esc($titleBgColor, 'attr') ?>"
  data-bb-tap-active="<?= esc($tapBgColorActive, 'attr') ?>"
  data-bb-tap-border="<?= esc($tapBorderColorActive, 'attr') ?>"
></div>
