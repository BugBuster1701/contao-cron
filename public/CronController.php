<?php

/**
 * Contao Open Source CMS, Copyright (C) 2005-2017 Leo Feyer
 *
 * Contao Module "Cron Scheduler"
 *
 * @copyright  Glen Langer 2013..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Cron
 * @license    LGPL
 * @filesource
 * @see	       https://github.com/BugBuster1701/contao-cron
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace BugBuster\Cron;

/**
 * Initialize the system
 */
if (!defined('TL_MODE')) 
{
    define('TL_MODE', 'BE');
    
    $dir = __DIR__;

    while ($dir != '.' && $dir != '/' && !is_file($dir . '/system/initialize.php'))
    {
        $dir = dirname($dir);
    }
    
    if (!is_file($dir . '/system/initialize.php'))
    {
        echo 'Could not find initialize.php!';
        exit(1);
    }
    require($dir . '/system/initialize.php');
}


define('CRON_MAX_RUN', 5);	// stop processung jobs in one trigger after this time in seconds 

/**
 * Class CronController
 * 
 * @copyright  Glen Langer 2013..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Cron
 */
class CronController extends \Backend
{
	/**
	 * Initialize the controller
	 */
	public function __construct()
	{
        $this->import('BackendUser', 'User');
        parent::__construct();
        $this->User->authenticate();
	} // __construct

	/**
	 * Run controller
	 */
	public function run()
	{
		global $cronJob;
		
		$limit = is_null($GLOBALS['TL_CONFIG']['cron_limit']) ? 5 : intval($GLOBALS['TL_CONFIG']['cron_limit']);
		if ($limit<=0) 
		{ 
		    return; 
		}
		$currtime = time();
		$endtime  = $currtime+$limit;
		
		// process cron list
		$q = \Database::getInstance()->prepare("SELECT * FROM `tl_crontab` 
                                                WHERE `enabled`='1' 
                                                AND (
                                                      (`nextrun`>0 and `nextrun`<?) 
                                                   OR (`nextrun`=0 and `scheduled`<?)
                                                    ) 
                                                ORDER BY `nextrun`, `scheduled`")
                                     ->executeUncached($currtime, $currtime-86400);
		$locked = false;
		while ($q->next()) 
		{
			$currtime = time();
			if ($currtime >= $endtime) 
			{
			    break; 
			}
			if (!$locked) 
			{
				// ensure exclusive access
				$ql = \Database::getInstance()->prepare("SELECT get_lock('cronlock',0) AS lockstate")->executeUncached();
				if ( !$ql->next() || !intval($ql->lockstate) ) 
				{
				    return;
				}
				$locked = true;
			} // if
			if ($q->nextrun>0) 
			{ // due to execute
				$cronJob = array(
					'id'		=> $q->id,
					'title'		=> $q->title,
					'lastrun'	=> $q->lastrun,
					'endtime'	=> $endtime,
					'runonce'	=> intval($q->runonce) > 0,
					'logging'	=> intval($q->logging) > 0,
					'completed'	=> true
				);
				$output = $this->runJob($q);
				if ($cronJob['completed']) 
				{
					if ($cronJob['runonce'])
					{
						$dataset = array(
							'lastrun'	=> $currtime,
							'nextrun'	=> 0,
							'scheduled'	=> 0,
							'enabled'	=> '0'
						);
					}
					else
					{
						$dataset = array(
							'lastrun'	=> $currtime,
							'nextrun'	=> $this->schedule($q),
							'scheduled'	=> $currtime
						);
					}
					\Database::getInstance()->prepare("UPDATE `tl_crontab` %s WHERE id=?")
                                            ->set($dataset)
                                            ->executeUncached($q->id);
				} // if
				if ($cronJob['logging'] || $output!='') 
				{
					if ($output!='') 
					{
						$this->log(
							'Cron job '.$q->title.' failed: '.$output, 
							'CronController run()', 
							TL_ERROR);
					}
					else 
					{
						$this->log(
							'Cron job '.$q->title.' '.($cronJob['completed'] ? 'completed.' : 'processed partially.'), 
							'CronController run()', 
							TL_GENERAL);
					}
				} // if
			} 
			else 
			{
				$dataset = array(
					'nextrun'	=> $this->schedule($q),
					'scheduled'	=> $currtime
				);
				\Database::getInstance()->prepare("UPDATE `tl_crontab` %s WHERE id=?")
                                        ->set($dataset)
                                        ->executeUncached($q->id);
			} // if
		} // while
		
		// release lock
		if ($locked)
		{
			\Database::getInstance()->prepare("SELECT release_lock('cronlock')")->executeUncached();
		}
	} // run
	
	/**
	 * Run job and return the captured output
	 */
	private function runJob(&$qjob)
	{
		ob_start();
		$e = error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_USER_DEPRECATED);
		include(TL_ROOT . '/' . $qjob->job);
		error_reporting($e);
		return str_replace("\n",'<br />', trim(preg_replace('#<\s*br\s*/?\s*>#i', "\n", ob_get_flush())));
	} // runJob
		
