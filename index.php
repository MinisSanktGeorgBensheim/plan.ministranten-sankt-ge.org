<?php
/**
 * Created by PhpStorm.
 * User: Tom Herbers (dev@herbetom.de)
 * Date: 01.01.18
 * Time: 14:00
 */
//ini_set('display_errors', false);
date_default_timezone_set('Europe/Berlin');
setlocale(LC_ALL, 'de_DE');

if (!file_exists("vendor/autoload.php")) exit("Please use composer to install the necessary dependencies: <code>composer install;</code>");
require_once __DIR__ . '/vendor/autoload.php';

if (!file_exists("config/config.php")) exit("No config file found. You can create one by copying the file <code>config.example.php</code> and renaming it to <code>config.php</code>");
require_once "config/getConfig.php";
$config = new getConfig();

ini_set('display_errors', $config->isOutputError());

require_once "system/system.php";
$system = new system();
require_once "system/menu.php";
$menu = new menu();

require_once "system/errorMessage.php";
require_once "system/design.php";
require_once "system/design.dashboardTemplate.php";
$design = new design();
$dashboard = new dashboardTemplate();
$errorMessage = new errorMessage();

$navbar =array();

$navbar[] = array("name" =>"Home", "url" => "/");
$navbar[] = array("name" =>"Miniplan", "url" => "/view/current");
$navbar[] = array("name" =>"FAQ", "url" => "/faq/");
$navbar[] = array("name" =>"RSS Feed", "url" => "/rss/2.0/", "newTab"=>true);
$navbar[] = array("name" =>"|", "url" => "#");
$navbar[] = array("name" =>"Datenschutz", "url" => "https://ministranten-sankt-ge.org/datenschutz/", "newTab"=>true);
$navbar[] = array("name" =>"Impressum", "url" => "https://ministranten-sankt-ge.org/impressum/", "newTab"=>true);

require_once "system/miniplan.php";
$miniplan = new miniplan();

function miniplanListenHTML () {
    global $errorMessage;
    global $miniplan;
    $html ="";
    $miniplanList = $miniplan->getMiniplaene(true);

//    $html .= "<br><h3>Aktuell</h3>\n";
    $html .= "<br>\n";

    foreach ($miniplanList["current"] as $entry) {
        $html .= "<p><b><a href=\"/view/".$entry["file"]."/\">Miniplan vom ".$entry["from"]. " bis zum ".$entry["to"];
        if ($entry["version"] > 1) $html .= " <span class=\"badge badge-primary\">v".$entry["version"]."</span>";
        if ($entry["plattform"] == "") $html .= " print";
        if ($entry["plattform"] != "web") $html .= " ".$entry["plattform"];
        $html .= "</a></b> (<a href='/files/".$entry["file"]."?download=1'>herunterladen</a>)\n";
        $html .= "</p>\n";
    }
    $html .= "<br><br><h3>Archiv</h3>\n";
    foreach ($miniplanList["archive"] as $entry) {
        $html .= "<p><a class=\"text-secondary\" href=\"/view/".$entry["file"]."/\">Miniplan vom ".$entry["from"]. " bis zum ".$entry["to"];
        if ($entry["version"] > 1) $html .= " <span class=\"badge badge-secondary\">v".$entry["version"]."</span>";
        if ($entry["plattform"] == "") $html .= " print";
        if ($entry["plattform"] != "web") $html .= " ".$entry["plattform"];
        $html .= "</a> (<a class=\"text-secondary\" href='/files/".$entry["file"]."?download=1'>herunterladen</a>)\n";
        $html .= "</p>\n";
    }

    $html .= '
    <br>
    <a href="/rss/2.0/" title="Website Feed" rel="nofollow, noindex" target="_blank"><img src="img/rss-icon-black.png"> RSS Feed</a>
    <br>
    <br>';
    return $html;
}


$url = $menu->urlExplode($_SERVER["REQUEST_URI"]);

if (count($url)<1) array_shift($url);

if (isset($url[0])) {
    $url[0] = strtok($url[0], "?");
    switch (strtolower($url[0])) {
        case "view":
            require_once "system/pdfViewer.php";
            $pdfViewer = new pdfViewer();
            if (!array_key_exists(1, $url)) $url[1] = "";
            $content = $pdfViewer->output($url[1]);
            $dashboard->setContainerFull(true);
            $dashboard->addToBody($content);
            break;
        case "faq":
            $dashboard->setTitle("Miniplan FAQ");
            $dashboard->addToBody("<div class='container'><h1>FAQ</h1><br><br>
            <p><b>Wo finde ich den jeweils aktuellen Miniplan?</b><br>
            Den jeweils aktuellen Miniplan gibt es immer unter <a href='/view/current'>diesem</a> Link.</p>
            <p><b>Wo finde ich den neuesten Miniplan?</b><br>
            Den jeweils neuesten Miniplan gibt es immer unter <a href='/view/latest'>diesem</a> Link.</p>
            <p><b>Was macht der Planmacher?</b><br>
            Der Planmacher erstellt den Miniplan in welchem er die Ministranten für die Gottesdienste einteilt.</p>
            <p><b>Wie kann ich den Planmacher kontaktieren?</b><br>
            Die Telefonnummer des aktuellen Planmachers steht vorne auf dem Miniplan. <br>Außerdem gibt es die E-Mail Adresse <a href='mailto:planmacher@ministranten-sankt-ge.org'>planmacher@ministranten-sankt-ge.org</a>.</p>
            <p><b>Wie kann ich immer den aktuellen Plan per Mail zugeschickt bekommen?</b><br>
            Du kannst den Mail-Verteiler abonnieren. Das Formular um dies zu tun findet sich <a href='https://mailer.ministranten-sankt-ge.org/?p=subscribe&id=2' target='_blank'>hier</a>.</p>
            <p><b>Wie kann ich die Minipläne per RSS abonnieren?</b><br>
            Füge einfach <a href='https://plan.ministranten-sankt-ge.org/rss/2.0/'>diesen</a> Link in deinem favorisierten RSS Reader ein.</p>
            <br><br>
            ");
            $dashboard->addToBody("");
            break;
        case "gottesdienste":
            require_once "system/gottesdienste.php";
            $gottesdienste = new gottesdienste();
            $dashboard->setTitle("Gottesdienste");
            $dashboard->addToBody("<div class='container'><h1>Gottesdienste</h1><br><br>");
            $dashboard->addToBody($gottesdienste->output());
            $dashboard->addToBody("");
            break;
        case "manifest.json":
            require_once "system/staticFiles/manifest.json.php";
            exit;
            break;
        case "robots.txt":
            require_once "system/staticFiles/robots.txt.php";
            exit;
            break;
        case "":
            $dashboard->addToBody("<div class='container'><h1 class=\"display-1\">".$config->getSiteName()."</h1><h2>".$config->getOrganization()."</h2><br><br>");
            $dashboard->addToBody(miniplanListenHTML());
            $dashboard->addToBody("</div>");
            break;
        default:
            $system->header(404);

            $dashboard->addToBody("<div class='container'><h1>﻿404 - Nicht gefunden</h1><br><br>");
            $dashboard->addToBody("<p>Diese Seite konnte leider nicht gefunden werden.</p>");
            $dashboard->addToBody("<p><a href='".$config->getSiteBaseURL()."'>zur Startseite</a></p>");
            $dashboard->addToBody("</div>");

    }
}
$dashboard->createNavbar($menu->navbarAddCurrent($url, $navbar));
$dashboard->output();