<section id="<?= $data->title()->lower() ?>" <?php if ($bgColor = $data->bgColor()->lower()) : ?> class="<?= $bgColor ?>" <?php endif ?>>
  <h2><?= $data->headline()->or($data->title()) ?></h2>
  <p class="subheadline"><?= $data->subheadline() ?></p>
</section>

<section class="team-cards-wrapper">
    <?php foreach ($data->children()->listed() as $member) {
      snippet(('member'), ['data' => $member]);
    } ?>
</section>