# Berlin Tiger – Website

Website von **Berlin Tiger – Basketball in Kreuzberg e.V.** auf Basis von [Kirby CMS](https://getkirby.com) (4.x).
Live: https://berlintiger.de · Panel: https://berlintiger.de/panel

## Grundprinzip

| Was | Quelle der Wahrheit | Weg |
|---|---|---|
| **Code** (`assets/`, `kirby/`, `site/`) | dieses Git-Repo | `scripts/deploy-code.sh` → Server |
| **Inhalte** (`content/`) | **Live-Panel** | Server → Repo (nächtliche GitHub Action / `scripts/content-pull.sh`) |
| **Uploads** (`media/`) | wird von Kirby generiert | nie versioniert, nie deployt |

Inhalte werden **nie** von lokal nach live geschoben. Was live im Panel gepflegt wird, gewinnt immer.

## Lokal einrichten

1. Repo klonen und mit [Laravel Herd](https://herd.laravel.com) verlinken → `https://berlintiger.test`.
2. PHP 8.1–8.3 verwenden (Kirby 4.x).
3. Aktuellen Live-Content holen: `git pull` (die Action committet ihn täglich) oder direkt `scripts/content-pull.sh`.
4. Panel-Account lokal anlegen: `https://berlintiger.test/panel` (Accounts sind nicht im Repo).

## Konfiguration

Kirby lädt automatisch `site/config/config.php` und danach die Datei passend zum Host:

| Datei | Wo | Inhalt | In Git |
|---|---|---|---|
| `config.php` | überall | Basis, `debug => false` | ja |
| `config.berlintiger.test.php` | lokal | `debug => true` | ja |
| `config.berlintiger.de.php` | live | Secrets (reCAPTCHA) | **nein** |
| `config.www.berlintiger.de.php` | live (www) | lädt die Datei darüber | ja |

Die Live-Config aus `config.berlintiger.de.example.php` erstellen. Sie wird von `deploy-code.sh` separat hochgeladen und von `--delete` nie angefasst.

## Deploy (nur Code)

FTPS-Zugang in `scripts/.env.ftps` (Vorlage `.env.ftps.example`) oder im macOS-Schlüsselbund. Benötigt `lftp` (`brew install lftp`).

```bash
scripts/deploy-code.sh             # Dry-Run: zeigt Uploads und Löschungen
scripts/deploy-code.sh --execute   # wirklich hochladen
scripts/deploy-code.sh --execute --no-delete   # ohne Aufräumen verwaister Dateien
```

- Lädt nur `assets/`, `kirby/`, `site/`, `index.php`, `.htaccess`, `composer.json`.
- Löscht verwaiste Dateien **nur** in diesen drei Ordnern. Nie betroffen: `content/`, `media/`, `site/accounts|cache|sessions`, Lizenz, Live-Config.
- Immer erst den Dry-Run lesen.

## Content-Sync & Backups

- **GitHub Action „Content pull“** (`.github/workflows/content-pull.yml`): lädt nachts den Live-Content per FTPS herunter (nur lesend) und committet Änderungen auf `main`. Manuell startbar unter *Actions → Content pull → Run workflow*. Benötigt die Repository-Secrets `FTPS_HOST`, `FTPS_USER`, `FTPS_PASS` (optional `FTPS_PORT`, `FTPS_REMOTE_ROOT`).
- `scripts/content-pull.sh`: dasselbe lokal; ersetzt `content/` und legt vorher eine Sicherheitskopie in `../berlintiger-live-backups/` ab.
- `scripts/backup-live.sh`: vollständiges Backup vom Server.

## Struktur

- `site/templates`, `site/snippets`, `site/controllers` – Ausgabe
- `site/blueprints` – Panel-Formulare
- `assets/css/templates/<template>.css` und `assets/js/templates/<template>.js` werden per `@auto` geladen
- Spielplan-Widgets: `assets/js/bb-widgets.js` (basketball-bund.net)
