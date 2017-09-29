<?php

$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = '1234';
$mysql_db = 'cap';

// 접속
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password);
$dbconn = mysqli_select_db($conn,$mysql_db);


// charset 설정, 설정하지 않으면 기본 mysql 설정으로 됨, 대체적으로 euc-kr를 많이 사용
//mysqli_query("set names utf8");

$sql="SELECT * FROM number";
echo $sql;

 

$result = mysqli_query($conn,$sql) ;


$str_dt_create="";
$str_g_num="";
while ($row = mysqli_fetch_array($result)) {
 echo($row['dt_create']."--------------".$row['g_num']."<br>");
 $str_dt_create .="'".$row['dt_create']."',";
 $str_g_num .="".$row['g_num'].",";
}
$str_dt_create= substr($str_dt_create,0,-1);
$str_g_num= substr($str_g_num,0,-1);
//echo $str_dt_create;

?>
<!DOCTYPE HTML>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Temperature Example</title>

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <style type="text/css">
${demo.css}
  </style>
  <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Average Temperature'
        },
        subtitle: {
            text: 'Source: ilikesan.com'
        },
        xAxis: {
            categories: [<?php echo $str_dt_create?>]
        },
        yAxis: {
            title: {
                text: 'Temperature (°C)'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: '수치',
            data: [<?php echo $str_g_num?>]
        }
  
  ]
    });
});
  </script>
 </head>
 <body>
<script src="/highchart/js/highcharts.js"></script>
<script src="/highchart/js/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

 </body>
</html>

