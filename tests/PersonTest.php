<?php

namespace BlockScore;

class PersonTest extends TestCase
{
    // Test URL generator
    public function testUrl()
    {
        $this->assertSame(Person::classUrl(), '/people');
    }

    public function testUrlBuilding()
    {
        $person = self::createTestPerson();
        $this->assertSame($person, 'https://api.blockscore.com/people');
    }
}