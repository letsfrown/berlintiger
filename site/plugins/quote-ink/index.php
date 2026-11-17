<?php

/**
 * Quote-Ink: macht aus gescannten Handschrift-Zitaten (Schrift auf Papier)
 * ein PNG mit transparentem Hintergrund. So liegt die Schrift in jedem
 * Browser direkt auf dem Hero-Hintergrund – ohne mix-blend-mode.
 *
 * Nutzung im Template:  $file->inkUrl(500)
 * Die PNGs werden beim ersten Aufruf erzeugt und unter media/quote-ink/ gecacht.
 */

use Kirby\Cms\App as Kirby;
use Kirby\Cms\File;
use Kirby\Filesystem\Dir;
use Kirby\Http\Response;

function quoteInkName(File $file, int $height): string
{
    return $file->uuid()->id() . '-' . $height . '-' . $file->modified() . '.png';
}

function quoteInkRender(File $file, int $height, string $target): bool
{
    if (!function_exists('imagecreatefromstring')) {
        return false;
    }

    $src = @imagecreatefromstring(file_get_contents($file->root()));
    if (!$src) {
        return false;
    }

    $w = imagesx($src);
    $h = imagesy($src);
    $nh = min($height, $h);
    $nw = (int)round($w * $nh / $h);

    $img = imagecreatetruecolor($nw, $nh);
    imagecopyresampled($img, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
    imagedestroy($src);

    $out = imagecreatetruecolor($nw, $nh);
    imagealphablending($out, false);
    imagesavealpha($out, true);

    // Papier (hell) wird transparent, Striche werden schwarz mit passender Deckkraft.
    $paper = 205; // ab dieser Helligkeit gilt ein Pixel als Papier
    $ink   = 70;  // ab dieser Dunkelheit voll deckend
    for ($y = 0; $y < $nh; $y++) {
        for ($x = 0; $x < $nw; $x++) {
            $rgb = imagecolorat($img, $x, $y);
            $lum = (int)(0.299 * (($rgb >> 16) & 0xFF) + 0.587 * (($rgb >> 8) & 0xFF) + 0.114 * ($rgb & 0xFF));
            if ($lum >= $paper) {
                $alpha = 127;
            } elseif ($lum <= $ink) {
                $alpha = 0;
            } else {
                $alpha = (int)round(127 * ($lum - $ink) / ($paper - $ink));
            }
            imagesetpixel($out, $x, $y, ($alpha << 24) | 0x111111);
        }
    }
    imagedestroy($img);

    Dir::make(dirname($target));
    $ok = imagepng($out, $target, 9);
    imagedestroy($out);

    return $ok;
}

Kirby::plugin('berlintiger/quote-ink', [
    'fileMethods' => [
        'inkUrl' => function (int $height = 500): string {
            return kirby()->url('media') . '/quote-ink/' . quoteInkName($this, $height);
        },
    ],
    'routes' => [
        [
            // Existiert das PNG schon, liefert der Webserver es direkt aus.
            'pattern' => 'media/quote-ink/(:any)',
            'action'  => function (string $name) {
                if (!preg_match('/^([a-zA-Z0-9]+)-(\d{2,4})-(\d+)\.png$/', $name, $m)) {
                    return false;
                }

                $file = kirby()->file('file://' . $m[1]);
                $height = max(100, min(1200, (int)$m[2]));

                if (!$file || $file->type() !== 'image') {
                    return false;
                }

                $target = kirby()->root('media') . '/quote-ink/' . quoteInkName($file, $height);

                if (!is_file($target) && !quoteInkRender($file, $height, $target)) {
                    // Fallback: Original-Thumbnail
                    return go($file->resize(null, $height)->url());
                }

                return Response::file($target);
            },
        ],
    ],
]);
