<?

global $vbulletin;
$vbo = &$vbulletin->options;
$vbu = &$vbulletin->userinfo;

if ($vbu['userid'] == 1)
{
    if (isset($vbo["remindme_dateline"]))
    {
        $datetxt = vbdate($vbo['dateformat'], $vbo["remindme_dateline"]);
        $timetxt = vbdate($vbo['timeformat'], $vbo["remindme_dateline"]);

        print ("<h1>($datetxt)($timetxt)[$vbo[$remindme_msgtext]]</h1>");
        print("<hr>");

        $quotetext = "[QUOTE=$post[username];$post[postid]$post[message][/QUOTE]";
        $msgtext = "$quotetext\n\nI'll message you on $datetxt $timetxt to remind you of this post";
        if (isset($vbo["remindme_msgtext"]) && strlen($vbo["remindme_msgtext"]) > 0)
        {
            $msgtext = "$quotetext\n\nI'll message you on $datetxt at $timetxt to remind you of this post with the message\n\n[b]$vbo[remindme_msgtext][/b]";
        }

        print ("<h1>($datetxt)($timetxt)[$msgtext]</h1>");

        print("<pre>");
        print_r($post);
        print("</pre>");

        // just to be sure
        unset($vbo["remindme_dateline"]);
        die;
    }
}

?>