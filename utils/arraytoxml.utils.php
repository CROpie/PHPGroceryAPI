<?php
function arrayToXmlString(array $array) {
    $xmlRoot = new SimpleXMLElement("<root/>");
    arrayToXml($array, $xmlRoot);
    $xmlString = $xmlRoot->asXML();
    $debug = "variable";
    return $xmlString;
}

function arrayToXml(array $array, SimpleXMLElement $xml) {
    foreach ($array as $key => $value) {
        // if the key is numeric, create a default element name
        if (is_numeric($key)) {
            $key = "item";
        }

        // if value is array, recursively call this function
        // otherwise, add it as a child element
        if (is_array($value)) {
            $childXml = $xml->addChild($key);
            arrayToXml($value, $childXml);
        } else {
            $xml->addChild($key, htmlspecialchars($value));
        }
    }
}