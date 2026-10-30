<section id="<?= $data->title()->lower() ?>" <?php if ($bgColor = $data->bgColor()->lower()) : ?> class="geschichte <?= $bgColor ?>" <?php endif ?>>



  <h2><?= $data->headline()->or($data->title()) ?></h2>
  <p class="subheadline"><?= $data->subheadline() ?></p>
  <?= $data->text()->kirbytext() ?>


  <?php if ($timeline = $data->src()->toFile()) : ?>
    <figure class="annotated">

      <?php foreach ($data->markers()->toStructure() as $marker) : ?>
        <div class="dot"  style="<?php echo markerStyle($marker); ?>">
          <p><span><?= $marker->year() ?><br></span>
            <?= $marker->comment() ?>
          </p>
        </div>
      <?php endforeach; ?>
      <img src="<?php echo $timeline->url() ?>">

    </figure>
  <?php endif; ?>

</section>