<?
error_reporting(E_ALL & ~E_NOTICE);
define('THIS_SCRIPT', 'remindmecron');
$DEBUG = true;

$botuserid = 2976;

$db = $vbulletin->db;
$botinfo = fetch_userinfo($botuserid);
$permissions = cache_permissions($botinfo);

$reminders = $db->query_read("SELECT * FROM ". TABLE_PREFIX ."remindme WHERE delivered!=1");
while ($reminderinfo = $db->fetch_array($reminders))
{
	if ($reminderinfo['dateline'] < TIMENOW)
	{
		print("<pre>"); print_r($reminderinfo); print("</pre>");

		$touserinfo = fetch_userinfo($reminderinfo['userid']);

		$pmdm =& datamanager_init('PM', $vbulletin, ERRTYPE_ARRAY);
		$pmdm->set('dateline', TIMENOW);
		$pmdm->set('fromuserid', $botinfo['userid']);
		$pmdm->set('fromusername', $botinfo['username']);
		$pmdm->set_recipients($touserinfo['username'], $permissions, 'cc');
		$pmdm->set('title', 'RemindMe Bot Reminder');
		$pmdm->set('message', $reminderinfo['message']);
		$pmdm->set_info('savecopy', false);
		$pmdm->set_info('cantrackpm', false);
		$pmdm->set('showsignature', true);
		$pmdm->set('allowsmilie', true);
		
		$pmdm->pre_save();

		if(count($pmdm->errors) < 1)
		{
			$pmdm->save();
		}
		else
		{
			// TODO: maybe retry and track the number of retries so it's only retried X number of times
			// but for now we'll just mark it as delivered
		}

		$db->query_write("UPDATE ". TABLE_PREFIX ."remindme SET delivered=1 WHERE remindmeid=$reminderinfo[remindmeid]");
	}
}

?>
