<?php

namespace BlockScore\Util;

use BlockScore\Object;

class Util
{
  public static function isList($object)
  {
    if ($object->object == 'list') {
      return true;
    }

    return false;
  }

  /**
   * @param Object(JSON) $response
   *
   * @return Object Returns a "BlockScore Object" type.
   */
  public static function convertToBlockScoreObject($response)
  {
    $types = array(
      'person' => 'BlockScore\\Person',
      'candidate' => 'BlockScore\\Candidate',
      'company' => 'BlockScore\\Company',
      'question_set' => 'BlockScore\\QuestionSet',
    );

    if (self::isList($response)) {
      $mapped = array();
      $data = $response;
      if (isset($response->data)) {
        $data = $response->data;
      }

      foreach($data as $i) {
        array_push($mapped, self::convertToBlockScoreObject($i));
      }

      return $mapped;
    } elseif (isset($types[$response->object])) {
      if (isset($types[$response->object])) {
        $class = $types[$response->object];
      } else {
        $class = 'BlockScore\\Object';
      }

      return $class::constructObject($response);
    } else {
      return Object::constructObject($response);
    }
  }
}