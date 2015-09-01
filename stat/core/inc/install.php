<?php

$dbs->exec('
CREATE TABLE IF NOT EXISTS stat (id INTEGER PRIMARY KEY NOT NULL, site INTEGER, page INTEGER, refer INTEGER, user INTEGER, os INTEGER, platform INTEGER, udevice INTEGER, browser INTEGER, resolution INTEGER, adblock INTEGER, date INTEGER, time INTEGER, lang INTEGER, ip INTEGER, bot INTEGER, session INTEGER, userhash INTEGER, countryiso INTEGER, country INTEGER, encountry INTEGER, city INTEGER, encity INTEGER, citylat INTEGER, citylon INTEGER, protocol INTEGER, memory INTEGER, loadtime INTEGER);
');

$dbs->exec('
CREATE TABLE IF NOT EXISTS rank (id INTEGER PRIMARY KEY NOT NULL, site INTEGER, googlerank INTEGER, yarank INTEGER, date INTEGER, time INTEGER);
');

$dbs->exec('
CREATE TABLE IF NOT EXISTS online (id INTEGER PRIMARY KEY NOT NULL, date INTEGER, starttime INTEGER, endtime INTEGER, usersonline INTEGER, nstarttime INTEGER, nendtime INTEGER);
');

$dbs->exec('
CREATE TABLE IF NOT EXISTS errors (id INTEGER PRIMARY KEY NOT NULL, name INTEGER, page INTEGER, date INTEGER, time INTEGER, ip INTEGER, session INTEGER);
');

?>
