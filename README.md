Create a database of your own uploads to Mixcloud
=================================================

…so that you can:
* create charts of the tracks you play
* search mixes by title, track or artist

Usage:
======

To create a searchable DB of your mixes:
1. create a mixcloud.db file
  sqlite3 mixcloud.db <Mixcharts/Mixcharts.sql
2. populate it from your mixcloud account
  php ./mixcharts_refresh.php --token <api token from your mixcloud account> --user <mixcloud username>

To use that DB from the command line:
1. php ./find_mixes.php --term <keyword>

To use that DB from a web page:
1. See example results.php
