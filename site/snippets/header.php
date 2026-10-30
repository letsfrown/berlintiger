<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if ($page->isHomePage()) : ?>
        <title><?= $site->title() ?></title>
    <?php else : ?>
        <title><?= $page->title() ?> | <?= $site->title() ?></title>
    <?php endif ?>

    <?php snippet('autofavicon') ?>
    <?= css('/assets/css/reset.css') ?>
    <?= css('/assets/css/index.css') ?>
    <?= css('@auto') ?>

    <script type="text/javascript" src="//www.basketball-bund.net/rest/widget/widgetjs"></script>

</head>

<body>
    <header>
        <!-- MAIN MENU -->
        <menu class="menu">

            <!-- HOME-BUTTON / LOGO -->
            <a href="<?= $site->url() ?>" <?php e($site->homePage()->isOpen(), 'class="home-link active"') ?> name="Home" class="home-link">
                <?php if ($page->template()->name() === 'dbv') : ?>
                    <img src="/assets/logo/berlintiger-logo_dbv.svg" alt="Berlin Tiger DBV Logo">
                <?php else : ?>
                    <img src="/assets/logo/berlintiger-logo.svg" alt="Berlin Tiger Logo">
                <?php endif ?>
                <span><?= $site->title() ?></span>
            </a>


            <!-- MENU BUTTON (MOBILE) -->
            <input type="checkbox" id="menu-button">
            <label for="menu-button">
                <i class="hamburger-line"></i>
            </label>

            <!-- NAV LINKS -->
            <nav class="navigation">
                <a href="<?= $site->url() ?>" name="Home" class="mobileVisible">
                    <span><?= $site->title() ?></span>
                </a>

                <?php foreach ($site->children()->listed() as $item) : ?>
                    <?php
                    $classes = [];
                    if ($item->isOpen()) $classes[] = 'active';
                    if ($item->template()->name() === 'dbv') $classes[] = 'dbv-page';
                    ?>
                    <a href="<?= $item->url() ?>"
                        name="<?= $item->title() ?>"
                        <?= !empty($classes) ? 'class="' . implode(' ', $classes) . '"' : '' ?>>
                        <span><?= $item->title() ?></span>
                    </a>
                <?php endforeach ?>

                <a href="<?= $site->shop()->url() ?>" target="blank">
                    <span>Shop</span>
                </a>
            </nav>
        </menu>

        <!-- SUBMENU -->
        <?php if ($page->hasChildren() && $page->isListed()) : ?>
            <nav class="subnavigation">
                <?php foreach ($page->children()->listed() as $item) : ?>
                    <a href="#<?= $item->title()->lower() ?>"><?= $item->title() ?></a>
                <?php endforeach ?>
            </nav>
        <?php endif ?>

    </header>