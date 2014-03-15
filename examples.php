<?php
require_once('blockscore.class.php');

$blockscore = new blockscore('sk_test_n5049aa6053c9a0217bea78070fbf501');

// International Verification
$name['first'] = 'John';
$name['middle'] = 'W';
$name['last'] = 'Smith';
$name['gender'] = 'M';
$dob = '1980-10-10';
$passport = 'X110000';

$address['street1'] = 'Bahnhofstrasse 70';
$address['street2'] = '';
$address['city'] = 'Zurich';
$address['state'] = 'ZH';
$address['postal_code'] = '8001';
$address['country_code'] = 'CH';

try {
    $VerifyIntlResult = $blockscore->VerifyIntl($name, $dob, $passport, $address);
} catch (Exception $e) {
    die( 'Caught exception: ' .  $e->getMessage() . "\n" );
}

echo "VerifyIntlResult: <xmp>";
var_dump($VerifyIntlResult);
echo "</xmp>";


// US Verification
$name['first'] = 'John';
$name['middle'] = 'W';
$name['last'] = 'Smith';
$dob = '1980-10-10';
$lastfour = '0000';

$address['street1'] = '123 Broadway Ave';
$address['street2'] = '';
$address['city'] = 'New York';
$address['state'] = 'NY';
$address['postal_code'] = '10011';

try {
    $VerifyUSResult = $blockscore->VerifyUS($name, $dob, $lastfour, $address);
} catch (Exception $e) {
    die( 'Caught exception: ' .  $e->getMessage() . "\n" );
}

echo "VerifyUSResult: <xmp>";
var_dump($VerifyUSResult);
echo "</xmp>";


// Question Set Retrieval
if(!empty($VerifyUSResult['id']))
{
	try {
		$QuestionSet = $blockscore->QuestionSet();
	} catch (Exception $e) {
	    die( 'Caught exception: ' .  $e->getMessage() . "\n" );
	}
	
	echo "QuestionSetResult: <xmp>";
	var_dump($QuestionSet);
	echo "</xmp>";
	
	// Question Set Answer Checking
	if(!empty($QuestionSet['question_set_id']))
	{
		try {
			$QuestionResults = $blockscore->CheckQuestionAnswers(array(array('question_id'=>1, 'answer_id'=>rand(1,5)),array('question_id'=>2, 'answer_id'=>rand(1,5)),array('question_id'=>3, 'answer_id'=>rand(1,5)),array('question_id'=>4, 'answer_id'=>rand(1,5)),array('question_id'=>5, 'answer_id'=>rand(1,5))));
		} catch (Exception $e) {
		    die( 'Caught exception: ' .  $e->getMessage() . "\n" );
		}
		
		echo "Answer Checking Result: <xmp>";
		var_dump($QuestionResults);
		echo "</xmp>";
	}
}
?>
