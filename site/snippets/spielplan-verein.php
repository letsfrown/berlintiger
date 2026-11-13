<?php
$desktopHeight = (int)($iframeHeight ?? 1200);
$mobileHeight = (int)($iframeHeightMobile ?? 600);
$vereinId = $vereinsId ?? '3986';
$baseId = 'widget_' . $widgetNumber;
?>
<div
  class="bb-widget mobileHidden"
  id="<?= esc($baseId, 'attr') ?>"
  data-bb-type="verein"
  data-bb-verein="<?= esc($vereinId, 'attr') ?>"
  data-bb-height="<?= $desktopHeight ?>"
  data-bb-title-color="FFFFFF"
  data-bb-title-bg="FE5000"
  data-bb-tap-active="FE5000"
  data-bb-tap-border="FE5000"
  data-bb-range-days="7"
></div>

<div
  class="bb-widget mobileVisible"
  id="<?= esc($baseId . '2', 'attr') ?>"
  data-bb-type="verein"
  data-bb-verein="<?= esc($vereinId, 'attr') ?>"
  data-bb-height="<?= $mobileHeight ?>"
  data-bb-title-color="FFFFFF"
  data-bb-title-bg="FE5000"
  data-bb-tap-active="FE5000"
  data-bb-tap-border="FE5000"
  data-bb-range-days="7"
></div>
