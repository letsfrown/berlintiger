<section id="<?= $data->title()->lower() ?>">
  <article class="max-width">

    <div class="turnier-header">
      <h2><?= $data->headline()->or($data->title()) ?></h2>
      <?php if ($logo = $data->cover()->toFile()) : ?>
        <img src="<?= $logo->resize(200)->url() ?>" alt="<?= $logo->alt() ?>" class="turnier-logo">
      <?php else : ?>
        <div class="turnier-logo placeholder"></div>
      <?php endif ?>
    </div>

    <?= $data->text()->kirbytext() ?>

  </article>
  
  <?php if ($data->gallery()->isNotEmpty()) : ?>
    <ul class="gallery">
      <?php foreach ($data->gallery()->toFiles() as $image) : ?>
        <li><img src="<?= $image->resize(800)->url() ?>" alt="<?= $image->alt() ?>"></li>
      <?php endforeach ?>
    </ul> <?php endif ?>
</section>