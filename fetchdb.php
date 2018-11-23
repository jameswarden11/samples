<?

/* 

Fetch statistics from soccer24.com for FantasyLig
Developed March 2018

*/


function file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, 'https://www.soccer24.com/match/4rE9KKVd/#player-statistics;0');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-Fsign: SW9D1eZo'
    ));

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
curl_setopt( $ch, CURLOPT_COOKIESESSION, true );
curl_setopt( $ch, CURLOPT_COOKIEJAR, 'blah.txt' );
curl_setopt( $ch, CURLOPT_COOKIEFILE, 'blah.txt' );
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

## referer:
##https://www.soccer24.com/match/4rE9KKVd/#player-statistics;0	
## feed url:
##https://d.soccer24.com/x/feed/d_ps_4rE9KKVd_en_2
function startdownload($league, $week) {
global $objPHPExcel;
global $rowCount;
$objPHPExcel = new PHPExcel(); 
$objPHPExcel->setActiveSheetIndex(0); 
$rowCount = 1; 

$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,'Game ID');
$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,'Team ID');
$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,'Team Name');
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,'Player ID');
$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,'Player Name');
$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,'Goal (G)');
$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount,'Assist (A)');
$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount,'Goal Attempt (GA)');
$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount,'Shots on Goal (SG)');
$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount,'Blocked Shots (BS)');
$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount,'Offsides (O)');
$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount,'Fouls Committed (FC)');
$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount,'Fouls Suffered (FS)');
$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount,'Yellow Card (YC)');
$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount,'Red Card (RC)');
$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount,'70% to 100% Pass Success (PS-A)');
$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount,'50% to 70% Pass Success (PS-B)');
$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount,'0% to 50% Pass Success (PS-C)');
$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount,'Minutes - 0-45 (M0-45)');
$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount,'Minutes - 46-75 (M46-75)');
$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount,'Minutes - 76-90 (M76-90)');
$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount,'Own Goal (OG)');
$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount,'Hatrick+ (H)');
$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount,'Goalie Clean Sheet (GCS)');
$filename = "./xls/$league-$week.xlsx";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save($filename);
$type = $_GET["type"];
if ($type == 0 || $type == "") {
$path = "results";
}
if ($type == 1) {
$path = "fixtures";
}
 
