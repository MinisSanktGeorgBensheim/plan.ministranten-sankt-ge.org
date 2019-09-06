<?php
header('Content-Type: text/plain; charset=utf-8');

ini_set('display_errors', true);
error_reporting(E_ALL);

require_once "../system/miniplan.php";
$miniplan = new miniplan();

require_once "../config/getConfig.php";
$config = new getConfig();

$filename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

if ($filename == "latest") {
    $filelist = scandir($config->getMiniplanLocalPath(), SCANDIR_SORT_DESCENDING);
    $filelist = preg_grep($miniplan->getRegexPattern(), $filelist);

    $filelist = $miniplan->sort($filelist);

    if (count($filelist) > 0) $filename = $filelist[0];
}

if ($filename == "current") {
    $filelist = scandir($config->getMiniplanLocalPath(), SCANDIR_SORT_DESCENDING);
    $filelist = preg_grep($miniplan->getRegexPattern(), $filelist);

    $filelist = $miniplan->sort($filelist);
    if (count($filelist) > 0) $filenameBefore = $filelist[0];
    foreach ($filelist as $entry) {
        preg_match($miniplan->getRegexPattern(), $entry, $reg);

        $toTS = strtotime($reg[2]);
        $to = date("d.m.Y", $toTS);

        if ($toTS < mktime(0, 0, 0, date("m") - 1, date("d"), date("Y") - 1)) {
            break;
        }

        if ($toTS + 20 * 60 * 60 <= time()) {
            $filename = $filenameBefore;
            break;
        }
        else $filenameBefore = $entry;
    }
}


preg_match($miniplan->getRegexPattern(), $filename, $reg);

if (count($reg)>0) { /** Filename is a valid Miniplan name */
    if (file_exists($config->getMiniplanLocalPath().$filename)) { /** File exists */

        if (isset($_GET["download"]) AND $_GET["download"]==true) {
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename=' . urlencode(basename($filename)));
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            ob_clean();
            flush();
            readfile($config->getMiniplanLocalPath().$filename);
            exit;
        }
        else {
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            readfile($config->getMiniplanLocalPath().$filename);
        }
    }
    else {
        header("HTTP/1.0 404 Not Found");

        echo "the file \"";
        echo $filename;
        echo "\" wasn't found.";
    }
}
else {
    header("HTTP/1.0 404 Not Found");
    echo "no valid Miniplan filename";
}
