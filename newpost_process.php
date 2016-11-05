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

    // let strtotime get a first pass at it
    $val = strtotime($dtext);
    if ($val === FALSE)
    {
        // let's try to do some parsing of our own
        if ((strcasecmp($dtext, "tommorrow") == 0) 
            || (strcasecmp($dtext, "tomorrow") == 0)
            || (strcasecmp($dtext, "tommorow") == 0))
        {
            $val = TIMENOW + 86400;
        }
    }

    return $val;
}

$rmtoken = 'remindme! ';

$remindtext = trim($post['message']);

// the remindme! message must be the first text of the post and only be one line 
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

    if ($dateval === FALSE)
    {
        eval(standard_error("Invalid date/time. Please press 'BACK' and try again."));
    }
    else if ($dateval <= TIMENOW)
    {
        eval(standard_error("Must specify a time in the future. Please press 'BACK' and try again."));
    }
    else
    {
        $vbo["remindme_dateline"] = $dateval;
        $vbo["remindme_msgtext"] = $msgtext;
    }
}

?>