AVirtual
========================

- Clone the project
- mv parameters.yml.dist parameters.yml
- Edit the db parameters in parameters.yml.dist
- curl -sS https://getcomposer.org/installer | php
- php composer.phar install
- php app/console doctrine:database:create
- php app/console doctrine:schema:create
- php app/console assets:install
- php app/console assetic:dump
- php app/console server:run
- Enjoy!

This software is under development. not used in production.
