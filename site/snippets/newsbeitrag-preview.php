<a href="<?= $data->url() ?>">
    <article class="news-preview">
        <p class="date"><?= $data->date()->toDate('d.m.Y') ?></p>
        <h2><?= $data->title(); ?></h2>

        <?php if ($cover = $data->cover()->toFile()) : ?>
            <img src="<?= $cover->crop(600, 400)->url() ?>" alt="<?= $cover->alt() ?>">
        <?php endif ?>

        <p>
            <?= $data->intro()
            ->or($data->text()->excerpt(160, false, '…')) ?>
            <span class="readmore">&rarr;&nbsp;mehr&nbsp;lesen</span>
        </p>
    </article>
</a>