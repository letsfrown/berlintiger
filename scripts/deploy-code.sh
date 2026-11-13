#!/usr/bin/env bash
# Upload CODE ONLY to live via FTPS. Never touches content/, media/, accounts, cache, sessions, license.
# Default: dry-run. Pass --execute to actually upload.
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck disable=SC1091
source "${SCRIPT_DIR}/lib/ftps-env.sh"

DRY_RUN=1
PRUNE=1
for arg in "$@"; do
  case "${arg}" in
    --execute)  DRY_RUN=0 ;;
    --no-delete) PRUNE=0 ;;
    -h|--help)
      echo "Usage: $0 [--execute] [--no-delete]"
      echo "  (default)    dry-run: zeigt, was hochgeladen UND was auf dem Server gelöscht würde"
      echo "  --execute    wirklich hochladen (nie content/ oder media/)"
      echo "  --no-delete  verwaiste Code-Dateien auf dem Server NICHT löschen"
      exit 0
      ;;
    *) echo "unknown option: ${arg}" >&2; exit 1 ;;
  esac
done

# Allowlist only — content/ and media/ are intentionally absent.
ALLOWLIST_DIRS=(assets kirby site)
ALLOWLIST_FILES=(index.php .htaccess composer.json)

# Refuse to run if someone reintroduces content into the allowlist.
for item in "${ALLOWLIST_DIRS[@]}" "${ALLOWLIST_FILES[@]}"; do
  if [[ "${item}" == "content" || "${item}" == "content/" || "${item}" == "media" || "${item}" == "media/" ]]; then
    echo "error: refuse to deploy content/ or media/" >&2
    exit 1
  fi
done

if [[ "${DRY_RUN}" -eq 1 ]]; then
  DRY_FLAG="--dry-run"
  echo "DRY-RUN (no changes on server). Re-run with --execute to upload."
else
  DRY_FLAG=""
  echo "EXECUTE: uploading allowlisted code only to ${FTPS_HOST}."
fi

echo "Allowlist dirs: ${ALLOWLIST_DIRS[*]}"
echo "Allowlist files: ${ALLOWLIST_FILES[*]}"
echo "Never deployed: content/, media/, site/accounts|cache|sessions, .license, scripts/"
echo "Prune (--delete): $([[ ${PRUNE} -eq 1 ]] && echo an || echo aus) – nur assets/, kirby/, site/"

cd "${ROOT_DIR}"

# --delete entfernt verwaiste Dateien NUR innerhalb von assets/, kirby/ und site/.
# Ausgeschlossene Pfade (exclude-glob) werden weder hochgeladen noch gelöscht:
# Panel-Accounts, Cache, Sessions, Lizenz und die Live-Config bleiben auf dem Server unangetastet.
# Paths are relative to the mirrored source dir (site/), not the project root.
DELETE_FLAG=""
if [[ "${PRUNE}" -eq 1 ]]; then
  DELETE_FLAG="--delete"
fi

COMMON_EXCLUDES=(
  --exclude-glob .DS_Store
)

SITE_EXCLUDES=(
  --exclude-glob accounts/**
  --exclude-glob cache/**
  --exclude-glob sessions/**
  --exclude-glob config/.license
  --exclude-glob config/config.berlintiger.de.php
)

# Live-Config mit Secrets wird separat hochgeladen (siehe unten), damit --delete sie nie entfernt.
LIVE_CONFIG="site/config/config.berlintiger.de.php"

for dir in "${ALLOWLIST_DIRS[@]}"; do
  if [[ ! -d "${dir}" ]]; then
    echo "skip missing dir: ${dir}"
    continue
  fi
  remote="$(remote_path "${dir}")"
  echo "→ mirror ${dir}/ -> ${remote}/"
  EXTRA_STR="${COMMON_EXCLUDES[*]}"
  if [[ "${dir}" == "site" ]]; then
    EXTRA_STR="${EXTRA_STR} ${SITE_EXCLUDES[*]}"
  fi
  run_lftp "mirror --reverse --verbose ${DRY_FLAG} ${DELETE_FLAG} ${EXTRA_STR} ${dir} ${remote}"
done

for file in "${ALLOWLIST_FILES[@]}"; do
  if [[ ! -f "${file}" ]]; then
    echo "skip missing file: ${file}"
    continue
  fi
  remote="$(remote_path "${file}")"
  echo "→ put ${file} -> ${remote}"
  if [[ "${DRY_RUN}" -eq 1 ]]; then
    echo "   (dry-run) would upload ${file}"
  else
    run_lftp "put -O $(dirname "${remote}")/ ${file}"
  fi
done

if [[ -f "${LIVE_CONFIG}" ]]; then
  remote="$(remote_path "${LIVE_CONFIG}")"
  echo "→ put ${LIVE_CONFIG} -> ${remote}"
  if [[ "${DRY_RUN}" -eq 1 ]]; then
    echo "   (dry-run) would upload ${LIVE_CONFIG}"
  else
    run_lftp "put -O $(dirname "${remote}")/ ${LIVE_CONFIG}"
  fi
else
  echo "warn: ${LIVE_CONFIG} fehlt lokal – Live-Config auf dem Server bleibt unverändert."
  echo "      (Vorlage: site/config/config.berlintiger.de.example.php)"
fi

echo "Done."
if [[ "${DRY_RUN}" -eq 1 ]]; then
  echo "This was a dry-run. Nothing was uploaded."
fi
