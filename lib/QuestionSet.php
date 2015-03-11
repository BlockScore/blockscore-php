<?php

namespace BlockScore;

class QuestionSet extends ApiResource
{
    // @var array Existing question sets for a user.
    private $_existing;

    /**
     * @param string $id The id of a Person.
     */
    public function __construct($id)
    {
        $this->id = $id;

        $this->_existing = array();
    }

    /**
     * @return QuestionSet The created QuestionSet.
     */
    public function create()
    {
        return self::_createQuestionSet();
    }

    /**
     * @return array An array of QuestionSets for the current Person.
     */
    public function all()
    {
        return $this->_existing;
    }

    /**
     * @param QuestionSet $qs A new QuestionSet.
     *
     * Adds a new QuestionSet to the existing array. Helper method.
     */
    protected function addExisting($qs)
    {
        array_push($this->_existing, $qs);
    }

    /**
     * @param string $id The id of a QuestionSet to retrieve.
     *
     * @return QuestionSet The specific QuestionSet.
     */
    public static function retrieve($id)
    {
        return self::_retrieve($id);
    }

    /**
     * @param array $answers The answers for a QuestionSet.
     *
     * @return QuestionSet The QuestionSet with the score.
     */
    public function score($answers)
    {
        return self::_score($answers);
    }
}