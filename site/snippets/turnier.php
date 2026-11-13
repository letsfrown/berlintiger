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
  
  <?php snippet('gallery', ['images' => $data->gallery()]) ?>
</section>