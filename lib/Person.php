<?php

namespace BlockScore;

class Person extends ApiResource
{

    /**
     * @param string $id The ID of the person to retrieve.
     *
     * @return Person The retrieved person.
     */
    public static function retrieve($id)
    {
        return self::_retrieve($id);
    }

    /**
     * @param array|null $options
     *
     * @return array An array of Persons.
     */
    public static function all($options = null)
    {
        return self::_all($options);
    }

    /**
     * @param array $params
     *
     * @return Person The created person.
     */
    public static function create($params)
    {
        return self::_create($params);
    }
}