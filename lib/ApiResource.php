<?php

namespace BlockScore;

class ApiResource extends Object
{
  // An array of the resources
  private static $resources = array(
    'person' => 'people',
    'company' => 'companies',
    'candidate' => 'candidates',
    'questionset' => 'question_sets',
    'watchlist' => 'watchlists',
  );

  public static function classUrl()
  {
    $className = str_replace('BlockScore\\', '', get_called_class());
    $resource = self::$resources[strtolower($className)];
    return "/{$resource}";
  }

  public function instanceUrl()
  {
    $id = $this['id'];
    $base = static::classUrl();
    return "{$base}/{$id}";
  }

  public function refresh()
  {
    $request = new ApiRequestor(BlockScore::$apiKey, BlockScore::$apiEndpoint);
    $url = static::classUrl();
    $url = "{$url}/{$this['id']}";
    $response = $request->execute('get', $url, null, null);
    $this->refreshObject(json_decode($response));
    return $this;
  }

  public static function _makeRequest($method, $url, $params, $options)
  {
    $request = new ApiRequestor(BlockScore::$apiKey, BlockScore::$apiEndpoint);
    $response = $request->execute($method, $url, $params, $options);
    return $response;
  }

  protected static function _retrieve($id, $options = null)
  {
    $instance = new static($id);
    $instance->refresh();
    return $instance;
  }

  protected static function _all($params = null, $options = null)
  {
    $url = static::classUrl();
    $response = static::_makeRequest('get', $url, $params, $options);
    return json_decode($response)->data;
  }

  protected static function _create($params = null, $options = null)
  {
    $url = static::classUrl();
    $response = static::_makeRequest('post', $url, $params, $options);
    return json_decode($response);
  }

  protected function _delete()
  {
    $url = $this->instanceUrl();
    $response = static::_makeRequest('delete', $url, null, null);
    $this->refreshObject(json_decode($response));
    return $this;
  }

  protected function _save()
  {
    $params = $this->getUnsavedValues();
    if (count($params) > 0) {
      $url = $this->instanceUrl();
      $response = static::_makeRequest('patch', $url, $params, null);
      $this->refreshObject(json_decode($response));
    }
    return $this;
  }
}