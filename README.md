blockscore-php
==============

PHP class for interacting with BlockScore's API (https://manage.blockscore.com/docs/index)


Usage
==============
    require_once('blockscore.class.php');
    $blockscore = new blockscore(YOUR_API_KEY);
    $VerifyIntlResult = $blockscore->VerifyIntl($name, $dob, $passport, $address);
    $VerifyUSResult = $blockscore->VerifyUS($name, $dob, $lastfour, $address);
    $QuestionSet = $blockscore->QuestionSet();
    $QuestionResults = $blockscore->CheckQuestionAnswers(array($answers));

Please see `examples.php` for more information.