if ($league == "epl") {
getgames("https://www.soccer24.com/england/premier-league/$path/", $league, $week, $type);
}
if ($league == "laliga") {
getgames("https://www.soccer24.com/spain/laliga/$path/", $league, $week, $type);
}
if ($league == "mls") {
getgames("https://www.soccer24.com/usa/mls/$path/", $league, $week, $type);
}
if ($league == "npfl") {
getgames("https://www.soccer24.com/nigeria/npfl/$path/", $league, $week, $type);
}
}
$urlkeys[0] = "";
$hometeams[0] = "";
$awayteams[0] = "";
$hometeamsid[0] = "";
$awayteamsid[0] = "";
$gamesid[0] = "";
$games = 0;
$curround = "";
$games2 = 0;
$rounds[0] = 0;
$timestamps[0] = "";
$tdates = 0;
$excess[0] = "";
$numex = 0;
$homescore[0] = "";
$awayscore[0] = "";
function getgames($gurl, $league, $week, $type) {
global $urlkeys;
global $hometeams;
global $awayteams;
global $curround;
global $games;
global $games2;
global $league;
global $leagueflag;

$explodestring1 = "class=\"fs-table tournament-page\"";
$tdata2 = explode($explodestring1, file_get_contents($gurl));


$mstring = "<div id=\"tournament-page-season-results\"";
if ($type == 1) {
$mstring = "<div id=\"tournament-page-sport-fixtures\"";
}
$findend = stripos($tdata2[1], $mstring);
$tdata3 = substr($tdata2[1], 0, $findend);
$tdata = preg_split('/(÷Round)/', $tdata3, -1, PREG_SPLIT_DELIM_CAPTURE);

if ($leagueflag == "1") {
$tdata = preg_split('/(¬RW÷)/', $tdata3, -1, PREG_SPLIT_DELIM_CAPTURE);
}



##$tdata = explode($explodekey, file_get_contents_curl($gurl));
$hometeam3 = "";
$awayteam3 = "";
$games = 0;
$games2 = 0;
$lastround = "";
$theround = "";
global $homescore;
global $matchedteam1;
global $matchedteam2;
global $awayscore;
global $rounds;
global $tdates;
global $timestamps;
foreach ($tdata as $feed){

$stampsearch = "¬AD÷";
$findstamp = stripos($feed, $stampsearch);
if ($findstamp !== false) {
$endstamp = "¬";
$thestamp = substr($feed, $findstamp + strlen($stampsearch));
$findstamp2 = stripos($thestamp, $endstamp);
$thestamp2 = substr($thestamp, 0, $findstamp2);
$thestamp2 = trim($thestamp2);
$timestamps[$tdates] = date("Y-m-d H:i:s", $thestamp2);
$tdates++;
}

$sstring0 = "¬RW÷";
$findround = stripos($feed, $sstring0);
if ($findround !== false) {
##$theround = substr($feed, $findround + strlen($sstring0));
##$findround2 = stripos($theround, "¬");
$theround2 = substr($feed, 0, $findround);
$theround = trim($theround2);
if ($theround == "") {
$theround = "-1";
}
$rounds[$games] = $theround;
}
$curround = $theround;
$urlkey = "";
$sstring = "~AA÷";
$findteam = stripos($feed, $sstring);
if ($findteam !== false) {
$newfeed7 = substr($feed, $findteam + strlen($sstring));
$findteam2 = stripos($newfeed7, "¬AD");
$newfeed8 = substr($newfeed7, 0, $findteam2);
##print $newfeed8 . "\n";
$urlkey = $newfeed8;

if (strlen($urlkey) > 2) {
$urlkeys[$games] = $newfeed8;
$games++;
}
}
$sstring33 = "¬AH÷";
$homescor = stripos($feed, $sstring33);
if ($homescor !== false) {
$newfeed7 = substr($feed, $homescor + strlen($sstring33));
$homescor2 = stripos($newfeed7, "¬");
$newfeed8 = substr($newfeed7, 0, $homescor2);
$homescor3 = $newfeed8;
$awayscore[$games2] = $homescor3;
}


$sstring3 = "¬AE÷";
$hometeam = stripos($feed, $sstring3);
if ($hometeam !== false) {
$newfeed7 = substr($feed, $hometeam + strlen($sstring3));
$hometeam2 = stripos($newfeed7, "¬JA");
$newfeed8 = substr($newfeed7, 0, $hometeam2);
$hometeam3 = $newfeed8;
if (strlen($hometeam3) > 2) {
$hometeams[$games2] = $hometeam3;

$matchedteam2[$games2] = 0;
}}

$sstring44 = "¬AG÷";
$awayscor = stripos($feed, $sstring44);
if ($awayscor !== false) {
$newfeed7 = substr($feed, $awayscor + strlen($sstring44));
$awayscor2 = stripos($newfeed7, "¬");
$newfeed8 = substr($newfeed7, 0, $awayscor2);
$awayscor3 = $newfeed8;
$homescore[$games2] = $awayscor3;

}



$sstring0 = "AF÷";
$awayteam = stripos($feed, $sstring0);
if ($awayteam !== false) {
$newfeed7 = substr($feed, $awayteam + strlen($sstring0));
$awayteam2 = stripos($newfeed7, "¬JB");
$newfeed8 = substr($newfeed7, 0, $awayteam2);
$awayteam3 = $newfeed8;
if (strlen($awayteam3) > 2) {

$awayteams[$games2] = $awayteam3;
$matchedteam1[$games2] = 0;
$games2++;
}
}
}



##print $feed;
// get player statistics
if ($type == 0) {
parsensave($league, $week);
}
// get future schedule
if ($type == 1) {
savegames($league);
}

}
$matchedteam1[0] = 0;
$matchedteam2[0] = 0;
function matchteam($teamname) {
global $league;
global $seasonid;
global $normalizeChars;
$league2 = strtoupper($league);
global $connection;
$sql = "SELECT DISTINCT Name, Id FROM fantasylig_team WHERE Id >=\"345\" AND league=\"$league2\" AND leagueseasonid=\"$seasonid\" ORDER BY Id DESC"; 
$result = @mysql_query($sql, $connection) or die("Error");
echo mysql_error();
$officialteams[0] = "";
$officialids[0] = "";
$official = 0;
$theteam = "0";
$theteamid = "0";
$teamname = str_replace("FC", "", $teamname);
$teamname = trim($teamname);
while ($row=mysql_fetch_array($result)) {
$officialteams[$official] = $row["Name"];
$officialids[$official] = $row["Id"];
$official++;
}
$tempawayteam = $teamname;

for ($x=0;$x<$official;$x++) {
$tempteamname = $officialteams[$x];
$tempteamname = str_replace("FC", "", $tempteamname);
$tempteamname = trim($tempteamname);
$tempteamname = strtr($tempteamname, $normalizeChars);
## database values

if ($matchedteam <= 3) {
if (strtolower($tempteamname) == strtolower($tempawayteam)) {
$matchedteam = 4;
$theteam = $officialteams[$x];
$theteamid = $officialids[$x];
}}
if ($matchedteam <= 2) {
if (scanteam2($tempteamname, $tempawayteam, 1)) {
$theteam = $officialteams[$x];
$theteamid = $officialids[$x];
$matchedteam = 3;
}
}

if ($matchedteam <= 1) {
if (scanteam2($tempteamname, $tempawayteam, 2)) {
$theteam = $officialteams[$x];
$theteamid = $officialids[$x];
$matchedteam = 2;
}
}
if ($matchedteam == 0) {
if (scanteam($tempteamname, $tempawayteam)) {
$theteam = $officialteams[$x];
$theteamid = $officialids[$x];
$matchedteam = 1;
}
}


}
if ($matchedteam > 0) {
    return array ($theteam, $theteamid);
} else {
return array("<b>" . $teamname . " (Bad)</b>", 0);
}
}

