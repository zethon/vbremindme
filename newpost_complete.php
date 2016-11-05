<?

global $vbulletin,$db;
$vbo = &$vbulletin->options;
$vbu = &$vbulletin->userinfo;

$botuserid = 2976;

if (isset($vbo["remindme_dateline"]))
{
    // create the reminder
    $newposturl = $vbo['bburl'] . "/showthread.php?p=" . $post['postid'] . "#post". $post['postid'];
    $remindertext = "I am the RemindMe Bot reminding you about this post: [url]$newposturl[/url]";
    if (isset($vbo["remindme_msgtext"]) && strlen($vbo["remindme_msgtext"]) > 0)
    {
        $remindertext .= "\n\n[QUOTE]$vbo[remindme_msgtext][/QUOTE]"; 
    }
    $db->query_write("INSERT INTO ".TABLE_PREFIX."remindme (userid, dateline, message) VALUES ($vbu[userid],$vbo[remindme_dateline],'$remindertext');");

    // create a new post informing the user we've created the reminder
    $datetxt = vbdate($vbo['dateformat'], $vbo["remindme_dateline"]);
    $timetxt = vbdate($vbo['timeformat'], $vbo["remindme_dateline"]);

    $quotetext = "[QUOTE=$post[username];$post[postid]]$post[message][/QUOTE]";
    $botposttext = "$quotetext\n\nI will send you a private message on $datetxt $timetxt to remind you of this post";
    if (isset($vbo["remindme_msgtext"]) && strlen($vbo["remindme_msgtext"]) > 0)
    {
        $botposttext = "$quotetext\n\nI will send you a private message on $datetxt at $timetxt to remind you of this post with the message[QUOTE]$vbo[remindme_msgtext][/QUOTE]";
    }

    $newpost =& datamanager_init('Post', $vbulletin, $error_type, 'threadpost');
    $newpost->set('userid', $botuserid); 
    $newpost->set_info('forum', $foruminfo);
    $newpost->set_info('thread', $threadinfo); 
    $newpost->set_info('is_automated', true);
    $newpost->set_info('skip_floodcheck', true);        
    $newpost->set('threadid', $threadinfo['threadid']);
    $newpost->set('parentid', $post['postid']);
    $newpost->set('pagetext', $botposttext); 
    $newpost->set('visible', true);
    $newpost->set('allowsmilie',  true);
    $newpost->set('showsignature',true);
    $newpost->pre_save();
    if(count($newpost->errors) < 1)
    {
        $newid = $newpost->save();
        unset($newpost);
        build_thread_counters($newpost);
    }
    else
    {
        print "<h1>Error making new post! " . $newpost->errors[0] . $newpost->errors[1] . $newpost->errors[2] ."</h1>";   
    }   

    // just to be sure
    unset($vbo["remindme_dateline"]);
}

?>