<?php
/**
 * Created by PhpStorm.
 * User: Tom Herbers (dev@herbetom.de)
 * Date: 31.12.17
 * Time: 20:20
 */

class design {
    private $header = array();
	private $head = array();
	private $body = array();
	private $title = "";
	private $description ="";


	public function __construct() {

	}

	public function output(bool $return = false) {
		global $config;

		if ($this->title == "") $this->title = $config->getSiteName();
        $this->title = $this->title." | ".$config->getOrganization();

        if ($this->description == "") $this->description = $config->getDescription();

		self::addToHead(self::head(), 0, true);
        $html = self::frame(self::getHead(), self::getBody());
//        if ($config->isMinify()) $html = preg_replace(array('/ {2,}/', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'), array(' ',''), $html);

        if ($return) return $html;
        else {
            header(static::getHeader());
            echo $html;
            return true;
        }
	}

	private function frame($header = "", $body ="", $htmlTagAttribute = 'lang="de"') {
		$html = "<!DOCTYPE html>
<html ".$htmlTagAttribute.">
	<head>".$header."
	</head>
	<body>
		".$body."
	</body>
</html>";
		return $html;
	}

	private function head() {
	    global $config;
		$html = '
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
        <meta name="description" content="'.$this->description.'">
        <link rel="icon" type="image/svg+xml" href="/img/minilogo-circle.svg" sizes="any">
        <title>'.$this->title. '</title>
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png" />
        <link rel="icon" type="image/png" sizes="192x192" href="/img/android-chrome-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <link rel="mask-icon" href="/img/minilogo.svg" color="'.$config->getColorPrimary().'">
        <link rel="shortcut icon" href="/favicon.ico">
        <meta name="apple-mobile-web-app-title" content="' .$config->getSiteNameShort().'">
        <meta name="application-name" content="'.$config->getSiteNameShort().'">
        <meta name="theme-color" content="'.$config->getColorPrimary().'">
        <link rel="alternate" type="application/rss+xml" href="/rss/2.0/">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=0.5 maximum-scale=1.0">
	    <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="/vendor/fortawesome/font-awesome/css/all.min.css">
        <link rel="stylesheet" href="/style.css?v=1">
        <script src="/vendor/components/jquery/jquery.slim.min.js"></script>
        <script>
        // Initialize popover component
        $(function () {
          $(\'[data-toggle="popover"]\').popover({html:true})
        })
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>';
		return $html;
	}

	public function reloadFunctionJS(string $refreshURL, string $refreshHTMLelementID, int $interval=60) {
        $html = '<script>
            function makeHttpObject() {
              try {return new XMLHttpRequest();}
              catch (error) {}
              try {return new ActiveXObject("Msxml2.XMLHTTP");}
              catch (error) {}
              try {return new ActiveXObject("Microsoft.XMLHTTP");}
              catch (error) {}
            
              throw new Error("Could not create HTTP request object.");
            }
            
            function reload() {
                let request = makeHttpObject();
                request.open("GET", "' .$refreshURL.'", true);
                request.send(null);
                request.onreadystatechange = function() {
                  if (request.readyState === 4){
                      if (request.status === 200)
                          document.getElementById("'.$refreshHTMLelementID.'").innerHTML = request.responseText;
                      else console.log("HTTP Request Status response is "+request.status+". To succeed it must be 200.");
                  }
                };
            }
            setInterval(reload, '.($interval*1000).');
            </script>';
        return $html;
    }

	protected function addTabs(int $count, bool $newLineBefore=false) :string {
	    $returnTabs = "";
	    if ($newLineBefore) $returnTabs .= "\n\r";
	    for ($i=0; $i<$count; $i++) $returnTabs .="    ";
	    return $returnTabs;
    }

	public function addToHeader(string $header, int $numOfTabs=0, bool $ahead = false) {
	    $header = $this->addTabs($numOfTabs).$header;
	    if ($ahead) array_unshift($this->header, $header);
	    else $this->header[] = $header;
	}
	public function addToHead(string $head, int $numOfTabs=0, bool $ahead = false) {
        $head = $this->addTabs($numOfTabs).$head;
        if ($ahead) array_unshift($this->head, $head);
        else $this->head[] = $head;
	}
	public function addToBody(string $body, int $numOfTabs=0, bool $ahead = false) {
        $body = $this->addTabs($numOfTabs).$body;
        if ($ahead) array_unshift($this->body, $body);
        else $this->body[] = $body;
	}

	public function setHeader( string $header ) {
		$this->header = array();
		$this->addToHeader($header);
	}
    public function setHead( string $head ) {
        $this->head = array();
        $this->addToHead($head);
    }
	public function setBody( string $body ) {
        $this->body = array();
        $this->addToHead($body);
	}

	public function getHeader(): string {
		return implode("\n".$this->addTabs(1), $this->header)."\n";
	}
	public function getHead(): string {
		return implode("\n", $this->head)."\n";
	}
	public function getBody(): string {
		return implode("\n", $this->body);
	}

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

}