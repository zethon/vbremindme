<?
error_reporting(E_ALL & ~E_NOTICE);
define('THIS_SCRIPT', 'remindmecron');
$DEBUG = true;

$botuserid = 2976;

// this means a server cron job is launching this or we are 
// manually launching it from a shell
if ($isconsole) 
{
	// allows us to include vbulletin framework in a console script
	define('NO_REGISTER_GLOBALS', 1);
	define('SKIP_SESSIONCREATE', 1);
	define('SESSION_BYPASS', 1);
	define('NOCOOKIES', 1);
	//define('DIE_QUIETLY', 1);
	//error_reporting(0);	
	chdir('..');
//require_once('./install/init.php');
//require_once('./../includes/functions.php');
//require_once(DIR . '/install/upgrade_language_en.php');

	print("xxxxxxx\n");
	print (">" . getcwd()); print("]\n");	

print("1111111111111\n");
	require_once('/amb/www/global.php'); 
	print("222222222222\n"); 
//	$db = $vbulletin->db;
	print("\nBYE!!!");

}
else // this means vbulletin's scheduled tasks is running us
{
	$db = $vbulletin->db;
}

print("-----------------------\n");

$botinfo = fetch_userinfo($botuserid);
print("<pre>"); print_r($botinfo); print("</pre>");

$reminders = $db->query_read("SELECT * FROM ". TABLE_PREFIX ."remindme");
while ($reminderinfo = $db->fetch_array($reminders))
{
	print_r($reminderinfo);
	$pmdm =& datamanager_init('PM', $vbulletin, ERRTYPE_ARRAY);
	$pmdm->set_info('savecopy', false);
	$pmdm->set_info('cantrackpm', false);
	$pmdm->set('fromuserid', $botuserid);
	$pmdm->set('fromusername', $botinfo['username']);	
	$pmdm->setr('title', 'RemindeMe Message');

}

print("xxxxx");

?>
