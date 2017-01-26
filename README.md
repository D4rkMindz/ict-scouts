# ICT-Scouts Web Application

[![Build Status](https://travis-ci.org/PReimers/ict-scouts.svg?branch=master)](https://travis-ci.org/PReimers/ict-scouts) [![Coverage Status](https://coveralls.io/repos/github/PReimers/ict-scouts/badge.svg?branch=master)](https://coveralls.io/github/PReimers/ict-scouts?branch=master) [![StyleCI](https://styleci.io/repos/74136676/shield?branch=master)](https://styleci.io/repos/74136676) [![Dependency Status](https://www.versioneye.com/user/projects/58333d30eaa74b002c69e8ba/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/58333d30eaa74b002c69e8ba) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/83636cb1-9998-402c-bb9b-7f8b215b1bfd/mini.png)](https://insight.sensiolabs.com/projects/83636cb1-9998-402c-bb9b-7f8b215b1bfd) 

ICT-Scouts Campus WebApp

## Systemvoraussetzungen

* PHP 7.0
  * php7.0-curl
  * php7.0-xml
  * php7.0-mbstring
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

## Strukturbeschreibung

`/app`: Autoloader, Kernel und Konfigurationen der Symfony 3 Applikation.

`/app/Resources`: Überschriebene, **Bundle-Unabhängige** Ressourcen.

`/app/config`: Konfigurationen der Applikation.

`/app/config/config.yml`: Allgemeine Einstellungen.

`/app/config/config_dev.yml`: Einstellungen für Entwicklungsumgebung.

`/app/config/config_prod.yml`: Einstellungen für Produktivumgebung.

`/app/config/config_test.yml`: Einstellungen für Testingumgebung.

`/app/config/parameters.yml`: Nicht indexierte, installationsabhängige Konfigurationen mit Passwörtern, Datenbankverbindungsdefinitionen, etc..

`/app/config/parameters.yml.dist`: Indexierte `parameters.yml`-Vorlage.

`/app/config/routing.yml`: Routing-Informationen.

`/app/config/routing_dev.yml`: Zusätzliche Routing-Informationen für Entwicklungsumgebung.

`/app/config/security.yml`: Software-Firewall, ACL-Definitionen, Authentifizierungs- und Authentifizierungseinstellungen.

`/app/config/services.yml`: Service-Definitionen. Klassen welche in den DependencyInjection-Container aufgenommen werden.

`/bin`: Ausführbare Dateien welche zur Entwicklung beitragen.

`/build`: Builds welche hauptsächlich CI betreffen.

`/src`: Sourcecode der Symfony 3 Applikation.

`/tests`: Unit und Functionaltests.

`/var`: Cache und Logs des Backends.

`/vendor`: Composer-Dependencies und PSR-4-Autoloader.

`/web`: Document-Root der Webapplikation.

`/web/assets`: Assets welche zum Frontend beitragen.

`/web/assets/vendor`: Dependencies welche von Bower berechnet wurden.

`/web/build`: Frontend-Cache welcher vom Backend gerendert wurde.

`/web/bundles`: Symbolische Verknüpfung zum `public`-Ordner der Bundles.

`/web/app.php`: Indexdatei, welche den Kernel lädt und den Request an das Symfony Framework weiterleitet.

`/web/app_dev.php`: Indexdatei wie `app.php`, lädt Kernel mit Entwicklungseinstellungen und weniger Caching.

`/.bowerrc`: Bower-Konfigurationen.

`/.travis.yml`: Travis-Konfigurationen.

`/composer.json`: Abhängigkeitendefinition des Backends.

`/composer.lock`: Berechnete Abhängigkeiten des Backends.

`/phpunit.xml.dist`: PHPUnit-Konfigurationsdatei.