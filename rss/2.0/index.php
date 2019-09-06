<?php
/**
 * Created by PhpStorm.
 * User: Tom Herbers (dev@herbetom.de)
 * Date: 05.08.18
 * Time: 15:43
 */
header('Content-Type: application/rss+xml; charset=utf-8');

require_once "../../config/getConfig.php";
$config = new getConfig();

require_once "../../system/miniplan.php";
$miniplan = new miniplan();

/** XML-Datei automatisch erstellen */
$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true;

$rss = $xml->createElement('rss');
$rss->setAttribute('version', '2.0');
$xml->appendChild($rss);

$channel = $xml->createElement('channel');
$rss->appendChild($channel);

/** Head des Feeds */
$head = $xml->createElement('title', 'Miniplan - Ministranten Sankt Georg Bensheim');
$channel->appendChild($head);

$head = $xml->createElement('link', 'https://plan.ministranten-sankt-ge.org/');
$channel->appendChild($head);

$head = $xml->createElement('description', 'Hier findet man die Webausgaben der Minipläne der Ministranten der Pfarrei Sankt Georg Bensheim');
$channel->appendChild($head);

$head = $xml->createElement('language', 'de-de');
$channel->appendChild($head);

$head = $xml->createElement('copyright', 'Planmacher - Ministranten Sankt Georg Bensheim');
$channel->appendChild($head);


/** Bild des Feeds einbinden */
$image = $xml->createElement('image');
$channel->appendChild($image);

$imgAttr = $xml->createElement('url', 'https://plan.ministranten-sankt-ge.org/img/minilogo-circle-noShadow_512px.png');
//$imgAttr = $xml->createElement('url', 'https://www.ministranten-sankt-ge.org/wp-content/uploads/minizeichen.svg');
$image->appendChild($imgAttr);

$imgAttr = $xml->createElement('title', 'Miniplan - Ministranten Sankt Georg Bensheim');
$image->appendChild($imgAttr);

$imgAttr = $xml->createElement('link', 'https://plan.ministranten-sankt-ge.org/files/');
$image->appendChild($imgAttr);




/** Aktuelle Zeit, falls time() in MESZ ist, muss 1 Stunde abgezogen werden */
$head = $xml->createElement('lastBuildDate', date("D, d M Y H:i:s O", time() - 3600));
$channel->appendChild($head);

/** Feed Einträge */
$filelist = scandir($config->getMiniplanLocalPath(), SCANDIR_SORT_DESCENDING);

$filelist = preg_grep($miniplan->getRegexPattern(), $filelist);

$filelist = $miniplan->sort($filelist);


foreach ($filelist as $entry) {
	$filename = $config->getMiniplanLocalPath().$entry;

	preg_match($miniplan->getRegexPattern(), $entry, $reg);

	$from = date("d.m.Y", strtotime($reg[1]));
	$to = date("d.m.Y", strtotime($reg[2]));
	$version = $reg[3];
	$plattform = $reg[4];

    $item = $xml->createElement('item');
    $channel->appendChild($item);


    $title = "Miniplan vom ".$from. " bis zum ".$to."";
    if ($version != 0) $title .= " v".$version;
    $data = $xml->createElement('title', $title);
    $item->appendChild($data);

	$description="";

	if ($plattform != "web") {
		$description .= "Bitte beachten: Es handelt sich hier um die ";
		if ($plattform =="") $description .= "Druckausgabe";
		else $description .= $plattform."  Variante";
		$description .= " des Miniplans.";
	}

    $description .= "&lt;a href=\"https://plan.ministranten-sankt-ge.org/view/".$entry."/\"&gt;Miniplan anzeigen&lt;/a&gt;";

    $data = $xml->createElement('description', $description);
    $item->appendChild($data);

    $data = $xml->createElement('link', "https://plan.ministranten-sankt-ge.org/view/".$entry."/");
    $item->appendChild($data);

    $data = $xml->createElement('pubDate', date("D, d M Y H:i:s O", filemtime($filename)));
    $item->appendChild($data);

    $data = $xml->createElement('guid', "https://plan.ministranten-sankt-ge.org/rss/2.0/".$entry.""); // unique ID of the entry
    $item->appendChild($data);

}

print $xml->saveXML();