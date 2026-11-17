<section id="<?= $data->slug() ?>" class="grid-2clm nopadding reverse
<?php if ($bgColor = $data->bgColor()->lower()) : ?> 
<?= $bgColor ?> 
<?php endif ?>">

  <article class="padding-responsive-lg">
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p><?= $data->subheadline() ?></p>
    <?= $data->text()->kirbytext() ?>
  </article>

  <?php if ($coverFile = $data->cover()->toFile()) :
    $img = $coverFile->crop(800) ?>
    <img src="<?= $img->url() ?>" alt="">
  <?php endif ?>

</section>