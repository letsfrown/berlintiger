#!/usr/bin/env bash
# Shared FTPS env loader for Berlin Tiger sync scripts.
# Prefer scripts/.env.ftps; fall back to macOS Keychain.

set -euo pipefail

SCRIPTS_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ROOT_DIR="$(cd "${SCRIPTS_DIR}/.." && pwd)"
ENV_FILE="${SCRIPTS_DIR}/.env.ftps"

FTPS_HOST="${FTPS_HOST:-wp11076001.server-he.de}"
FTPS_PORT="${FTPS_PORT:-21}"
FTPS_USER="${FTPS_USER:-ftp11076001-ernst}"
FTPS_PASS="${FTPS_PASS:-}"
FTPS_REMOTE_ROOT="${FTPS_REMOTE_ROOT:-}"
FTPS_KEYCHAIN_HOST="${FTPS_KEYCHAIN_HOST:-wp11076001.server-he.de}"
FTPS_KEYCHAIN_ACCOUNT="${FTPS_KEYCHAIN_ACCOUNT:-ftp11076001-ernst}"

if [[ -f "${ENV_FILE}" ]]; then
  # shellcheck disable=SC1090
  set -a
  source "${ENV_FILE}"
  set +a
fi

if [[ -z "${FTPS_PASS}" ]]; then
  if command -v security >/dev/null 2>&1; then
    FTPS_PASS="$(security find-internet-password -s "${FTPS_KEYCHAIN_HOST}" -a "${FTPS_KEYCHAIN_ACCOUNT}" -w 2>/dev/null || true)"
  fi
fi

if [[ -z "${FTPS_PASS}" ]]; then
  echo "error: FTPS password not found. Create scripts/.env.ftps from .env.ftps.example or store it in Keychain." >&2
  exit 1
fi

if ! command -v lftp >/dev/null 2>&1; then
  echo "error: lftp is required (brew install lftp)." >&2
  exit 1
fi

# Build remote path prefix (no leading/trailing slash mess)
remote_path() {
  local sub="${1:-}"
  local base="${FTPS_REMOTE_ROOT#/}"
  base="${base%/}"
  sub="${sub#/}"
  if [[ -n "${base}" && -n "${sub}" ]]; then
    echo "${base}/${sub}"
  elif [[ -n "${base}" ]]; then
    echo "${base}"
  else
    echo "${sub}"
  fi
}

run_lftp() {
  lftp -u "${FTPS_USER},${FTPS_PASS}" "ftp://${FTPS_HOST}:${FTPS_PORT}" -e "
set ftp:ssl-force true
set ftp:ssl-protect-data true
set ftp:ssl-auth TLS
set ssl:verify-certificate no
set ftp:passive-mode true
set net:timeout 30
set net:max-retries 5
set mirror:parallel-transfer-count 2
$*
bye
"
}

BACKUP_ROOT="${BACKUP_ROOT:-${ROOT_DIR}/../berlintiger-live-backups}"
