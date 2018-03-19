#!/usr/bin/env bash
/usr/local/bin/docker-compose exec --user laradock -T workspace /var/www/vendor/bin/behat
