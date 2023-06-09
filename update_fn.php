<?php

include("liga_bd.php");
$post_order = isset($_POST["post_order_ids"]) ? $_POST["post_order_ids"] : [];
if(count($post_order)>0){
	for($order_no= 0; $order_no < count($post_order); $order_no++)
	{
        $query = "UPDATE question SET order_num = '".($order_no+1)."' WHERE id = '".$post_order[$order_no]."'";
        mysqli_query($link, $query);
	}
	echo true;
}else{
	echo false;
}

?>