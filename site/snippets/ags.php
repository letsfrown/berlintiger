<section id="<?= $data->title()->lower() ?>" class="grid-2clm 
<?php if ($bgColor = $data->bgColor()->lower()) : ?> 
<?= $bgColor ?> 
<?php endif ?>">

  <?php if ($map = $data->src()->toFile()) : ?>
    <figure class="annotated">
    <img src="<?php echo $map->url() ?>">

      <?php foreach ($data->markers()->toStructure() as $marker) : ?>
        <div class="dot" style="<?php echo markerStyle($marker); ?>">
          <p><?= $marker->schoolName() ?><br>
            <span><?= $marker->schoolBezirk() ?><?php if ($marker->schoolAgs()->isNotEmpty()) : ?>, <?= $marker->schoolAgs() ?>&nbsp;AGs</span>
          <?php endif ?>
          </p>
        </div>
      <?php endforeach; ?>

    </figure>
  <?php endif; ?>

  <article>
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p><?= $data->subheadline() ?></p>
    <?= $data->text()->kt() ?>
  </article>

</section>