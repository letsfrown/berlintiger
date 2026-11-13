<section id="<?= $data->slug() ?>" class="grid-2clm nopadding align-top reverse
<?php if ($bgColor = $data->bgColor()->lower()) : ?> 
<?= $bgColor ?> 
<?php endif ?>">

  <article class="padding-responsive-lg">
    <h2><?= $data->headline()->or($data->title()) ?></h2>
    <p><?= $data->subheadline() ?></p>
    <?= $data->text()->kirbytext() ?>
  </article>

  <?php
  // Labels der Altersklassen aus dem Team-Blueprint (z. B. "u14" => "U14")
  $firstTeam = $teams->first();
  $ageGroups = $firstTeam ? ($firstTeam->blueprint()->field('category')['options'] ?? []) : [];
  ?>

  <ul class="accordeon padding-responsive-lg">
    <!-- Trainingsgruppen, gruppiert nach Altersklasse -->
    <?php foreach ($groupedTeams as $ageGroup => $teamsPerAge) : ?>
      <?php $ageLabel = $ageGroups[$ageGroup] ?? strtoupper((string)$ageGroup); ?>
      <li>
        <details>
          <summary>Trainingsgruppen der <?= esc($ageLabel) ?></summary>
          <ul class="panel">
            <?php foreach ($teamsPerAge as $team) : ?>
              <li><a href="<?= $team->url() ?>"><?= $team->title() ?></a></li>
            <?php endforeach ?>
          </ul>
        </details>
      </li>
    <?php endforeach ?>
  </ul>





</section>