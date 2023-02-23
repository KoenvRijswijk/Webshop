<?php
class XmlGenerator
{
	protected $xml;
//=============================================================================
	public function createMultiRecXml(string $datasetname, string $recordsname, array $records) : DOMDocument
	{
		//create new DOMDocument
		$this->xml = new DOMDocument();
		//create first child items
		$rootNode = $this->xml->appendChild($this->xml->createElement($datasetname));
		foreach ($records as $record) 
		{
			if (! empty($record)) 
			{
				//add node child roos until final one
				$rootNode->appendChild($this->createRecordNode($recordsname, $record));
			}
		}
		return $this->xml;
	}
//=============================================================================
	public function createSingleRecXml(string $recordsname, array $record) : DOMDocument
	{
		$this->xml = new DOMDocument();
		//create one child
		$this->xml->appendChild($this->createRecordNode($recordsname, $record));
		return $this->xml;
	}
//=============================================================================
	protected function createRecordNode(string $nodename, array $record) :  DOMElement|false
	{
		$node = $this->xml->createElement($nodename);
		//loop trough record and add columns as children
		foreach ($record as $column => $value) 
		{
			$node->appendChild($this->xml->createElement($column,$value));
		}
		return $node;
	}
//=============================================================================
}	