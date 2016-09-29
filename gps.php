<?
include 'vars.php';

$latitude = $_GET["lat"];
$longitude = $_GET["lon"];
$toreturn = "";
$fid = $_GET["fid"];
$tid = $_GET["tid"];
$connection = @mysql_connect($db_host, $db_login, $db_password) or die ("Couldnt Connect");
$db_name = $db_goals;
$db = @mysql_select_db($db_name, $connection) or die ("Couldnt2");
$zone = "";
$longname = "";
$state = "";
$country = "";

$api_key = "AIzaSyDZTJpb29JMonWxkHB3ciwr1LfYpMdZk4o";

$data = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=true"));
if($data->status == "OK") {
    if(count($data->results)) {
        foreach($data->results[0]->address_components as $component) {
              $zone = $component->types[0];
              $longname = $component->long_name;

                            if ($zone == "country") {
                            $country = $longname;
                            }
                            if ($zone == "administrative_area_level_1") {
                            $state = $longname;;
                            }
                        }}}
$toreturn = "";
if ($country == "United States") {
$sql = "SELECT name, rid FROM resources$tid WHERE name=\"$state\"";
$result = @mysql_query($sql, $connection) or die("Couldnt1");
while ($row = mysql_fetch_array($result)) {
$toreturn = $row["rid"];
}
}
if ($toreturn == "") {

$sql2 = "SELECT name, rid FROM resources$tid WHERE name LIKE '%$country%'";
$result2 = @mysql_query($sql2, $connection) or die("Couldnt2");
while ($row2 = mysql_fetch_array($result2)) {
$toreturn = $row2["rid"];
}

}
$sql3 = "SELECT lid FROM resourcelocs$tid WHERE rid=\"$toreturn\"";
$result3 = @mysql_query($sql3, $connection) or die("Couldnt3");
while ($row3 = mysql_fetch_array($result3)) {
$toreturn = $row3["lid"];
}

print $toreturn;

?>
