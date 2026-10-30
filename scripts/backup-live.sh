#!/usr/bin/env bash
# Download live content/ only (read-only). Never uploads.
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck disable=SC1091
source "${SCRIPT_DIR}/lib/ftps-env.sh"

STAMP="$(date +%Y%m%d-%H%M%S)"
DEST="${BACKUP_ROOT}/${STAMP}"
mkdir -p "${DEST}"

REMOTE_CONTENT="$(remote_path content)"

echo "Backing up ftp://${FTPS_HOST}/${REMOTE_CONTENT:-content} -> ${DEST}/content"
echo "(read-only mirror; no uploads)"

curl -s --ssl-reqd --user "${FTPS_USER}:${FTPS_PASS}" \
  "ftp://${FTPS_HOST}/" \
  > "${DEST}/remote-root-listing.txt" || true

run_lftp "mirror --verbose ${REMOTE_CONTENT} ${DEST}/content"

TXT_COUNT="$(find "${DEST}/content" -name '*.txt' 2>/dev/null | wc -l | tr -d ' ')"
SIZE="$(du -sh "${DEST}/content" | awk '{print $1}')"

echo "Backup complete: ${DEST}"
echo "content/ size=${SIZE} txt_files=${TXT_COUNT}"

if [[ "${TXT_COUNT}" -lt 1 ]]; then
  echo "error: backup looks empty — aborting." >&2
  exit 1
fi
