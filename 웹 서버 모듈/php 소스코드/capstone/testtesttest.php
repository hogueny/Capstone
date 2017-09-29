<?php
	
//DB 연결
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = '1234';
$mysql_db = 'cap';

// Mysql Connection
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password ,$mysql_db);
$dbconn = mysqli_select_db( $conn, $mysql_db);

	
$sql2="
select * from (
SELECT DATE_FORMAT( dt_create,  '%Y년 %m월 %d일 %h시 %i분 %s초') as dt_create , COUNT( * ) cnt, SUM( g_num ) , 
round(SUM( g_num ) / COUNT( * ))  as g_num
FROM  number 
WHERE  day(dt_create) = '01' and year(dt_create) = '2017'
GROUP BY DATE_FORMAT( dt_create,  '%Y%m%d%H' )
order by dt_create desc
limit 12
) gas
order by gas.dt_create
";

$result2 = mysqli_query( $conn,$sql2 )or die(mysqli_error($conn));
 
 

$str_date2="";
$str_num2=0;
$num=0; 
$num2=0;
// 온습도 문자열 연결
while ($row2 = mysqli_fetch_array($result2)) {
 $num = $str_num2;

 $str_date2 .="'".$row2['dt_create']."',";
 $str_num2 =$row2['g_num'];
 $num2 .="".(($str_num2 - $num)*4).",";
 #echo $str_date2;
 #echo "<br>";
# echo $str_num2;
# echo "<br>";
 #echo $num2;
 #echo "<br>";
 
}
 
// 오른쪽 공백 제거
$str_date2= substr($str_date2,0,-1);
$num2= substr($num2,0,-1);

 
 
?>





<!DOCTYPE HTML>
<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>가스 검침량</title>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <style type="text/css">${demo.css}</style>
        <script type="text/javascript">
 
$(function () {
    $('#temp').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: '가스 검침 결과'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [<?php echo $str_date2?>]
        },
        yAxis: [{ // Primary yAxis
        labels: {
            format: '{value}',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        title: {
            text: '검침량',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        }
    }, { // Secondary yAxis
        title: {
            text: '온도',
            style: {
                color: Highcharts.getOptions().colors[0]
            }
        },
        labels: {
            format: '{value} C',
            style: {
                color: Highcharts.getOptions().colors[0]
            }
        },
        opposite: true
    }],
    tooltip: {
        shared: true
    },

 legend: {
        layout: 'vertical',
        align: 'center',
        x: 120,
        verticalAlign: 'top',
        y: 100,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
    },

        series: [{
               name: '사용자',
	type: 'column',
  	data: [<?php echo $num2?>]
       
}]
      });
  
});
        </script>
</head>
<body>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <div id="temp" style="width: 1000px; height: 440px; margin: 30px auto"></div>
    
</body>
</html>

