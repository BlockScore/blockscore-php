<?php

namespace BlockScore;

class Candidate extends ApiResource
{

  /**
   * @param string $id The ID of the candidate to retrieve.
   * @param array|string|null $options
   *
   * @return Candidate
   */
  public static function retrieve($id, $options = null)
  {
    return self::_retrieve($id, $options);
  }

  /**
   * @param string $id The ID of the candidate to edit.
   * @param array|string|null $options
   *
   * @return Candidate
   */
  public function save()
  {
    return $this->_save();
  }

  /**
   * @param string $id The ID of the candidate to retrieve.
   * @param array|string|null $options
   *
   * @return Candidate
   */
  public function delete()
  {
    return $this->_delete();
  }

  /**
   * @param array|null $params
   * @param array|string|null $options
   *
   * @return array An array of Candidates.
   */
  public static function all($params = null, $options = null)
  {
    return self::_all($params, $options);
  }

  /**
   * @param array|null $params
   * @param array|string|null $options
   *
   * @return Candidate The created Candidate.
   */
  public static function create($params = null, $options = null)
  {
    return self::_create($params, $options);
  }
}