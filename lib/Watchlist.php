<?php

namespace BlockScore;

class Watchlist extends ApiResource
{

  /**
   * @param string $id The ID of the candidate to search for.
   * @param array|string|null $options
   *
   * @return Watchlist
   */
  public static function search($id, $options = null)
  {
    return self::_search($id, $options);
  }
}