function savegames($league) {
global $seasonid;
global $timestamps;
global $hometeams;
global $awayteams;
global $connection;
global $seasonid;
global $league;
global $total;
global $cron;
global $games2;
global $awayteamsid;
global $hometeamsid;
global $hometeamname;
global $normalizeChars;
global $awayteamname;
global $matchedteam1;
global $matchedteam2;
global $startdate;

$league2 = strtoupper($league);

$matches = 0;


print "Beginning save games.." . count($timestamps) . " time signatures collected.<br>";
$currentweek = date("Y-m-d");
$latestweek = "";

$latestgame = max($timestamps);
$earliestgame = min($timestamps);
/* check to see if game schedule is already in the database for that league */
$query1 = "SELECT Id, StartDate, EndDate FROM fantasylig_week WHERE StartDate >= \"$startdate\" ORDER BY StartDate ASC";
$result = @mysql_query($query1, $connection) or die("Error connection 2");
print "Matching $games2 games with DB teams..<br>";
while ($row=mysql_fetch_array($result)) {
$latestweek = $row["EndDate"];
$earliestweek = $row["StartDate"];
$weekid = $row["Id"];
$query2 = "SELECT * FROM fantasylig_game WHERE WeekId=\"$weekid\" AND LeagueSeasonId=\"$seasonid\"";
$result2 = @mysql_query($query2, $connection) or die("Error connection 21");
if (mysql_num_rows($result2) == 0) {
for ($i=0;$i<count($timestamps);$i++) {
$timestamps2 = date("Y-m-d", strtotime($timestamps[$i]));
##print $timestamps[$i] . "<br>";
if ($timestamps2 >= $earliestweek && $timestamps2 <= $latestweek) {
$awayteamida = 0;
$hometeamida = 0;
list($awayteama, $awayteamida) = matchteam($awayteams[$i]);
list($hometeama, $hometeamida) = matchteam($hometeams[$i]);
if ($awayteamida != "0" && $hometeamida != "0") {
print "Available: $timestamps[$i] ($hometeama vs. $awayteama) falls within $earliestweek - $latestweek (Week ID:$weekid)<br>";
$matches++;
$nomatch = 0;
} else {
$nomatch = 1;
print "One of the teams $hometeama or $awayteama did not match DB records.<br>";
}

if ($cron == "1" && $nomatch == "0") {
$query3="INSERT INTO fantasylig_game (League,LeagueSeasonId, WeekId,StartDateTime, HomeTeamId, AwayTeamId, Score) VALUES (\"$league2\",\"$seasonid\",\"$weekid\",\"$timestamps[$i]\", \"$hometeamida\",\"$awayteamida\",\"0:0\");";
$result3 = @mysql_query($query3, $connection) or die("Error updating");
##echo mysql_error();
##print $query3;
}

}
}
} else {

print "Games already exist for $earliestweek - $latestweek (ID:$weekid)<br>";
}

}
if ($cron != "1") {
print "Matched $matches of $games2 games to teams.<br>";
print "</font><font face=\"Arial\" color=\"black\"><b>Update link: <a href=\"http://www.fantasylig.com/autodl/cronupdater2.php?league=$league&type=1&override=1&cron=1\">Click Here to manually update the games database.</font></a></b><br>";
} else {
print "<b>Added $matches games to schedule.</b><br>";
}

}

function scanteam($storedteam, $parsedteam) {
$newname2 = $parsedteam;
$newname = explode(" ", $newname2);
$firstchar = substr($parsedteam, 0, 1);
$checkmechar = substr($storedteam, 0, 1);
$checkme = $storedteam;
$matched = 0;
$nnlength = 0;
for ($x=0;$x<count($newname);$x++) {
if (strlen($newname[$x]) > 1) {
$nnlength++;
if ($checkmechar == $firstchar && stripos($checkme, $newname[$x]) !== false) {
##print $firstchar . " " . $newname[$x];
##print "Matched $storedteam with $parsedteam!<br>";
$matched++;
}

}
}
if (count($nnlength) > 2 && $matched > 1 || count($nnlength) <= 2 && $matched >= 1 || stripos($storedteam, $parsedteam) !== false) {
return true;
} else {
return false;
}
}

