FlexiHUBee
==========

Platforma pro výměnu dat mezi instancemi FlexiBee.

Nejprve se zaregistrují jednotlivé instance flexibee: 

  * Pokud se jedná plnou licenci s právem zápisu, je aktivováno Changes Api a nastaven WebHook ukazující na FlexiHUBee.
  * V případě licence zdarma jsou do tabulky journal zaznamenány aktuální čísla záznamu.

Systém sleduje pohyby evidencí FlexiBee **fakatura-vydaná** a **faktura-prijata**
Pokud v nich najde doklad který je určený pro některou z dalších registrovaných instancí FlexiBee, která je zapisovatelná, stáhne jej a naimportuje do cílového FlexiBee.

Před exportem faktury kontroluje, zdali existuje záznam v adresáři cílového FlexiBee.
Pokud ne, tento záznam vytvoří a pokud se původní data změní, tento záznam zaktualizuje.

FlexiHUBee se v pravidelných intervalech dotazuje všech zaregistrovaných RO FlexiBee zdali nemají nový doklad jehož ID je vyšší než to poznamenané v tabulce @journ

Database Init
------------------

    su postgres
    psql 
    CREATE USER system WITH PASSWORD 'system';
    CREATE DATABASE system OWNER system;
    \q
    vendor/bin/phinx migrate


