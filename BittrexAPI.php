<?php

/**
 * Bittrex API wrapper class
 * An API wrapper class for the Bittrex altcoin exchange platform (https://bittrex.com/home/api).
 * @author yuriy eurweb@gmail.com
 * @created 21.11.2017
 */

class BittrexAPI
{
	const BASE_URL   = 'https://bittrex.com/api/v1.1/';
	
	// public api
	protected $public_set = array('getmarkets','getcurrencies','getticker','getmarketsummaries','getorderbook','getmarkethistory');
	// private
	protected $market_set = array('buylimit','selllimit','cancel','getopenorders');
	protected $account_set = array('getbalances','getbalance','getdepositaddress','withdraw','getorder','getorderhistory','getwithdrawalhistory','getdeposithistory');
	
	protected $apiKey    = 'YYYYY';
	protected $apiSecret = 'XXXXX';
	
	protected $curl;
	protected $private_api = false;
    
	public function __construct ($apiKey='', $apiSecret='')
	{
		$this->apiKey    = $apiKey;
		$this->apiSecret = $apiSecret;
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
	}
	
	public function  __call($name, $req)
	{
		$name = strtolower ($name);
		if (sizeof($req))
			$req = $req[0];
		return $this->apiCall($name, $req);
	}
	
   protected function apiCall($method, array $req) 
   {
   	  $url = $this->makeUrl($method, $req);
   	  
       //curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; Cryptopia.co.nz API PHP client; FreeBSD; PHP/'.phpversion().')');
       curl_setopt($this->curl, CURLOPT_URL, $url );
      
      if ($this->private_api)
      {
      	$sign_headers = hash_hmac('sha512', $uri, $this->apiSecret);
      	curl_setopt($this->curl, CURLOPT_HTTPHEADER, $sign_headers);
      }
      // run the query
      curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, TRUE); // Do Not Cache
      $res = curl_exec($this->curl);
      if (!$res)
      {
      	throw new Exception('Could not get reply: '.curl_error($this->curl));
      }
      $res = json_decode($res); 
      if (!$res->success) 
      {
      	throw new Exception ($res->message);
      }
      
      return $res;  
   }
   
   protected function makeUrl($method, $req)
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
		$req['nonce']=time();
   		$args = join('&' , $req);
   		$url= $url. '?' . $args;
   	}
   	$url= trim(preg_replace('/\s+/', '', $url));
   	return $url;
   }
}
?>