	/**
	 * Find new schedule time for job
	 */
	private function schedule(&$qjob)
	{
	    $minute = array();
	    $hour   = array();
	    $dom    = array();
	    $month  = array();
	    $dow    = array();
	    
		$dowNum = 
			str_ireplace(
				array('Sun','Mon','Tue','Wed','Thu','Fri','Sat'),
				array(0,1,2,3,4,5,6),
				$qjob->t_dow
			);
		$monthNum = 
			str_ireplace(
				array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'),
				array(1,2,3,4,5,6,7,8,9,10,11,12),
				$qjob->t_month
			);
		$this->parseElement($qjob->t_minute,	$minute,	0,	60);
		$this->parseElement($qjob->t_hour,		$hour,		0,	24);
		$this->parseElement($qjob->t_dom,		$dom,		1,	31);
		$this->parseElement($monthNum,			$month,		1,	12);
		$this->parseElement($dowNum,			$dow,		0,	 7);	
		
		$nextrun = time()+60;
		$maxdate = $nextrun+31536000; // schedule for one year ahead max
		while ($nextrun < $maxdate) 
		{
			$dateArr	= getdate($nextrun);
			$_seconds	= $dateArr['seconds'];
			$_minutes	= $dateArr['minutes'];
			$_hours		= $dateArr['hours'];
			$_mday		= $dateArr['mday'];
			$_wday		= $dateArr['wday'];
			$_mon		= $dateArr['mon'];
			
			if (!$month[$_mon] || !$dom[$_mday] || !$dow[$_wday]) 
			{
				// increment to 00:00:00 of next day
				$nextrun += 60*(60*(24-$_hours)-$_minutes)-$_seconds;
				continue;
			} // if
			
			$allhours = ($_hours==0);
			while ($_hours < 24) 
			{
				if ($hour[$_hours]) 
				{
					$allminutes = ($_minutes==0);
					while ($_minutes < 60) 
					{
						if ($minute[$_minutes]) return $nextrun;
						// increment to next minute
						$nextrun += 60-$_seconds;
						$_minutes++;
						$_seconds = 0;
					} // while
					if ($allminutes) return 0;
					$_hours++;
					$_minutes = 0;
				} 
				else 
				{
					// increment to next hour
					$nextrun += 60*(60-$_minutes)-$_seconds;
					$_hours++;
					$_minutes = $_seconds = 0;
				} // if
			} // while
			if ($allhours) return 0;
		} // while
		return 0;
	} // schedule
	
	/**
	 * Parse timer element of syntax  from[-to][/step] or *[/step] and set flag for each tick
	 */
	private function parseElement($element, &$targetArray, $base, $numberOfElements) 
	{
		if (trim($element)=='') $element = '*';
		$subelements = explode(',', $element);
		for ($i = $base; $i < $base+$numberOfElements; $i++)
			$targetArray[$i] = $subelements[0] == "*";
	
		for ($i = 0; $i < count($subelements); $i++) {
			if ( preg_match("~^(\\*|([0-9]{1,2})(-([0-9]{1,2}))?)(/([0-9]{1,2}))?$~", $subelements[$i], $matches) ) 
			{
				if ($matches[1]=='*') 
				{
					$matches[2] = $base;					// all from
					$matches[4] = $base+$numberOfElements;	// all to
				} 
				elseif ($matches[4]=='') 
				{
					$matches[4] = $matches[2];	// to = from
				} // if
				if ($matches[5][0]!='/')
					$matches[6] = 1;			// default step
				$from	= intval(ltrim($matches[2],'0'));
				$to		= intval(ltrim($matches[4],'0'));
				$step	= intval(ltrim($matches[6],'0'));
				for ($j = $from; $j <= $to; $j += $step) $targetArray[$j] = true;
			} // if
		} // for
	} // parseElement
	
} // class CronController

/**
 * Instantiate controller
 */
$objCron = new \BugBuster\Cron\CronController();
$objCron->run();

