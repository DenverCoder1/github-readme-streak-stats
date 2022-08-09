#!/bin/bash
set -e

cd /workspaces/github-readme-streak-stats
if [ -n "$GITHUB_TOKEN" ]; then
  echo "TOKEN=$GITHUB_TOKEN" > .env
fi
