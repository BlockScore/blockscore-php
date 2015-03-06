<?php

namespace BlockScore;

class CandidateTest extends TestCase
{
  // Test URL generator
  public function testUrl()
  {
    $this->assertSame(Candidate::classUrl(), '/candidates');
  }

  public function testListAllCandidates()
  {
    $candidate = self::createTestCandidate();
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
    $deleted_candidate = Candidate::delete($candidate->id);
    $this->assertTrue($deleted_candidate->deleted);
    foreach (self::$test_candidate as $key => $value) {
      $this->assertSame($deleted_candidate->$key, $value);
    }
  }
}