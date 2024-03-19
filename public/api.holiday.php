<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: false');



/* PHP 샘플 코드 */

$ch = curl_init();
$url = 'http://apis.data.go.kr/B090041/openapi/service/SpcdeInfoService/getRestDeInfo'; /*URL*/
$queryParams = '?' . urlencode('ServiceKey') . '=0uFBsPAvdo7B7DD9qw9EpS%2FX14TMRt5nMctpFu4ZIRYa17dJqDzwmIAa52l0xK49yfnOsVpmKsYDmTUctmT0Sw%3D%3D'; /*Service Key*/
$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1'); /**/
$queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('100'); /**/
$queryParams .= '&' . urlencode('solYear') . '=' . urlencode('2021'); /**/
//$queryParams .= '&' . urlencode('solMonth') . '=' . urlencode('02'); /**/

curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);


$load_string = simplexml_load_string($response);
echo "<pre>";
print_r($load_string);
echo "</pre>";
?>
