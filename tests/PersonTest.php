<?php

namespace BlockScore;

class PersonTest extends TestCase
{
  public function testUrl()
  {
    $this->assertSame(Person::classUrl(), '/people');
  }
  
  public function testListAllPeople()
  {
    $person = self::createTestPerson();
    sleep(2);
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
    foreach (self::$test_person as $key => $value) {
      $this->assertSame($retrieved_person->$key, $value);
    }
  }
  
  public function testCreatePerson()
  {
    $person = self::createTestPerson();
    foreach (self::$test_person as $key => $value) {
      $this->assertSame($person->$key, $value);
    }
  }
}