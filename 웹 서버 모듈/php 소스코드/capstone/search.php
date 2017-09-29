<?php
echo $_POST['dt_create'];
?>

<form action="./search_list.php" method="post" name="form" onSubmit="return check()">
   <center>
   <table>
      <tr>
         
        <br><br> 날짜를 선택하세요 <br><br>
      </tr>
      <tr>
             <select name="year">  
                <option selected>년 </option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
	 <option value="2017">2017</option>
                <option value="2018">2018</option>
	 <option value="2019">2019</option>
                <option value="2020">2020</option>
             </select>
      </tr>
  &nbsp  &nbsp<tr>
         
             <select name="month"> 
                <option selected>월 </option>
                <option value="1">01</option>
                <option value="2">02</option>
	 <option value="3">03</option>
                <option value="4">04</option>
	 <option value="5">05</option>
                <option value="6">06</option>
	 <option value="7">07</option>
                <option value="8">08</option>
                <option value="9">09</option>
                <option value="10">10</option>
	 <option value="11">11</option>
                <option value="12">12</option>
             </select>

        
         <tr>&nbsp<input type="submit" value="조회" /></tr> 
      </tr>
   </table>
   </center>
</form>

<?php 
 
//DB 연결
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = '1234';
$mysql_db = 'cap';

// Mysql Connection
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password ,$mysql_db);
$dbconn = mysqli_select_db( $conn, $mysql_db);



$sql="
select * from (
SELECT DATE_FORMAT( dt_create,  '%Y년 %m월 %d일 %h시 %i분 %s초') as dt_create , COUNT( * ) cnt, SUM( g_num ) , 
round(SUM( g_num ) / COUNT( * ))  as g_num
FROM  number 
GROUP BY DATE_FORMAT( dt_create,  '%Y%m%d%H' )
order by dt_create desc

) gas
order by gas.dt_create
";


$result = mysqli_query( $conn,$sql )or die(mysqli_error($conn));
 
echo("
    <html>
    <head><title>데이터 </title></head>
    <body>
    <center>

    <table width='1000' border='1' >
    <tr>
    <td width='50%' align='center'  bgcolor='#00FF99'>시간</td>
    <td width='50%' align='center'  bgcolor='#00FF99'>측정숫자</td>
  
    </tr>
    </body>
	

    </html>
");


while($row = mysqli_fetch_array($result))
    {
        echo("
        <tr>
        <td align='center'>$row[dt_create]</td>
        <td align='center'>$row[g_num]</td>
      
        </tr>
		
         ");
    }
	
?>


<!--                               그래프                                -->

<?php
// MySQL 접속
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = '1234';
$mysql_db = 'cap';
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password);
$dbconn = mysqli_select_db( $conn, $mysql_db);


// DB에서 원하는 데이터 검색
$sql="
select * from (
SELECT DATE_FORMAT( dt_create,  '%Y년 %m월 %d일 %h시 %i분 %s초' ) as dt_create , COUNT( * ) cnt, SUM( g_num ) , 
round(SUM( g_num ) / COUNT( * ),1)  as g_num
FROM  number
GROUP BY DATE_FORMAT( dt_create,  '%Y%m%d%H' )
order by dt_create desc

) gas
order by gas.dt_create
";


$sql2="select * from (
SELECT DATE_FORMAT( dt_create,  '%Y년 %m월 %d일 %h시 %i분 %s초') as dt_create, temper FROM temp
GROUP BY DATE_FORMAT( dt_create,  '%Y%m%d%H' )
order by dt_create desc

) gas
order by gas.dt_create
";

$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$result2 = mysqli_query($conn,$sql2) or die(mysqli_error($conn));



$str_date="";
$str_num="";


$str_date2="";
$str_num2="";
 
// 온습도 문자열 연결
while ($row = mysqli_fetch_array($result)){


 $str_date .="'".$row['dt_create']."',";
 $str_num .="".$row['g_num'].",";

 
}
 
// 오른쪽 공백 제거
$str_date= substr($str_date,0,-1);
$str_num= substr($str_num,0,-1);

while ($row = mysqli_fetch_array($result2)){


 $str_date2 .="'".$row['dt_create']."',";
 $str_num2 .="".$row['temper'].",";

}
 
// 오른쪽 공백 제거
$str_date2= substr($str_date2,0,-1);
$str_num2= substr($str_num2,0,-1);

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
            categories: [<?php echo $str_date?>]
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
  	data: [<?php echo $str_num?>]
       
},{
            name: '온도',
            text: 'Rainfall',
            yAxis: 1,
            data: [<?php echo $str_num2?>]
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


