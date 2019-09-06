<?php
/**
 * Created by PhpStorm.
 * User: Tom Herbers (dev@herbetom.de)
 * Date: 05.08.18
 * Time: 16:39
 *
 *
 * Updates the file Dates of the Minipläne to the date when they were created based on the PDF metadata.
 */

include 'vendor/autoload.php';
header('Content-Type: text/plain; charset=utf-8');


$output = false;
echo "noting done, exit"; exit;

$counter = 0;
$filelist = scandir("../ministranten/Dateien/Miniplan/", SCANDIR_SORT_DESCENDING);
$filelist = array_diff($filelist, array('.', '..'));
foreach ($filelist as $entry) {

	$filename = "../ministranten/Dateien/Miniplan/".$entry;

	if (file_exists($filename)) {
		$parser = new \Smalot\PdfParser\Parser();
		$pdf    = $parser->parseFile($filename);
		$text   = $pdf->getDetails();

//	print_r($text);

		if(isset($text["Pages"]) AND $text["Pages"] > 0) {


			if (is_array($text["CreationDate"])) $text["CreationDate"] = $text["CreationDate"][0];
			if (is_array($text["Title"])) $text["Title"] = $text["Title"][0];
			if (is_array($text["ModDate"])) $text["ModDate"] = $text["ModDate"][0];


//		print_r($text["CreationDate"]);
			if ($output) echo $text["CreationDate"];

			$filetime = strtotime($text["CreationDate"]);
			if ($output) echo " - ". $filetime." - ". date("d.m.Y H:i:s", $filetime);
			if ($output) echo " - ";
			if ($output) echo $text["Title"];



			if (!touch($filename, $filetime)) {
				if ($output) echo ' Ein Fehler ist aufgetreten ...';
			}
			else $counter++;
		}
		else {
			if ($output) echo "Fehler. Nur ".$text["Pages"]." Seiten. -".$entry."-";
		}
	}
	else {
		if ($output) echo "Datei nicht gefunden.";
	}
	if ($output) echo "\n";
}

if (count($filelist) == 0) {
	echo "ERROR - No Files found";
}
else if ($counter>0 AND $counter == count($filelist)) {
	echo "OK";
}
else if ($counter>0 AND $counter != count($filelist)) {
	echo "OK - but ".(count($filelist) - $counter)." ERRORS";
}
else echo "ERROR";