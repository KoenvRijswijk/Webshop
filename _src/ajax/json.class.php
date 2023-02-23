<?php 
################################################################################
# Author        : Geert - Mankind
# Date          : 12/12/2022
# Project       : Educom Learnings 
# Class         : Ajax JSON Handler Base Class
################################################################################
require_once SRC."ajax\ajax.class.php";
abstract class JSONHandler extends AjaxHandler
{
//=======================================================
    public function execute()
    {
        $data = $this->getData(); //--> Dit is je basis voor request, get data is een extend functie op deze class
        $d = json_encode($data);	
        if ($d === false)
        {	
            $msg = "JSON PARSE ERROR - ";
            switch (json_last_error()) 
            {
                case JSON_ERROR_NONE:
                        $msg .= 'No errors';
                        break;
                case JSON_ERROR_DEPTH:
                        $msg .= 'Maximum stack depth exceeded';
                        break;
                case JSON_ERROR_STATE_MISMATCH:
                        $msg .= 'Underflow or the modes mismatch';
                        break;
                case JSON_ERROR_CTRL_CHAR:
                        $msg .= 'Unexpected control character found';
                        break;
                case JSON_ERROR_SYNTAX:
                        $msg .= 'Syntax error, malformed JSON';
                        break;
                case JSON_ERROR_UTF8:
                        $msg .= 'Malformed UTF-8 characters, possibly incorrectly encoded';
                        break;
                default:
                        $msg .= 'Unknown error';
                        break;
            }			
            //Debug::_dump("JSON RAW DATA ", $data);
            throw new Exception($msg);
        }
        else
        {	
                header('HTTP/1.1 200 OK');
                header("Content-type: application/json charset=utf-8"); // 9-1-2020 utf8 toegevoegd!
                echo $d;	
        }
    }
//=======================================================
	abstract protected function getData();
}