function scanteam2($storedteam, $parsedteam, $mode) {
$newname2 = $parsedteam;

$newname = explode(" ", $newname2);
$newname = preg_split('/( )/', $newname2, -1);
$stored = explode(" ", $storedteam);
$stored = preg_split('/( )/', $storedteam, -1);
$firstchar = substr($parsedteam, 0, 2);
$checkmechar = substr($storedteam, 0, 2);
$checkme = $storedteam;
$matched = 0;
$nnlength = 0;
$charmatches = 0;
for ($x=0;$x<=count($stored);$x++) {
$nnlength++;
if ($checkmechar == $firstchar) {
$firstchar = substr($newname[$x], 0, 2);
$checkmechar = substr($stored[$x], 0, 2);
$charmatches++;
if (stripos($stored[$x], $newname[$x]) !== false) {
##print $firstchar . " " . $newname[$x];
$matched++;
}

}
if ($nnlength >= 3 && $matched >= 2 || $nnlength  == $matched && $mode == 2 || $charmatches == $nnlength && $nnlength > 1 && $mode == 2) {
##print "Matched $storedteam with $parsedteam! $matched : $nnlength : ".count($stored)."<br>";
}
}
if ($nnlength >= 3 && $matched >= 2 || $nnlength == $matched && $mode == 2  || $charmatches == $nnlength && $nnlength > 1 && $mode == 2 ) {
return true;
} else {
return false;
}
}
$totalgames = 0;
function parsensave($league, $week) {
global $hometeams;
global $games;
global $header;
global $awayteams;
global $urlkeys;
global $gamesid;
global $games2;
global $hometeamname;
global $gameid;
global $curround;
global $awayteamname;
global $total;
global $totalgames;
$hometeams = array_filter($hometeams);
$awayteams = array_filter($awayteams);
$urlkeys = array_filter($urlkeys);
global $awayteamsid;
global $hometeamsid;
global $hometeamid;
global $awayteamid;
global $rounds;
global $timestamps;
global $missedgames;
global $startdate;
global $enddate;
global $latest;
global $latestgames;
/*
for ($y=0;$y<$games2;$y++) {
if ($rounds[$y] == "-1") {
$rounds[$y] = max($rounds);
}}
*/
##$totgames = count(array_keys($rounds, max($rounds)));
$totgames = count($timestamps);
$matched[0] = 0;
for ($x=0;$x<$games2;$x++) {
##print "$awayteams[$x] @ $hometeams[$x]<br>";
if (isset($urlkeys[$x])) {
$matched[$x] = 0;
$temphometeam = $hometeams[$x];
$tempawayteam = $awayteams[$x];
$temphometeam = str_replace("FC", "", $hometeams[$x]);
$tempawayteam = str_replace("FC", "", $awayteams[$x]);
$temphometeam = trim($temphometeam);
$tempawayteam = trim($tempawayteam);
for ($y=0;$y<$total;$y++) {
## database values
$temphometeamname = str_replace("FC", "", $hometeamname[$y]);
$tempawayteamname = str_replace("FC", "", $awayteamname[$y]);
$temphometeamname = trim($temphometeamname);
$tempawayteamname = trim($tempawayteamname);
##$temphometeamname = str_replace(" ", "", $temphometeamname);
##$tempawayteamname = str_replace(" ", "", $tempawayteamname);

if (scanteam($tempawayteamname, $tempawayteam) && scanteam($temphometeamname, $temphometeam) || scanteam2($tempawayteamname, $tempawayteam, 1) && scanteam2($temphometeamname, $temphometeam, 1)) {
$hometeams[$x] = $hometeamname[$y];
$gamesid[$x] = $gameid[$y];	
$hometeamsid[$x] = $hometeamid[$y];
$awayteams[$x] = $awayteamname[$y];
$awayteamsid[$x] = $awayteamid[$y];
$matched[$x] = $matched[$x] + 2;
}

/*
if ($matched[$x] < 2) {
if (stripos($temphometeamname, $temphometeam) !== false) { 
$hometeams[$x] = $hometeamname[$y];
$gamesid[$x] = $gameid[$y];	
$hometeamsid[$x] = $hometeamid[$y];
$matched[$x]++;
}

if (stripos($tempawayteamname, $tempawayteam) !== false) { 
$awayteams[$x] = $awayteamname[$y];
$gamesid[$x] = $gameid[$y];
$awayteamsid[$x] = $awayteamid[$y];
$matched[$x]++;
}
}
if ($matched[$x] <= 1) {
if (scanteam($tempawayteamname, $tempawayteam)) {
$awayteams[$x] = $awayteamname[$y];
$gamesid[$x] = $gameid[$y];
$awayteamsid[$x] = $awayteamid[$y];
$matched[$x]++;
}

if (scanteam($temphometeamname, $temphometeam)) {
$hometeams[$x] = $hometeamname[$y];
$gamesid[$x] = $gameid[$y];
$hometeamsid[$x] = $hometeamid[$y];
$matched[$x]++;

}
}
*/
}


}
}
for ($x=0;$x<=$totgames;$x++) {

if (!empty($awayteamsid[$x]) && !empty($hometeamsid[$x]) && $matched[$x]  >= 2) {
$tempdate = date("Y-m-d", strtotime($timestamps[$x]));
if ($tempdate >= $startdate && $tempdate <= $enddate) {
$latestgames[$latest] = $timestamps[$x];
$latest++;
print "($timestamps[$x]) $hometeams[$x] versus $awayteams[$x]..";
$statsurl = "https://d.soccer24.com/x/feed/d_ps_" . $urlkeys[$x] . "_en_2";
getminutes($urlkeys[$x], $x);
getstats($statsurl, $x, $league, $week);
$totalgames++;
}
}

}

}
$latestgames[0] = "";
$latest = 0;
$minutenames[0] = "";
## determines calculation, 1 if split 0 if not
$minutestatus[0] = "";
$minutes = 0;
$positions[0][0] = "";
## status 0 = not found
## status 1 = in starting lineup
## status 2 = two players played

function getplayerminutes($name) {
global $minutenames;
global $minutestatus;
global $minutes;
$toreturn = 0;

for ($x=0;$x<$minutes;$x++) {
if ($minutenames[$x] == $name) {
$toreturn = $minutestatus[$x];
}}
return $toreturn;
}


