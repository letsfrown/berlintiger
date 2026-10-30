#!/usr/bin/env bash
# Upload CODE ONLY to live via FTPS. Never touches content/, media/, accounts, cache, sessions, license.
# Default: dry-run. Pass --execute to actually upload.
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck disable=SC1091
source "${SCRIPT_DIR}/lib/ftps-env.sh"

DRY_RUN=1
if [[ "${1:-}" == "--execute" ]]; then
  DRY_RUN=0
elif [[ "${1:-}" == "--help" || "${1:-}" == "-h" ]]; then
  echo "Usage: $0 [--execute]"
  echo "  (default) dry-run upload of allowlisted code paths"
  echo "  --execute  perform upload (still never uploads content/ or media/)"
  exit 0
fi

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

cd "${ROOT_DIR}"

# Intentionally NO --delete so remote-only files stay put.
# Paths are relative to the mirrored source dir (site/), not the project root.
SITE_EXCLUDES=(
  --exclude-glob .DS_Store
  --exclude-glob accounts/**
  --exclude-glob cache/**
  --exclude-glob sessions/**
  --exclude-glob config/.license
)

for dir in "${ALLOWLIST_DIRS[@]}"; do
  if [[ ! -d "${dir}" ]]; then
    echo "skip missing dir: ${dir}"
    continue
  fi
  remote="$(remote_path "${dir}")"
  echo "→ mirror ${dir}/ -> ${remote}/"
  EXTRA_STR=""
  if [[ "${dir}" == "site" ]]; then
    EXTRA_STR="${SITE_EXCLUDES[*]}"
  fi
  run_lftp "mirror --reverse --verbose ${DRY_FLAG} ${EXTRA_STR} ${dir} ${remote}"
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

echo "Done."
if [[ "${DRY_RUN}" -eq 1 ]]; then
  echo "This was a dry-run. Nothing was uploaded."
fi
