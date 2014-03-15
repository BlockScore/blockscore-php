# blockscore-php

This is the official library for PHP clients of the BlockScore API. [Click here to read the full documentation](https://manage.blockscore.com/docs).

## Dependencies

PHP 5.2+ w/ fopen wrapper and SSL extensions enabled

## Getting Started

### Initializing BlockScore

```
require_once('blockscore.class.php');
$blockscore = new blockscore(YOUR_API_KEY);
```

## Verifications

### Create a new US verification

```
$VerifyUSResult = $blockscore->VerifyUS($name, $dob, $lastfour, $address);
```

### Create a new international verification

```
$VerifyIntlResult = $blockscore->VerifyIntl($name, $dob, $passport, $address);
```


## Question Sets

### Create a new question set

```
$QuestionSet = $blockscore->QuestionSet();
```

### Score a question set

```
$QuestionResults = $blockscore->CheckQuestionAnswers(array($answers));
```

## Examples

Please see `examples.php` for more information.

## Credit

Many thanks to FusionCash, Inc's Tyler Derheim for creating the initial PHP library!

## Contributing to BlockScore
 
* Check out the latest master to make sure the feature hasn't been implemented or the bug hasn't been fixed yet.
* Check out the issue tracker to make sure someone already hasn't requested it and/or contributed it.
* Fork the project.
* Start a feature/bugfix branch.
* Commit and push until you are happy with your contribution.
* Make sure to add tests for it. This is important so I don't break it in a future version unintentionally.
* Please try not to mess with the Rakefile, version, or history. If you want to have your own version, or is otherwise necessary, that is fine, but please isolate to its own commit so I can cherry-pick around it.

## Copyright

Copyright (c) 2014 BlockScore. See LICENSE.txt for further details.