<?php
/**
 * Created by PhpStorm.
 * User: Tom Herbers (dev@herbetom.de)
 * Date: 06.08.18
 * Time: 04:26
 */

class miniplan {

    private $regexPattern = "/(?<=Miniplan_)([0-9]{4}-[0-9]{2}-[0-9]{2})_bis_([0-9]{4}-[0-9]{2}-[0-9]{2})(?=_?v?([0-9]+)?_?([a-zA-Z0-9]+)?.pdf)/";
    private $miniplanPath = "/var/www/ministranten/Dateien/Miniplan/";

    private $domain = "plan.ministranten-sankt-ge.org";

    /**
     * @return string
     */
    public function getDomain(): string {
        return $this->domain;
    }

    /**
     * @return string
     */
    public function getMiniplanPath(): string
    {
        return $this->miniplanPath;
    }


    /**
     * @return string
     */
    public function getRegexPattern(): string {
        return $this->regexPattern;
    }

    private function cmp(string $a, string $b) :int {

        preg_match_all($this->getRegexPattern(), $a."\n".$b, $reg);

        /** Compare Date */
        if ($reg[0][0] != $reg[0][1])
            return $reg[0][0] > $reg[0][1] ? -1 : 1;

        /** Compare Plattform */
        if (!isset($reg[4][0]) OR $reg[4][0]=="") $reg[4][0]="";
        if (!isset($reg[4][1]) OR $reg[4][1]=="") $reg[4][1]="";
        if ($reg[4][0] != $reg[4][1])
            return $reg[4][0] > $reg[4][1] ? -1 : 1;

        /** Compare Version */
        if (!isset($reg[3][0]) OR $reg[3][0]=="") $reg[3][0]=0;
        if (!isset($reg[3][1]) OR $reg[3][1]=="") $reg[3][1]=0;
        if ($reg[3][0] != $reg[3][1])
            return $reg[3][0] > $reg[3][1] ? -1 : 1;

        return 0;
    }

    public function sort (array $filelist) :array {
        if (!usort($filelist, array($this,"cmp"))) echo "ERROR - wasn't able to sort";
        return $filelist;
    }

    function getMiniplaene($webOnly=false, int $onlyUntil=-1) {

        if ($onlyUntil<0) $onlyUntil = mktime(0, 0, 0, date("m")-1, date("d"), date("Y") -1);

        $filelist = scandir($this->getMiniplanPath(), SCANDIR_SORT_DESCENDING);
        $filelist = preg_grep($this->getRegexPattern(), $filelist);

        $filelist = $this->sort($filelist);

        foreach ($filelist as $entry) {
            $miniplan = $this->getMiniplanInfosFromName($entry);

            //nicht zu alte Pläne anzeigen
            if ($miniplan["toTS"] < $onlyUntil) break;

            // überspringen falls nicht Web Version
            if ($webOnly AND $miniplan["plattform"] != "web") continue;

            $miniplan["size"] = (filesize($this->getMiniplanPath()."".$entry)/1000);

            if ($miniplan["toTS"]+20*60*60 <= time()) $miniplanArchiv[]= $miniplan;
            else $miniplanCurrent[] = $miniplan;

        }
        return array("current"=>$miniplanCurrent,"archive"=>$miniplanArchiv);
    }

    public function getMiniplanInfosFromName($planName) {
        $miniplan = array();
        preg_match($this->getRegexPattern(), $planName, $reg);

        if(count($reg)>=3) {
            $miniplan["file"] = $planName;
            $miniplan["fromTS"] = strtotime($reg[1]);
            $miniplan["from"] = date("d.m.Y", $miniplan["fromTS"]);
            $miniplan["toTS"] = strtotime($reg[2]);
            $miniplan["to"] = date("d.m.Y", $miniplan["toTS"]);
            $miniplan["version"] = $reg[3];
            $miniplan["plattform"] = $reg[4];

            $miniplan["name"] = "Miniplan vom ".$miniplan["from"]. " bis zum ".$miniplan["to"];
            if ($miniplan["version"] > 1) $miniplan["name"] .= " v".$miniplan["version"]."";
            if ($miniplan["plattform"] == "") $miniplan["name"] .= " print";
            if ($miniplan["plattform"] != "web") $miniplan["name"] .= " ".$miniplan["plattform"];

            $miniplan["url"] = "/files/".$planName;
            $miniplan["urlView"] = "/view/".$planName;

            if ($miniplan["toTS"]+20*60*60 <= time()) $miniplan["current"]= false;
            else $miniplan["current"] = true;
            return $miniplan;
        }
        else return false;
    }
}