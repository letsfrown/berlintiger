<section id="<?= $data->title()->lower() ?>" class="grid-2clm align-top
<?php if ($bgColor = $data->bgColor()->lower()) : ?> 
<?= $bgColor ?> 
<?php endif ?>">

  <article>
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p class="subheadline"><?= $data->subheadline() ?></p>
    <?= $data->text()->kirbytext() ?>
  </article>

  <?php if ($img = $data->cover()->toFile()) : ?>
    <img class="cover ellipse" src="<?= $img->url() ?>" alt="<?= $img->alt() ?>">
  <?php endif ?>

</section>