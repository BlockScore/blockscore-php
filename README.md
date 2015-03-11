# blockscore-php

This is the official library for PHP clients of the BlockScore API. [Click here to read the full documentation](http://docs.blockscore.com/php).

## Options

When making your API request, you can pass in various options as an array. More information is available in the [documentation](http://docs.blockscore.com).

Example:

```php
$options = array(
  'offset' => 20,
  'count' => 10,
  'start_date' => '1399335994',
  'end_date' => '1399462730',
  'filter[status]' => 'valid',
);

$person = Person::create($person_array, $options);
```

### Available Options

- offset
- count
- start_date
- end_date
- filter[key]
- [$attribute[$comparator]](http://docs.blockscore.com/v4.0/curl/#filtering)
- filter[q] \(Fuzzy search)

## Contributing to BlockScore
 
* Check out the latest master to make sure the feature hasn't been implemented or the bug hasn't been fixed yet.
* Check out the issue tracker to make sure someone already hasn't requested it and/or contributed it.
* Fork the project.
* Start a feature/bugfix branch.
* Commit and push until you are happy with your contribution.
* Make sure to add tests for it. This is important so I don't break it in a future version unintentionally.
* Please try not to mess with the Rakefile, version, or history. If you want to have your own version, or is otherwise necessary, that is fine, but please isolate to its own commit so I can cherry-pick around it.

## Copyright

Copyright (c) 2015 BlockScore. See `LICENSE.txt` for further details.
