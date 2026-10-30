Es gibt eine neue Anmeldung!

<p>Name: <?= $name ?></p>
<p>Kontakt: <?= $email ?></p>

<p>Geburtstag: <?= date("d.m.Y", strtotime($birthday)); ?></p>
<p>Körpergröße: <?= $height ?></p>
<p>Schule: <?= $school ?></p>
<p>Sporterfahrung: <?= $experience ?></p>