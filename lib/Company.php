<?php

namespace BlockScore;

class Company extends ApiResource
{

    /**
     * @param string $id The ID of the company to retrieve.
     * @param array|string|null $options
     *
     * @return Company
     */
    public static function retrieve($id, $options = null)
    {
        return self::_retrieve($id, $options);
    }

    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return array An array of Companies.
     */
    public static function all($params = null, $options = null)
    {
        return self::_all($params, $options);
    }

    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Company The created company.
     */
    public static function create($params = null, $options = null)
    {
        return self::_create($params, $options);
    }
}