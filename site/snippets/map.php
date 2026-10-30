<?php if ($map = $data->src()->toFile()) : ?>
  <?php $mapImage = $map->resize(1200); ?>
  <figure class="annotated">
    <img src="<?= $mapImage->url() ?>" width="<?= $mapImage->width() ?>" height="<?= $mapImage->height() ?>" alt="Karte: Basketball-Standorte von Berlin Tiger in Kreuzberg">

    <?php foreach ($data->markers()->toStructure() as $marker) : ?>
      <div class="dot" style="<?php echo markerStyle($marker); ?>">
        <p><?= $marker->schoolName() ?><br>
          <span><?= $marker->schoolBezirk() ?>
            <?php if ($marker->schoolAgs()->toInt() > 1) : ?>, <?= $marker->schoolAgs() ?>&nbsp;AGs<?php endif ?>
            <?php if ($marker->schoolAgs()->toInt() == 1) : ?>, <?= $marker->schoolAgs() ?>&nbsp;AG<?php endif ?>
          </span>
        </p>
      </div>
    <?php endforeach; ?>

  </figure>
<?php endif; ?>