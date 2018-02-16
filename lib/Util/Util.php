<?php

namespace BlockScore\Util;

use BlockScore\BaseObject;

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
     * @param BaseObject(JSON) $response
     *
     * @return BaseObject Returns a "BlockScore BaseObject" type.
     */
    public static function convertToBlockScoreObject($response)
    {
        $types = array(
            'person' => 'BlockScore\\Person',
            'candidate' => 'BlockScore\\Candidate',
            'company' => 'BlockScore\\Company',
            'question_set' => 'BlockScore\\QuestionSet',
        );

        if (!isset($response->object) && isset($response->searched_lists)) {
            $class = 'BlockScore\\Watchlist';
            return $class::constructObject($response);
        }

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
                $class = 'BlockScore\\BaseObject';
            }

            return $class::constructObject($response);
        } else {
            $class = 'BlockScore\\BaseObject';
            return $class::constructObject($response);
        }
    }
}