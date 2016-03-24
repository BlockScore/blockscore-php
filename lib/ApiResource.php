<?php

namespace BlockScore;

class ApiResource extends Object
{
    // @var array An array mapping the API resources to their class name.
    private static $resources = array(
        'person' => 'people',
        'company' => 'companies',
        'candidate' => 'candidates',
        'questionset' => 'question_sets',
        'watchlist' => 'watchlists',
    );

    /**
     * @return string Returns the URL for the API resource needed.
     */
    public static function classUrl()
    {
        $className = str_replace('BlockScore\\', '', get_called_class());
        $resource = self::$resources[strtolower($className)];
        return "/{$resource}";
    }

    /**
     * @return string Returns the URL for the specific instance of the object.
     */
    public function instanceUrl()
    {
        $id = $this['id'];
        $base = static::classUrl();
        return "{$base}/{$id}";
    }

    /**
     * @return Object Returns the "refreshed" Object from the BlockScore API.
     */
    public function refresh()
    {
        $request = new ApiRequestor(BlockScore::$apiKey, BlockScore::$apiEndpoint);
        $url = static::classUrl();
        $url = "{$url}/{$this['id']}";
        $response = $request->execute('get', $url);
        $this->refreshObject($response);
        return $this;
    }

    /**
     * @param string $method The HTTP method to use.
     * @param string $url The URL to use.
     * @param array|string|null $params The parameters to use for the request.
     * @param array|null $options The options to use for the response.
     *
     * @return JSON The response from the BlockScore API.
     */
    public static function _makeRequest($method, $url, $params = null, $options = null)
    {
        $request = new ApiRequestor(BlockScore::$apiKey, BlockScore::$apiEndpoint);
        $response = $request->execute($method, $url, $params, $options);
        return $response;
    }

    /**
     * @param string $id The ID of the object to retrieve.
     *
     * @return Object The retrieved instance.
     */
    protected static function _retrieve($id)
    {
        $instance = new static($id);
        $instance->refresh();
        return $instance;
    }

    /**
     * @param array|null $options The options to use for the response.
     *
     * @return array An array of Objects.
     */
    protected static function _all($options = null)
    {
        $url = static::classUrl();
        $response = static::_makeRequest('get', $url, null, $options);
        return Util\Util::convertToBlockScoreObject($response);
    }

    /**
     * @param array $params The parameters to use.
     *
     * @return Object The created BlockScore Object.
     */
    protected static function _create($params)
    {
        $url = static::classUrl();
        $response = static::_makeRequest('post', $url, $params);
        return Util\Util::convertToBlockScoreObject($response);
    }

    /**
     * @return Object The refreshed (and now deleted) instance.
     */
    protected function _delete()
    {
        $url = $this->instanceUrl();
        $response = static::_makeRequest('delete', $url);
        $this->refreshObject($response);
        return $this;
    }

    /**
     * @return Object The refreshed instance after saving unsaved attributes.
     */
    protected function _save()
    {
        $params = $this->getUnsavedValues();
        if (count($params) > 0) {
            $url = $this->instanceUrl();
            $response = static::_makeRequest('patch', $url, $params);
            $this->refreshObject($response);
        }
        return $this;
    }

    /**
     * @return array The history of the Candidate in an array of Candidates.
     */
    protected function _history()
    {
        $url = $this->instanceUrl() . '/history';
        $response = static::_makeRequest('get', $url);
        $data = json_encode($response);

        // Normalize response
        $response = "{ \"object\": \"list\", \"data\": {$data} }";
        $response = json_decode($response);
        return Util\Util::convertToBlockScoreObject($response);
    }

    /**
     * @return array The hits of the Candidate in an array of Candidates.
     */
    protected function _hits()
    {
        $url = $this->instanceUrl() . '/hits';
        $response = static::_makeRequest('get', $url);
        return Util\Util::convertToBlockScoreObject($response);
    }

    /**
     * @return Watchlist The Watchlist search results.
     */
    protected function _search()
    {
        $url = static::classUrl();
        $params = array('candidate_id' => $this->id);
        $response = static::_makeRequest('post', $url, $params);
        return Util\Util::convertToBlockScoreObject($response);
    }

    /**
     * @return QuestionSet The created QuestionSet.
     */
    protected function _createQuestionSet()
    {
        $url = static::classUrl();
        $params = array('person_id' => $this->id);
        $response = static::_makeRequest('post', $url, $params);
        $qs_obj = Util\Util::convertToBlockScoreObject($response);
        $this->addExisting($qs_obj);
        return $qs_obj;
    }

    /**
     * @param array $answers The answers to pass.
     *
     * @return QuestionSet The scored QuestionSet.
     */
    protected function _score($answers)
    {
        $url = static::instanceUrl() . '/score';

        // Weird request requires us to build the cURL request manually
        $params = '';
        foreach ($answers as $key => $value) {
            $params = $params . 'answers[][{$key}]={$value}&';
        }
        rtrim($params, '&');

        $response = static::_makeRequest('post', $url, $params);
        return Util\Util::convertToBlockScoreObject($response);
    }
}