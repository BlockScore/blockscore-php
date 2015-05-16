<?php

namespace BlockScore;

/**
 * Base class for BlockScore test cases.
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    const API_KEY = null;
    private static $first_names = array(
        'Jane',
        'Alain',
        'John',
        'Chris',
        'Beau',
        'Andrew',
        'Connor',
        'Daniel',
    );
    private static $company_names = array(
        'Jane BlockScore',
        'John BlockScore',
        'Alain BlockScore',
        'John BlockScore',
        'Chris BlockScore',
        'Beau BlockScore',
        'Andrew BlockScore',
        'Connor BlockScore',
        'Daniel BlockScore',
    );
    protected static $test_person = array(
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
    );
    protected static $test_candidate = array(
        'name_first' => 'Jane',
        'name_last' => 'Doe',
        'date_of_birth' => '1990-01-01',
        'ssn' => '0000',
        'address_street1' => '123 Something Ave',
        'address_city' => 'Newton Falls',
        'address_subdivision' => 'OH',
        'address_postal_code' => '44444',
        'address_country_code' => 'US',
        'note' => 'A test note.',
    );
    protected static $test_company = array(
        'entity_name' => 'Test Company',
        'tax_id' => '123410000',
        'incorporation_day' => 1,
        'incorporation_month' => 1,
        'incorporation_year' => 1990,
        'incorporation_state' => 'DE',
        'incorporation_country_code' => 'US',
        'incorporation_type' => 'corporation',
        'dbas' => 'TestCompany',
        'registration_number' => '123123123',
        'email' => 'test@example.com',
        'phone_number' => '6505555555',
        'ip_address' => '8.8.8.8',
        'address_street1' => '123 Something Ave',
        'address_street2' => nil,
        'address_city' => 'Newton Falls',
        'address_subdivision' => 'OH',
        'address_postal_code' => '44444',
        'address_country_code' => 'US',
    );

    protected static function setTestApiKey()
    {
        if (self::API_KEY == null) {
            BlockScore::setApiKey(getenv('BLOCKSCORE_API_KEY'));
        }
        else {
            BlockScore::setApiKey(self::API_KEY);
        }
    }

    protected static function createTestPerson()
    {
        self::setTestApiKey();
        self::randomizeFirstNameOfPerson();
        return Person::create(self::$test_person);
    }

    protected static function createTestCandidate()
    {
        self::setTestApiKey();
        self::randomizeFirstNameOfCandidate();
        return Candidate::create(self::$test_candidate);
    }

    protected function createTestJohnCandidate()
    {
        self::setTestApiKey();
        $test_john = array(
            'name_first' => 'John',
            'name_last' => 'Bredenkamp',
        );
        return Candidate::create($test_john);
    }

    protected static function createTestCompany()
    {
        self::setTestApiKey();
        self::randomizeCompanyName();
        return Company::create(self::$test_company);
    }

    protected static function randomizeFirstNameOfPerson()
    {
        $new_first_name = self::$first_names[array_rand(self::$first_names)];
        self::$test_person['name_first'] = $new_first_name;
    }

    protected static function randomizeFirstNameOfCandidate()
    {
        $new_first_name = self::$first_names[array_rand(self::$first_names)];
        self::$test_candidate['name_first'] = $new_first_name;
    }

    protected static function randomizeCompanyName()
    {
        $new_name = self::$company_names[array_rand(self::$company_names)];
        self::$test_company['entity_name'] = $new_name;
    }
}