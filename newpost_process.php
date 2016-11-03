<?

global $vbulletin;
$vbo = &$vbulletin->options;
$vbu = &$vbulletin->userinfo;

// parses the raw $dtext and returns the epoch time for the reminder
// returns FALSE if the text cannot be parsed into a valid time
function getDateline($dtext)
{
    global $vbulletin;
    $dtext = trim($dtext);

    $temp = date_parse($dtext); 
    if ($temp !== FALSE && $temp['year'] > 0 && $temp['month'] > 0 && $temp['day'] > 0)
    {
        print ("<h1>Hell yeah!</h1>");
        print ("<pre>");
        print_r($temp);
        print ("</pre>");
        die;
    }
    else if ((strcasecmp($dtext, "tommorrow") == 0) 
        || (strcasecmp($dtext, "tomorrow") == 0)
        || (strcasecmp($dtext, "tommorow") == 0))
    {
        $val = TIMENOW + 86400;
    }
    else
    {
        $val = FALSE;
    }

    return $val;    
}

$rmtoken = 'remindme! ';

$remindtext = trim($post['message']);
if (!strstr($remindtext, "\n") && stripos($remindtext,$rmtoken)===0) 
{
    $msgtext = "";
    $dtext = "";

    $dstart = strlen($rmtoken);
    
    if (substr_count($remindtext,'"') === 2)
    {
        $qstart = strpos($remindtext, '"') + 1;
        $qend = strpos($remindtext, '"', $qstart);
        $dtext = trim(substr($remindtext, $dstart, ($qstart - $dstart)-1));
        $msgtext = substr($remindtext, $qstart, $qend-$qstart);            
    }
    else
    {
        $dtext = substr($remindtext, $dstart);
    }

    $dateval = getDateline($dtext);

    if ($dateval !== FALSE)
    {
        $vbo["remindme_dateline"] = $dateval;
        $vbo["remindme_msgtext"] = $msgtext;
    }
    else
    {
        eval(standard_error("Invalid date/time. Please press 'BACK' and try again."));
    }
}


?>