function insertminutes($name, $status) {
global $minutenames;
global $minutestatus;
global $minutes;
$match = 0;
$name = str_replace(".", "", $name);
for ($x=0;$x<$minutes;$x++) {
if ($minutenames[$x] == $name) {
$minutestatus[$x] = $status;
$match = 1;
}
}
if ($match == 0) {
$minutenames[$minutes] = $name;
$minutestatus[$minutes] = $status;
$minutes++;
}

}


function getposition($playerid) {
global $connection;
$theposition = "unknown";
$sql = "SELECT position FROM fantasylig_player WHERE fantasylig_player.Id=\"$playerid\"";
$result = @mysql_query($sql, $connection) or die("Fetch position Error");
while ($row=mysql_fetch_array($result)) {
$theposition = $row["position"];
}
return $theposition;
}

function getminutes($ukey, $ref) {
$surl = "https://d.soccer24.com/x/feed/d_li_$ukey" . "_en_2";
##print "Fetching substitution stats from $surl" . "...\n";
##$tdata = explode("<tr class=", file_get_contents_curl($purl));
$tdata = explode("<td colspan=\"2\" class=\"h-part\">", file_get_contents_curl($surl));

$tdata2 = explode("div class=\"name\"", $tdata[1]);
foreach ($tdata2 as $feed){
$feed2 = explode("window.open('/player", $feed);

## found a substitution, divide by two
if (count($feed2) >= 3) {
for ($x=1;$x<=2;$x++) {
##print $feed2[$x] . "\n\n";
$namesearch = "return false;\">";
$namesearch2 = "<";
$atspot = stripos($feed2[$x], $namesearch);
if ($atspot !== false) {
$foundname = substr($feed2[$x], $atspot + strlen($namesearch));
$getname = stripos($foundname, $namesearch2);
$thename = substr($foundname, 0, $getname);
$thename = trim($thename);
##print "Divide me: $thename\n";
insertminutes($thename, 2);
}
}
}
## no substitution if feed2 is tdata[1] else count as substitute
if (count($feed2) <= 2) {
$namesearch = "return false;\">";
$namesearch2 = "<";
$atspot = stripos($feed2[1], $namesearch);
if ($atspot !== false) {
$foundname = substr($feed2[1], $atspot + strlen($namesearch));
$getname = stripos($foundname, $namesearch2);
$thename = substr($foundname, 0, $getname);
$thename = trim($thename);
##print $thename . "\n";
insertminutes($thename, 1);


}
}

}
}

$header[0] = "";
$player[0][0] = "";
$playerid[0][0] = "";
$teamid[0][0] = "";
$teamname2[0][0] = "";
$playergoals[0][0] = "";
$playerstats[0][0][0] = "";
$stats1 = 0;
$stats2 = 0;
$stats3 = 0;
$playerid[0][0] = "";
$team[0][0] = "";
$normalizeChars = array(
    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
    'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
    'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', 'ü'=>'u',
    'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
);

