    <!-- FORM START -->


    <?php if ($success) : ?>
      <div class="alert success">
        <p><?= $success ?></p>
      </div>
    <?php else : ?>
      <?php if (isset($alert['error'])) : ?>
        <p><?= $alert['error'] ?></p>
      <?php endif ?>


      <form method="post" action="<?= $page->url() ?>" class="mitglied-form">



        <div class="honeypot">
          <label for="website">Website <abbr title="required">*</abbr></label>
          <input type="url" id="website" name="website" tabindex="-1">
        </div>

        <div class="field">
          <label for="name">Name<abbr title="required">*</abbr></label>
          <input type="text" id="name" name="name" placeholder="Vor- und Nachname" value="<?= esc($data['name'] ?? '', 'attr') ?>" required<?= isset($alert['name']) ? ' aria-invalid="true"' : '' ?>>
          <?= isset($alert['name']) ? '<span class="alert error">' . esc($alert['name']) . '</span>' : '' ?>
        </div>

        <div class="field">
          <label for="email">Email<abbr title="required">*</abbr></label>
          <input type="email" id="email" name="email" placeholder="name@beispiel.de" value="<?= esc($data['email'] ?? '', 'attr') ?>" required<?= isset($alert['email']) ? ' aria-invalid="true"' : '' ?>>
          <?= isset($alert['email']) ? '<span class="alert error">' . esc($alert['email']) . '</span>' : '' ?>
        </div>

        <div class="field">
          <label for="birthday">Geburtstag<abbr title="required">*</abbr></label>
          <input type="date" id="birthday" name="birthday" min="1900-01-01" max="2100-01-01" placeholder="TT.MM.JJJJ" value="<?= esc($data['birthday'] ?? '', 'attr') ?>" required<?= isset($alert['birthday']) ? ' aria-invalid="true"' : '' ?>>
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



        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <div class="g-recaptcha" data-sitekey="6LcYoNMqAAAAAOBKkAOsb6GT2oBlItsYA1VmTwzS"></div>

        <input type="submit" name="submit" value="Anmelden" class="button">
        <p class="disclaimer"><?= $parent->text()->kirbytags() ?></p>

      </form>
    <?php endif ?>
    <!-- FORM END -->