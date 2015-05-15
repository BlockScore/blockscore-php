<?php

namespace BlockScore;

class QuestionSetTest extends TestCase
{
    public function testUrl()
    {
        $this->assertSame(QuestionSet::classUrl(), '/question_sets');
    }

    public function testClassType()
    {
        $person = self::createTestPerson();
        $qs = $person->question_sets->create();
        $this->assertTrue($qs instanceof QuestionSet);
        $this->assertTrue($qs instanceof Object);
    }
    
    public function testCreateQuestionSet()
    {
        $person = self::createTestPerson();
        $qs = $person->question_sets->create();
        foreach ($qs->questions as $i) {
            $this->assertSame('None Of The Above',$i->answers[4]->answer);
        }
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
        $answers = array(
            array('question_id' => 1, 'answer_id' => 2),
            array('question_id' => 2, 'answer_id' => 2),
            array('question_id' => 3, 'answer_id' => 2),
            array('question_id' => 4, 'answer_id' => 2),
            array('question_id' => 5, 'answer_id' => 2),
        );
        $score = $qs->score($answers);
        foreach ($qs as $key => $value) {
            $this->assertSame($score->questions->$key, $value);
        }
    }
}