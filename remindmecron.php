<?
error_reporting(E_ALL & ~E_NOTICE);
define('THIS_SCRIPT', 'remindmecron');
$globaltemplates     = array(); // TODO: may not need?
$specialtemplates     = array();// TODO: may not need?
$actiontemplates     = array();// TODO: may not need?
$phrasegroups         = array();// TODO: may not need?

$isconsole = (($_SERVER['argv'][1]) == 'console');
$DEBUG = true;

// this means a server cron job is launching this or we are 
// manually launching it from a shell
if ($isconsole) 
{
	// allows us to include vbulletin framework in a console script
	define('NO_REGISTER_GLOBALS', 1);
	define('SKIP_SESSIONCREATE', 1);
	define('SESSION_BYPASS', 1);
	define('NOCOOKIES', 1);
	define('DIE_QUIETLY', 1);
	error_reporting(0);	
	chdir('..');
	require_once('./global.php');  
}
else // this means vbulletin's scheduled tasks is running us
{
	$db = $vbulletin->db;
}

?>