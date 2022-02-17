<?php

class XMLParser
{

    function __construct() {
    }


    function parse(string $url): array {
        $data = array();
        $root = array();

        try {
            $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
            $xml = file_get_contents($url, false, $context);
            $xmlData = new SimpleXMLElement($xml, LIBXML_NOCDATA | LIBXML_PARSEHUGE);
            $rootNode = null;

            $children = $xmlData->children();
            foreach ($children as $child) {
                $rootNode = $child->getName();

                if (false !== $nextChildren = $child->children()) {
                    foreach ($nextChildren as $nodeName => $nodeValue) {
                        $data[$nodeName] = str_replace(array('<![CDATA[', ']]>'), array('', ''), $nodeValue); //extract text from cdata
                    }
                }
                break; //only return the first node
            }
            $root[$rootNode] = $data;

        } catch (Exception $e) {
            $root['error'] = $e->getMessage();
        }

        return $root;
    }

}