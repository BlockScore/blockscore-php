<?php

namespace BlockScore;

class ErrorTest extends TestCase
{
    public function testApiErrorHandling()
    {
        self::setTestApiKey();
    
        try {
            Person::create(array(
                'name_first' => 'Jane',
                'name_last' => 'Doe',
                'birth_day' => 1,
                'birth_month' => 'January',
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
        } catch (\Exception $e) {
            $expected = 'Error (api_error): An error has occurred. If this problem persists, please message support@blockscore.com.';
            $this->assertSame($e->getMessage(), $expected);
            $this->assertTrue($e instanceof \Exception);
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
        } catch (\Exception $e) {
            $expected = 'Error (invalid_request_error): The supplied API key is invalid.';
            $this->assertSame($e->getMessage(), $expected);
            $this->assertTrue($e instanceof \Exception);
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
        } catch (\Exception $e) {
            $expected = 'Curl Error (6): Could not resolve host: totally-legit-api-endpoint.blockscore.com';
            $this->assertSame($e->getMessage(), $expected);
            $this->assertTrue($e instanceof \Exception);
        }

        BlockScore::$apiEndpoint = $endpoint;
    }

    public function testInvalidOptionsErrorHandling()
    {
        try {
            $options = "count: 5";
            $people = Person::all($options);
        } catch (\Exception $e) {
            $expected = 'Invalid format for options. Options must be an array. Attemped options: count: 5.';
            $this->assertSame($e->getMessage(), $expected);
            $this->assertTrue($e instanceof \Exception);
        }
    }
}