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
    $company = Company::retrieve($company->id);
    $this->assertSame($company->instanceUrl(), "/companies/{$company->id}");
  }
  
  public function testListAllCompanies()
  {
    $company = self::createTestCompany();
    sleep(2);
    $companies = Company::all();
    $first_company = $companies[0];
    foreach (self::$test_company as $key => $value) {
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
}