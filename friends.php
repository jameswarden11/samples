<?
include 'vars.php';
session_start();



include_once 'privileges.php';
##$sql = "SELECT * FROM accounts WHERE name LIKE '%$search%'";
$fid = $_GET["fid"];
$tid = $_GET["tid"];
$accessfid = $tid;
$lid = $_GET["lid"];
if (isset($_GET["fromm"])) {
$_SESSION["atresource2"] = $_GET["fromm"];
}
if ($_GET["typeselect"] == "friends") {
$_GET["f"] = 1;
}

if (isset($_GET["f"])) {
$sql = "SELECT * FROM privileges WHERE fid=\"$tid\" AND type=\"0\"";
} else {
$sql = "SELECT * FROM accounts WHERE fbid <> ''";
}
$alphabet[0] = "A";
$alphabet[1] = "B";
$alphabet[2] = "C";
$alphabet[3] = "D";
$alphabet[4] = "E";
$alphabet[5] = "F";
$alphabet[6] = "G";
$alphabet[7] = "H";
$alphabet[8] = "I";
$alphabet[9] = "J";
$connection = mysql_connect($db_host, $db_login, $db_password) or die ("Couldnt Connect");
$db = mysql_select_db($db_goals, $connection) or die ("Couldnt select database");

$lcategories = "";
$toprating[0] = "";
$toproot[0] = "";
$topid[0] = "";
$numroot = 0;
$atname = "";
$ratetotal = "";
$tlocation3 = 0;
$tloc3 = -1;
$templid = 0;
$atresource = $_SESSION["atresource2"];
if ($atresource == "") {
$atresource = $_SESSION["atresource"];
$_SESSION["atresource2"] = $atresource;
}
if ($atresource >= 0) {

## Retrieve Initial Rating, Name and Parent Location for current resource
## from USER IDs resource locations table and USER IDs resources table


$sql77 = "SELECT rid, location3, lid3 FROM resourcelocs0 WHERE lid=\"$atresource\"";
$result77 = @mysql_query($sql77,$connection) or die("Error");
while ($row3 = mysql_fetch_array($result77)) {
$temprid = $row3["rid"];
$tlocation3 = $row3["location3"];
$tloc3 = $row3["lid3"];
$sql88 = "SELECT name, categories, rating FROM resources0 WHERE rid=\"$temprid\"";
$result888 = @mysql_query($sql88,$connection);

while ($row4 = mysql_fetch_array($result888)) {
$ratetotal = $row4["rating"];
$atname = $row4["name"];
$lcategories = $row4["categories"];

}


}

## Retrieve ratings for current resource from USER IDs resource notes
$initrating = getrating($ratetotal, $temprid, $userid);

## Default number of ratings for resources is one, because of initial rating.
$numrates = 1;

$iterations3 = 0;


## Using Parent Location, find full path to current resource.
##$toproot[$numroot] = $atname;
##$topid[$numroot] = $atresource;
##$numroot++;

while ($tlocation3 > 0) {

## Retrieve Name, Parent ID and Resource ID for parent location
if ($tloc3 >= 0) {
$sql99 = "SELECT rid, lid, lid3, location3 FROM resourcelocs0 WHERE lid=\"$tloc3\" LIMIT 1";
} else {
$sql99 = "SELECT rid, lid, lid3, location3 FROM resourcelocs0 WHERE rid=\"$tlocation3\" LIMIT 1";
}

$result99 = @mysql_query($sql99,$connection) or die("Err");
echo mysql_error();
##print $sql99;
$tlocation3 = "-1";
while ($row99 = mysql_fetch_array($result99)) {
$tlocation3 = $row99["location3"];
$tloc3 = $row99["lid3"];
$trid = $row99["rid"];
$sql10 = "SELECT name, rating from resources0 WHERE rid=\"$trid\"";
$result10 = @mysql_query($sql10, $connection);
while ($row10 = mysql_fetch_array($result10)) {
$nname = $row10["name"];
$therating = getrating($row10["rating"], $trid, $userid);

}

$atname2 = $nname . " - " . $atname2;

## Save the path

$toproot[$numroot] = $nname;
$toprating[$numroot] = $therating;
 $totalratings[$numroot] = $toprating[$numroot];
 $totalratings2[$numroot] = $toprating[$numroot];
$topid[$numroot] = $row99["lid"];
$numroot++;
}

}

}
## Retrieve child resources of parent that are a certain type depending on parent


