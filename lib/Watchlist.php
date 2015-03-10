<?php

namespace BlockScore;

class Watchlist extends ApiResource
{ 

    /**
     * @param string|null $id The BlockScore object ID
     *
     * Constructor for Objects.
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $id The ID of the candidate to search for.
     * @param array|string|null $options
     *
     * @return Watchlist
     */
    public function search($options = null)
    {
        return self::_search($options);
    }
}