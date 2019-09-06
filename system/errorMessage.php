<?php
/**
 * Created by PhpStorm.
 * User: Tom Herbers
 * Date: 27.08.2017
 * Time: 19:07
 */

class errorMessage {

    private $errorMessage = array();
    private $infoMessage = array();
    private $warningMessage = array();
    private $successMessage = array();
    private $primaryMessage = array();

	function __construct() {


	}

	public function addErrorMessage(string $message, string $function = "", string $heading = "", bool $visible = true, string $id = "") {
	    $this->errorMessage[] = array("function" =>$function, "message"=>$message, "heading"=>$heading, "visible" => $visible, "id" => $id);
    }

    public function addInfoMessage(string $message, string $function = "", string $heading = "", bool $visible = true, string $id = "") {
	    $this->infoMessage[] = array("function" =>$function, "message"=>$message, "heading"=>$heading, "visible" => $visible, "id" => $id);
    }

    public function addWarningMessage(string $message, string $function = "", string $heading = "", bool $visible = true, string $id = "") {
	    $this->warningMessage[] = array("function" =>$function, "message"=>$message, "heading"=>$heading, "visible" => $visible, "id" => $id);
    }

    public function addSuccessMessage(string $message, string $function = "", string $heading = "", bool $visible = true, string $id = "") {
	    $this->successMessage[] = array("function" =>$function, "message"=>$message, "heading"=>$heading, "visible" => $visible, "id" => $id);
    }

	public function addMessage(string $message, string $function = "", string $heading = "", bool $visible = true, string $id = "") {
		$this->primaryMessage[] = array("function" =>$function, "message"=>$message, "heading"=>$heading, "visible" => $visible, "id" => $id);
	}

    public function getErrorMessagesHTML($removeAfterOutput = true) :string {
	    $html = "";
	    foreach ($this->errorMessage AS $message) {
	        if ($message['visible'] == true) $visible = "";
	        else $visible = "display: none;";

	        if ($message['id'] != "") $id = ' id="'.$message['id'].'"';
	        else $id = "";

            $html .= '<div class="alert alert-danger" role="alert" style="'.$visible.'" '.$id.'>';
		    if ($message['heading'] != "") $html .= '<h4 class="alert-heading">'.$message['heading'].'</h4><p>';
		    if ($message['function'] != "") $html .= '<strong>'.$message['function'].':</strong> ';
            $html .= $message["message"];
		    if ($message['heading'] != "") $html .= '</p>';
		    $html .= '</div>';

	    }
        if ($removeAfterOutput) $this->errorMessage = array();
        return $html;
    }

    public function getInfoMessagesHTML($removeAfterOutput = true) :string {
	    $html = "";
	    foreach ($this->infoMessage AS $message) {
            if ($message['visible'] == true) $visible = "";
            else $visible = "display: none;";

            if ($message['id'] != "") $id = ' id="'.$message['id'].'"';
            else $id = "";

            $html .= '<div class="alert alert-secondary" role="alert" style="'.$visible.'" '.$id.'>';
		    if ($message['heading'] != "") $html .= '<h4 class="alert-heading">'.$message['heading'].'</h4><p>';
		    if ($message['function'] != "") $html .= '<strong>'.$message['function'].':</strong> ';
		    $html .= $message["message"];
		    if ($message['heading'] != "") $html .= '</p>';
		    $html .= '</div>';
        }
        if ($removeAfterOutput) $this->infoMessage = array();
        return $html;
    }

    public function getWarningMessagesHTML($removeAfterOutput = true) :string {
	    $html = "";
	    foreach ($this->warningMessage AS $message) {
            if ($message['visible'] == true) $visible = "";
            else $visible = "display: none;";

            if ($message['id'] != "") $id = ' id="'.$message['id'].'"';
            else $id = "";

            $html .= '<div class="alert alert-warning" role="alert" style="'.$visible.'" '.$id.'>';
		    if ($message['heading'] != "") $html .= '<h4 class="alert-heading">'.$message['heading'].'</h4><p>';
		    if ($message['function'] != "") $html .= '<strong>'.$message['function'].':</strong> ';
		    $html .= $message["message"];
		    if ($message['heading'] != "") $html .= '</p>';
		    $html .= '</div>';
        }
        if ($removeAfterOutput) $this->warningMessage = array();
        return $html;
    }

    public function getSuccessMessagesHTML($removeAfterOutput = true) :string {
	    $html = "";
	    foreach ($this->successMessage AS $message) {
            if ($message['visible'] == true) $visible = "";
            else $visible = "display: none;";

            if ($message['id'] != "") $id = ' id="'.$message['id'].'"';
            else $id = "";

            $html .= '<div class="alert alert-success" role="alert" style="'.$visible.'" '.$id.'>';
		    if ($message['heading'] != "") $html .= '<h4 class="alert-heading">'.$message['heading'].'</h4><p>';
		    if ($message['function'] != "") $html .= '<strong>'.$message['function'].':</strong> ';
		    $html .= $message["message"];
		    if ($message['heading'] != "") $html .= '</p>';
		    $html .= '</div>';
        }
        if ($removeAfterOutput) $this->successMessage = array();
        return $html;
    }

	public function getPrimaryMessagesHTML($removeAfterOutput = true) :string {
		$html = "";
		foreach ($this->primaryMessage AS $message) {
            if ($message['visible'] == true) $visible = "";
            else $visible = "display: none;";

            if ($message['id'] != "") $id = ' id="'.$message['id'].'"';
            else $id = "";

			$html .= '<div class="alert alert-primary" role="alert" style="'.$visible.'" '.$id.'>';
			if ($message['heading'] != "") $html .= '<h4 class="alert-heading">'.$message['heading'].'</h4><p>';
			if ($message['function'] != "") $html .= '<strong>'.$message['function'].':</strong> ';
			$html .= $message["message"];
			if ($message['heading'] != "") $html .= '</p>';
			$html .= '</div>';
		}
		if ($removeAfterOutput) $this->primaryMessage = array();
		return $html;
	}

    public function getMessagesHTML($removeAfterOutput = true) :string {
	    $html = "";

	    $html .= $this->getErrorMessagesHTML($removeAfterOutput);
	    $html .= $this->getWarningMessagesHTML($removeAfterOutput);
        $html .= $this->getSuccessMessagesHTML($removeAfterOutput);
	    $html .= $this->getPrimaryMessagesHTML($removeAfterOutput);
	    $html .= $this->getInfoMessagesHTML($removeAfterOutput);


        return $html."<br>";
    }


	public function getMessages() :array {
		$array = array();

		$array["errorMessage"] = $this->errorMessage;
		$array["warningMessage"] = $this->warningMessage;
		$array["successMessage"] = $this->successMessage;
		$array["infoMessage"] = $this->infoMessage;
		return $array;

//		return array_push(
//			array_unshift($this->errorMessage, array("type" => "errorMessage")),
//			array_unshift($this->warningMessage, array("type" => "warningMessage")),
//			array_unshift($this->successMessage, array("type" => "successMessage")),
//			array_unshift($this->infoMessage, array("type" => "infoMessage"))
//		);
	}


}