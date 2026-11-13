<?php

/**
 * Basis-Config (gilt überall, auch live).
 * Host-spezifische Overrides liegen in config.<host>.php:
 *   - config.berlintiger.test.php      lokal (Herd), debug an
 *   - config.berlintiger.de.php        live, enthält Secrets (gitignored)
 *   - config.www.berlintiger.de.php    live (www), lädt config.berlintiger.de.php
 * Vorlage für die Live-Datei: config.berlintiger.de.example.php
 */
return [

  'debug' => false,

  // PANEL STYLES
  'panel' => [
    'css' => 'assets/css/custom-panel.css'
  ],

  // AUTOFAVICON
  'medienbaecker.autofavicon.text' => '',
  'medienbaecker.autofavicon.color' => '#FE5000',
  'medienbaecker.autofavicon.color_dark' => '#FE5000',

  // reCAPTCHA – Site-Key ist öffentlich, Secret kommt aus der Host-Config
  'recaptcha.sitekey' => '6LcYoNMqAAAAAOBKkAOsb6GT2oBlItsYA1VmTwzS',
  'recaptcha.secret'  => null,

  // Anmeldeformular: Mindestabstand zwischen zwei Anfragen pro Session (Sekunden)
  'anmeldung.throttle' => 60,

  // // MAILHOG (lokal Mails abfangen)
  // 'email' => [
  //   'transport' => [
  //     'type' => 'smtp',
  //     'host' => 'localhost',
  //     'port' => 1025,
  //     'security' => false
  //   ]
  // ],

];
