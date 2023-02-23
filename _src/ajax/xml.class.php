<?php 
################################################################################
# Author        : KvR.Development 
# Date          : 12/12/2022
# Project       : Educom Learnings 
# Class         : Ajax XML Handler Base Class
################################################################################
require_once SRC."ajax\ajax.class.php";
require_once SRC."Tools\XMLGenerator.php";
abstract class XMLHandler extends AjaxHandler
{
//=======================================================
    public function execute()
    {
        $data = $this->getData(); //--> Dit is je basis voor request, get data is een extend functie op deze class
        //tools::dump(sizeof($data));
        $xmlGen = new XmlGenerator();
        //test for multiple array or single
        if (count($data) == count($data, COUNT_RECURSIVE)) 
        {
                $d = $xmlGen->createSingleRecXml('roos', $data);
        }
        else
        {
                $d = $xmlGen->createMultiRecXml('items', 'roos', $data);
        }

        if ($d === false)
        {	
            $msg = "XML PARSE ERROR - ";
           
            //Debug::_dump("JSON RAW DATA ", $data);
            throw new Exception($msg);
        }
        else
        {	
                header('HTTP/1.1 200 OK');
                header('Content-type: text/xml');
                print $d->saveXML();
        }
        
    }
//=======================================================
	abstract protected function getData();
}