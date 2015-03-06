<?php

namespace BlockScore;

class PersonTest extends TestCase
{
  // Test URL generator
  public function testUrl()
  {
    $this->assertSame(Person::classUrl(), '/people');
  }

  public function testListAllPeople()
  {
    $person = self::createTestPerson();
    $people = Person::all();
    $first_person = $people[0];
    foreach (self::$test_person as $key => $value) {
      $this->assertSame($first_person->$key, $value);
    }
  }

  public function testRetrievePerson()
  {
    $person = self::createTestPerson();
    $retrieved_person = Person::retrieve($person->id);
    $this->assertEquals($retrieved_person, $person);
  }

  public function testCreatePerson()
  {
    $person = self::createTestPerson();
    foreach (self::$test_person as $key => $value) {
      $this->assertSame($person->$key, $value);
    }
  }
}