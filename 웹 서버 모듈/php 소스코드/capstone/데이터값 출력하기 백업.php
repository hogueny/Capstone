

<?php

echo "mysql connect <br>";

$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = '1234';
$mysql_db = 'dust';

// Mysql Connection

$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password ,$mysql_db);
$dbconn = mysqli_select_db( $conn, $mysql_db);

$sql=" 
select * from (
SELECT dt_create, pm010, pm025, pm100
FROM  `finedust` 
order by dt_create desc
limit 10  
)t_a
order by t_a.dt_create";

//echo $sql;
 
$result = mysqli_query($conn, $sql) ;

$str_mdh="";
$str_atemper="";

while ($row = mysqli_fetch_array($result)) {
	echo($row['dt_create']."__[PM1.0: ".$row['pm010']."]__[PM2.5: ".$row['pm025']."]__[PM10: ".$row['pm100']."]<br>");
	$str_mdh .="'".$row['mdh']."',";
	$str_pm010 .="".$row['pm010'].",";
	$str_pm025 .="".$row['pm025'].",";
	$str_pm100 .="".$row['pm100'].",";
}

$str_mdh= substr($str_mdh,0,-1);
$str_pm010= substr($str_pm010,0,-1);
$str_pm025= substr($str_pm025,0,-1);
$str_pm100= substr($str_pm100,0,-1);

//echo $str_pm010;
//echo $str_pm025;
//echo $str_pm100;
?>