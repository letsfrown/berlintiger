<section id="<?= $data->title()->lower() ?>" class="grid-2clm align-top ratio-3-2
<?php if ($bgColor = $data->bgColor()->lower()) : ?> 
<?= $bgColor ?> 
<?php endif ?>">

  <article>
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p class="subheadline md"><?= $data->subheadline() ?></p>

    <?php snippet('mitgliedwerden-form', ['parent' => $data])  ?>

  </article>

  <?php if ($data->cover()->isNotEmpty()) :
    $img = $data->cover()->toFile()->crop(900, 450) ?>
    <img src="<?= $img->url() ?>" alt="<?= $img->alt() ?>" class="rounded">
  <?php endif ?>

</section>