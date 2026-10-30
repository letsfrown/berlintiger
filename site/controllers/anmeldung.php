<?php
return function ($kirby, $page, $site) {

  $alert = null;

  if ($kirby->request()->is('POST') && get('submit')) {

    // Check the honeypot (spam prevention)
    if (empty(get('website')) === false) {
      go($page->url());
      exit;
    }

    // Validate reCAPTCHA
    $recaptchaSecret = "6LcYoNMqAAAAAFiuwhxVAhrq5kJ517HieeMzoRCi";
    $recaptchaResponse = get('g-recaptcha-response');

    if (!$recaptchaResponse) {
      $alert['error'] = "Bitte bestätigen Sie, dass Sie kein Roboter sind.";
    } else {
      $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
      $responseData = json_decode($verifyResponse);

      if (!$responseData->success) {
        $alert['error'] = "reCAPTCHA-Überprüfung fehlgeschlagen. Bitte versuchen Sie es erneut.";
      }
    }

    // If reCAPTCHA failed, return early
    if (!empty($alert['error'])) {
      return [
        'alert'       => $alert,
        'data'        => $_POST, // Keep entered data
        'success'     => false,
        'tigerEmail'  => $site->email(),
      ];
    }

    // Start session to track form submissions
    session_start();

    // Check if the email was recently submitted
    if (!empty($_SESSION['last_submission']) && $_SESSION['last_submission'] == get('email')) {
      $alert['error'] = "Du hast bereits eine Anfrage geschickt. Bitte warte, bevor du es erneut versuchst.";
      return [
        'alert'       => $alert,
        'data'        => $_POST, // Keep entered data
        'success'     => false,
        'tigerEmail'  => $site->email(),
      ];
    }

    // Store the email in session to prevent immediate resubmission
    $_SESSION['last_submission'] = get('email');

    // Collect form data
    $data = [
      'name'        => get('name'),
      'email'       => get('email'),
      'birthday'    => get('birthday'),
      'height'      => get('height'),
      'school'      => get('school'),
      'experience'  => get('experience'),
    ];

    // Validation rules
    $rules = [
      'name'        => ['required', 'minLength' => 3],
      'email'       => ['required', 'email'],
      'birthday'    => ['required', 'date'],
      'experience'  => ['maxLength' => 500],
    ];

    // Validation messages
    $messages = [
      'name'        => 'Bitte gib deinen Namen ein.',
      'email'       => 'Bitte gib eine gültige Email-Adresse ein.',
      'birthday'    => 'Bitte gib ein gültiges Datum ein.',
      'experience'  => 'Bitte gib nicht mehr als 500 Zeichen ein.',
    ];

    // Validate form fields
    if ($invalid = invalid($data, $rules, $messages)) {
      $alert = $invalid;
    } else {
      try {
        // Send email
        $kirby->email([
          'template' => 'email',
          'from'     => 'info@berlintiger.de',
          'replyTo'  => $data['email'],
          'to'       => 'info@berlintiger.de',
          'subject'  => esc($data['name']) . ' hat eine Mitgliedsanfrage geschickt! ',
          'data'     => $data
        ]);
      } catch (Exception $error) {
        if (option('debug')) {
          $alert['error'] = '<strong>' . $error->getMessage() . '</strong>';
        } else {
          $alert['error'] = 'Das Formular konnte nicht abgeschickt werden.';
        }
      }

      // Success message
      if (empty($alert)) {
        $success = 'Danke für deine Anmeldung, ' . esc($data['name']) . '! Wir melden uns bald bei dir zurück.';
        $data = [];
      }
    }
  }

  return [
    'alert'       => $alert,
    'data'        => $data ?? false,
    'success'     => $success ?? false,
    'tigerEmail'  => $site->email(),
  ];
};
?>
