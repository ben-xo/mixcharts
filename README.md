Create a database of your own uploads to Mixcloud
=================================================

…so that you can:
* create charts of the tracks you play
* search mixes by title, track or artist

Usage:
======

Searchable DB
-------------

To create a searchable DB of your mixes:
1. create a mixcloud.db file
   ```
   sqlite3 mixcloud.db <Mixcharts/Mixcharts.sql
   ```
2. populate it from your mixcloud account
   ```
   php ./mixcharts_refresh.php --token <api token from your mixcloud account> --user <mixcloud username>
   ```

To use that DB from the command line:
1. ```
   php ./find_mixes.php --term <keyword>
   ```

To use that DB from a web page:
1. See example `results.php`

All Time Track Chart
--------------------

To get your all time top played tracks in a chart:
1. ```
   php ./mixcharts_chart.php --token <api token from your mixcloud account> --user <mixcloud username>
   ```


Get All Comments On All Your Mixes
----------------------------------

Got 300 comments and no idea which of your 500 uploads they're on?
1. ```
   php ./get_all_comments.php --token <api token from your mixcloud account> --user <mixcloud username>
   ```


