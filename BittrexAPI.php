<?php

/**
 * Bittrex API wrapper class
 * @author yuriy <eurweb@gmail.com>
 * @created 21.11.2017
 */

class BittrexAPI
{
	const BASE_URL   = 'https://bittrex.com/api/v1.1/';
	
	// public api
	private $public_set = array('getmarkets','getcurrencies','getticker','getmarketsummaries','getorderbook','getmarkethistory');
	// private
	private $market_set = array('buylimit','selllimit','cancel','getopenorders');
	private $account_set = array('getbalances','getbalance','getdepositaddress','withdraw','getorder','getorderhistory','getwithdrawalhistory','getdeposithistory');
	
	private $apiKey    = 'YYYYY';
	private $apiSecret = 'XXXXX';
	
	private $private_api = false;
    
	public function __construct ($apiKey='', $apiSecret='')
	{
		$this->apiKey    = $apiKey;
		$this->apiSecret = $apiSecret;
	}
	
	public function  __call($name, $req  = array())
	{
		$name = strtolower ($name);
		if (sizeof($req))
			$req = $req[0];
		return $this->apiCall($name, $req);
	}
	
   private function apiCall($method, array $req) 
   {
   	  $url = $this->makeUrl($method, $req);
      static $ch = null;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; Cryptopia.co.nz API PHP client; FreeBSD; PHP/'.phpversion().')');
      curl_setopt($ch, CURLOPT_URL, $url );
      
      if ($this->private_api)
      {
      	$nonce=time();
      	$sign_headers = hash_hmac('sha512', $uri, $this->apiSecret);
      	curl_setopt($ch, CURLOPT_HTTPHEADER, $sign_headers);
      }
      // run the query
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); // Do Not Cache
      $res = curl_exec($ch);
      if ($res === false)
      {
      	throw new Exception('Could not get reply: '.curl_error($ch));
      }
      //var_dump($res);
      $res = json_decode($res);
      
      if ($res->success == false) 
      {
      	throw new Exception ($res->message);
      }
      
      return $res;  
   }
   
   private function makeUrl($method, $req)
   {
   	if ( in_array( $method ,$this->public_set ) )
   	{
   		$this->private_api = false;
   		$url = self::BASE_URL.'public/'. $method;
   	}
   	elseif (in_array( $method ,$this->market_set))
   	{
   		$this->private_api = true;
   		$url = self::BASE_URL.'market/'. $method;
   	}
   	elseif (in_array( $method ,$this->account_set))
   	{
   		$this->private_api = true;
   		$url = self::BASE_URL.'account/'. $method;
   	}
   	else
   	{
   		throw new Exception('Unknow method: '.$method);
   	}

   	if (sizeof($req))
   	{
   		//$url .= '?'.http_build_query($req);
   		$args = join('&' , $req);
   		$url= $url. '?' . $args;
   	}
   	$url= trim(preg_replace('/\s+/', '', $url));
   	return $url;
   }
}
?>