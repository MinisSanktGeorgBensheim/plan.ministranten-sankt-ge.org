<?php


class pdfViewer {
    public function output($url) {
        global $dashboard;
        global $miniplan;
        global $config;
        global $system;

        $dashboard->addToNavbar("<a href=\"/files/".$url."?download=1\"><button type=\"button\" class=\"btn btn-outline-secondary\"\">Download</button></a>");

        $notFound = false;
        $html = "";

        $miniplanInfos = $miniplan->getMiniplanInfosFromName($url);

        if (!empty($miniplanInfos)) {
            $dashboard->setTitle($miniplanInfos["name"]);
        }
        else if ($url=="current") {
            $dashboard->setTitle("aktueller Miniplan");
//            $dashboard->setDescription("der aktuelle Miniplan der Ministranten St. Georg Bensheim");
        }
        else if ($url=="latest") {
            $dashboard->setTitle("neuester Miniplan");
//            $dashboard->setDescription("der neueste Miniplan der Ministranten St. Georg Bensheim");
        }
        else if ($url=="" OR !file_exists($miniplan->getMiniplanPath()."$url.")) {
            $notFound = true;
        }

        if (!$notFound) {
            /** Miniplan gefunden - zeige diesen */
            $html .= '<iframe id="pdfFrame" style="width:100%;height:100%;display:block;position:absolute;top:0;z-index:1041;left:0; border:0;" src="/pdfjs/web/viewer.html?file=/files/'.$url.'" sandbox="allow-scripts allow-same-origin allow-popups allow-modals allow-top-navigation" allowfullscreen="true">
                    <br>
                    <br>
                    <br>
                    lade Miniplan. Falls nicht erfolgreich gibt es <a href="/files/'.$url.'">hier</a> das PDF.
                  </iframe>';
        }
        else {
            /** Keinen Miniplan gefunden - zeige Fehlermeldung */
//            $system->
            $system->header(404);
            $html .= "<div class='container'><h1>﻿404 - Nicht gefunden</h1><br><br>";
            $html .= "<p>Dieser Miniplan konnte leider nicht gefunden werden.</p>";
            $html .= "<p><a href='".$config->getSiteBaseURL()."'>zur Startseite</a></p>";
            $html .= "</div>";
        }
        return $html;
    }
}