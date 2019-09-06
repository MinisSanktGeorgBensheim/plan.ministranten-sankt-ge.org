<?php

$system->header("json");

$array = array();
$array["name"] = $config->getSitename();
$array["short_name"] = $config->getSitenameShort();
$array["description"] = $config->getDescription();
$array["start_url"] = $config->getSiteBaseURL();
$array["scope"] = "/";
$array["display"] = "standalone";
$array["orientation"] = "any";
$array["lang"] = "de-DE";
$array["theme_color"] = $config->getColorPrimary();
$array["background_color"] = $config->getColorPrimary();
$icon1= array("src"=>"/img/android-chrome-192x192.png", "sizes"=>"192x192", "type"=>"image/png");
$icon2= array("src"=>"/img/android-chrome-512x512.png", "sizes"=>"512x512", "type"=>"image/png");
$array["icons"] = array($icon1, $icon2);

echo json_encode($array);