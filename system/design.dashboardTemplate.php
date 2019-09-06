<?php
/**
 * Created by PhpStorm.
 * User: Tom Herbers (dev@herbetom.de)
 * Date: 01.01.18
 * Time: 15:44
 */

class dashboardTemplate extends design {
    private $body ="";
    private $navbar = "";
    private $containerFull = false;

	public function createNavbar (array $navbarItems = array()) {
		global $config;

		$html = '<nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color: '.$config->getColorPrimary().';">';
		$html .= '
            <a class="navbar-brand" href="/"><img src="/img/minilogo-circle-noShadow.svg" width="30" height="30" class="d-inline-block align-top" alt="">  '.$config->getSiteName().'</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsExampleDefault">';
		$html .= '
		        <ul class="navbar-nav mr-auto">';
		foreach ($navbarItems as $item) {

			if(!isset($item['name']) OR !isset($item['url'])) {
				error("dashboardTemplate->createNavbar", "name or url are not set");
				break;
			};

			if (isset($item['newTab']) AND $item['newTab'] == true) $newTab = " target=\"_blank\"";
			else $newTab = "";

			if (isset($item['current']) AND $item['current'] == true) {
				$html .= '
		            <li class="nav-item active">
		                <a class="nav-link" href="'.$item['url'].'"'.$newTab.'>'.$item['name'].' <span class="sr-only">(current)</span></a>
		            </li>';
			}
			else {
				$html .= '
		            <li class="nav-item">
		                <a class="nav-link" href="'.$item['url'].'"'.$newTab.'>'.$item['name'].'</a>
		            </li>';

			}

		}
		$html .= "\n</ul>";
//		$html .= '
//		        <form class="form-inline mt-2 mt-md-0" action="index.php" method="post">
//		            <input class="form-control mr-sm-2" type="text" placeholder="Nutzername">
//		            <input class="form-control mr-sm-2" type="password" placeholder="Passwort">
//		            <!--<input class="form-control mr-sm-2" type="text" placeholder="Search">-->
//		            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Anmelden</button>
//		        </form>';

        $html .= $this->navbar."\n";
        $html .= "</div>";
		$html .= '</nav>';

		$this->navbar = $html;
	}

    private function frame($content = "") {
	    if ($this->containerFull) $html = '<main role="main" class="containerFull vh-100 vv-100">';
	    else $html = '<main role="main" class="container"><div class="starter-template">';
	    $html .= $content;
	    $html .= "</div></main>";
	    return $html;
    }

	public function output(bool $return = false) {
	    parent::addToBody($this->navbar);
	    parent::addToBody(self::frame(self::getBody()));
        parent::output($return);
	}


    public function addToBody(string $body, int $numOfTabs=0, bool $ahead = false) {
        if ($ahead) $this->body = $body.$this->body;
        else $this->body = $this->body.$body;
    }

    public function setBody( string $body ) {
        $this->body = $body;
    }

    public function getBody(): string {
        return $this->body;
    }

    public function addToNavbar(string $html) {
        $this->navbar .= $html;
    }

    /**
     * @param bool $containerFull
     */
    public function setContainerFull(bool $containerFull)
    {
        $this->containerFull = $containerFull;
    }
}