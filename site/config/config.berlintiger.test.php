<?php

// Lokale Entwicklung (Laravel Herd). Wird nur auf berlintiger.test geladen.
return [
  'debug' => true,
  // Zum lokalen Testen des Formulars ggf. eigenen Test-Secret eintragen:
  // 'recaptcha.secret' => '…',

  // Lokal eigene Werte, damit das Panel keine Default-Warnungen zeigt
  'content' => [
    'salt' => 'local-dev-content-salt-berlintiger-test-only',
  ],
  'cookie' => [
    'key' => 'local-dev-cookie-key-berlintiger-test-only',
  ],
];
