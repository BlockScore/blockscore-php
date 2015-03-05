<?php

namespace BlockScore;

class BlockScore
{
    // @var string The BlockScore API key to be used with requests.
    public static $apiKey;

    // @var string The URL for the BlockScore API.
    public static $apiEndpoint = 'https://api.blockscore.com';

    // @var string|null Version of the BlockScore API.
    public static $apiVersion = null;

    /**
     * @return string The BlockScore API key used for requests.
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * Sets the BlockScore API key to be used with requests.
     *
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * @return string The BlockScore API version used for requests.
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * Sets the BlockScore API version to be used with requests.
     *
     * @param string $apiVersion
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }
}