<section id="<?= $data->slug() ?>" class="grid-2clm align-top
<?php if ($bgColor = $data->bgColor()->lower()) : ?> 
<?= $bgColor ?> 
<?php endif ?>">

  <article>
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p class="subheadline md"><?= $data->subheadline() ?></p>

    <?php snippet('mitgliedwerden-form', ['parent' => $data])  ?>

  </article>

  <?php if ($coverFile = $data->cover()->toFile()) :
    // Square crop matches .rounded 1/1; width/height reserve layout space (CLS).
    $img = $coverFile->crop(900, 900) ?>
    <img src="<?= $img->url() ?>" alt="<?= $img->alt() ?>" width="<?= $img->width() ?>" height="<?= $img->height() ?>" class="rounded">
  <?php endif ?>

</section>