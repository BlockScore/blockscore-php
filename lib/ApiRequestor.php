<?php

namespace BlockScore;

class ApiRequestor
{

    private $_apiKey;
    private $_apiEndpoint;

    public function __construct($apiKey = null, $apiEndpoint = null)
    {
        $this->_apiKey = $apiKey;
        if ($apiEndpoint == null) {
            $apiEndpoint = BlockScore::$apiEndpoint;
        }
        $this->_apiEndpoint = $apiEndpoint;
    }

    public function execute($method, $url, $params = null, $options = null)
    {
        $full_url = "{$this->_apiEndpoint}{$url}";
        return $full_url;
    }
}