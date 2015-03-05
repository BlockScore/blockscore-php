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

    protected static function _retrieve($id, $options = null)
    {
        // parse options
        $url = static::classUrl();
        // validate param
        // convert into object
    }

    protected static function _all($params = null, $options = null)
    {
        // validate params
        $url = static::classUrl();
        // make request
        // convert into object
    }

    protected static function _create($params = null, $options = null)
    {
        // validate params
        $url = static::classUrl();
        // make request
        // convert into object
    }
}