$notfound = 0;
$recovered = 0;
$nanames[0] = "";
$nateam[0] = "";
$playermins[0] = "";
function getstats($purl, $ref, $league, $week) {
global $player;
global $nanames;
global $seasonid;
global $nateam;
global $total;
global $normalizeChars;
global $playerdata;
global $teamname2;
global $playerstats;
global $playergoals;
global $stats1;
global $stats2;
global $header;
global $stats3;
global $playerid;
global $connection;
global $team;
global $positions;
global $hometeams;
global $awayteams;
global $hometeamsid;
global $hometeamid;
global $awayteamid;
global $awayteamsid;
global $hometeamname;
global $awayteamname;
global $teamid;
global $recovered;
global $notfound;
global $playermins;
$stats1 = 0;
$stats2 = 0;
$stats3 = 0;
##print "Fetching player stats from $purl" . "...<br>";
$tdata = explode("<td class='player-label'>", file_get_contents_curl($purl));
$newfeed8 = "";
$lastname = "12345";
foreach ($tdata as $feed){
##print $feed;
$findname = stripos($feed, "return false;\">");
if ($findname !== false) {
$newfeed7 = substr($feed, $findname + 15);
$findname2 = stripos($newfeed7, "<");
$newfeed8 = substr($newfeed7, 0, $findname2);
$newfeed8 = trim($newfeed8);
$findname3 = stripos($newfeed8, " ");
$lastname = substr($newfeed8, 0, $findname3 + 2);
##$lastname = strtolower($lastname);
$lastname = trim($lastname);
$hometeamid2 = $hometeamsid[$ref]; 
$awayteamid2 = $awayteamsid[$ref];
if (strlen($lastname) > 1) {
$playermins[$ref][$stats1] = getplayerminutes($lastname);
$sql = "SELECT Name, Id, TeamId FROM fantasylig_player WHERE fantasylig_player.TeamId=\"$hometeamid2\" OR fantasylig_player.TeamId=\"$awayteamid2\"";
$result = @mysql_query($sql, $connection) or die("Fetch DB Error");
##print $sql . ":" . mysql_num_rows($result) . "<br>";
##echo mysql_error();

$totalaz = 0;
$nomatch = 1;

while ($row=mysql_fetch_array($result)) {
$newname2 = strtr($row["Name"], $normalizeChars);
##$newname = strtolower($newname);
$newname1 = stripos($newname2, " ");
$newname = substr($newname2, 0, $newname1 + 2);

if (strtolower($newname) == strtolower($lastname)) {
$nomatch = 0;
##print "Tacking ($lastname to $newname)<br>";
}
// end pattern match stage 1

if ($nomatch == 1) {
if (stripos($newname2, $lastname) !== false) {
if ($totalaz > 0) {
##print "Warning:Found more than one match for $lastname:" . $row["Name"] . "<br>";
}
$nomatch = 0;
$totalaz++;
}}
// end no match

if ($nomatch == 0) {
##print "Result:" . $row["Name"] . "($ref)($stats1)<br>";
$playerid[$ref][$stats1] = $row["Id"];
$player[$ref][$stats1] = $row["Name"];
$teamid[$ref][$stats1] = $row["TeamId"];
$team[$ref][$stats1] = "Unknown";
$positions[$ref][$stats1] = getposition($row["Id"]);
if ($row["TeamId"] == $hometeamid2) {
$team[$ref][$stats1] = $hometeams[$ref];
$teamid[$ref][$stats1] = $hometeamid2;
}
if ($row["TeamId"] == $awayteamid2) {
$team[$ref][$stats1] = $awayteams[$ref];
$teamid[$ref][$stats1] = $awayteamid2;
} // end find string

// if team wasnt matching, try a different one
if ($team[$ref][$stats1] == "Unknown") {
$sql2 = "SELECT Id, Name from fantasylig_team WHERE Id=\"" . $row["TeamId"] . "\"";
$result2 = @mysql_query($sql2, $connection);
while ($row2=mysql_fetch_array($result2)) {
$team[$ref][$stats1] = $row2["Name"];
$teamid[$ref][$stats1] = $row2["Id"];
}
}


break;
} // end no match
} // end while

if ($nomatch == 1) {
##print "No match found for $lastname..Recovering..<br>";
$sql = "SELECT fantasylig_player.Name, fantasylig_player.Id, fantasylig_player.TeamId FROM fantasylig_player, fantasylig_team WHERE fantasylig_team.Id=fantasylig_player.TeamId AND fantasylig_team.leagueseasonId=\"$seasonid\" ORDER BY TeamId DESC";
$result = @mysql_query($sql, $connection) or die("Fetch DB Error");
##print $sql . ":" . mysql_num_rows($result) . "<br>";
##echo mysql_error();

$totalaz = 0;
while ($row=mysql_fetch_array($result)) {
$newname3 = strtr($row["Name"], $normalizeChars);
##$newname = strtolower($newname);
$newname1 = stripos($newname3, " ");
$newname = substr($newname3, 0, $newname1);
$lastname3 = $lastname;
$tlastname = stripos($lastname3, " ");
$tlastname2 = substr($lastname3, 0, $tlastname);

if (strtolower($newname) == strtolower($tlastname2)) {
##print "Matched $newname with $tlastname2 ..<br>";
$nomatch = 0;
}

if ($nomatch == 1) {

if (stripos($newname, $lastname) !== false) {
$nomatch = 0;
}}
if ($nomatch == 0) {
$recovered++;
$playerid[$ref][$stats1] = $row["Id"];
$player[$ref][$stats1] = $row["Name"];
$teamid[$ref][$stats1] = $row["TeamId"];
$team[$ref][$stats1] = "Unknown";
$positions[$ref][$stats1] = getposition($row["Id"]);
if ($row["TeamId"] == $hometeamid2) {
$team[$ref][$stats1] = $hometeams[$ref];
} 
if ($row["TeamId"] == $awayteamid2) {
$team[$ref][$stats1] = $awayteams[$ref];
} 
if ($team[$ref][$stats1] == "Unknown") {
$sql2 = "SELECT Name from fantasylig_team WHERE Id=\"" . $row["TeamId"] . "\"";
$result2 = @mysql_query($sql2, $connection);
while ($row2=mysql_fetch_array($result2)) {
$team[$ref][$stats1] = $row2["Name"];
} // end  loop
} // end if unknown
break;
} // end no match

} // end while
} // end no match



if ($nomatch == 1 && !checkdup($lastname)) {
##print "$lastname not found..<br>";
$nanames[$notfound] = $lastname;
$notfound++;
}

}} // end ifs 


if (!checkdup($lastname)) {


$sstring = "lass='team-label'>";
$findteam = stripos($feed, $sstring);
if ($findteam !== false) {
$newfeed7 = substr($feed, $findteam + strlen($sstring));
$findteam2 = stripos($newfeed7, "<");
$newfeed8 = substr($newfeed7, 0, $findteam2);
$teamname2[$ref][$stats1] = $newfeed8;
$newfeed8 = trim($newfeed8);
$nateam[$notfound] = $newfeed8;
if (empty($nateam[$notfound])) {
$nateam[$notfound] = $hometeams[$ref] . " or " . $awayteams[$ref];
}
$stats1++;
} 

$newfeed = $feed;






$sstring2 = "td class='value-col";
$findstats = stripos($newfeed, $sstring2);
$tstats = 0;

while ($findstats !== false) {
$newfeed7 = substr($newfeed, $findstats + strlen($sstring2));
$findstats1 = stripos($newfeed7, ">");
$newfeed9 = substr($newfeed7, $findstats1 + 1);

$findstats2 = stripos($newfeed9, "<");
$newfeed8 = substr($newfeed9, 0, $findstats2);
$newfeed = substr($newfeed9, $findstats2);
##print $newfeed . "\n\n\n";
$findstats = stripos($newfeed, $sstring2);
$newfeed8 = trim($newfeed8);

$playerstats[$ref][$stats3][$tstats] = $newfeed8;

$tstats++;
##$newfeed0 .= "," . $newfeed8;
}
if ($tstats > 0) {
$stats3++;
}
##print $newfeed0 . "\n"; 
$newfeed0 = "";
$newfeed = "";
}
}



/* 


write to XLSX file from week id + league name

*/

printsheet($ref, $league, $week);
}
$lines = 0;
$playerdata[0] = "";


