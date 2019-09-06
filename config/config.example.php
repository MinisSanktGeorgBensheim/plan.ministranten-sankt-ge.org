<?php
/**
 * Dies ist eine Beispiel Konfiguration. Einfach nach belieben anpassen.
 */

class config {
    protected $siteName = "Miniplan";
    protected $siteNameShort = "Miniplan";
    protected $navbarIcon = "Miniplan";
    protected $description = "Minipläne der Ministranten";
    protected $adminMail = "webmaster@example.com";

    protected $organization = "Ministranten Sankt Example";
    protected $organizationDomain = "https://plan.example.com";

    protected $domain = array("plan.ministranten-sankt-ge.org");

    protected $logLevel = 7;

    protected $colorPrimary = "#343a40";
//    protected $colorPrimary = "#b00";

    protected $dbHost = "";
    protected $dbName = "";
    protected $dbUser = "";
    protected $dbPass = "";

    protected $minify = false;

    protected $outputError = true;

    protected $siteBaseURL = "/";

    protected $miniplanLocalPath = "/var/www/ministranten/Dateien/Miniplan/";
}