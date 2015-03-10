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
     * @return Candidate
     */
    public function save()
    {
        return $this->_save();
    }

    /**
     * @return Candidate
     */
    public function delete()
    {
        return $this->_delete();
    }

    /**
     * @return array An array of Candidates
     */
    public function history()
    {
        return $this->_history();
    }

    /**
     * @return array An array of Candidates
     */
    public function hits()
    {
        return $this->_hits();
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