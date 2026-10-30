<section id="<?= $data->title()->lower() ?>" class="grid-2clm nopadding 
<?php if ($bgColor = $data->bgColor()->lower()) : ?> 
<?= $bgColor ?> 
<?php endif ?>">

  <?php if ($data->cover()->isNotEmpty()) :
    $img = $data->cover()->toFile()->crop(800) ?>
    <img src="<?= $img->url() ?>" alt="">
  <?php endif ?>

  <article>
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p><?= $data->subheadline() ?></p>
    <?= $data->text()->kirbytext() ?>
  </article>

</section>