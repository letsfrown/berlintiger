<footer>
  <!-- CONTACT, INTERNAL LINKS, SOCIALS -->
  <section>

    <article>
      <p>Kontakt</p>
      <nav>
        <?php if ($email = $site->email()) : ?>
          <a href="mailto:<?= $email ?>" class="button"><?= $email ?></a>
        <?php endif ?>
        <?php if ($ig = $site->instagram()) : ?>
          <a href="<?= $ig ?>" target="_blank" rel="noopener noreferrer" class="button">Instagram</a>
        <?php endif ?>
      </nav>
    </article>

    <article>
      <p>Mitmachen</p>
      <nav class="arrowLinks">
        <a href="/anmeldung#mitglied%20werden">Anmelden</a>
        <a href="/anmeldung#jobs">Bewerben</a>
        <a href="/about#spenden">Unterstützen</a>
      </nav>
    </article>

    <article>
      <p>Infos</p>
      <nav class="arrowLinks">
        <a href="/news">News</a>
        <a href="/angebote#teams">Teams</a>
        <a href="/angebote#schul-ags">Schul-AGs</a>
      </nav>
    </article>

    <article>
      <img src="<?= url('/assets/icons/kinderschutz.png') ?>" class="kinderschutz" alt="Kinderschutz-Siegel">
    </article>

  </section>

  <!-- IMPRINT, PRIVACY -->
  <section>
    <a href="<?= $site ?>">
      <img src="<?= url('/assets/logo/berlintiger-logo.svg') ?>" class="logo" alt="Berlin Tiger Logo">
    </a>

    <nav>
      <?php if ($satzung = $site->satzung()->toFile()) : ?>
        <a href="<?= $satzung->url() ?>" target="_blank" rel="noopener noreferrer">Satzung</a>
      <?php endif ?>

      <?php foreach ($site->children()->unlisted()->flip() as $unlistedPage) : ?>
        <?php if ($unlistedPage->footerLink()->isTrue()) : ?>
          <a href="<?= $unlistedPage->url() ?>"><?= $unlistedPage->title() ?></a>
        <?php endif ?>
      <?php endforeach ?>
    </nav>

    <span>©<?= date("Y") ?> Berlin Tiger – Basketball in Kreuzberg e.V.</span>

  </section>

</footer>


<?= js('assets/js/index.js') ?>
<?= js('@auto') ?>


</body>

</html>