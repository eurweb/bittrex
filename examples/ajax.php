<?php 
include 'api/BittrexAPI.php';
$result = array();
$type_set = array('sell','buy');
$market =  trim(preg_replace('/[^A-Z0-9-]/', '', $_GET['market']));
$type = 'sell';//trim($_GET['type']);

try
{
	if (!in_array( $type , $type_set))
	{
		throw new Exception ('unknow type val');
	}
	$bittrex_api = new BittrexAPI();
	$res = $bittrex_api->getOrderbook(array('market='.$market,'type='.$type));
	$i = 1;
	$prices= array();
	foreach ($res->result as $market)
	{
		if (is_numeric($market->Rate))
			$prices[] = $market->Rate;
	}
	sort($prices, SORT_NUMERIC);
	$prices= array_slice($prices, 0, 5);
	$result['success'] = true;
	$result['result'] = $prices;
}
catch(Exception $e)
{
	$result['success'] = false;
	$result['message'] =  $e->getMessage();
}
echo json_encode($result);
?>