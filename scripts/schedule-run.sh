#!/usr/bin/env bash
# Laravel scheduler — run every minute from system cron:
# * * * * * /bin/bash /path/to/spotmee/scripts/schedule-run.sh >> /dev/null 2>&1

set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

php artisan schedule:run --no-interaction --verbose
