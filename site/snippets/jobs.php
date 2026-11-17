<section id="<?= $data->slug() ?>" class="grid-2clm align-top reverse
<?php if ($bgColor = $data->bgColor()->lower()) : ?> 
<?= $bgColor ?> 
<?php endif ?>">

<?php if ($cover = $data->cover()->toFile()) :
    $img = $cover->crop(900, 450) ?>
    <img src="<?= $img->url() ?>" alt="<?= $img->alt() ?>" class="rounded" loading="lazy">
  <?php endif ?>

  <article>
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p class="subheadline jobs"><?= $data->subheadline() ?></p>

    <!-- Bewerben per E-Mail -->
    <?php
    $jobMail = (string)$tigerEmail;
    $mailto = fn (string $subject) => 'mailto:' . $jobMail . '?subject=' . rawurlencode($subject);
    ?>
    <div class="job-apply">
      <p class="cta">Bewirb dich bei uns als:</p>
      <div class="job-apply-buttons">
        <a class="button button--mail" href="<?= esc($mailto('Bewerbung als Trainer:in'), 'attr') ?>">Als Trainer:in bewerben</a>
        <a class="button button--mail" href="<?= esc($mailto('Bewerbung als Schiedsrichter:in'), 'attr') ?>">Als Schiri bewerben</a>
      </div>
      <p class="job-apply-mail sm">Oder schreib direkt an <a href="mailto:<?= esc($jobMail, 'attr') ?>"><?= esc($jobMail) ?></a></p>
    </div>




  </article>


</section>