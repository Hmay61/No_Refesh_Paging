<?php

	//create the traditional paging, connect mysql, get the data and show the paging
	@mysql_connect('192.168.1.107','root','0601') or die(mysql_error());
	mysql_select_db('data');

	//2:get the total rows number
	$sql1 = "select * from products";
	$rs1 = mysql_query($sql1); 
	$total = mysql_num_rows($rs1);
	$per = 5;

	//3:
	$pageCount = ceil($total/$per);

	//paging part, get the current pageNo
	$pageNo = isset($_GET['pageNo'])?$_GET['pageNo']:1;
	if($pageNo<1){
		$pageNo=1;
	}
	if($pageNo>$pageCount){
		$pageNo=$pageCount;
	}

	//get the StartNo
	$startNo = ($pageNo-1)*$per;

	//get the content of the startNo
	$sql ="SELECT proID as id,proName as nm,proQuantity as qu,proPrice as pr,proImage as im FROM `products` limit $startNo,$per";   //资源型结果集，里面有好多数据, 我们先把名称变少一点，为了节省带宽
	$rs = mysql_query($sql);

	$info = array();
	while($rows=mysql_fetch_assoc($rs)){
		$info[] = $rows;  //this is two array.
	}
	
	$pagelist = "";
	for($i=1;$i<=$pageCount;$i++){
		$pagelist.= "<a href='javascript:showpage(\"./05json_page.php?pageNo={$i}\")'>$i</a>&nbsp;";
	}
	$pagelist.="<a href='javascript:showpage(\"./05json_page.php?pageNo=1\")'>first</a>&nbsp;";
	$p1 = (string)$pageNo-1;
	$pagelist.="<a href='javascript:showpage(\"./05json_page.php?pageNo=$p1\")'>previous</a>&nbsp";
	$p2 = (string)$pageNo+1;
	$pagelist.="<a href='javascript:showpage(\"./05json_page.php?pageNo=$p2\")'>next</a>&nbsp";
	$pagelist.="<a href='javascript:showpage(\"./05json_page.php?pageNo=$pageCount\")'>last</a>&nbsp;";
	//echo $pagelist;
	$info[] = $pagelist;

	//print_r($info);   //index-array and assoc array inside it
	                  /*Array
							(
							    [0] => Array
							        (
							            [id] => 1
							            [nm] => strawberry1
							            [qu] => 10
							            [pr] => 5
							            [im] => images/1.png
							        )

							    [1] => Array
							        (
							            [id] => 2
							            [nm] => banana
							            [qu] => 10
							            [pr] => 2
							            [im] => images/2.png
							        )
							   ）  
						*/
	echo json_encode($info);  //[{},{},{},{},{}]



	//we need to combine all the information then use only one time json_encode（）
?>

