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
   * @return object Returns the "refreshed" object from the BlockScore API.
   */
  public function refresh()
  {
    $request = new ApiRequestor(BlockScore::$apiKey, BlockScore::$apiEndpoint);
    $url = static::classUrl();
    $url = "{$url}/{$this['id']}";
    $response = $request->execute('get', $url, null, null);
    $this->refreshObject(json_decode($response));
    return $this;
  }

  /**
   * @param string $method The HTTP method to use.
   * @param string $url The URL to use.
   * @param array|null $params The parameters to use for the request.
   * @param array|null $options The options to use for the response.
   *
   * @return string The response from the BlockScore API.
   */
  public static function _makeRequest($method, $url, $params = null, $options = null)
  {
    $request = new ApiRequestor(BlockScore::$apiKey, BlockScore::$apiEndpoint);
    $response = $request->execute($method, $url, $params, $options);
    return $response;
  }

  /**
   * @param string $id The ID of the object to retrieve.
   * @param array|null $options The options to use.
   *
   * @return Object The refreshed instance.
   */
  protected static function _retrieve($id, $options = null)
  {
    $instance = new static($id);
    $instance->refresh();
    return $instance;
  }

  /**
   * @param array|null $params The parameters to use .
   * @param array|null $options The options to use for the response.
   *
   * @return JSON The response from the BlockScore API in JSON format.
   */
  protected static function _all($params = null, $options = null)
  {
    $url = static::classUrl();
    $response = static::_makeRequest('get', $url, $params, $options);
    return json_decode($response)->data;
  }

  /**
   * @param array|null $params The parameters to use.
   * @param array|null $options The options to use for the response.
   *
   * @return JSON The response from the BlockScore API in JSON format.
   */
  protected static function _create($params = null, $options = null)
  {
    $url = static::classUrl();
    $response = static::_makeRequest('post', $url, $params, $options);
    return json_decode($response);
  }

  /**
   * @return Object The refreshed instance.
   */
  protected function _delete()
  {
    $url = $this->instanceUrl();
    $response = static::_makeRequest('delete', $url);
    $this->refreshObject(json_decode($response));
    return $this;
  }

  /**
   * @return Object The refreshed instance.
   */
  protected function _save()
  {
    $params = $this->getUnsavedValues();
    if (count($params) > 0) {
      $url = $this->instanceUrl();
      $response = static::_makeRequest('patch', $url, $params);
      $this->refreshObject(json_decode($response));
    }
    return $this;
  }

  /**
   * @return array The history of the candidate in an array of candidates.
   */
  protected function _history()
  {
    $url = $this->instanceUrl() . '/history';
    $response = static::_makeRequest('get', $url);
    return json_decode($response);
  }

  /**
   * @return array The hits of the candidate in an array of candidates.
   */
  protected function _hits()
  {
    $url = $this->instanceUrl() . '/hits';
    $response = static::_makeRequest('get', $url);
    return json_decode($response)->data;
  }

  protected static function _search($id, $options = null)
  {
    $url = self::classUrl();

    // Set up params
    if($options == null) {
      $params = array(
        'candidate_id' => $id,
      );
    }
    else {
      $params = $options;
      $params['candidate_id'] = $id;
    }

    $response = static::_makeRequest('post', $url, $params);
    return json_decode($response);
  }
}