function printsheet($sheetref, $league, $week) {               
global $player;
global $excess;
global $numex;
global $playerstats;
global $playergoals;
global $teamname2;
global $playerid;
global $stats1;
global $team;
global $teamid;
global $stats2;
global $stats3;
global $header;
global $hometeams;
global $awayteams;
global $playerdata;
global $gameid;
global $total;
global $positions;
global $gamesid;
global $lines;
global $playermins;
global $homescore;
global $awayscore;
$y = 1;
$newline = "";
$toappend = "";
$totfields = 23;
$pointcalc = 0;
$tlines = count($player[$sheetref]);
##print "$tlines versus ".count($player[1]);
$filename = "./xls/$league-$week.xlsx";
$objPHPExcel = PHPExcel_IOFactory::load($filename);
$objPHPExcel->setActiveSheetIndex(0);
$rowCount = $objPHPExcel->getActiveSheet()->getHighestRow()+1;
for ($i=0;$i<$tlines;$i++) {
if ($player[$sheetref][$i] != "") {
for ($x=0;$x<=$totfields;$x++) {
$toappend = "0";
if ($x == 0) {
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $gamesid[$sheetref]);
$toappend = $gamesid[$sheetref];
}

if ($x == 1) {
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $teamid[$sheetref][$i]);
$toappend = $teamid[$sheetref][$i];
}

if ($x == 2) {
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $team[$sheetref][$i]);
$toappend = $team[$sheetref][$i];
}
if ($x == 3) {
    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $playerid[$sheetref][$i]);
$toappend = $playerid[$sheetref][$i];
}

if ($x == 4) {
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $player[$sheetref][$i]);
$toappend = "\"" . $player[$sheetref][$i] . "\"";
}

if ($x >= 5 && $x <= 23) {
$pointcalc = 0;
if ($x == 5) {
## goals

$pointcalc = $playerstats[$sheetref][$i][0] * 10;
    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $pointcalc);
$toappend = $pointcalc;
}
## assists
if ($x == 6) {
$pointcalc = $playerstats[$sheetref][$i][1] * 5;
$toappend = $pointcalc;
    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $pointcalc);
}
## goal attempts
if ($x == 7) {
$pointcalc = $playerstats[$sheetref][$i][2] * 3;
if ($league == "npfl") {$pointcalc = 0;}
$toappend = $pointcalc;
    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $pointcalc);
}
## shots on goal
if ($x == 8) {
$pointcalc = $playerstats[$sheetref][$i][3] * 1;
if ($league == "npfl") {$pointcalc = 0;}
$toappend = $pointcalc;
    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $pointcalc);
}
## blocked shots
if ($x == 9) {
$pointcalc = $playerstats[$sheetref][$i][4] * 1;
if ($league == "npfl") {$pointcalc = 0;}
$toappend = $pointcalc;
    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $pointcalc);
}
## offsides
if ($x == 10) {
$pointcalc = $playerstats[$sheetref][$i][5] * -2;
if ($league == "npfl") {$pointcalc = 0;}
$toappend = $pointcalc;
    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $pointcalc);
}
## fouls committed
if ($x == 11) {
$pointcalc = $playerstats[$sheetref][$i][6] * -2;
if ($league == "npfl") {$pointcalc = 0;}
    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $pointcalc);
$toappend = $pointcalc;
}
## fouls suffered
if ($x == 12) {
$pointcalc = $playerstats[$sheetref][$i][7] * 4;
if ($league == "npfl") {$pointcalc = 0;}
$toappend = $pointcalc;
    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $pointcalc);
}
## yellow card
if ($x == 13) {
$pointcalc = $playerstats[$sheetref][$i][8] * -5;
if ($league == "npfl") {$pointcalc = $playerstats[$sheetref][$i][2] * -5;}
$toappend = $pointcalc;
    $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $pointcalc);
}

