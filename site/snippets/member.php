<div class="team-card-mask">

  <div class="team-card">
    <?php if ($img = $data->headshot()->toFile()) : ?>
      <?= $img->crop(450, 450) ?>
    <?php else : ?>
      <div class="img-placeholder"></div>
    <?php endif ?>


    <article>
      <h3><?= $data->title() ?></h3>
      <h4><?= $data->jobs() ?></h4>

      <?php if ($data->email()->isNotEmpty()) : ?>
        <br>
        <a class="button sm" href="mailto: <?= $data->email() ?>"><?= $data->email() ?></a>
      <?php endif ?>

      <div class="invisible">
        <?php if ($facts = $data->facts()->kt()) : ?>
          <?= $facts ?>
        <?php endif ?>

      </div>
    </article>


  </div>
</div>