$sql26 = "SELECT rid, lid FROM resourcelocs0 WHERE location3=\"$temprid\"";
$result90 = @mysql_query($sql26, $connection) or die ("Database connection error.");

## Store resource information in an array

$rrid[0] = 0;
$rrname[0] = 0;
$rb = 0;
while ($rrow = mysql_fetch_array($result90)) {
$rrid[$rb] = $rrow["lid"];
$brid = $rrow["rid"];
$rrname[$rb] = "";
$sql27 = "SELECT name FROM resources0 WHERE rid=\"$brid\"";
$result27 = @mysql_query($sql27, $connection);
while ($row27 = mysql_fetch_array($result27)) {
$rrname[$rb] = $row27["name"];
}
$rb++;
}

$rrname = array_map('strtolower', $rrname);
array_multisort($rrname, SORT_ASC, $rrid);





function getrating($rating, $rid, $theid) {
global $connection;
global $accessfid;
$sql = "SELECT rating,ownerid FROM resource_notes0 WHERE rid=\"$rid\"";
$ratetotalb = $rating;

$result44 = @mysql_query($sql,$connection);
echo mysql_error();
$numratesb = 1;

while ($row2 = mysql_fetch_array($result44)) {
$ownerid = $row2["ownerid"];
if (arenaaccess($accessfid, $ownerid, "notes") || arenaaccess("-1", $ownerid, "notes") || arenaaccess("-3", $ownerid, "notes")) {
$numratesb = $numratesb + 1;
$zrating = $row2["rating"];
$ratetotalb = $ratetotalb + $zrating;
}}
$rating = $ratetotalb / $numratesb;
##$rating = round($rating);
$rating = intval($rating);
return $rating;
}




## temporary
$from2 = $_SESSION["atresource2"];


function tracetolocation($glid) {
global $connection;
$gresid = 0;
global $from2;
$tlocation3 = 0;
$tloc3 = $glid;
$traced = 0;
$loc3 = 0;
$grid = -1;

$sql = "SELECT rid FROM resourcelocs0 WHERE lid=\"$from2\"";
$result = @mysql_query($sql,$connection);
while ($row=mysql_fetch_array($result)) {
$grid = $row["rid"];
}
$sql = "SELECT rid,location3,lid3 FROM resourcelocs0 WHERE lid=\"$glid\"";
$result = @mysql_query($sql,$connection);
while ($row=mysql_fetch_array($result)) {
$grid2 = $row["rid"];
$loc3 = $row["lid3"];
}

$tlocation3 = $grid2;
if  ($loc3 == $from2) {

$traced = 1;
}

if ($glid == $from2) {
$traced = 1;
}
if ($grid == $grid2) {
$traced = 1;
}
while ($tlocation3 > -6) {
## Retrieve Name, Parent ID and Resource ID for parent location
if ($tloc3 >= 0) {
$sql99 = "SELECT rid, lid, lid3, location3 FROM resourcelocs0 WHERE lid=\"$tloc3\"";
} else {
$sql99 = "SELECT rid, lid, lid3, location3 FROM resourcelocs0 WHERE rid=\"$tlocation3\"";
}
$rresult99 = @mysql_query($sql99,$connection) or die("3rd error");
$tlocation3 = "-6";

while ($rrow99 = mysql_fetch_array($rresult99)) {
$tlocation3 = $rrow99["location3"];
$tloc3 = $rrow99["lid3"];
$thelid = $rrow99["lid"];

if ($thelid == $from2) {
$traced = 1;
}

if ($tloc3 == $from2) {
$traced = 1;

}
if ($tlocation3 == $grid && $tloc3 == -1) {
$traced = 1;

}
##print "($from2,$tloc3,$grid,$tlocation3)";

}



}
return $traced;

}




?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>ROOT2020.com - Friends List</title>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" type="text/css" />
    <link rel="stylesheet" href="menu3.css" type="text/css" />

<script type="text/javascript" src="windowfiles/dhtmlwindow.js">

// popup window frames for goal and resource adding & editing


<link rel="stylesheet" href="windowfiles/modalfiles/modal.css" type="text/css">

<script type="text/javascript" src="windowfiles/modalfiles/modal.js"></script>
<script>
function setpermissions(fid, tid, priv, type) {
var fragment_url = "http://<? echo $path;?>/lms/setpermissions.php?fid=" + fid + "&tid=" + tid + "&priv=" + priv + "&type=" + type;
 xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", fragment_url);
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      //    element.innerHTML = xmlhttp.responseText;
           // element.firstChild.nodeValue = xmlhttp.responseText;
            }
        }
       xmlhttp.send(null);
