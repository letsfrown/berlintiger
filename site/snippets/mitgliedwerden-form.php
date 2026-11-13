    <!-- FORM START -->


    <?php if ($success) : ?>
      <div class="alert success" role="status">
        <p><?= $success ?></p>
      </div>
    <?php else : ?>
      <?php if (isset($alert['error'])) : ?>
        <p class="alert error" role="alert"><?= $alert['error'] ?></p>
      <?php endif ?>


      <form method="post" action="<?= $page->url() ?>" class="mitglied-form">



        <div class="honeypot">
          <label for="website">Website <abbr title="required">*</abbr></label>
          <input type="url" id="website" name="website" tabindex="-1">
        </div>

        <div class="field">
          <label for="name">Name<abbr title="required">*</abbr></label>
          <input type="text" id="name" name="name" autocomplete="name" placeholder="Vor- und Nachname" value="<?= esc($data['name'] ?? '', 'attr') ?>" required<?= isset($alert['name']) ? ' aria-invalid="true"' : '' ?>>
          <?= isset($alert['name']) ? '<span class="alert error">' . esc($alert['name']) . '</span>' : '' ?>
        </div>

        <div class="field">
          <label for="email">Email<abbr title="required">*</abbr></label>
          <input type="email" id="email" name="email" autocomplete="email" placeholder="name@beispiel.de" value="<?= esc($data['email'] ?? '', 'attr') ?>" required<?= isset($alert['email']) ? ' aria-invalid="true"' : '' ?>>
          <?= isset($alert['email']) ? '<span class="alert error">' . esc($alert['email']) . '</span>' : '' ?>
        </div>

        <div class="field">
          <label for="birthday">Geburtstag<abbr title="required">*</abbr></label>
          <input type="date" id="birthday" name="birthday" autocomplete="bday" min="1900-01-01" max="2100-01-01" placeholder="TT.MM.JJJJ" value="<?= esc($data['birthday'] ?? '', 'attr') ?>" required<?= isset($alert['birthday']) ? ' aria-invalid="true"' : '' ?>>
          <?= isset($alert['birthday']) ? '<span class="alert error">' . esc($alert['birthday']) . '</span>' : '' ?>
        </div>

        <div class="field">
          <label for="height">Körpergröße in cm</label>
          <input type="text" id="height" name="height" placeholder="z. B. 165" inputmode="numeric" value="<?= esc($data['height'] ?? '', 'attr') ?>"<?= isset($alert['height']) ? ' aria-invalid="true"' : '' ?>>
          <?= isset($alert['height']) ? '<span class="alert error">' . esc($alert['height']) . '</span>' : '' ?>
        </div>

        <div class="field school">
          <label for="school">Meine Schule</label>
          <input type="text" id="school" name="school" placeholder="Name der Schule" value="<?= esc($data['school'] ?? '', 'attr') ?>"<?= isset($alert['school']) ? ' aria-invalid="true"' : '' ?>>
          <?= isset($alert['school']) ? '<span class="alert error">' . esc($alert['school']) . '</span>' : '' ?>
        </div>

        <div class="field">
          <label for="experience">Beschreibe kurz deine Sport-Erfahrung</label>
          <textarea id="experience" name="experience" rows="4" placeholder="z. B. schon Basketball gespielt, andere Sportarten …"<?= isset($alert['experience']) ? ' aria-invalid="true"' : '' ?>><?= esc($data['experience'] ?? '') ?></textarea>
          <?= isset($alert['experience']) ? '<span class="alert error">' . esc($alert['experience']) . '</span>' : '' ?>
        </div>



        <!-- reCAPTCHA lädt erst, wenn jemand mit dem Formular interagiert -->
        <div class="g-recaptcha" data-sitekey="<?= esc(option('recaptcha.sitekey'), 'attr') ?>"></div>
        <script>
          (function () {
            var form = document.currentScript.closest('form');
            if (!form) return;
            var loaded = false;
            function loadRecaptcha() {
              if (loaded) return;
              loaded = true;
              var s = document.createElement('script');
              s.src = 'https://www.google.com/recaptcha/api.js';
              s.async = true;
              s.defer = true;
              document.head.appendChild(s);
            }
            form.addEventListener('focusin', loadRecaptcha, { once: true });
            form.addEventListener('pointerdown', loadRecaptcha, { once: true });
            <?php if (!empty($alert)) : ?>loadRecaptcha();<?php endif ?>
          })();
        </script>

        <input type="submit" name="submit" value="Anmelden" class="button">

        <div class="form-next">
          <p><strong>Wie geht's weiter?</strong> Nach dem Absenden melden wir uns per E-Mail bei dir und schlagen dir einen Termin für ein kostenloses Probetraining in einer passenden Trainingsgruppe vor.</p>
          <p>Geburtstag, Körpergröße und Schule helfen uns, dich der richtigen Altersklasse und einem Trainingsort in deiner Nähe zuzuordnen. Größe und Schule sind freiwillig.</p>
        </div>
        <p class="disclaimer"><?= $parent->text()->kirbytags() ?></p>

      </form>
    <?php endif ?>
    <!-- FORM END -->