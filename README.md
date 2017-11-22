# bittrex
PHP wrapper class for Bittrex API

This class is a wrapper for the Bittrex altcoin trader platform API https://bittrex.com/home/api. 

You can use it to check market values, do tradings with your wallet, deposit and withdraw coins, write your own trading bot, etc.



include 'BittrexAPI.php';

$bittrex_api = new BittrexAPI();

$res = $bittrex_api->getOrderbook(array('market=BTC-LTC','type=sell'));


