<?php
/*
$input_array = array(
    'items' => array(
        array(
            'title' => 'Favorite Star Rating with jQuery',
            'link' => 'https://phppot.com/jquery/dynamic-star-rating-with-php-and-jquery/',
            'description' => 'Doing favorite star rating using jQuery Displays HTML stars.'
        ),
        array(
            'title' => 'PHP RSS Feed Read and List',
            'link' => 'https://phppot.com/php/php-simplexml-parser/',
            'description' => 'simplexml_load_file() function is used for reading data from XML.'
        )
    )
);

//create new DOMDocument
$xml = new DOMDocument();

//create first child items
$rootNode = $xml->appendChild($xml->createElement("items"));

//loop trough items 
foreach ($input_array['items'] as $item) {
    if (! empty($item)) {
         //add node child roos until final one
        $itemNode = $rootNode->appendChild($xml->createElement('roos'));
        //loop trough siblings of roos and add children
        foreach ($item as $k => $v) {
            $itemNode->appendChild($xml->createElement($k, $v));
        }
    }
}

//save the XML file
$xml->formatOutput = true;
$items_file_name = 'file_items_' . time() . '.xml';
$xml->save($items_file_name);

//downloading file
header('Content-Description: File Transfer');
header('Content-Type: application/xml');
header('Content-Disposition: attachment; filename=' . basename($items_file_name));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($items_file_name));
ob_clean();
flush();


readfile($items_file_name);
exec('rm ' . $items_file_name);
*/



//paste your XML file here
$xml = '
<?xml version="1.0" encoding="UTF-8"?>
<Parent>
<Child>
<Name>Roshini</Name>
<Age>5</Age>
</Child>
</Parent>';
// give the path of the Third party site
$url = "bierkreek.nl";
$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_MUTE, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
echo $output;
curl_close($ch);
