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
        list($response_body, $response_code) =
        $this->_makeCurlRequest($method, $url, $params, $options);
        $response = $this->_interpretResponse($response_body, $response_code);
        return $response;
    }

    private function _interpretResponse($response_body, $response_code)
    {
        try {
            $response = json_decode($response_body);
        }
        catch (\Exception $e) {
            $message = "API returned invalid response body {$response_body} " .
                       "with HTTP response code {$response_code}";
            throw new \Exception($message);
        }

        // If response code is not within the "OK" range, throw error
        if ($response_code < 200 || $response_code >= 300) {
            $this->handleApiError($response_body, $response_code, $response);
        }

        return $response;
    }

    public function handleApiError($response_body, $response_code, $response)
    {
        if (!isset($response->error)) {
            $message = "API returned invalid response body {$response_body} " .
                   "with HTTP response code {$response_code}";
            throw new \Exception($message);
        }

        $error = $response->error->type;
        $message = $response->error->message;
        throw new \Exception("Error ({$error}): {$message}");
    }

    private function _makeCurlRequest($method, $url, $params = null, $options = null)
    {
        $curl = curl_init();

        switch ($method) {
            case 'post':
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                }
                break;

            case 'patch':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
                if ($params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                }
                break;

            case 'delete':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if ($params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                }
                break;

            case 'get':
                if ($params) {
                    $url = sprintf("%s?%s", $url, http_build_query($params));
                }
                break;

            default:
                throw new \Exception('Unrecognized HTTP method.');
        }

        // Auth with API key
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "{$this->_apiKey}:");

        // Set correct headers
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/vnd.blockscore+json;version=4'
        ));

        // Set user agent
        $clientVersion = BlockScore::$clientVersion;
        $user_agent = "blockscore-php/{$clientVersion} (https://github.com/BlockScore/blockscore-php)";
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response_body = curl_exec($curl);

        // Did Curl throw an error?
        if ($response_body === false) {
            $errno = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            throw new \Exception("Curl Error ({$errno}): {$message}");
        }

        $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return array($response_body, $response_code);
    }
}