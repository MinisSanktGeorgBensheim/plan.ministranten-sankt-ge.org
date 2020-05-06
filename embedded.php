<?php
/**
 * Created by PhpStorm.
 * User: Tom Herbers (dev@herbetom.de)
 * Date: 08.08.18
 * Time: 17:22
 *
 * This is for embedding the current Miniplan list in the WP Site.
 */

//if (!file_exists("vendor/autoload.php")) exit("Please use composer to install the necessary dependencies: <code>composer install;</code>");
//require_once __DIR__ . '/vendor/autoload.php';

//if (!file_exists(__DIR__."config/config.php")) exit("No config file found. You can create one by copying the file <code>config.example.php</code> and renaming it to <code>config.php</code>");
//if (!file_exists("config/config.php")) exit("No config file found. You can create one by copying the file <code>config.example.php</code> and renaming it to <code>config.php</code>");
//require_once "config/getConfig.php";
//$config = new getConfig();

$ssl = true;

//$configMP = new configMP();
//ini_set('display_errors', $config->isOutputError());
ini_set('display_errors', true);

require_once "system/miniplan.php";
$miniplan = new miniplan();

$html = '';

$miniplanList = $miniplan->getMiniplaene(true,mktime(0, 0, 0, date("m")-8, date("d"), date("Y")));

if ($miniplanList["current"] == "" OR count($miniplanList["current"])<=0) echo "<p><b>Es steht zur Zeit leider kein aktueller Miniplan zur Verfügung. Es wird in kürze ein neuer veröffentlicht werden.</b></p>";

foreach ((array) $miniplanList["current"] as $entry) {
    $html .= "<p><b><a href=\"".($ssl ? "https": "http")."://".$miniplan->getDomain()."/view/".$entry["file"]."\" target=\"_blank\">Miniplan vom ".$entry["from"]. " bis zum ".$entry["to"];
    if ($entry["version"] > 1) $html .= " <span class=\"badge badge-secondary\">v".$entry["version"]."</span>";
    if ($entry["plattform"] == "") $html .= " print";
    if ($entry["plattform"] != "web") $html .= " ".$entry["plattform"];
    $html .= "</a>";
    $html .= " (<a href='".($ssl ? "https": "http")."://".$miniplan->getDomain()."/files/".$entry["file"]."?download=1' target=\"_blank\">herunterladen</a>)\n";
    $html .= "</b></p>\n";
}
$html .= "<br>Archiv:\n";
foreach ($miniplanList["archive"] as $entry) {
    $html .= "<p><a href=\"".($ssl ? "https": "http")."://".$miniplan->getDomain()."/view/".$entry["file"]."\" target=\"_blank\">Miniplan vom ".$entry["from"]. " bis zum ".$entry["to"];
    if ($entry["version"] > 1) $html .= " <span class=\"badge badge-secondary\">v".$entry["version"]."</span>";
    if ($entry["plattform"] == "") $html .= " print";
    if ($entry["plattform"] != "web") $html .= " ".$entry["plattform"];
    $html .= "</a>";
    $html .= " (<a href='".($ssl ? "https": "http")."://".$miniplan->getDomain()."/files/".$entry["file"]."?download=1' target=\"_blank\">herunterladen</a>)\n";
    $html .= "</p>\n";
}

$html .= "<br>";

$html .= "<a href=\"".($ssl ? "https": "http")."://".$miniplan->getDomain()."/rss/2.0/\" title=\"Website Feed\" rel=\"nofollow, noindex\" target=\"_blank\"><img src=\"".($ssl ? "https": "http")."://".$miniplan->getDomain()."/img/rss-icon-black.png\"></a>";
$html .= " <a href=\"".($ssl ? "https": "http")."://".$miniplan->getDomain()."/rss/2.0/\" title=\"Website Feed\" rel=\"nofollow, noindex\" target=\"_blank\">RSS Feed</a>";
$html .= "<br><br>";

echo $html;

