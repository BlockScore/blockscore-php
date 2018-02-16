<?php

namespace BlockScore;

class QuestionSetTest extends TestCase
{
    private static $qs_answers = array(
        'Which one of the following addresses is associated with you?' => '309 Colver Rd',
        'Which one of the following area codes is associated with you?' => '812',
        'Which one of the following counties is associated with you?' => 'Jasper',
        'Which one of the following zip codes is associated with you?' => '49230',
        'What state was your SSN issued in?' => 'None Of The Above',
        'Which one of the following adult individuals is most closely associated with you?' => 'None Of The Above',
    );

    public function testUrl()
    {
        $this->assertSame(QuestionSet::classUrl(), '/question_sets');
    }

    public function testClassType()
    {
        $person = self::createTestPerson();
        $qs = $person->question_sets->create();
        $this->assertTrue($qs instanceof QuestionSet);
        $this->assertTrue($qs instanceof BaseObject);
    }

    public function testCreateQuestionSet()
    {
        $person = self::createTestPerson();
        $qs = $person->question_sets->create();
        foreach ($qs->questions as $i) {
            $this->assertSame('None Of The Above',$i->answers[4]->answer);
        }
    }

    public function testCreateQuestionSetWithTimeLimit()
    {
        $person = self::createTestPerson();
        $qs = $person->question_sets->create(17);

        $this->assertSame($qs->time_limit, 17);
    }

    public function testTimeLimitExpired()
    {
        $person = self::createTestPerson();
        $qs = $person->question_sets->create(2);
        sleep(4);

        $retrieved_qs = $person->question_sets->retrieve($qs->id);
        $this->assertSame($retrieved_qs->expired, true);
    }

    public function testAllQuestionSet()
    {
        $person = self::createTestPerson();
        $this->assertSame(0, count($person->question_sets->all()));
        $qs = $person->question_sets->create();
        $this->assertSame(1, count($person->question_sets->all()));
        $all_qs = $person->question_sets->all();
        $this->assertSame($qs->id, $all_qs[0]->id);
    }

    public function testRetrieveQuestionSet()
    {
        $person = self::createTestPerson();
        $qs = $person->question_sets->create();
        $retrieved_qs = $person->question_sets->retrieve($qs->id);
        foreach ($qs as $key => $value) {
            $this->assertSame($retrieved_qs->$key, $value);
        }
    }

    public function testScoringQuestionSet()
    {
        $person = self::createTestPerson();
        $qs = $person->question_sets->create();

        // Compute the correct answer to the first question
        $first_question = $qs->questions[0];
        $first_question_answer = self::$qs_answers[$first_question->question];
        $first_question_answer_id = 0;

        foreach ($first_question->answers as $answer) {
            if ($answer->answer == $first_question_answer) {
                $first_question_answer_id = $answer->id;
            }
        }

        $answers = array(
            array('question_id' => 1, 'answer_id' => $first_question_answer_id),
            array('question_id' => 2, 'answer_id' => 6),
            array('question_id' => 3, 'answer_id' => 6),
            array('question_id' => 4, 'answer_id' => 6),
            array('question_id' => 5, 'answer_id' => 6),
        );

        $score = $qs->score($answers);

        // Test whether questions returned are the same
        foreach ($qs as $key => $value) {
            $this->assertSame($score->questions->$key, $value);
        }

        // Test whether we scored a 20%!
        $this->assertSame($score->score, 20.0);
    }

    public function testScoringQuestionSet2()
    {
        $person = self::createTestPerson();
        $qs = $person->question_sets->create();

        // Compute the correct answer to all the questions!
        $answer_ids = array();

        foreach ($qs->questions as $question) {
            $question_answer = self::$qs_answers[$question->question];
            foreach ($question->answers as $answer) {
                if ($answer->answer == $question_answer) {
                    array_push($answer_ids, $answer->id);
                }
            }
        }

        $answers = array(
            array('question_id' => 1, 'answer_id' => $answer_ids[0]),
            array('question_id' => 2, 'answer_id' => $answer_ids[1]),
            array('question_id' => 3, 'answer_id' => $answer_ids[2]),
            array('question_id' => 4, 'answer_id' => $answer_ids[3]),
            array('question_id' => 5, 'answer_id' => $answer_ids[4]),
        );

        $score = $qs->score($answers);

        // Test whether questions returned are the same
        foreach ($qs as $key => $value) {
            $this->assertSame($score->questions->$key, $value);
        }

        // Test whether we scored a 100%!
        $this->assertSame($score->score, 100.0);
    }

    public function testScoringQuestionSet3()
    {
        $person = self::createTestPerson();
        $qs = $person->question_sets->create();

        $answers = array(
            array('question_id' => 1, 'answer_id' => 6),
            array('question_id' => 2, 'answer_id' => 6),
            array('question_id' => 3, 'answer_id' => 6),
            array('question_id' => 4, 'answer_id' => 6),
            array('question_id' => 5, 'answer_id' => 6),
        );

        $score = $qs->score($answers);

        // Test whether questions returned are the same
        foreach ($qs as $key => $value) {
            $this->assertSame($score->questions->$key, $value);
        }

        // Test whether we scored a 0%!
        $this->assertSame($score->score, 0.0);
    }
}