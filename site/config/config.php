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
    'css' => 'assets/css/custom-panel.css',
    // Sidebar: Inhalte direkt erreichbar, Technik nach unten
    'menu' => function () {
      // Aktiv-Status: Panel-Pfad der aktuellen Ansicht gegen den Seitenpfad prüfen
      // (Kirby kodiert "/" im Panel-Pfad als "+", also zählen auch Unterseiten)
      $path   = '/' . trim(kirby()->request()->path()->toString(), '/');
      $isPage = fn (string $id) => preg_match(
        '#/pages/' . preg_quote($id, '#') . '(\+|/|$)#',
        $path
      ) === 1;
      $shortcuts = [
        'news'      => ['News',       'text',      'news'],
        'teams'     => ['Teams',      'users',     'angebote+teams'],
        'members'   => ['Mitglieder', 'account',   'about+members'],
        'turniere'  => ['Turniere',   'star',      'tiger-turnier'],
        'anmeldung' => ['Anmeldung',  'edit-line', 'anmeldung'],
      ];

      $menu = [
        'site' => [
          'label'   => 'Dashboard',
          'icon'    => 'dashboard',
          'link'    => 'site',
          // Dashboard nur aktiv, wenn keine Shortcut-Seite offen ist
          'current' => function (string|null $area) use ($shortcuts, $isPage) {
            foreach ($shortcuts as [, , $id]) {
              if ($isPage($id)) {
                return false;
              }
            }
            return $area === 'site';
          },
        ],
        '-',
      ];

      foreach ($shortcuts as $key => [$label, $icon, $id]) {
        $menu[$key] = [
          'label'   => $label,
          'icon'    => $icon,
          'link'    => 'pages/' . $id,
          'current' => fn () => $isPage($id),
        ];
      }

      return [...$menu, '-', 'users', 'system'];
    },
    // Runtime Vue only – our Panel plugins are precompiled
    'vue' => [
      'compiler' => false,
    ],
  ],

  // AUTOFAVICON
  'medienbaecker.autofavicon.text' => '',
  'medienbaecker.autofavicon.color' => '#FE5000',
  'medienbaecker.autofavicon.color_dark' => '#FE5000',

  // Unbekannte Team-Slugs (z. B. aus alten Links) zur Teamübersicht leiten
  'routes' => [
    [
      'pattern' => 'angebote/teams/(:any)',
      'action'  => function (string $slug) {
        if ($team = page('angebote/teams/' . $slug)) {
          return $team;
        }
        return go('angebote#teams', 301);
      }
    ],
  ],

  // reCAPTCHA site key is public by design (widget in the browser).
  // The secret stays in config.berlintiger.de.php only (gitignored).
  'recaptcha.sitekey' => '6LfnKB8uAAAAAEiMyBdsyavCz7tJGrIkBzhWexhy',
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
