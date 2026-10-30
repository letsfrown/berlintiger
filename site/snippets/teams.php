<section id="<?= $data->title()->lower() ?>" class="grid-2clm nopadding align-top reverse
<?php if ($bgColor = $data->bgColor()->lower()) : ?> 
<?= $bgColor ?> 
<?php endif ?>">

  <article class="padding-responsive-lg">
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p><?= $data->subheadline() ?></p>
    <?= $data->text()->kirbytext() ?>
  </article>

  <!-- Array mit Altersklassen anlegen -->
  <?php foreach ($teams as $team) : ?>
    <?php $ageGroups = $team->blueprint()->field('category')['options'] ?>
  <?php endforeach ?>

  <ul class="accordeon padding-responsive-lg ">
    <!-- Liste Trainingsgruppen, gruppiert nach Altersklasse -->
    <?php foreach ($groupedTeams as $ageGroup => $teamsPerAge) : ?>

      <li>
        <!-- Altersklasse aus Array abrufen -->
        <label for="<?= $ageGroups[$ageGroup] ?>">Trainingsgruppen der <?= $ageGroups[$ageGroup] ?></label>
        <input id="<?= $ageGroups[$ageGroup] ?>" name="radio" type="radio" />

        <!-- Trainingsgruppen in Altersklasse -->
        <ul class="panel">
          <?php foreach ($teamsPerAge as $team) : ?>
            <a href="<?= $team->url() ?>"><?= $team->title() ?></a>
          <?php endforeach ?>
        </ul>

      </li>
    <?php endforeach ?>
  </ul>





</section>