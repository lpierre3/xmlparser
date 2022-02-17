<?php

require_once('inc/vendor/autoload.php');
require_once('inc/classes/XMLParser.php');
require_once('inc/classes/APIClient.php');


process();

function process() {
    $sanitizeOptions = array('url' => 'string');
    $optionLengths = array('url' => 2048); //max length of url to accept
    $apiClient = new APIClient(APIClient::POST, $sanitizeOptions, $optionLengths);

    try {
        $xmlParser = new XMLParser();
        $data = $xmlParser->parse($apiClient->url);
        $apiClient->sendSuccessResponse($data);
    } catch (Exception $e) {
        $apiClient->sendResponse(500, $e->getMessage());
    }
}