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
        $Candidate = self::createTestCandidate();
        $this->assertTrue($Candidate instanceof Candidate);
        $this->assertTrue($Candidate instanceof Object);
    }
    
    public function testListAllCandidates()
    {
        $candidate = self::createTestCandidate();
        sleep(2);
        $candidates = Candidate::all();
        $first_candidate = $candidates[0];
        foreach (self::$test_candidate as $key => $value) {
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
        $this->assertSame($new_candidate->ssn, '9999');
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
        $this->assertSame($new_candidate->ssn, '8888');
        $this->assertSame($new_candidate->note, '');
    }
    
    public function testEditCandidateNullConversion()
    {
        $candidate = self::createTestCandidate();
        $candidate->note = null;
        $new_candidate = $candidate->save();
        $this->assertSame($new_candidate->note, '');
    }
    
    public function testCandidateHistory()
    {
        $candidate = self::createTestCandidate();
        $history1 = $candidate->history();
        $this->assertSame(count($history1), 1);
        $candidate->ssn = '7777';
        $new_candidate = $candidate->save();
        $history2 = $candidate->history();
        $this->assertSame(count($history2), 2);
        $this->assertNotEquals($history1[0]->ssn, $history2[0]->ssn);
        $this->assertSame($history1[0]->name_first, $history2[0]->name_first);
    }
    
    public function testCandidateHitsEmpty()
    {
        $candidate = self::createTestCandidate();
        $hits = $candidate->hits();
        $candidate->watchlists->search();
        $this->assertSame(count($hits), 0);
    }
}