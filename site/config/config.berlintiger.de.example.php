<?php

// Vorlage: als config.berlintiger.de.php speichern und Secrets eintragen.
// Die echte Datei ist gitignored und wird nur per deploy-code.sh hochgeladen.
return [
  'debug' => false,
  'recaptcha.secret' => 'HIER_SECRET_EINTRAGEN',

  // Lange Zufallsstrings – nie committen, nur in der Live-Datei
  'content' => [
    'salt' => 'HIER_CONTENT_SALT_EINTRAGEN',
  ],
  'cookie' => [
    'key' => 'HIER_COOKIE_KEY_EINTRAGEN',
  ],
];
