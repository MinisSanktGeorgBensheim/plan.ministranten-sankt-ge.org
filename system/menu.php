<?php
/**
 * Created by PhpStorm.
 * User: Tom Herbers (dev@herbetom.de)
 * Date: 03.01.18
 * Time: 15:04
 */

class menu {
    public function urlExplode(string $url) :array {
        if($url=="") return array(false);
        $url = explode("/", $url);
        if (isset($url[0])) {
            if ($url[0] == "") {
                array_shift($url);
            }
        }
        return $url;
    }

    public function navbarAddCurrent($url, $navbarArray) {
        foreach ($navbarArray AS $key => $item) {
            if (static::urlExplode($item["url"])[0]==$url[0]) $navbarArray[$key]["current"] = true;
        }
        return $navbarArray;
    }
}