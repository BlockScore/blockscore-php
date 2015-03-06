<?php

namespace BlockScore;

class ApiResource
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

  public static function _makeRequest($method, $url, $params, $options)
  {
    $request = new ApiRequestor(BlockScore::$apiKey, BlockScore::$apiEndpoint);
    $response = $request->execute($method, $url, $params, $options);
    return $response;
  }

  protected static function _retrieve($id, $options = null)
  {
    $url = static::classUrl();
    $url = "{$url}/{$id}";
    $response = static::_makeRequest('get', $url, null, $options);
    return json_decode($response);
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

  protected static function _delete($id, $options = null)
  {
    $url = static::classUrl();
    $url = "{$url}/{$id}";
    $response = static::_makeRequest('delete', $url, null, $options);
    return json_decode($response);
  }

  protected static function _save($id, $params, $options = null)
  {
    
  }
}