<?php 

/**
 * Contao Open Source CMS, Copyright (C) 2005-2013 Leo Feyer
 *
 * Contao Module "Cron Scheduler"
 * TL_ROOT/system/modules/cron/languages/de/tl_crontab.php
 * Deutsch translation file
 *
 * @copyright  Glen Langer 2013 <http://www.contao.glen-langer.de>
 * @author     Glen Langer (BugBuster)
 * @package    Cron
 * @license    LGPL
 * @filesource
 * @see	       https://github.com/BugBuster1701/contao-cron
 */

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_crontab']['title']['0']    = "Titel";
$GLOBALS['TL_LANG']['tl_crontab']['title']['1']    = "Geben Sie einen beschreibenden Titel für den Auftrag ein.";
$GLOBALS['TL_LANG']['tl_crontab']['job']['0']      = "Job";
$GLOBALS['TL_LANG']['tl_crontab']['job']['1']      = "Geben Sie den relativen Pfad zum auszuführenden PHP-Script an, Beispiel: system/modules/cron/jobs/PurgeLog.php";
$GLOBALS['TL_LANG']['tl_crontab']['t_minute']['0'] = "Minute";
$GLOBALS['TL_LANG']['tl_crontab']['t_minute']['1'] = "Minutenangabe in Listenform zum Beispiel 5,10,15-20,30.<br />Um einen Job alle 15 Minuten auszuführen geben Sie */15 ein.<br />* steht für jede Minute.";
$GLOBALS['TL_LANG']['tl_crontab']['t_hour']['0']   = "Stunde";
$GLOBALS['TL_LANG']['tl_crontab']['t_hour']['1']   = "Stundenangabe in Listenform zum Beispiel 2,4,5-7,9.<br />Um einen Job alle 3 Stunden auszuführen geben Sie */3 ein.<br />* steht für jede Stunde.";
$GLOBALS['TL_LANG']['tl_crontab']['t_dom']['0']    = "Tag im Monat";
$GLOBALS['TL_LANG']['tl_crontab']['t_dom']['1']    = "Tage im Monat in Listenform. Zum Beispiel: 1,10,14-16,20.<br />* steht für jeden Tag.";
$GLOBALS['TL_LANG']['tl_crontab']['t_month']['0']  = "Monat";
$GLOBALS['TL_LANG']['tl_crontab']['t_month']['1']  = "Monate in Listenform als Zahl zum Bsp. 1,3,7-9, oder englische Abk&uuml;rzung zum Bsp: Jan,Mar,Jul-Sep.<br />* steht f&uuml;r jeden Monat.";
$GLOBALS['TL_LANG']['tl_crontab']['t_dow']['0']    = "Wochentag";
$GLOBALS['TL_LANG']['tl_crontab']['t_dow']['1']    = "Wochentage in Listenform entweder als Zahlen (0=Sonntag) zum Bsp: 0,2-4,7 oder englische Abkürzung zum Bsp: Sun,Tue-Thu,Sat.<br />* steht für jeden Wochentag.";
$GLOBALS['TL_LANG']['tl_crontab']['runonce']['0']  = "Nur einmal auf&uuml;hren";
$GLOBALS['TL_LANG']['tl_crontab']['runonce']['1']  = "Job nach Abschluss deaktivieren.";
$GLOBALS['TL_LANG']['tl_crontab']['enabled']['0']  = "Aktiviert";
$GLOBALS['TL_LANG']['tl_crontab']['enabled']['1']  = "Aktiviert die Ausführung dieses Jobs.";
$GLOBALS['TL_LANG']['tl_crontab']['logging']['0']  = "Logging";
$GLOBALS['TL_LANG']['tl_crontab']['logging']['1']  = "Log-Eintrag schreiben, wenn Job ausgeführt wird";

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_crontab']['tl_minute']     = "Minute";
$GLOBALS['TL_LANG']['tl_crontab']['tl_hour']       = "Stunde";
$GLOBALS['TL_LANG']['tl_crontab']['tl_dom']        = "Tag im Monat";
$GLOBALS['TL_LANG']['tl_crontab']['tl_month']      = "Monat";
$GLOBALS['TL_LANG']['tl_crontab']['tl_dow']        = "Wochentag";
$GLOBALS['TL_LANG']['tl_crontab']['lastrun']       = "Letzte Ausührung";
$GLOBALS['TL_LANG']['tl_crontab']['nextrun']       = "Nächste Ausführung";

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_crontab']['new']['0']      = "Neu";
$GLOBALS['TL_LANG']['tl_crontab']['new']['1']      = "Neuen Job anlegen.";
$GLOBALS['TL_LANG']['tl_crontab']['edit']['0']     = "Bearbeiten";
$GLOBALS['TL_LANG']['tl_crontab']['edit']['1']     = "Die Einstellungen dieses Jobs bearbeiten.";
$GLOBALS['TL_LANG']['tl_crontab']['copy']['0']     = "Kopieren";
$GLOBALS['TL_LANG']['tl_crontab']['copy']['1']     = "Diesen Job kopieren.";
$GLOBALS['TL_LANG']['tl_crontab']['delete']['0']   = "Löschen";
$GLOBALS['TL_LANG']['tl_crontab']['delete']['1']   = "Diesen Job löschen.";
$GLOBALS['TL_LANG']['tl_crontab']['show']['0']     = "Anzeigen";
$GLOBALS['TL_LANG']['tl_crontab']['show']['1']     = "Details anzeigen.";
$GLOBALS['TL_LANG']['tl_crontab']['ena_logging']['0'] = "Logging aktivieren";
$GLOBALS['TL_LANG']['tl_crontab']['ena_logging']['1'] = "Logging für Job %s aktivieren";
$GLOBALS['TL_LANG']['tl_crontab']['dis_logging']['0'] = "Logging deaktivieren";
$GLOBALS['TL_LANG']['tl_crontab']['dis_logging']['1'] = "Logging für Job %s deaktivieren";
$GLOBALS['TL_LANG']['tl_crontab']['enable']['0']      = "Aktivieren";
$GLOBALS['TL_LANG']['tl_crontab']['enable']['1']      = "Ausführung für Job %s aktivieren";
$GLOBALS['TL_LANG']['tl_crontab']['disable']['0']     = "Deaktivieren";
$GLOBALS['TL_LANG']['tl_crontab']['disable']['1']     = "Ausführung für Job %s deaktivieren";
 
