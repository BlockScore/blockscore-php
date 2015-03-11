<?php

namespace BlockScore;

class QuestionSet extends ApiResource
{
    private $_existing;

    public function __construct($id)
    {
        // Actually the person id...
        $this->id = $id;

        // Existing question sets for the person
        $this->_existing = array();
    }

    public function create($options = null)
    {
        return self::_createQuestionSet();
    }

    public function all()
    {
        return $this->_existing;
    }

    public function addExisting($qs)
    {
        array_push($this->_existing, $qs);
    }

    public static function retrieve($id, $options = null)
    {
        return self::_retrieve($id, $options);
    }

    public function score($answers, $options = null)
    {
        return self::_score($answers, $options);
    }
}