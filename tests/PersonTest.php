<?php

namespace BlockScore;

class PersonTest extends TestCase
{
    public function testUrl()
    {
        $this->assertSame(Person::classUrl(), '/people');
    }
    
    public function testInstanceUrl()
    {
        $person = self::createTestPerson();
        $this->assertSame($person->instanceUrl(), "/people/{$person->id}");
    }
    
    public function testClassType()
    {
        $person = self::createTestPerson();
        $this->assertTrue($person instanceof Person);
        $this->assertTrue($person instanceof BaseObject);
    }
    
    public function testListAllPeople()
    {
        $person = self::createTestPerson();
        $people = Person::all();
        $first_person = $people[0];
        foreach ($person as $key => $value) {
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

    public function testOptionsForAllPeople()
    {
        $options = array('count' => 1);
        self::createTestPerson();
        self::createTestPerson();
        $people = Person::all($options);
        $this->assertSame(1, count($people));
    }
}