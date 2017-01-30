# ICT-Scouts Web Application

[![Build Status](https://travis-ci.org/PReimers/ict-scouts.svg?branch=master)](https://travis-ci.org/PReimers/ict-scouts) [![Coverage Status](https://coveralls.io/repos/github/PReimers/ict-scouts/badge.svg?branch=master)](https://coveralls.io/github/PReimers/ict-scouts?branch=master) [![StyleCI](https://styleci.io/repos/74136676/shield?branch=master)](https://styleci.io/repos/74136676) [![Dependency Status](https://www.versioneye.com/user/projects/58333d30eaa74b002c69e8ba/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/58333d30eaa74b002c69e8ba) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/83636cb1-9998-402c-bb9b-7f8b215b1bfd/mini.png)](https://insight.sensiolabs.com/projects/83636cb1-9998-402c-bb9b-7f8b215b1bfd) 

ICT-Scouts Campus WebApp

## Systemvoraussetzungen

* PHP 7.1
  * php7.1-curl
  * php7.1-xml
  * php7.1-mbstring
* [Composer](https://getcomposer.org/)
* MySQL 5.6 oder MariaDB 10.0.27

## Entwicklung

Informationen und Arbeitsschritte für Entwickler der Applikation.

### Installation

1. Klone Repository: `git clone https://github.com/PReimers/ict-scouts`
2. Gehe in den Ordner: `cd ict-scouts`
3. Überprüfe ob deine PHP-Installation kompatibel ist mit der Symfony-Version. `php bin/symfony_requirements`
4. Starte Datenbankserver. (Individuell)
5. Installiere Composer-Dependencies. `composer install`
   * Fülle die Informationen welche gefragt werden aus.
6. Starte den Webserver. `php bin/console server:run`

## Wichtige Befehle

* Cache löschen: `php bin/console cache:clear`
* Datenbankeinträge neu einfüllen: `php bin/console doctrine:fixtures:load`
* Datenbankstruktur überschreiben: `php bin/console doctrine:schmea:update --force` Statt `--force` kann man auch 
`--dump-sql` angeben umd den SQL-Befehl zu sehen welcher ausgeführt werden würde.


Mehr Informationen in der [Dokumentation](src/AppBundle/Resources/doc/index.rst).
