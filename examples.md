## blockscore-php

Quite a few examples can be found in the tests directory!

### Initializing

```php
\BlockScore\BlockScore::setApiKey('your-api-key');
```

---

### Person

#### Create a person

```php
$person = \BlockScore\Person::create(array(
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
  )
);
```

#### Retrieve a person

```php
\BlockScore\Person::retrieve('54e96f7f3638330003140000');
```

#### List all people

```php
\BlockScore\Person::all();
```

---

### Question Set

#### Create a question set

```php
$qs = \BlockScore\QuestionSet::create('54e96f7f3638330003140000');
```

#### Retrieve a question set

```php
$qs = \BlockScore\QuestionSet::retrieve('54e96f7f3638330003140001');
```

#### List all question sets

```php
$qs = \BlockScore\QuestionSet::all();
```

#### Score a question set

```php
$qs = \BlockScore\QuestionSet::retrieve("54e96f7f3638330003140001");
$qs->score($answers);

```

---

### Candidate

#### Create a candidate

```php
$candidate = \BlockScore\Candidate::create(array(
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
  )
);
```

#### Retrieve a candidate

```php
\BlockScore\Candidate::retrieve("54e96f7f3638330003140000");
```

#### List all candidates

```php
\BlockScore\Candidate::all();
```

#### Delete a candidate

```php
$candidate = \BlockScore\Candidate::retrieve("54e96f7f3638330003140000");
$candidate->delete();
```

#### Update a candidate

```php
$candidate = \BlockScore\Person::retrieve("54e96f7f3638330003140000");
$candidate->name_first = 'Alain';
$candidate->save();
```

#### View candidate history

```php
$candidate = \BlockScore\Candidate::retrieve("54e96f7f3638330003140000");
$candidate->history();
```

#### View candidate hits

```php
$candidate = \BlockScore\Candidate::retrieve("54e96f7f3638330003140000");
$candidate->hits();
```

---

### Watchlist

### Search watchlists

```php
$wl = \BlockScore\Watchlist::search("54e96f7f3638330003140000");
```

---

### Company

#### Create a company

```php
$company = \BlockScore\Company::create(array(
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
  )
);
```

#### Retrieve a company

```php
\BlockScore\Company::retrieve("54e96f7f3638330003140000");
```

#### List all companies

```php
\BlockScore\Company::all();
```