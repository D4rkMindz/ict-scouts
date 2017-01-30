Dokumentation
=============

Strukturbeschreibung
--------------------

``/app``: Autoloader, Kernel und Konfigurationen der Symfony 3 Applikation.

``/app/Resources``: Überschriebene, **Bundle-Unabhängige** Ressourcen.

``/app/config``: Konfigurationen der Applikation.

``/app/config/config.yml``: Allgemeine Einstellungen.

``/app/config/config_dev.yml``: Einstellungen für Entwicklungsumgebung.

``/app/config/config_prod.yml``: Einstellungen für Produktivumgebung.

``/app/config/config_test.yml``: Einstellungen für Testingumgebung.

``/app/config/parameters.yml``: Nicht indexierte, installationsabhängige Konfigurationen mit Passwörtern,
Datenbankverbindungsdefinitionen, etc..

``/app/config/parameters.yml.dist``: Indexierte ``parameters.yml``-Vorlage.

``/app/config/routing.yml``: Routing-Informationen.

``/app/config/routing_dev.yml``: Zusätzliche Routing-Informationen für Entwicklungsumgebung.

``/app/config/security.yml``: Software-Firewall, ACL-Definitionen, Authentifizierungs- und Authentifizierungseinstellungen.

``/app/config/services.yml``: Service-Definitionen. Klassen welche in den DependencyInjection-Container aufgenommen werden.

``/app/config/AppKernel.php``: Kernel. Beinhaltet registrierte Bundles.

``/app/config/autoloader.php``: PSR-4 autoloader.

``/bin``: Ausführbare Dateien welche zur Entwicklung beitragen.

``/bin/console``: CLI erweiterung für Symfony commands.

``/bin/symfony_requirements``: Überprüft PHP-Installation und schlägt Erweiterungen vor.

``/build``: Builds welche hauptsächlich CI betreffen.

``/src``: Sourcecode der Symfony 3 Applikation.

``/src/AppBundle``: Haupt Bundle der Applikation.

``/src/AppBundle/AppBundle.php``: Bundledefinition.

``/src/AppBundle/Command``: Eigene CLI Befehle.

``/src/AppBundle/Controller``: Controller der MVC-Struktur.

``/src/AppBundle/DataFixtures``: Datenfixturen welche als Stammdaten zählen.

``/src/AppBundle/Entity``: Entitäten/Datenbanktabellen. Enthält eine Object-Representation einer Tabelle in der Datenbank.

``/src/AppBundle/Repository``: Enthält eigene Schnittstellen von Entität zur Datenbank.

``/src/AppBundle/Resources``: Ressourcen. Frontend und Bundle-spezifische Konfigurationen.

``/src/AppBundle/Resources/config``: Bundle-spezifische Konfigurationen.

``/src/AppBundle/Resources/public``: Öffentliche Dateien. Gemacht für CSS und JS. Beim generieren der Symlinks wird der
Inhalt dieses Ordners für das Frontend zugänglich sein.

``/src/AppBundle/Resources/views``: Templates für das Front-end.

``/src/AppBundle/Service``: Sicherheitskomponenten. Ermöglicht eigene Authentifizierungsdefinitionen.

``/src/AppBundle/Service``: Enthält Service-Klassen welche in den DependencyInjeciton-Container geladen werden.

``/tests``: Unit und Functionaltests. Enthält gleiche Struktur wie ``/src``-Ordner.

``/var``: Cache und Logs des Backends.

``/vendor``: Composer-Dependencies und PSR-4-Autoloader.

``/web``: Document-Root der Webapplikation.

``/web/assets``: Assets welche zum Frontend beitragen.

``/web/assets/vendor``: Dependencies welche von Bower berechnet wurden.

``/web/build``: Frontend-Cache welcher vom Backend gerendert wurde.

``/web/bundles``: Symbolische Verknüpfung zum ``public``-Ordner der Bundles.

``/web/app.php``: Indexdatei, welche den Kernel lädt und den Request an das Symfony Framework weiterleitet.

``/web/app_dev.php``: Indexdatei wie ``app.php``, lädt Kernel mit Entwicklungseinstellungen und weniger Caching.

``/.bowerrc``: Bower-Konfigurationen.

``/.travis.yml``: Travis-Konfigurationen.

``/composer.json``: Abhängigkeitendefinition des Backends.

``/composer.lock``: Berechnete Abhängigkeiten des Backends.

``/phpunit.xml.dist``: PHPUnit-Konfigurationsdatei.