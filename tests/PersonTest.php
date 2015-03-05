<?php

namespace BlockScore;

class PersonTest extends TestCase
{
    // Test URL generator
    public function testUrl()
    {
        $this->assertSame(Person::classUrl(), '/people');
    }
}