<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT * FROM sales_flat_order_item";
$result = $conn->query($query);
$products=array();
$response=array();
$i=0;
if($result->num_rows > 0)
{      
	while($row=$result->fetch_assoc())
	{	
		$date=$row['created_at'];
		$orderdate=explode(' ',$date);
		$day=explode('-',$orderdate[0]);
		// echo $orderdate[0];
			$currentdate=date("Y-m-d");
			// $currentday=explode('-',$currentdate);
			// echo $currentdate;
		
		 if($orderdate[0]==$currentdate){
			$price=$row['price']*$row['qty_ordered'];
			$res=MatchProducts($row['name'], $price,$row['qty_ordered']);
			if(!$res)
			{
			$response[]=array(
			"Name"=>$row['name'],
			"Quantity"=>$row['qty_ordered'],
			"Price"=>$price);
		    	}
		}
	}
	// echo "<pre/>";
	echo json_encode($response);
}
else
{
	echo -1;
	$conn->close();
}
function MatchProducts($pro,$pri,$qua){
	// print_R($response);
	if(count($GLOBALS['response'])>0){
		
		for($i=0;$i<count($GLOBALS['response']);$i++)
		{
			if($GLOBALS['response'][$i]['Name']==$pro)
			{
			$GLOBALS['response'][$i]['Price']=$GLOBALS['response'][$i]['Price']+$pri;
			$GLOBALS['response'][$i]['Quantity']=$GLOBALS['response'][$i]['Quantity']+$qua;
			return true;
			}
		}
	}
	else {
	 return false;
  		}
	}
$conn->close();
?>