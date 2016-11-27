#!/usr/bin/env bash
php bin/console doctrine:schema:drop --force && php bin/console doctrine:schema:create && vendor/bin/phpunit