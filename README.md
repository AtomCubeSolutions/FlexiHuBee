FlexiHUBee
==========


_Platforma pro výměnu dat mezi instancemi FlexiBee._

* Synchronizuje doklady mezi API s možností zápisu.
* Pro API pouze pro čtení zobrazuje přehled změnených dokladů s možností stáhnout si aktualizační soubor.
* Pokud je aktualizační soubor stažen a naimportován ozančí se záznam v přehledu za synchronizovaný
* Nastavuje si WebHook nebo periodicky kontroluje nove záznamy v evidencích

Evidence instanci FlexiBee
--------------------------

Nejprve se zaregistrují jednotlivé instance flexibee: 

  * Pokud se jedná plnou licenci s právem zápisu, je aktivováno Changes Api a nastaven WebHook ukazující na FlexiHUBee.
  * V případě licence zdarma jsou do tabulky @journal@ zaznamenány aktuální čísla záznamu.

Synchronizace faktur
--------------------

Systém sleduje pohyby evidencí FlexiBee @fakatura-vydaná@ a @faktura-prijata@
Pokud v nich najde doklad který je určený pro některou z dalších registrovaných instancí FlexiBee, která je zapisovatelná, stáhne jej a naimportuje do cílového FlexiBee.

Synchronizace adresáře
----------------------

Před exportem faktury kontroluje, zdali existuje záznam v adresáři cílového FlexiBee.
Pokud ne, tento záznam vytvoří a pokud se původní data změní, tento záznam zaktualizuje.

Interní Žurnál
--------------

FlexiHUBee se v pravidelných intervalech dotazuje všech zaregistrovaných RO FlexiBee zdali nemají nový doklad jehož ID je vyšší než to poznamenané v tabulce @journal@.

Stahování aktualizačních souborů
--------------------------------

Pokud bude nalezen doklad určený k importu do FlexiBee, které má API pouze pro čtení, tento doklad zaznamená a zobrazuje jej na přehledu dokladů určených k ručnímu importu spolu s linkem odkazující na stažení souboru importovatelného do FlexiBee ručně.

Párovani faktur z bankovnich dokladu
------------------------------------

Kontroluje se také evidence banka protějšku. Pokud protějšek zaplatí fakturu, je tato skutečnost přenesena na druhou stranu, faktura bude spárována s takto vytvořeným dokladem a označena za zaplacenou, pokud je možné zapisovat API. 
V opačném případě je tato skutečnost viditelná v přehledu faktur s možností importu aktualizačního souboru. Po kliknutí na něj a jeho importu, je faktura ve FlexiBee označena za zaplacenou. 

Když pak pri další kontrole FlexiHUBee zjistí že faktura je již aktualizována nastaví patřičnému záznamu v přehledu faktur uživatele FlexiHUBee jako synchronizovanou. 
 
Database Init
=============

    su postgres
    psql 
    CREATE USER flexihubee WITH PASSWORD 'flexihubee';
    CREATE DATABASE flexihubee OWNER flexihubee;
    \q
    vendor/bin/phinx migrate


