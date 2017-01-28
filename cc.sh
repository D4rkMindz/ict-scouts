#!/usr/bin/env bash
php bin/console cache:clear && php bin/console --env=prod cache:clear && php bin/console --env=test cache:clear