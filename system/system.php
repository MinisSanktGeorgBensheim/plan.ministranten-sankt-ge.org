<?php


class system {
    public function header ($header='html', $parameter='', bool $return = false) {
        global $config;

        $header = strtolower($header);
        if (is_numeric($header)) {
            if (($text = $this->httpStatusCodeString($header)) =="") $this->error("unknown numeric http status code: ".htmlentities($header), 3, true);
            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
            $headerString = $protocol . ' ' . $header . ' ' . $text;
        }
        // Dateityp festlegen
        elseif ($header == "html") $headerString = 'Content-Type: text/html; charset=UTF-8';
        elseif ($header == "text") $headerString = 'Content-Type: text/plain; charset=utf-8';
        elseif ($header == "txt") $headerString = 'Content-Type: text/plain; charset=utf-8';
        elseif ($header == "xml") $headerString = 'Content-Type: application/xml; charset=utf-8';
        elseif ($header == "json") $headerString = 'Content-Type: application/json; charset=utf-8';
        elseif ($header == "png") $headerString = 'Content-Type: image/png';
        elseif ($header == "jpg") $headerString = 'Content-Type: image/jpeg';
        elseif ($header == "gif") $headerString = 'Content-Type: image/gif';
        elseif ($header == "pdf") $headerString = 'Content-type: application/pdf';
        // Download anbieten
        elseif ($header == "download") $headerString = 'Content-Disposition: attachment; filename="'.$parameter.'"';
        // Umleitung
        elseif ($header == "location") $headerString = 'Location: '.$parameter.'';
        // Lokaler-API-Zugriff
        elseif ($header == "allow_local") $headerString = 'Access-Control-Allow-Origin: *';
        else {
            $this->error("unknown http status code: ".htmlentities($header), 3, true);
        }

        if ($header == "401") header("WWW-Authenticate: Basic none");

        header($headerString);
    }

    private function httpStatusCodeString(int $code):string {
        switch ($code) {
            case 100: $text = 'Continue'; break;
            case 101: $text = 'Switching Protocols'; break;
            case 200: $text = 'OK'; break;
            case 201: $text = 'Created'; break;
            case 202: $text = 'Accepted'; break;
            case 203: $text = 'Non-Authoritative Information'; break;
            case 204: $text = 'No Content'; break;
            case 205: $text = 'Reset Content'; break;
            case 206: $text = 'Partial Content'; break;
            case 300: $text = 'Multiple Choices'; break;
            case 301: $text = 'Moved Permanently'; break;
            case 302: $text = 'Moved Temporarily'; break;
            case 303: $text = 'See Other'; break;
            case 304: $text = 'Not Modified'; break;
            case 305: $text = 'Use Proxy'; break;
            case 400: $text = 'Bad Request'; break;
            case 401: $text = 'Unauthorized'; break;
            case 402: $text = 'Payment Required'; break;
            case 403: $text = 'Forbidden'; break;
            case 404: $text = 'Not Found'; break;
            case 405: $text = 'Method Not Allowed'; break;
            case 406: $text = 'Not Acceptable'; break;
            case 407: $text = 'Proxy Authentication Required'; break;
            case 408: $text = 'Request Time-out'; break;
            case 409: $text = 'Conflict'; break;
            case 410: $text = 'Gone'; break;
            case 411: $text = 'Length Required'; break;
            case 412: $text = 'Precondition Failed'; break;
            case 413: $text = 'Request Entity Too Large'; break;
            case 414: $text = 'Request-URI Too Large'; break;
            case 415: $text = 'Unsupported Media Type'; break;
            case 500: $text = 'Internal Server Error'; break;
            case 501: $text = 'Not Implemented'; break;
            case 502: $text = 'Bad Gateway'; break;
            case 503: $text = 'Service Unavailable'; break;
            case 504: $text = 'Gateway Time-out'; break;
            case 505: $text = 'HTTP Version not supported'; break;
            default:$text = ""; break;
        }
        return $text;
    }

    public function error (string $message, int $level, bool $interrupt = true, bool $output = true, int $httpErrorCode = 500) {
        global $config;

        if (isset(debug_backtrace()[0]['file']) AND isset(debug_backtrace()[0]['line'])) $fileAndLine = debug_backtrace()[0]['file'].":".debug_backtrace()[0]['line'];
        else $fileAndLine = "";

        if (isset(debug_backtrace()[1]['class']) AND isset(debug_backtrace()[1]['function'])) $classAndFunction = debug_backtrace()[1]['class']."->".debug_backtrace()[1]['function']."(\"<i>".implode("</i>\", \"<i>",debug_backtrace()[1]['args'])."</i>\")";
        else $classAndFunction = "";

        $errorMsg = $config->getLogLevelString($level).": ".$fileAndLine." ";
        if ($classAndFunction!="") $errorMsg .= "[".$classAndFunction."]";
        $errorMsg .= " the following was thrown: ";
        $errorMsg .= "<pre>".$message."</pre>";

        if ($output) {
            $this->header($httpErrorCode);
            echo "<h1>".$httpErrorCode. " - ".$this->httpStatusCodeString($httpErrorCode)."</h1>";
            if ($config->isOutputError()) echo $errorMsg."\r\n";
            else echo "Es ist ein Fehler aufgetreten. Bitte kontaktieren sie den Admin unter <a href=\"mailto:".$config->getAdminMail()."\">".$config->getAdminMail()."</a>. Vielen Dank!";
        }

        error_log($errorMsg);
        if ($interrupt) exit;
    }
}