<?php

namespace BlockScore;

class CandidateTest extends TestCase
{
    public function testUrl()
    {
        $this->assertSame(Candidate::classUrl(), '/candidates');
    }
    
    public function testInstanceUrl()
    {
        $candidate = self::createTestCandidate();
        $candidate = Candidate::retrieve($candidate->id);
        $this->assertSame($candidate->instanceUrl(), "/candidates/{$candidate->id}");
    }

    public function testClassType()
    {
        $candidate = self::createTestCandidate();
        $this->assertTrue($candidate instanceof Candidate);
        $this->assertTrue($candidate instanceof BaseObject);
    }

    public function testListAllCandidates()
    {
        $candidate = self::createTestCandidate();
        $candidates = Candidate::all();
        $first_candidate = $candidates[0];
        foreach ($candidate as $key => $value) {
            $this->assertSame($first_candidate->$key, $value);
        }
    }
    
    public function testRetrieveCandidate()
    {
        $candidate = self::createTestCandidate();
        $retrieved_candidate = Candidate::retrieve($candidate->id);
        foreach (self::$test_candidate as $key => $value) {
            $this->assertSame($retrieved_candidate->$key, $value);
        }
    }
    
    public function testCreateCandidate()
    {
        $candidate = self::createTestCandidate();
        foreach (self::$test_candidate as $key => $value) {
            $this->assertSame($candidate->$key, $value);
        }
    }
    
    public function testDeleteCandidate()
    {
        $candidate = self::createTestCandidate();
        $deleted_candidate = $candidate->delete();
        $this->assertTrue($deleted_candidate->deleted);
        foreach (self::$test_candidate as $key => $value) {
            $this->assertSame($deleted_candidate->$key, $value);
        }
    }
    
    public function testEditCandidateSimple()
    {
        $candidate = self::createTestCandidate();
        $candidate->ssn = '9999';
        $new_candidate = $candidate->save();
        $this->assertNotEquals(self::$test_candidate['ssn'], $new_candidate->ssn);
        $this->assertSame('9999', $new_candidate->ssn);
    }
    
    public function testEditCandidateComplex()
    {
        $candidate = self::createTestCandidate();
        $candidate->ssn = '9999';
        $candidate->ssn = '8888';
        $candidate->note = '';
        $new_candidate = $candidate->save();
        $this->assertNotEquals(self::$test_candidate['ssn'], $new_candidate->ssn);
        $this->assertNotEquals(self::$test_candidate['note'], $new_candidate->note);
        $this->assertSame('8888', $new_candidate->ssn);
        $this->assertSame('', $new_candidate->note);
    }
    
    public function testEditCandidateNullConversion()
    {
        $candidate = self::createTestCandidate();
        $candidate->note = null;
        $new_candidate = $candidate->save();
        $this->assertSame('', $new_candidate->note);
    }
    
    public function testCandidateHistory()
    {
        $candidate = self::createTestCandidate();
        $history1 = $candidate->history();
        $this->assertSame(1, count($history1));
        $candidate->ssn = '7777';
        $new_candidate = $candidate->save();
        $history2 = $candidate->history();
        $this->assertSame(2, count($history2));
        $this->assertNotEquals($history1[0]->ssn, $history2[0]->ssn);
        $this->assertSame($history1[0]->name_first, $history2[0]->name_first);
    }
    
    public function testCandidateHitsEmpty()
    {
        $candidate = self::createTestCandidate();
        $hits = $candidate->hits();
        $candidate->watchlists->search();
        $this->assertSame(0, count($hits));
    }

    public function testOptionsForAllCandidates()
    {
        $options = array('count' => 1);
        self::createTestCandidate();
        self::createTestCandidate();
        $candidates = Company::all($options);
        $this->assertSame(1, count($candidates));
    }
}