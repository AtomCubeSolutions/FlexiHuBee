FlexiHUBee
==========


Database Init
------------------

    su postgres
    psql 
    CREATE USER system WITH PASSWORD 'system';
    CREATE DATABASE system OWNER system;
    \q
    vendor/bin/phinx migrate


