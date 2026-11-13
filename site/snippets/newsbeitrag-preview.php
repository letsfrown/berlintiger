<a href="<?= $data->url() ?>">
    <article class="news-preview">
        <?php if ($cover = $data->cover()->toFile()) : ?>
            <img src="<?= $cover->crop(480, 480)->url() ?>" alt="<?= $cover->alt() ?>">
        <?php endif ?>

        <p class="date"><?= $data->date()->toDate('d.m.Y') ?></p>
        <h2><?= $data->title(); ?></h2>

        <?php
        // Intro is stored as HTML (<p>…</p>); strip so it stays one plain block at sm size.
        $excerpt = $data->intro()->isNotEmpty()
            ? Str::unhtml($data->intro()->value())
            : $data->text()->excerpt(110, true, '…');
        ?>
        <p class="news-preview-excerpt sm"><?= esc($excerpt) ?></p>
        <span class="readmore">mehr&nbsp;lesen</span>
    </article>
</a>
