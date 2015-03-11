<?php

namespace BlockScore;

class Candidate extends ApiResource
{

    /**
     * @param string $id The ID of the candidate to retrieve.
     *
     * @return Candidate
     */
    public static function retrieve($id)
    {
        return self::_retrieve($id);
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
     * @param array|null $options
     *
     * @return array An array of Candidates.
     */
    public static function all($options = null)
    {
        return self::_all($options);
    }

    /**
     * @param array $params
     *
     * @return Candidate The created Candidate.
     */
    public static function create($params)
    {
        return self::_create($params);
    }
}