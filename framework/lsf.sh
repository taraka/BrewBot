#!/bin/sh

# Find the php binary

if test "@php_bin@" != '@'php_bin'@'; then
    PHP_BIN="@php_bin@"
elif command -v php 1>/dev/null 2>/dev/null; then
    PHP_BIN=`command -v php`
else
    PHP_BIN=php
fi

# Find the scripts directory

if test "@php_dir@" != '@'php_dir'@'; then
    PHP_DIR="@php_dir@"
else
    SELF_LINK="$0"
    SELF_LINK_TMP="$(readlink "$SELF_LINK")"
    while test -n "$SELF_LINK_TMP"; do
        SELF_LINK="$SELF_LINK_TMP"
        SELF_LINK_TMP="$(readlink "$SELF_LINK")"
    done
    PHP_DIR="$(dirname "$SELF_LINK")"
fi

$PHP_BIN -d safe_mode=Off -f $PHP_DIR/index.php -- $@
