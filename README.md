System.Spoje.Net
=========

Co je hotové
------------

* Do aplikace je možné se zaregistrova
* Aplikace je schopna importu adresáře z LMS
* Aplikace je schopna likvidovat faktury placené v hotovosti
* Rozcestník a integrátor často používaných aplikací Spoje.Net s.r.o.

Co hotové není 
--------------

* Prava uživatelů aneb kdo může co vidět a dělat
* Import faktur
* Import skladu 


http://vyvojar.spoje.net/system/



© 2015-2016 Spoje.Net s.r.o.

Produkční adresa: [http://system.spoje.net/]
Testovací adresa: [http://vyvojar.spoje.net/system/]

Požadavky pro běh: 
------------------

 * PHP 5 a vyšší s mysqli rozšířením
 * Ease framework 
 * LMS
 * SQL Databáze


Informace pro vývojáře:
-----------------------

 * Aplikace je vyvíjena pod v NetBeans pod linuxem.
 * Dokumentace ApiGen se nalézá ve složce doc
 * Složka testing obsahuje testovací sady Selenium a PHPUnit a strukturu DB
 * Aktuální zdrojové kody: [git@git.spoje.net:/srv/git/flexibee.git]

Instalace databáze
------------------

    su postgres
    psql 
    CREATE USER system WITH PASSWORD 'system';
    CREATE DATABASE system OWNER system;
    \q
    vendor/bin/phinx migrate


napsal v roce 2015,2016 pro společnost Spoje.Net s.r.o.
Vítězslav Dvořák

