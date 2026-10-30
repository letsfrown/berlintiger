<?php
$titleColor = $titleColor ?? 'FFFFFF';
$titleBgColor = $titleBgColor ?? '000000';
$tapBgColorActive = $tapBgColorActive ?? 'FE5000';
$tapBorderColorActive = $tapBorderColorActive ?? 'FE5000';
?>
<div id="widget_<?= $schedule->widgetNumber() ?>"></div>

<script>
  widget.mannschaftswidget('widget_<?= $schedule->widgetNumber() ?>', {
    "iframeWidth": 800,
    "iframeHeight": 600,
    "showRefreshButton": true,
    "titleColor": "<?= esc($titleColor, 'js') ?>",
    "titleBgColor": "<?= esc($titleBgColor, 'js') ?>",
    "tapColor": "FFFFFF",
    "tapBgColor": "000000",
    "tapBgColorActive": "<?= esc($tapBgColorActive, 'js') ?>",
    "tapBorderColorActive": "<?= esc($tapBorderColorActive, 'js') ?>",
    "colorMatchGroup": "000000",
    "bgColorMatchGroup": "F5F1F0",
    "colorMatchListItem": "000000",
    "bgColorMatchListItem": "FFFFFF",
    "showKuerzelInSpiele": false,
    "mannschaftsId": "<?= $schedule->widgetId() ?>"
  });
</script>