## red card
## fix this
if ($x == 14) {
$pointcalc = $playerstats[$sheetref][$i][9] * -10;
if ($league == "npfl") {$pointcalc = $playerstats[$sheetref][$i][3] * -10;}
$toappend = $pointcalc;
    $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $pointcalc);
}
## passing (greater than 70)
if ($x == 15) {
$pointcalc = 0;
if ($playerstats[$sheetref][$i][10] >= 70) {
$pointcalc = 5;
}
/*

For NPFL Only, All defense players are awarded 1 point for not conceding any goal while all midfield earn 1 point for scoring at least 1 goal and all forward earn 2 points for scoring any goal and winning the game. -1 point is awarded for all players in a team for loss or goalless games. 1 point is awarded to all midfield and forward players for tie games where goals are scored.

GOALKEEPER
DEFENDER
MIDFIELD
FORWARD

*/
if ($league == "npfl") {
$pointcalc = 0;
$myscore = 0;
$therescore = 0;
if ($team[$sheetref][$i] == $hometeams[$sheetref]) {
$myscore = $homescore[$sheetref];
$therescore = $awayscore[$sheetref];
} else {
$myscore = $awayscore[$sheetref];
$therescore = $homescore[$sheetref];
}
if ($myscore == 0 || $myscore < $therescore) {
$pointcalc = -1;
}


if ($positions[$sheetref][$i] == "DEFENDER") {
if ($therescore == 0) {
$pointcalc = $pointcalc + 1;
}

}

if ($positions[$sheetref][$i] == "GOALKEEPER") {
}


if ($positions[$sheetref][$i] == "MIDFIELD") {
if ($myscore > 0) {
$pointcalc = $pointcalc + 1;
}
if ($myscore > 0 && $myscore == $therescore) {
$pointcalc = $pointcalc + 1;
}


}

if ($positions[$sheetref][$i] == "FORWARD") {
if ($myscore > 0 && $myscore > $therescore) {
$pointcalc = $pointcalc + 2;
}

if ($myscore > 0 && $myscore == $therescore) {
$pointcalc = $pointcalc + 1;
}



}




}
    $objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $pointcalc);
$toappend = $pointcalc;
}
## passing (greater than 50 < 70)
if ($x == 16) {
$pointcalc = 0;
if ($playerstats[$sheetref][$i][10] >= 50 && $playerstats[$sheetref][$i][10] < 70) {
$pointcalc =  2;
}
if ($league == "npfl") {$pointcalc = 0;}
    $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $pointcalc);
$toappend = $pointcalc;
}
## passing (< 50)
if ($x == 17) {
$pointcalc = 0;
if ($playerstats[$sheetref][$i][10] < 50) {
$pointcalc = -2;
}
if ($league == "npfl") {$pointcalc = 0;}
    $objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $pointcalc);
$toappend = $pointcalc;
}

## minutes
if ($x == 18) {
$pointcalc = 0;
if ($playermins[$sheetref][$i] == 0) {
$pointcalc  = -2;
}
    $objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $pointcalc);
$toappend = $pointcalc;
}
if ($x == 19) {
$pointcalc = 0;
if ($playermins[$sheetref][$i] == 2) {
$pointcalc =  45;
$pointcalc = 3;
}
    $objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $pointcalc);
$toappend = $pointcalc;
}
if ($x == 20) {
$pointcalc = 0;
if ($playermins[$sheetref][$i] == 1) {
##$pointcalc = -2;
$pointcalc = 90;
$pointcalc = 5;
}
    $objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $pointcalc);
$toappend = $pointcalc;
}
## own goal
if ($x == 21) {
##$pointcalc = $playerstats[$sheetref][$i][13] * -10;
$pointcalc = 0;
if ($league == "npfl") {$pointcalc = 0;}
    $objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $pointcalc);
$toappend = $pointcalc;
}
## hat trick
if ($x == 22) {
$pointcalc = 0;
if ($playerstats[$sheetref][$i][0] >= 3 && $playerstats[$sheetref][$i][0] < 6) {
$pointcalc =  50;
}
if ($playerstats[$sheetref][$i][0] >= 6) {
$pointcalc = 100;

}
    $objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $pointcalc);
$toappend = $pointcalc;
}
## GCS (didnt score against goalie)
if ($x == 23) {

$pointcalc = 0;
$toappend = $pointcalc;
    $objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $pointcalc);
##$pointcalc = $playerstats[$sheetref][$i][14] * 5;
}

if ($toappend == "-" || $toappend == "") {
$toappend = 0;
}
} // end if
if ($pointcalc <= -50) {
$excess[$numex] = $player[$sheetref][$i];
$numex++;
}
$newline .= ",$toappend";
} // end for total fields
$rowCount++;
$newline .= "\n";
$newline = substr($newline, 1);
$newline2 .= $newline;
$newline = "";
$lines++;
} // player cycle
}

$playerdata[$sheetref] .= $newline2;
##$playerdata[$sheetref] = substr($playerdata[$sheetref], 1);
##print $header[$sheetref];
##print $playerdata[$sheetref];
##$sheetdata = $header[$sheetref] . $playerdata[$sheetref];
$sheetdata = $playerdata[$sheetref];
print "..Final score: $homescore[$sheetref] to $awayscore[$sheetref]..done. <br>";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 

$objWriter->save($filename); 
##file_put_contents("./csv/$league-$week.csv", $sheetdata, FILE_APPEND);
##print $sheetdata;
}

$totstats = 0;
$dontdup[0] = "blank";
$namecount = 0;
function checkdup($name) {
$isdup = false;
global $dontdup;
global $namecount;
for ($x=0;$x<$namecount;$x++) {
if ($dontdup[$x] == $name) {
$isdup = true;
}}
if (!$isdup) {
$dontdup[$namecount] = $name;
$namecount++;
}
return $isdup;
}











?>

