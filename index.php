		<?
	##	print "<b><font face=\"Arial\" size=\"6\">Under Construction!</font>";
		$urlpams = "";
foreach($_GET as $key => $value)
{
$urlpams .= "$key?=$value&";
}

$myUrl .= $_SERVER['REQUEST_URI'];
$atname[0] = "";
$atprice[0] = "";
$googleprice[0] = "";
$newprice[0] = "";
$atitem = 0;
$lastname = 99999;
$lastitem = 99999;
$product = "notfound";
##$urlpams = rawUrlEncode($urlpams);
$urlpams = str_replace(" ","+",$urlpams);
##print $myUrl . "?" . $urlpams;
##/ebayhunter/index.php

$myUrl = substr($myUrl, 22);
if (strpos($myUrl, "from") > 0) {
$myUrl2 = "/sch/i.html?" . $myUrl;
$myUrl = $myUrl2;
}
if (strpos($myUrl, "udlo=") > 0) {
$myUrl2 = "/sch/i.html?" . $myUrl;
$myUrl = $myUrl2;
}
$url = "";
if (strlen($myUrl) <= 5) {
$url = "http://www.ebay.com/sch/i.html?_from=R40&_trksid=m570.l1313&_nkw=&_sacat=0&_udlo=50&_udhi";
}else{


		$url = "http://www.ebay.com/$myUrl&_dmd=1";
		}
	##	print $url;
		$shop = explode("\r\n", file_get_contents($url));
		$x = 0;
		$prices[0] = "";
		$num = 0;
		$found = 0;
		$found2 = 0;
		$replaces = 0;
		$ebayprice = 0;
$num2 = 0;
$ebayprice = "";
		foreach ($shop as $html) {

$pos = strpos($html, "<h3 class=\"lvtitle\">");
if ($pos > 0) {
$newfeed2 = substr($html, $pos + 25);
$pos2 = strpos($newfeed2, "\">");
if ($pos2 > 0) {
$newfeed3 = substr($newfeed2, $pos2 + 2);
$pos3 = strpos($newfeed3, "</a>");
$newfeed4 = substr($newfeed3, 0, $pos3);
if ($newfeed4 != "") {
$product = $newfeed4;
$product = str_replace("\"","",$product);

}
$atname[$atitem] = $product;
	$product3 = rawUrlEncode($product);

				$fragment_url = "http://www.root2020.com/ebayhunter/getprice.php?request=$product3";
						    $newhtml = file_get_contents($fragment_url);
	    $newhtml = str_replace(" ","",$newhtml);
				    $newhtml = str_replace(",","",$newhtml);
				    $newhtml = round($newhtml);
				    $googleprice[$atitem] = $newhtml;
}}





if ($atname[$atitem] != "" && $lastname != $atname[$atitem]) {
$pos10 = strpos($html, "<li class=\"lvprice prc\">");
if ($pos10 >= 0) {
$pos0 = strpos($html, "class=\"bold\">");
if ($pos0 > 0) {
$newstring0 = substr($html, $pos0 + 13);
$pos5 = strpos($newstring0, "</span");
if ($pos5 > 0) {
$newfeed6 = substr($newstring0, 0, $pos5);
$newfeed6 = str_replace(" ","",$newfeed6);
$newfeed6 = str_replace("$","",$newfeed6);
$newfeed6 = str_replace(",","",$newfeed6);
$ebayprice = $newfeed6;
$atprice[$atitem] = $ebayprice;
$atprice[$atitem] = round($atprice[$atitem]);
$newprice[$atitem] = $googleprice[$atitem] - $atprice[$atitem];
$newprice[$atitem] = round($newprice[$atitem]);
$newprice2 = $newprice[$atitem];
if ($newprice[$atitem] <= 0) {$newprice[$atitem] = "None";};

if ($newprice[$atitem] > 0) {
$newprice[$atitem] = "$" . $newprice[$atitem];
}
if ($newprice2 >= 50) {
$newprice[$atitem] = "<font color=\"#008000\" face=\"Arial\" size=\"5\">$newprice[$atitem]</font>";
}
$lastname = $atname[$atitem];
$atitem++;
}
}
}
}
if ($atitem > 0 && $atitem != $lastitem) {

$imp = strpos($html, "<div class=\"hotness bold\">");
if ($imp > 0) {

								    $atitems = $atitem - 1;

$product2 = $atname[$atitems];
	##$product2 = rawUrlDecode($product);

				$product2 = str_replace(" ","+",$product2);
								$product2 = str_replace("&","+",$product2);

$product2 = str_replace("%20","+",$product2);
				    				$html = str_replace("<div class=\"hotness bold\">", "<div class=\"hotness bold\" id=\"btemp$num2\"><div id=\"btemp$num2\">Profit Potential:<a target=\"_blank\" href=\"http://www.google.com/search?safe=off&hl=en&output=search&tbm=shop&q=$product2&oq=$product2\">$newprice[$atitems]</a></div>",$html);
$num2++;

		$num++;
		$found = 0;
$found2 = 0;
$lastitem = $atitem;
}}

				$html = str_replace("http://www.ebay.com/sch/","http://www.root2020.com/ebayhunter/index.php?/sch/",$html);
				$html = str_replace("http://www.ebay.com/chp/","http://www.root2020.com/ebayhunter/index.php?/chp/",$html);
		print $html;
		}
		?>
