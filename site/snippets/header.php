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

    <?php snippet('seo') ?>
    <?php snippet('autofavicon') ?>
    <?= css('/assets/css/reset.css') ?>
    <?= css('/assets/css/index.css') ?>
    <?= css('@auto') ?>

    <!-- Spielplan-Widgets (basketball-bund.net), blockiert das Rendern nicht -->
    <script src="https://www.basketball-bund.net/rest/widget/widgetjs" defer></script>

</head>

<body>
    <header>
        <!-- MAIN MENU -->
        <menu class="menu">

            <!-- HOME-BUTTON / LOGO -->
            <a href="<?= $site->url() ?>" class="home-link<?= $site->homePage()->isOpen() ? ' active' : '' ?>">
                <?php if ($page->template()->name() === 'dbv') : ?>
                    <img src="/assets/logo/berlintiger-logo_dbv_3.svg" alt="Berlin Tiger DBV Logo">
                <?php else : ?>
                    <img src="/assets/logo/berlintiger-logo.svg" alt="Berlin Tiger Logo">
                <?php endif ?>
                <span><?= $site->title() ?></span>
            </a>


            <!-- MENU BUTTON (MOBILE) -->
            <input type="checkbox" id="menu-button" aria-label="Menü öffnen" aria-controls="main-navigation">
            <label for="menu-button" title="Menü">
                <i class="hamburger-line" aria-hidden="true"></i>
            </label>

            <!-- NAV LINKS -->
            <nav class="navigation" id="main-navigation" aria-label="Hauptmenü">

                <?php foreach ($site->children()->listed() as $item) : ?>
                    <?php
                    $classes = [];
                    if ($item->isOpen()) $classes[] = 'active';
                    if ($item->template()->name() === 'dbv') $classes[] = 'dbv-page';
                    ?>
                    <a href="<?= $item->url() ?>"
                        <?= $item->isOpen() ? 'aria-current="page"' : '' ?>
                        <?= !empty($classes) ? 'class="' . implode(' ', $classes) . '"' : '' ?>>
                        <span><?= $item->title() ?></span>
                    </a>
                <?php endforeach ?>

                <a href="<?= $site->shop()->url() ?>" target="_blank" rel="noopener noreferrer">
                    <span>Shop</span>
                </a>
            </nav>
        </menu>

        <!-- SUBMENU -->
        <?php if ($page->hasChildren() && $page->isListed()) : ?>
            <nav class="subnavigation" aria-label="Abschnitte">
                <?php foreach ($page->children()->listed() as $item) : ?>
                    <a href="#<?= $item->slug() ?>"><?= $item->title() ?></a>
                <?php endforeach ?>
            </nav>
        <?php endif ?>

    </header>