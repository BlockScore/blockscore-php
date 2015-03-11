<?php

namespace BlockScore;

class ExceptionTest extends TestCase
{
    public function testApiErrorHandling()
    {
        self::setTestApiKey();
    
        try {
            Person::create(array());
            // Always fail
            $this->assertTrue(false);
        } catch (Util\Exception $e) {
            $expected = 'invalid_request_error';
            $this->assertSame($e->type, $expected);
            $this->assertTrue($e instanceof Util\Exception);
        }
    }
    
    public function testApiKeyErrorHandling()
    {
        BlockScore::$apiKey = 'sk_test_11111111111111111111111111111111';
    
        try {
            Person::create(array(
                'name_first' => 'Jane',
                'name_last' => 'Doe',
                'birth_day' => 1,
                'birth_month' => 1,
                'birth_year' => 1990,
                'document_type' => 'ssn',
                'document_value' => '0000',
                'address_street1' => '123 Something Ave',
                'address_city' => 'Newton Falls',
                'address_subdivision' => 'OH',
                'address_postal_code' => '44444',
                'address_country_code' => 'US',
            ));
    
            // Always fail
            $this->assertTrue(false);
        } catch (Util\Exception $e) {
            $expected = 'invalid_request_error';
            $this->assertSame($e->type, $expected);
            $this->assertTrue($e instanceof Util\Exception);
        }
    
        self::setTestApiKey();
    }

    public function testCurlErrorHandling()
    {
        $endpoint = BlockScore::$apiEndpoint;
        BlockScore::$apiEndpoint = 'https://totally-legit-api-endpoint.blockscore.com';
        try {
            self::createTestPerson();

            // Always fail
            $this->assertTrue(false);
        } catch (Util\Exception $e) {
            $expected = 'curl_error';
            $this->assertSame($e->type, $expected);
            $this->assertTrue($e instanceof Util\Exception);
        }

        BlockScore::$apiEndpoint = $endpoint;
    }

    public function testInvalidOptionsErrorHandling()
    {
        try {
            $options = "count: 5";
            $people = Person::all($options);
        } catch (Util\Exception $e) {
            $expected = 'invalid_options_error';
            $this->assertSame($e->type, $expected);
            $this->assertTrue($e instanceof Util\Exception);
        }
    }
}