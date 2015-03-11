<?php

namespace BlockScore;

class Company extends ApiResource
{

    /**
     * @param string $id The ID of the company to retrieve.
     *
     * @return Company
     */
    public static function retrieve($id)
    {
        return self::_retrieve($id);
    }

    /**
     * @param array|null $options
     *
     * @return array An array of Companies.
     */
    public static function all($options = null)
    {
        return self::_all($options);
    }

    /**
     * @param array $params
     *
     * @return Company The created company.
     */
    public static function create($params)
    {
        return self::_create($params);
    }
}