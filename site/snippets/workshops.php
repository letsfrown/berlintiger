<section id="<?= $data->slug() ?>" class="grid-2clm nopadding 
<?php if ($bgColor = $data->bgColor()->lower()) : ?> 
<?= $bgColor ?> 
<?php endif ?>">

  <?php if ($coverFile = $data->cover()->toFile()) :
    $img = $coverFile->crop(800) ?>
    <img src="<?= $img->url() ?>" alt="">
  <?php endif ?>

  <article>
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p><?= $data->subheadline() ?></p>
    <?= $data->text()->kirbytext() ?>
  </article>

</section>