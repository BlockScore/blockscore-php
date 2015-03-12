<?php

namespace BlockScore;

class ApiRequestor
{
    // @var string The API key to use for requests.
    private $_apiKey;

    // @var string The API endpoint to use for requests.
    private $_apiEndpoint;

    /**
     * @param string|null $apiKey The API key to use for requests.
     * @param string|null $apiEndpoint The API endpoint to use for requests.
     */
    public function __construct($apiKey = null, $apiEndpoint = null)
    {
        $this->_apiKey = $apiKey;
        if ($apiEndpoint == null) {
            $apiEndpoint = BlockScore::$apiEndpoint;
        }
        $this->_apiEndpoint = $apiEndpoint;
    }

    /**
     * @param string $method The HTTP method to use.
     * @param string $url The URL to use.
     * @param array|string|null $params The parameters to use for the request.
     * @param array|null $options The options to use for the response.
     *
     * @return JSON The response from the BlockScore API.
     */
    public function execute($method, $url, $params = null, $options = null)
    {
        $url = "{$this->_apiEndpoint}{$url}";
        if ($options != null && !is_array($options)) {
            throw new Util\Exception("Invalid format for options. Options must be an array. Attemped options: {$options}.", 'invalid_options_error');
        }
        list($response_body, $response_code) =
        $this->_makeCurlRequest($method, $url, $params, $options);
        $response = $this->_interpretResponse($response_body, $response_code);
        return $response;
    }

    /**
     * @param string $response_body The body of the response from the BlockScore API.
     * @param string $response_code The code of the response from the BlockScore API.
     *
     * @return JSON The response from the BlockScore API.
     */
    private function _interpretResponse($response_body, $response_code)
    {
        try {
            $response = json_decode($response_body);
        }
        catch (Util\Exception $e) {
            $message = "API returned invalid response body {$response_body} " .
                       "with HTTP response code {$response_code}.";
            throw new Util\Exception($message, 'invalid_response_body', $response_code);
        }

        // If response code is not within the "OK" range, throw error
        if ($response_code < 200 || $response_code >= 300) {
            $this->handleApiError($response_body, $response_code, $response);
        }

        return $response;
    }

    /**
     * @param string $response_body The body of the response from the BlockScore API.
     * @param string $response_code The code of the response from the BlockScore API.
     * @param JSON $response The response in a JSON object.
     *
     */
    public function handleApiError($response_body, $response_code, $response)
    {
        if (!isset($response->error)) {
            $message = "API returned invalid response body {$response_body} " .
                   "with HTTP response code {$response_code}.";
            throw new Util\Exception($message, 'invalid_response_body', $response_code);
        }

        $error = $response->error->type;
        $message = $response->error->message;
        throw new Util\Exception($message, $error, $response_code);
    }

    /**
     * @param array|string $params The parameters for a request.
     * @param array $options The options for a request.
     *
     * @return array|string The parameters combined with the options.
     */
    private function _combineParamsAndOptions($params, $options)
    {
        if ($params == null && $options == null) {
            return null;
        } elseif ($params != null && $options == null) {
            return $params;
        } elseif ($params == null && $options != null) {
            return $options;
        } else {
            if (is_array($params)) {
                // Add options to params array
                foreach ($options as $key => $value) {
                    $params[$key] = $value;
                }
            } else {
                // Add options to params string
                foreach ($options as $key => $value) {
                    $params .= "{$key}={$value}";
                }
            }

            return $params;
        }
    }

    /**
     * @param string $method The HTTP method to use.
     * @param string $url The URL to use.
     * @param array|string|null $params The parameters to use for the request.
     * @param array|null $options The options to use for the response.
     *
     * @return array The response body and code from the BlockScore API.
     */
    private function _makeCurlRequest($method, $url, $params = null, $options = null)
    {
        $curl = curl_init();

        $params = $this->_combineParamsAndOptions($params, $options);

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
                throw new Util\Exception('Unrecognized HTTP method.', 'unknown_http_method');
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
        $response = $response_body;

        // Did Curl throw an error?
        if ($response === false) {
            $errno = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            throw new Util\Exception("Curl Error ({$errno}): {$message}.", 'curl_error');
        }

        $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $code = $response_code;
        curl_close($curl);

        return array($response, $code);
    }
}