document.form1.submit();
}

   function FriendRequest(fid, uid) {


    var fragment_url = "http://<? echo $path;?>/lms/newfriend.php?fid=" + fid + "&friend1=" + fid + "&friend2=" + uid;


		document.getElementById("friends").innerHTML = "Request Sent";

   xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", fragment_url);
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      //    element.innerHTML = xmlhttp.responseText;
           // element.firstChild.nodeValue = xmlhttp.responseText;
            }
        }
       xmlhttp.send(null);
   }


function changecolor(color) {
var divs=document.getElementsByTagName("div");
	for (var i=0; i<divs.length; i++){ //go through divs inside dhtml window and extract All those with class="drag-" prefix
	if (divs[i].className=="drag-handle") {
	divs[i].style.backgroundColor=color;
	}
}
}
</script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<form name="form1" id="form1" method="GET" action="friends.php">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="1" width="100%" bordercolor="#000000" cellspacing="0" cellpadding="0">
			<tr>
				<td bgcolor="#0000FF"><?
if ($_GET["p"] != "") {
## general privileges?
$sql43 = "SELECT fid FROM privileges WHERE tid=\"$tid\" AND privileges=\"resources\" AND fid<=0";
$checktype = "";
$result43 = @mysql_query($sql43,$connection) or die("Couldnt43");
while ($row43 = mysql_fetch_array($result43)) {
$checktype = $row43["fid"];
}

## resource-specific privileges?
$sql44 = "SELECT fid, type FROM privileges WHERE tid=\"$tid\" AND privileges=\"$lid\" AND fid<=0";
$result44 = @mysql_query($sql44,$connection) or die("Couldnt44");
$checktype2 = "";
while ($row44 = mysql_fetch_array($result44)) {
$checktype2 = $row44["fid"];
}
if ($checktype2 != "") {
$checktype = $checktype2;
}

?>
<b><font face="Arial"><font color="#FFFFFF"><input type="radio" onchange="setpermissions('-2', '<? echo $tid;?>','<? echo $lid;?>', '9');" value="private" name="sharedtype" id="sharedtype" <? if ($checktype == "-2") {echo "checked";}?>>
				Private&nbsp;<input type="radio" onchange="setpermissions('-1', '<? echo $tid;?>','<? echo $lid;?>', '8');" value="shared" name="sharedtype" id="sharedtype" <? if ($checktype == "-1") {echo "checked";}?>>Public&nbsp;<input type="radio" value="shared" name="sharedtype" id="sharedtype" onchange="setpermissions('-3', '<? echo $tid;?>','<? echo $lid;?>', '8');" <? if ($checktype == "-3") {echo "checked";}?>>Friends&nbsp;&nbsp;(Select exceptions below)<br>

<?
}
?>
<b><font face="Arial"><font color="#FFFFFF">&nbsp;Show&nbsp; <select size="1" name="typeselect" onChange="this.form.submit()"><option value="public">Public</option><option value="friends" <? if (isset($_GET["f"])) {echo "selected";}?>>Friends</option></select> From</b></font></font><select style="width: 205px" id="all2" onChange="this.form.submit()" size="1" name="fromm"><?
 $bb = 0;
 $numroot = $numroot - 1;
 for ($v=$numroot;$v>=0;$v--) {
 if ($v < $numroot) {
 $totalratings[$v] = $totalratings[$v] + $totalratings[$v + 1];


$totalratings2[$v] = $totalratings[$v] / $bb;
$totalratings2[$v] = round($totalratings2[$v]);
 }

$newcolor = "yellow";
if ($totalratings2[$v] <= -3) {
$newcolor = "red";
}
if ($totalratings2[$v] == -2) {
$newcolor = "orange";
}
if ($totalratings2[$v] == -1) {
$newcolor = "orange";
}
if ($totalratings2[$v] == 0) {
$newcolor = "grey";
}
if ($totalratings2[$v] == 1) {
$newcolor = "blue";
}
if ($totalratings2[$v] == 2) {
$newcolor = "blue";
}
if ($totalratings2[$v] == 3) {
$newcolor = "saddlebrown";
}
if ($totalratings2[$v] >= 4) {
$newcolor = "green";
}
?>

 <option style="background-color:<? echo $newcolor;?>;color:white" value="<? echo $topid[$v];?>"><? echo $alphabet[$bb] . "." . $toproot[$v];?></option>
 <?
 $bb++;
 }
 if ($temprid != "-1") {

  $numroot2 = $numroot + 1;

  $newrating = $totalratings[0] + $num;
  $newrating = $newrating / $numroot2;
  $newrating = round($newrating);
  $num = $newrating;
print "(" . $numroot . ")";
if ($numroot < 0) {
$newrating = $initrating;
}
if ($newrating <= -3) {
$newcolor = "red";
}
if ($newrating == -2) {
$newcolor = "orange";
}
if ($newrating == -1) {
$newcolor = "orange";
}
if ($newrating == 0) {
$newcolor = "grey";
}
if ($newrating == 1) {
$newcolor = "blue";
}
if ($newrating == 2) {
$newcolor = "blue";
}
if ($newrating == 3) {
$newcolor = "saddlebrown";
}
if ($newrating >= 4) {
$newcolor = "green";
}

  if ($newcolor == "red") {
  print "<script>blink2();</script>";
  }
?>

   <option style="background-color:<? echo $newcolor;?>;color:white" value="<? echo $atresource; ?>" selected><? echo $alphabet[$numroot + 1] . ".";?><? echo $atname;?></option>


  <?
  }
