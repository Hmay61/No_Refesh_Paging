<?php

	//制作传统分页效果，连接数据库，获得数据，分页显示
	@mysql_connect('192.168.1.107','root','0601') or die(mysql_error());
	mysql_select_db('data');

	//2:获得总记录数
	$sql1 = "select * from products";
	$rs1 = mysql_query($sql1); 
	$total = mysql_num_rows($rs1);
	$per = 5;

	//3:
	$pageCount = ceil($total/$per);

	//分页部分，获得传递的当前页码
	$pageNo = isset($_GET['pageNo'])?$_GET['pageNo']:1;
	if($pageNo<1){
		$pageNo=1;
	}
	if($pageNo>$pageCount){
		$pageNo=$pageCount;
	}

	//求当前页的起始位置
	$startNo = ($pageNo-1)*$per;

	//获取当前页的内容
	$sql ="SELECT proID as id,proName as nm,proQuantity as qu,proPrice as pr,proImage as im FROM `products` limit $startNo,$per";   //资源型结果集，里面有好多数据, 我们先把名称变少一点，为了节省带宽
	$rs = mysql_query($sql);

	$info = array();
	while($rows=mysql_fetch_assoc($rs)){
		//$rows 代表每条记录的一维数组信息
		//要把全部的$rows整合到一起，变为一个二维数组
		$info[] = $rows;   
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

	//for($i=1;$i<=$pageCount;$i++){
		//'<a href="?pageNo='.$i.'">'.$i.'</a>&nbsp;'
	//}	
					//echo '<a href="?pageNo='.$i.'">'.$i.'</a>&nbsp;';
	//$info[] = $pageNo;

	//print_r($info);   //我们发现二维的是索引数组，二维里面的每一个是关联数组
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
	echo json_encode($info);  //最外面是数组，数组里面每个是json对象



		//需要把全部的信息整合一下
		//json_encode（）只能使用一次	
?>

