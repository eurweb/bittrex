<!DOCTYPE html>
<html>
<head>
<title>Bittrex APi</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script>
<!--
function callAjax(market)
{
	$("#loader").show();
    $.ajax({
        type: "GET",
        dataType: 'json',
        data:"market="+market,
        url: "ajax.php",
        success: function(msg){
        	$("#loader").hide();
        	if(msg.success)
        	{
        		//alert ( JSON.stringify( msg.result ) );
        		$('#orderbook').html('');
        		$.each(msg.result, function(i, star) {
                    $('#orderbook').append(star+"\n");
                });
            }
        	else
        	{
            	alert(msg.message);
            }
			
        },
        error: function(){
        	return true;
        },
    });
    return false;
}

$(document).ready(function(){
	$("#loader").hide();
	$("#target").change(function() 
	{
		market = $("#target option:selected").text();
		//$("#div_current_markt").html(market); 
		callAjax(market); 
	});
});	
//-->
</script>
</head>
<body>

<div class="container">
<div style="width: 400;margin: 100px auto;">
<div id="loader">please wait .....</div>
Currencies:
<?php
include 'api/BittrexAPI.php';
try
{
	$bittrex_api = new BittrexAPI();
	$res = $bittrex_api->getMarkets();
	echo '<select id="target">';
	foreach ($res->result as $market)
	{
		echo '<option>'.$market->MarketName.'</option>';
	}
	echo '</select>';
}
catch(Exception $e)
{
	echo $e->getMessage() . PHP_EOL;
	
}

?>
<br/>
<div id="div_current_markt"></div>
<br/>
<textarea id="orderbook" 
style="width:250px;height:150px;border:1px solid #ccc;overflow-y:auto;background:#F2F2F2;">
	
</textarea>
</div>
</div>

</body>
</html>