## Sort resources alphabetically by name

  for ($m=0;$m<$rb;$m++) {
$rrname[$m] = ucfirst($rrname[$m]);
  $length = strlen($rrname[$m]);
  if ($length > 27) {
  $rrname[$m] = substr($rrname[$m], 0, 25);
  $rrname[$m] = $rrname[$m] . "...";
  }
  if ($length > 0) {

  print "<option style=\"background-color:white;color:black\" value=\"$rrid[$m]\"";
  print ">$rrname[$m]</option>";
  }}
  ?>
</select><input name="from" value="<? echo $from;?>" type="hidden">
<!--googleon: all-->
   <script>
   function changecolor3(ncolor) {
   var color22 = ncolor;
   //alert(color22);
     document.getElementById("all2").style.backgroundColor = color22;
         document.getElementById("all2").style.color = 'white';
}
//changecolor3('<? echo $newcolor;?>');
   </script>

 <img src="http://www.root2020.com/lms/task.png"  width="1" height="1" alt=""
     onload="changecolor3('<? echo $newcolor;?>');this.parentNode.removeChild(this);" />


























</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="465">
			<tr>
			<?
				$tcount = 0;
				$result = @mysql_query($sql,$connection) or die("Couldnt33");
				$fbid = "";
				$acctid = "";
				$name = "";
				$status = "2";
$shared = 0;


