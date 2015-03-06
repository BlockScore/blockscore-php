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
        $url = "{$this->_apiEndpoint}{$url}";

        $curl = curl_init();

        switch ($method)
        {
            case "post":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($params)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case "put":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($params)
                    $url = sprintf("%s?%s", $url, http_build_query($params));
        }

        // Auth with API key
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "{$this->_apiKey}:");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // Set correct headers
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/vnd.blockscore+json;version=4'
        ));

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }
}