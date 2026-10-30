<?php
$desktopHeight = $iframeHeight ?? 1200;
$mobileHeight = $iframeHeightMobile ?? 600;
?>
<div class="mobileHidden" id="widget_<?= $widgetNumber ?>"></div>
<script>
  widget.vereinswidget('widget_<?= $widgetNumber ?>', {
    "iframeWidth": 800,
    "iframeHeight": <?= (int)$desktopHeight ?>,
    "showRefreshButton": true,
    "titleColor": "000000",
    "titleBgColor": "FE5000",
    "tapColor": "FFFFFF",
    "tapBgColor": "000000",
    "tapBgColorActive": "FE5000",
    "tapBorderColorActive": "FE5000",
    "colorMatchGroup": "000000",
    "bgColorMatchGroup": "F5F1F0",
    "colorMatchListItem": "000000",
    "bgColorMatchListItem": "FFFFFF",
    "showKuerzelInSpiele": false,
    "vereinsId": "3986",
    "homeMatchesOnlyInit": false,
    "rangeDays": 7
  });
</script>


<div class="mobileVisible" id="widget_<?= $widgetNumber ?>2"></div>
<script>
  widget.vereinswidget('widget_<?= $widgetNumber ?>2', {
    "iframeWidth": 800,
    "iframeHeight": <?= (int)$mobileHeight ?>,
    "showRefreshButton": true,
    "titleColor": "000000",
    "titleBgColor": "FE5000",
    "tapColor": "FFFFFF",
    "tapBgColor": "000000",
    "tapBgColorActive": "FE5000",
    "tapBorderColorActive": "FE5000",
    "colorMatchGroup": "000000",
    "bgColorMatchGroup": "F5F1F0",
    "colorMatchListItem": "000000",
    "bgColorMatchListItem": "FFFFFF",
    "showKuerzelInSpiele": false,
    "vereinsId": "3986",
    "homeMatchesOnlyInit": false,
    "rangeDays": 7
  });
</script>
