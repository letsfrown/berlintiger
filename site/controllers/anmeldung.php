<?php

use Kirby\Http\Remote;

return function ($kirby, $page, $site) {

  $alert   = null;
  $data    = [];
  $success = false;
  $fields  = ['name', 'email', 'birthday', 'height', 'school', 'experience'];

  $result = function () use (&$alert, &$data, &$success, $site) {
    return [
      'alert'      => $alert,
      'data'       => $data,
      'success'    => $success,
      'tigerEmail' => $site->email(),
    ];
  };

  if ($kirby->request()->is('POST') === false || !get('submit')) {
    return $result();
  }

  // Nur erwartete Felder übernehmen (Eingaben bleiben bei Fehlern erhalten)
  foreach ($fields as $field) {
    $data[$field] = trim((string)get($field, ''));
  }

  // Honeypot: Bots füllen das versteckte Feld aus
  if (empty(get('website')) === false) {
    go($page->url());
  }

  // Throttle: max. eine Anfrage pro Zeitfenster und Session
  $session  = $kirby->session();
  $throttle = (int)option('anmeldung.throttle', 60);
  $last     = (int)$session->get('anmeldung.lastSubmit', 0);

  if ($last > 0 && (time() - $last) < $throttle) {
    $alert['error'] = 'Du hast gerade schon eine Anfrage geschickt. Bitte warte kurz, bevor du es erneut versuchst.';
    return $result();
  }

  // reCAPTCHA serverseitig prüfen
  $secret   = option('recaptcha.secret');
  $response = (string)get('g-recaptcha-response', '');

  if (empty($secret)) {
    // Fail closed: ohne Secret wird nichts verschickt
    $alert['error'] = option('debug')
      ? 'reCAPTCHA-Secret fehlt in der Host-Config (recaptcha.secret).'
      : 'Das Formular ist gerade nicht verfügbar. Bitte schreib uns eine E-Mail.';
    return $result();
  }

  if ($response === '') {
    $alert['error'] = 'Bitte bestätige, dass du kein Roboter bist.';
    return $result();
  }

  try {
    $verify = Remote::request('https://www.google.com/recaptcha/api/siteverify', [
      'method'  => 'POST',
      'data'    => [
        'secret'   => $secret,
        'response' => $response,
        'remoteip' => $kirby->visitor()->ip(),
      ],
      'timeout' => 10,
    ])->json();
  } catch (Throwable $e) {
    $verify = null;
  }

  if (empty($verify['success'])) {
    $alert['error'] = 'Die reCAPTCHA-Prüfung ist fehlgeschlagen. Bitte versuch es noch einmal.';
    return $result();
  }

  // Validierung
  $rules = [
    'name'       => ['required', 'minLength' => 3, 'maxLength' => 100],
    'email'      => ['required', 'email'],
    'birthday'   => ['required', 'date'],
    'height'     => ['maxLength' => 10],
    'school'     => ['maxLength' => 150],
    'experience' => ['maxLength' => 500],
  ];

  $messages = [
    'name'       => 'Bitte gib deinen Namen ein.',
    'email'      => 'Bitte gib eine gültige E-Mail-Adresse ein.',
    'birthday'   => 'Bitte gib ein gültiges Datum ein.',
    'height'     => 'Bitte gib die Größe in cm an (z. B. 165).',
    'school'     => 'Bitte gib nicht mehr als 150 Zeichen ein.',
    'experience' => 'Bitte gib nicht mehr als 500 Zeichen ein.',
  ];

  if ($invalid = invalid($data, $rules, $messages)) {
    $alert = $invalid;
    return $result();
  }

  try {
    $kirby->email([
      'template' => 'email',
      'from'     => 'info@berlintiger.de',
      'replyTo'  => $data['email'],
      'to'       => 'info@berlintiger.de',
      'subject'  => $data['name'] . ' hat eine Mitgliedsanfrage geschickt!',
      'data'     => $data,
    ]);
  } catch (Throwable $error) {
    $alert['error'] = option('debug')
      ? esc($error->getMessage())
      : 'Das Formular konnte nicht abgeschickt werden. Bitte schreib uns eine E-Mail an ' . esc($site->email()) . '.';
    return $result();
  }

  $session->set('anmeldung.lastSubmit', time());

  $success = 'Danke für deine Anmeldung, ' . esc($data['name']) . '! Wir melden uns bald bei dir zurück.';
  $data    = [];

  return $result();
};
