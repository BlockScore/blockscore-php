<?php
/**
* Blockscore API Library
*
* https://github.com/bayareamarketing/blockscore-php
*
* @copyright @bayareamarketing on GitHub
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
*
* @author @bayareamarketing on GitHub
* @version 1.0.4
*
*/
class blockscore
{
const base_url = 'https://api.blockscore.com/';
const api_version = '4';
private $auth_key = null;
private $verification_id = null;
private $question_set_id = null;

/**
* Constructor function for all new blockscore instances
*
* Store key for later requests
*
* @param String $key
* @throws Exception if not a valid authentication key
* @return blockscore
*/
public function __construct($key="")
{
	if(!empty($key))
		$this->auth_key = $key;
	else
		throw new Exception('blockscore: No key (or empty key) provided to constructor');
}

/**
* Verify
* @param Array $name All three names of consumer e.g. array('first'=>'Joe', 'middle'=>'', 'last'=>'Smith')
* @param String $dob Date of birth in YYYY-MM-DD format
* @param String $identification Identification number (DL#, ID card number, SSN, last four of SSN...)
* @param String $idtype Identification type (ssn, drivers_license, passport, personal_identification, etc. See http://docs.blockscore.com/v4.0/curl/#documents)
* @param Array $address Full address of consumer e.g. array('street1'=>'20 Main', 'street2'=>'Ste 4', 'city'=>'Springfield', 'state'=>'IL', 'postal_code'=>'99999', 'country_code'=>'US')
* @param String $telephone Consumer telephone (optional)
* @param String $ip Consumer IP address (v4 / v6; optional)
* @param String $note Note to include with verification request, stored on BlockScore's end (optional)
* @throws Exception if request fails (see private function request() for details)
* @return Array
*/
public function Blockscore_Verification($name, $dob, $identification, $idtype, $address, $telephone = "", $ip = "", $note = "") {
	list($dobY, $dobM, $dobD) = explode("-", $dob);
	$postVars = array("name_first"=>$name['first'], "name_middle"=>$name['middle'], "name_last"=>$name['last'],	
		"birth_day" => $dobD, "birth_month" => $dobM, "birth_year" => $dobY, 
		"document_type" => $idtype, "document_value" => $identification,
		"address_street1" => $address['street1'], "address_street2" => $address['street2'],
		"address_city" => $address['city'], "address_subdivision" => $address['state'],  
		"address_postal_code" => $address['postal_code'], "address_country_code" => $address['country_code'],
		"ip_address"=>$ip, "phone_number"=>$telephone, "note"=>$note);
	return $this->request('POST', 'people', http_build_query($postVars));
}

/**
* List past verifications
* @param Int $offset How many items to offset the results (optional)
* @param Int $count Number of results to show, 0-100 (optional)
* @param Int $start_date Unix timestamp of start date (optional)
* @param Int $end_date Unix timestamp of end date (optional)
* @throws Exception if request fails (see private function request() for details)
* @return Array
*/
public function ListVerificationResults($offset = 0, $count = 100, $start_date = 0, $end_date = 0) {
	$verb = "verifications?offset={$offset}&count={$count}";
	if($start_date > 0)
		$verb .= "&start_date={$start_date}";
	if($end_date > 0)
		$verb .= "&end_date={$end_date}";
	return $this->request('GET', $verb);
}

/**
* List one verification by verification_id
* @param String $verificationid Verification ID from prior request (optional, assuming prior request was with the same object)
* @throws Exception if empty/unset verification_id, or if request fails (see private function request() for details)
* @return Array
*/
public function GetVerificationResult($verificationid) {
	if(empty($verificationid) && empty($this->verification_id))
		throw new Exception('blockscore: No (or empty) verification_id provided to GetVerificationResult');
	elseif(empty($verificationid) && !empty($this->verification_id))
		$verificationid = $this->verification_id;
	return $this->request('GET', 'people/'.$verificationid);
}

/**
* Get question set
* @param String $verificationid Verification ID from prior request (optional, assuming prior request was with the same object)
* @throws Exception if empty/unset verification_id, or if request fails (see private function request() for details)
* @return Array
*/
public function QuestionSet($verificationid = "") {
	if(empty($verificationid) && empty($this->verification_id))
		throw new Exception('blockscore: No (or empty) verification_id provided to QuestionSet');
	elseif(empty($verificationid) && !empty($this->verification_id))
		$verificationid = $this->verification_id;
	return $this->request('POST', 'question_sets', http_build_query(array('person_id'=>$verificationid)));
}

/**
* Retrieve previously-requested question set
* @param String $questionsetid Question Set ID from prior request (optional, assuming prior request was with the same object)
* @throws Exception if empty/unset question_set_id, or if request fails (see private function request() for details)
* @return Array
*/
public function RetrieveQuestionSet($questionsetid = "") {
	if(empty($questionsetid) && empty($this->question_set_id))
		throw new Exception('blockscore: No (or empty) question_set_id provided to RetrieveQuestionSet');
	elseif(empty($questionsetid) && !empty($this->question_set_id))
		$questionsetid = $this->question_set_id;
	return $this->request('GET', 'question_sets/'.$questionsetid);
}

/**
* Check the answers to a set of challenge questions
* @param Array $answers All answers by question ID, e.g. array( array('question_id'=>1, 'answer_id'=>2), array('question_id'=>2, 'answer_id'=>4) );
* @param String $verificationid Verification ID from prior request (optional, assuming prior request was with the same object)
* @param String $questionsetid Question Set ID from prior request (optional, assuming prior request was with the same object)
* @throws Exception if empty/unset verification_id/question_set_id, or if request fails (see private function request() for details)
* @return Array
*/
public function CheckQuestionAnswers($answers, $verificationid = "", $questionsetid = "") {
	if(empty($verificationid) && empty($this->verification_id))
		throw new Exception('blockscore: No (or empty) verification_id provided to CheckQuestionAnswers');
	elseif(empty($verificationid) && !empty($this->verification_id))
		$verificationid = $this->verification_id;
	if(empty($questionsetid) && empty($this->question_set_id))
		throw new Exception('blockscore: No (or empty) question_set_id provided to CheckQuestionAnswers');
	elseif(empty($questionsetid) && !empty($this->question_set_id))
		$questionsetid = $this->question_set_id;
	if(empty($answers))
		throw new Exception('blockscore: No (or empty) answer array provided to CheckQuestionAnswers');
	return $this->request('POST', 'question_sets/'.$questionsetid.'/score', json_encode(array('verification_id'=>$verificationid, 'question_set_id'=>$questionsetid, 'answers'=>$answers)));
}

/**
* (Private) Build HTTP request
* @param String $method E.g. GET or POST
* @param String $verb Path to API method, e.g. 'verifications' or 'questions/score'
* @throws Exception if request fails (HTTP error, API-level error, or response parsing error)
* @return Array
*/
protected function request($method, $verb, $postData = "") {
	$header = "Accept: application/vnd.blockscore+json;version=".blockscore::api_version."\r\n"
	. sprintf('Authorization: Basic %s', base64_encode($this->auth_key.':') ) . "\r\n";
	$httpCtxt = array('method'=>$method);
	if($method == "POST")
	{
		if($verb == 'questions/score' || (substr($verb, 0, 14) == 'question_sets/' && substr($verb, -6) == '/score'))
			$header .= "Content-type: application/json\r\n";
		else
			$header .= "Content-type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($postData) . "\r\n";
		$httpCtxt['content'] = $postData;
	}
	$httpCtxt['header'] = $header;
	$httpCtxt['ignore_errors'] = true;
	$result = file_get_contents(blockscore::base_url . $verb, false, stream_context_create(array ( 'http' => $httpCtxt )));
	if($result === false)
		throw new Exception('blockscore: Unable to retrieve ' . blockscore::base_url . $verb . ': ' . $http_response_header);
	$jsonResult = json_decode($result, true);
	if(json_last_error() == JSON_ERROR_NONE && (!empty($jsonResult['id']) || !empty($jsonResult['verification_id']) || !empty($jsonResult['question_set_id'])))
	{
		if(!empty($jsonResult['id']) && $verb == 'people')
			$this->verification_id = $jsonResult['id'];
		if(!empty($jsonResult['question_set_id']))
			$this->question_set_id = $jsonResult['question_set_id'];
		elseif(!empty($jsonResult['id']) && $verb == 'question_sets')
			$this->question_set_id = $jsonResult['id'];
		return $jsonResult;
	}
	elseif(json_last_error() == JSON_ERROR_NONE && !empty($jsonResult['error']))
		throw new Exception('blockscore: API Error '.$jsonResult['error']['type'].': '.$jsonResult['error']['message']);
	elseif(!empty($result) && json_last_error() != JSON_ERROR_NONE)
		throw new Exception('blockscore: Could not parse JSON response: '.$result);
	throw new Exception('blockscore: Unable to retrieve ' . blockscore::base_url . $verb . ': ' . $http_response_header);
}

/* To Be Depreciated */
/**
* Verify US (left for backwards compatibility, use country-agnostic Blockscore_Verification method instead)
* @param Array $name All three names of consumer e.g. array('first'=>'Joe', 'middle'=>'', 'last'=>'Smith')
* @param String $dob Date of birth in YYYY-MM-DD format
* @param String $lastfour Last four digits of SSN
* @param Array $address Full address of consumer e.g. array('street1'=>'20 Main', 'street2'=>'Ste 4', 'city'=>'Springfield', 'state'=>'IL', 'postal_code'=>'99999')
* @param String $telephone Consumer telephone
* @param String $ip Consumer IP address (v4 / v6)
* @throws Exception if request fails (see private function request() for details)
* @return Array
*/
public function Blockscore_Verification_US($name, $dob, $ssn, $address, $telephone = "", $ip = "") {
	$address['country_code'] = 'US';
	return $this->Blockscore_Verification($name, $dob, $ssn, 'ssn', $address, $telephone, $ip, "");
}

/**
* Verify International (left for backwards compatibility, use country-agnostic Blockscore_Verification method instead)
* @param Array $name All three names of consumer plus gender e.g. array('first'=>'Joe', 'middle'=>'', 'last'=>'Smith', 'gender'=>'M')
* @param String $dob Date of birth in YYYY-MM-DD format
* @param String $passport Full passport number
* @param Array $address Full address of consumer e.g. array('street1'=>'Bahnhofstrasse 70', 'street2'=>'', 'city'=>'Zurich', 'state'=>'ZH', 'postal_code'=>'8001', 'country_code'=>'CH')
* @param String $telephone Consumer telephone
* @param String $ip Consumer IP address (v4 / v6)
* @throws Exception if request fails (see private function request() for details)
* @return Array
*/
public function Blockscore_Verification_Intl($name, $dob, $passport, $address, $telephone = "", $ip = "") {
	return $this->Blockscore_Verification($name, $dob, $passport, 'passport', $address, $telephone, $ip, "");
}

} //class blockscore
?>
