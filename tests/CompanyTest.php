<?php

namespace BlockScore;

class CompanyTest extends TestCase
{
    public function testUrl()
    {
        $this->assertSame(Company::classUrl(), '/companies');
    }

    public function testInstanceUrl()
    {
        $company = self::createTestCompany();
        $this->assertSame($company->instanceUrl(), "/companies/{$company->id}");
    }
    
    public function testClassType()
    {
        $company = self::createTestCompany();
        $this->assertTrue($company instanceof Company);
        $this->assertTrue($company instanceof BaseObject);
    }
    
    public function testListAllCompanies()
    {
        $company = self::createTestCompany();
        $companies = Company::all();
        $first_company = $companies[0];
        foreach ($company as $key => $value) {
            $this->assertSame($first_company->$key, $value);
        }
    }
    
    public function testRetrievePerson()
    {
        $company = self::createTestCompany();
        $retrieved_company = Company::retrieve($company->id);
        foreach (self::$test_company as $key => $value) {
            $this->assertSame($retrieved_company->$key, $value);
        }
    }
    
    public function testCreatePerson()
    {
        $company = self::createTestCompany();
        foreach (self::$test_company as $key => $value) {
            $this->assertSame($company->$key, $value);
        }
    }

    public function testOptionsForAllCompanies()
    {
        $options = array('count' => 1);
        self::createTestCompany();
        self::createTestCompany();
        $companies = Company::all($options);
        $this->assertSame(1, count($companies));
    }
}