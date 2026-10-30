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
  echo "  (default) dry-run mirror of code paths"
  echo "  --execute  perform upload (still never uploads content/ or media/)"
  exit 0
fi

# Hard safety: refuse if critical excludes are missing from this script
REQUIRED_EXCLUDES=(content media site/accounts site/cache site/sessions)
for req in "${REQUIRED_EXCLUDES[@]}"; do
  if ! grep -q -- "$req" "$0"; then
    echo "error: safety check failed — expected exclude '$req' documented in deploy-code.sh" >&2
    exit 1
  fi
done

REMOTE_BASE="$(remote_path)"
MIRROR_OPTS=(
  --reverse
  --verbose
  --exclude-glob .git/
  --exclude-glob .DS_Store
  --exclude-glob content/
  --exclude-glob media/
  --exclude-glob scripts/
  --exclude-glob site/accounts/
  --exclude-glob site/cache/
  --exclude-glob site/sessions/
  --exclude-glob site/config/.license
  --exclude-glob "*.md"
  --exclude-glob phpinfo.php
  --exclude-glob _new-website/
  --exclude-glob berlintiger-live-backups/
)

if [[ "${DRY_RUN}" -eq 1 ]]; then
  MIRROR_OPTS+=(--dry-run)
  echo "DRY-RUN (no changes on server). Re-run with --execute to upload."
else
  echo "EXECUTE: uploading code only to ${FTPS_HOST} (content/ and media/ excluded)."
fi

# Only sync allowlisted top-level trees / files by mirroring project root with excludes.
# Excludes above ensure content/ and media/ are never uploaded or deleted remotely
# as long as we do NOT pass --delete.
# Intentionally NO --delete so remote-only files (e.g. leftover) are kept.

echo "Remote base: '${REMOTE_BASE:-/}'"
echo "Excluded from deploy: content/, media/, site/accounts|cache|sessions, .license, scripts/"

# Build lftp mirror command
OPTS_STR=""
for o in "${MIRROR_OPTS[@]}"; do
  OPTS_STR+=" ${o}"
done

TARGET="."
if [[ -n "${REMOTE_BASE}" ]]; then
  TARGET="${REMOTE_BASE}"
fi

cd "${ROOT_DIR}"
run_lftp "mirror ${OPTS_STR} . ${TARGET}"

echo "Done."
if [[ "${DRY_RUN}" -eq 1 ]]; then
  echo "This was a dry-run. Nothing was uploaded."
fi
