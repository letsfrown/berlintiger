#!/usr/bin/env bash
# Pull live content/ into the local project (live wins). Does not upload.
# Creates a safety copy of the previous local content/ under berlintiger-live-backups/.
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck disable=SC1091
source "${SCRIPT_DIR}/lib/ftps-env.sh"

STAMP="$(date +%Y%m%d-%H%M%S)"
TMP_PULL="${BACKUP_ROOT}/content-pull-${STAMP}"
LOCAL_CONTENT="${ROOT_DIR}/content"
SAFETY_COPY="${BACKUP_ROOT}/local-content-before-pull-${STAMP}"
REMOTE_CONTENT="$(remote_path content)"

mkdir -p "${TMP_PULL}"

echo "Pulling live ${REMOTE_CONTENT} -> ${TMP_PULL}/content (read-only)"
run_lftp "mirror --verbose ${REMOTE_CONTENT} ${TMP_PULL}/content"

TXT_COUNT="$(find "${TMP_PULL}/content" -name '*.txt' 2>/dev/null | wc -l | tr -d ' ')"
if [[ "${TXT_COUNT}" -lt 1 ]]; then
  echo "error: pulled content looks empty — local content left untouched." >&2
  exit 1
fi

if [[ -d "${LOCAL_CONTENT}" ]]; then
  echo "Saving previous local content/ -> ${SAFETY_COPY}"
  mkdir -p "${BACKUP_ROOT}"
  mv "${LOCAL_CONTENT}" "${SAFETY_COPY}"
fi

mv "${TMP_PULL}/content" "${LOCAL_CONTENT}"
rmdir "${TMP_PULL}" 2>/dev/null || rm -rf "${TMP_PULL}"

echo "Local content/ replaced with live content (${TXT_COUNT} .txt files)."
echo "Review with git status, then commit when ready."
