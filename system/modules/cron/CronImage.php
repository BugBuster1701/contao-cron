<?php
/**
 * TYPOlight Cron Scheduler
 *
 * Cron is a scheduler module for the TYPOlight CMS. It allows to automaticly 
 * execute php on a time schedule similar to the unix cron/crontab scheme.  
 * TYPOlight is a web content management system that specializes in accessibility
 * and generates W3C-compliant HTML code.
 *
 * If you need to contact the author of this module, please use the forum at 
 * http://www.typolight.org/forum. Additional documentation can be found at the 
 * 3rd party extensions WIKI http://www.typolight.org/wiki/extensions:extensions
 * For more information about TYPOlight and additional applications please visit 
 * the project website http://www.typolight.org. 
 *
 * NOTE: this file was edited with tabs set to 4.
 *
 * CronImage.php: Pseudo-image (invisible gif) for use on the fronend to trigger cron.
 *
 * PHP version 5
 * @copyright  Acenes 2007
 * @author     Acenes
 * @package    Cron
 * @license    GNU GENERAL PUBLIC LICENSE (GPL) Version 2, June 1991
 * @filesource
 */
Header("Content-Type: image/gif");
include("CronController.php");
echo base64_decode("R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==");
?>