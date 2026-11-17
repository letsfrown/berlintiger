<p>Es gibt eine neue Anmeldung!</p>

<p>Name: <?= esc($name) ?></p>
<p>Kontakt: <?= esc($email) ?></p>

<p>Geburtstag: <?= esc($birthday ? date('d.m.Y', strtotime($birthday)) : '') ?></p>
<p>Körpergröße: <?= esc($height) ?></p>
<p>Schule: <?= esc($school) ?></p>
<p>Sporterfahrung: <?= nl2br(esc($experience)) ?></p>
