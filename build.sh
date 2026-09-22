#!/bin/sh
# Builds the installable package. The zip is deliberately not committed; the
# release asset is the artifact.
set -e
V=$(sed -n 's:.*<version>\(.*\)</version>.*:\1:p' plg_system_personyze/personyze.xml | head -1)
rm -f "plg_system_personyze-$V.zip"
cd plg_system_personyze && zip -qr "../plg_system_personyze-$V.zip" . -x '.*'
echo "built plg_system_personyze-$V.zip"
