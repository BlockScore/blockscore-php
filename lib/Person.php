<?php

namespace BlockScore;

class Person extends ApiResource
{

    /**
     * @param string $id The ID of the person to retrieve.
     * @param array|string|null $options
     *
     * @return Person
     */
    public static function retrieve($id, $options = null)
    {
        return self::_retrieve($id, $options);
    }

    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return array An array of Persons.
     */
    public static function all($params = null, $options = null)
    {
        return self::_all($params, $options);
    }

    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Person The created person.
     */
    public static function create($params = null, $options = null)
    {
        return self::_create($params, $options);
    }
}