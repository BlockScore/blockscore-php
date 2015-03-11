<?php

namespace BlockScore;

class Watchlist extends ApiResource
{ 

    /**
     * @param string $id The BlockScore object ID
     *
     * Constructor for Objects.
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return Watchlist
     */
    public function search()
    {
        return self::_search();
    }
}