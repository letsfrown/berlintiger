<section id="<?= $data->title()->lower() ?>" class="grid-2clm align-top reverse
<?php if ($bgColor = $data->bgColor()->lower()) : ?> 
<?= $bgColor ?> 
<?php endif ?>">

<?php if ($data->cover()->isNotEmpty()) :
    $img = $data->cover()->toFile()->crop(900, 450) ?>
    <img src="<?= $img->url() ?>" alt="<?= $img->alt() ?>" class="rounded">
  <?php endif ?>

  <article>
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p class="subheadline jobs"><?= $data->subheadline() ?></p>

    <!-- Bewerbungsmaske -->
    <form method="post" action="<?= $page->url() ?>" class="job-form">
      <label class="cta">Bewirb dich bei uns als:</label>

      <div>
        <input type="radio" name="position" value="trainer" id="trainer">
        <label for="trainer">Trainer:in</label>
      </div>

      <div>
        <input type="radio" name="position" value="schiri" id="schiri">
        <label for="schiri">Schiedsrichter:in</label>
      </div>

      <div class="button">
        <a id="emailLink" href="mailto: <?= $tigerEmail ?>">Bewerben</a>
      </div>

    </form>



    <script>
      document.querySelectorAll('input[name="position"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
          var position = this.value;
          var emailLink = document.getElementById('emailLink');
          if (position === 'trainer') {
            emailLink.href = 'mailto: <?= $tigerEmail ?>?subject=Bewerbung als Trainer:in';
          } else if (position === 'schiri') {
            emailLink.href = 'mailto: <?= $tigerEmail ?>?subject=Bewerbung als Schiedsrichter:in';
          }
        });
      });
    </script>




  </article>


</section>