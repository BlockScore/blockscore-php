<?php
require_once('blockscore.class.php');

$blockscore = new blockscore('sk_test_n5049aa6053c9a0217bea78070fbf501');

// International Verification (new in API v4: both US & Int'l use the same function)
$name['first'] = 'John';
$name['middle'] = 'W';
$name['last'] = 'Smith';
$dob = '1980-10-10';
$documentNum = 'X110000';
$documentType = 'passport'; //accepted values vary by country, see http://docs.blockscore.com/v4.0/curl/#documents
$address['street1'] = 'Bahnhofstrasse 70';
$address['street2'] = '';
$address['city'] = 'Zurich';
$address['state'] = 'ZH';
$address['postal_code'] = '8001';
$address['country_code'] = 'CH';
// Optional values
$telephone = '+41-22-767-0000';
$ip = 'FE80:0000:0000:0000:0202:B3FF:FE1E:8329';  //or $ip = '127.0.0.1';
$note = 'Test user at ' . date("Y-m-d H:i:s") . ' on ' . gethostname();

try {
	$VerifyIntlResult = $blockscore->Blockscore_Verification($name, $dob, $documentNum, $documentType, $address, $telephone, $ip, $note);
} catch (Exception $e) {
	die( 'Caught exception: ' .  $e->getMessage() . "\n" );
}

echo "VerifyIntlResult: <xmp>";
var_dump($VerifyIntlResult);
echo "</xmp>\n";


// US Verification (new in API v4: both US & Int'l use the same function)
$name['first'] = 'John';
$name['middle'] = 'W';
$name['last'] = 'Smith';
$dob = '1980-10-10';
$documentNum = 'A880000';
$documentType = 'drivers_license'; //accepted values vary by country, see http://docs.blockscore.com/v4.0/curl/#documents
$address['street1'] = '123 Broadway Ave';
$address['street2'] = '';
$address['city'] = 'New York';
$address['state'] = 'NY';
$address['postal_code'] = '10011';
$address['country_code'] = 'US';
// Optional values
$telephone = '+1-212-555-1234';
$ip = 'FE80:0000:0000:0000:0202:B3FF:FE1E:8329';  //or $ip = '127.0.0.1';
$note = 'Test user at ' . date("Y-m-d H:i:s") . ' on ' . gethostname();

try {
	$VerifyUSResult = $blockscore->Blockscore_Verification($name, $dob, $documentNum, $documentType, $address, $telephone, $ip, $note);
} catch (Exception $e) {
	die( 'Caught exception: ' .  $e->getMessage() . "\n" );
}

echo "VerifyUSResult: <xmp>";
var_dump($VerifyUSResult);
echo "</xmp>\n";


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
	echo "</xmp>\n";
	
	// Question Set Answer Checking
	if(!empty($QuestionSet['id']))
	{
		try {
			$QuestionResults = $blockscore->CheckQuestionAnswers(array(array('question_id'=>1, 'answer_id'=>rand(1,5)),array('question_id'=>2, 'answer_id'=>rand(1,5)),array('question_id'=>3, 'answer_id'=>rand(1,5)),array('question_id'=>4, 'answer_id'=>rand(1,5)),array('question_id'=>5, 'answer_id'=>rand(1,5))));
		} catch (Exception $e) {
				die( 'Caught exception: ' .  $e->getMessage() . "\n" );
		}
		
		echo "Answer Checking Result: <xmp>";
		var_dump($QuestionResults);
		echo "</xmp>\n";
		
		try {
			$QuestionSetRetrieve = $blockscore->RetrieveQuestionSet($QuestionSet['id']);
		} catch (Exception $e) {
			die( 'Caught exception: ' . $e->getMessage() . "\n" );
		}
		echo "Question Set Retrieval (after the fact; works in v4 API): <xmp>";
		var_dump($QuestionSetRetrieve);
		echo "</xmp>\n";
	}
}

// Candidate creation
$name['first'] = 'John';
$name['middle'] = 'W';
$name['last'] = 'Smith';
$dob = '1980-10-10';
$ssn = '123-45-6789';
$passport = ''; // Used for non-US candidates, only
$address['street1'] = '123 Broadway Ave';
$address['street2'] = '';
$address['city'] = 'New York';
$address['state'] = 'NY';
$address['postal_code'] = '10011';
$address['country_code'] = 'US';
$note = 'Test candidate at ' . date("Y-m-d H:i:s") . ' on ' . gethostname();
try {
	$CreateCandidateResult = $blockscore->CreateCandidate($name, $dob, $ssn, $passport, $address, $note);
} catch (Exception $e) {
	die( 'Caught exception: ' .  $e->getMessage() . "\n" );
}

echo "CreateCandidateResult: <xmp>";
var_dump($CreateCandidateResult);
echo "</xmp>\n";

// Candidate edit
if (!empty($CreateCandidateResult['id'])) {
	try {
		$name['first'] = '';
		$name['middle'] = '';
		$name['last'] = '';
		$dob = '';
		$ssn = '987-65-4321';
		$passport = ''; // Used for non-US candidates, only
		$address['street1'] = '';
		$address['street2'] = '';
		$address['city'] = '';
		$address['state'] = '';
		$address['postal_code'] = '';
		$address['country_code'] = '';
		$EditCandidateResult = $blockscore->EditCandidate($CreateCandidateResult['id'], $name, $dob, $ssn, $passport, $address, $note);
	} catch (Exception $e) {
		die( 'Caught exception: ' .  $e->getMessage() . "\n" );
	}
	echo "EditCandidateResult: <xmp>";
	var_dump($EditCandidateResult);
	echo "</xmp>\n";
}

// Search watchlists
if (!empty($CreateCandidateResult['id'])) {
	try {
		$entityType = 'person';
		$SearchWatchlistsResult = $blockscore->SearchWatchlists($CreateCandidateResult['id'], $entityType);
	} catch (Exception $e) {
		die( 'Caught exception: ' .  $e->getMessage() . "\n" );
	}
	echo "SearchWatchlistsResult: <xmp>";
	var_dump($SearchWatchlistsResult);
	echo "</xmp>\n";
}