while ($row = mysql_fetch_array($result)) {
$shared = 0;
$toid = $row["tid"];
$toid2 = $toid;
if (isset($_GET["f"])) {
$status = 0;
$sql2 = "SELECT name, id, fbid FROM accounts WHERE id=\"$toid\"";
$result2 = @mysql_query($sql2,$connection) or die("Couldnt44");
while ($row2 = mysql_fetch_array($result2)) {
$fbid = $row2["fbid"];
$acctid = $row2["id"];
$name = $row2["name"];
}

} else {

$fbid = $row["fbid"];
$acctid = $row["id"];
$toid2 = $acctid;
$name = $row["name"];
$status = 2;
$sql3 = "SELECT type FROM privileges WHERE fid=\"$tid\" AND tid=\"$acctid\" AND type <=3";
$result3 = @mysql_query($sql3,$connection) or die("Couldnt55");
while ($row3 = mysql_fetch_array($result3)) {
$status = $row3["type"];
}



}
if ($checktype == "-2") {
$shared = 0;
##print "1";
}

if ($checktype == "-1") {
$shared = 1;
##print "2";
}

if (arenaaccess("-3", $tid, $lid) && $status == 0) {
$shared = 1;
##print "3";
}

$sql01 = "SELECT * FROM privileges WHERE fid=\"$toid\" AND tid=\"$tid\" AND privileges=\"$lid\"";
$result01 = @mysql_query($sql01,$connection);
while ($row01 = mysql_fetch_array($result01)) {
if ($row01["type"] == "9") {
$shared = 0;
}
if ($row01["type"] == "8") {
$shared = 1;
}

}
if ($toid == $tid) {
$shared = 1;
}

$sql11 = "SELECT atresource FROM networks WHERE nid=\"$toid2\"";
$result11 = @mysql_query($sql11,$connection) or die("Couldnt11");
while ($row11 = mysql_fetch_array($result11)) {
$socresource = $row11["atresource"];
}
if (tracetolocation($socresource) == 1) {

?>
				<td align="center" width="93" valign="top"><font face="Arial">

				<div class="menu3"   style="clear:both;display:inline;margin: 0px 0px 0px 0px;border-collapse: separate;z-index:auto;">
					              <ul style="display:inline;list-style-type: none;line-height:15px;"><li style="line-height:0px;display:inline;list-style-type: none;">
			<a href="http://www.root2020.com/lms/eventlog.php?fid=<? echo $fid;?>&tid=<? echo $acctid;?>" target="_blank">
				<img style="z-index: 2;" border="0" src="http://graph.facebook.com/<? echo $fbid;?>/picture?width=100&height=100" width="69" height="82">
				</font></a><ul style="list-style-type: none; position:absolute; height:auto; border:0px solid #000000; background:transparent; overflow:visible;text-decoration:none;">
				<li style="display:inline;z-index:100"><a href="#" onClick="window.parent.eventlog2('<? echo $fid;?>','<? echo $acctid;?>','<? echo $name ?>');"><img src="http://www.root2020.com/lms/stimulation.png" width="20" height="20"   border="0">ROOT2020</a>

				<?
				##if ($status != "0") {
				print "
				<ul>";
				##}

				 if (!empty($_GET["p"])) {

if ($shared == 0) {
?>
							<li style="display:inline;z-index:100"><a href="#" onClick="setpermissions('<? echo $toid;?>', '<? echo $tid;?>','<? echo $lid;?>','8');">

<img src="http://emojipedia-us.s3.amazonaws.com/cache/f8/69/f869f6512b0d7187f4e475fc9aa7f250.png" width="20" height="20"   border="0">

Share</a></li>
<?
}
if ($shared == 1) {
?>
<li><a href="#" onClick="setpermissions('<? echo $toid;?>', '<? echo $tid;?>','<? echo $lid;?>','9');">
<img src="http://emojipedia-us.s3.amazonaws.com/cache/2f/3c/2f3c03f9e546e4d9652560347210ba9d.png" width="20" height="20"   border="0">

Don't Share</a></li>

<?
}
?>
</ul></li>
				<? } else {
				if ($status == "2") {
				?>

							<li style="display:inline;z-index:100"><a href="#" onClick="javascript:FriendRequest('<? echo $fid;?>','<? echo $acctid;?>');"><img src="http://www.root2020.com/lms/stimulation.png" width="20" height="20"   border="0"><span id="friends">Friend Request</span></a></li></ul></li>
			<?
			}
			if ($status == "1") {
			?>
										<li style="display:inline;z-index:100"><a href="#"><img src="http://www.root2020.com/lms/stimulation.png" width="20" height="20"   border="0">Request Sent</a></li></ul></li>

			<?}
			}?>
			<li style="display:inline;z-index:100"><a href="#" onClick="window.open('https://www.facebook.com/app_scoped_user_id/<? echo $fbid;?>','_blank');"><img src="http://careers.wildlife.org/headers/cc/images/8764/facebook.png" width="20" height="20"   border="0">Facebook</a></li>
</ul></div>
	<br>
	<font face="Arial">
				<?
							$tcount++;
					$fname = explode(" ", $name);
						$names = count($fname);
		for ($oo=0;$oo<$names;$oo++) {
		echo $fname[$oo];
		if ($oo == 0) {echo "<br>";}
		}
				?></font></td>
<?
if ($tcount >= 5) {
echo "<tr>";
$tcount = 0;
}
}
}
?>
			</tr>
		</table>
		</td>
	</tr>
</table>
<input type="hidden" name="lid" value="<? echo $_GET["lid"];?>">
<input type="hidden" name="p" value="<? echo $_GET["p"];?>">
<input type="hidden" name="fid" value="<? echo $_GET["fid"];?>">
<input type="hidden" name="tid" value="<? echo $_GET["tid"];?>">

</form>
<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
var sc_project=5579865;
var sc_invisible=1;
var sc_security="c9c0ebfa";
var scJsHost = (("https:" == document.location.protocol) ?
"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" +
scJsHost+
"statcounter.com/counter/counter.js'></"+"script>");
</script>
<noscript><div class="statcounter"><a title="shopify stats"
href="http://statcounter.com/shopify/" target="_blank"><img
class="statcounter"
src="http://c.statcounter.com/5579865/0/c9c0ebfa/1/"
alt="shopify stats"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
</body>

</html>
