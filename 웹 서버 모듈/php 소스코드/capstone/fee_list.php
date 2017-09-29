<form action="./fee_list.php" method="post" name="form" onSubmit="return check()">
   <center>
   <table>
      <tr>
         
       <br><br> 기간을 선택하세요 <br><br>
      </tr>
     
             <select name="year1">  
                <option selected>년 </option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
	 			<option value="2017">2017</option>
                <option value="2018">2018</option>
	 			<option value="2019">2019</option>
                <option value="2020">2020</option>
             </select>
 
       <select name="month1"> 
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
   &nbsp;&nbsp;&nbsp;
 
             <select name="year2">  
                <option selected>년 </option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
				<option value="2017">2017</option>
                <option value="2018">2018</option>
				<option value="2019">2019</option>
                <option value="2020">2020</option>
             </select>
  
        
             <select name="month2"> 
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
// MySQL 접속
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = '1234';
$mysql_db = 'cap';
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password);
$dbconn = mysqli_select_db( $conn, $mysql_db);
$year1=$_POST["year1"];
$mon1=$_POST["month1"];   
$year2=$_POST["year2"];
$mon2=$_POST["month2"];

// DB에서 원하는 데이터 검색
$sql="
select * from (
SELECT DATE_FORMAT( dt_create,  '%Y년 %m월 %d일 %h시 %i분 %s초') as dt_create , COUNT( * ) cnt, SUM( g_num ) , 
round(SUM( g_num ) / COUNT( * ),1)  as g_num
FROM  number
WHERE  year(dt_create) like '%$year1%' and month(dt_create) like '%$mon1%' and day(dt_create) = '01'
GROUP BY DATE_FORMAT( dt_create,  '%Y%m%d%H' )
order by dt_create desc

) gas
order by gas.dt_create
";

$sql2="
select * from (
SELECT DATE_FORMAT( DATE_ADD(dt_create,interval 1 month),  '%Y년 %m월 %d일 %h시 %i분 %s초') as dt_create , COUNT( * ) cnt, SUM( g_num ) , 
round(SUM( g_num ) / COUNT( * ),1)  as g_num
FROM  number
WHERE  year(dt_create) like '%$year2%' and month(dt_create) like '%$mon2%' and day(dt_create) = '01'
GROUP BY DATE_FORMAT( dt_create,  '%Y%m%d%H' )
order by dt_create desc

) gas
order by gas.dt_create
";



$sql3="select * from (
SELECT DATE_FORMAT( dt_create,  '%Y년 %m월 %d일 %h시 %i분 %s초') as dt_create, temper 
FROM temp
WHERE month(dt_create) like '%$mon%' and year(dt_create) like '%$year%'
GROUP BY DATE_FORMAT( dt_create,  '%Y%m%d%H' )
order by dt_create desc

) gas
order by gas.dt_create
";


$result   = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$result2 = mysqli_query($conn,$sql2) or die(mysqli_error($conn));
$result3 = mysqli_query($conn,$sql3) or die(mysqli_error($conn));


$str_date="";
$str_num="";

$str_date2="";
$str_num2="";

$str_date3="";
$str_num3="";

// 날짜와 데이터 분리
while ($row = mysqli_fetch_array($result)){


 $str_date .="'".$row['dt_create']."',";
 $str_num .="".$row['g_num'].",";

}
 
// 오른쪽 공백 제거
$str_date= substr($str_date,0,-1);
$str_num= substr($str_num,0,-1);


while ($row = mysqli_fetch_array($result2)){


 $str_date2 .="'".$row['dt_create']."',";
 $str_num2 .="".$row['g_num'].",";

}
$str_date2= substr($str_date2,0,-1);
$str_num2= substr($str_num2,0,-1);


while ($row = mysqli_fetch_array($result3)){


 $str_date3 .="'".$row['dt_create']."',";
 $str_num3 .="".$row['temper'].",";

}
$str_date3= substr($str_date3,0,-1);
$str_num3= substr($str_num3,0,-1);

 


$fee = ($str_num2 - $str_num) * 4;
 


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
            text: '<?php echo $year1,"년 ",$mon1,"월","부터 ",$year2,"년 ",$mon2,"월","까지 ", $_POST["month2"]-$_POST["month1"],"개월간 요금입니다."; ?>'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [<?php echo $mon2-$mon1?>]
        },
        yAxis: [{ // Primary yAxis
        labels: {
            format: '{value}',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        title: {
            text: '요금',
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
               name: '사용자 요금',
	type: 'column',
   
  	data: [<?php echo $fee?>]
        

        
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












<?php
// DB 설정
require_once("./config.php");

//DB 연결
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = '1234';
$mysql_db = 'cap';

$year=$_POST["year"];
$mon=$_POST["month"];

// Mysql Connection
$connect = mysqli_connect($mysql_host, $mysql_user, $mysql_password ,$mysql_db);
$dbconn = mysqli_select_db( $connect, $mysql_db);

// book 테이블의 내용을 가져옴
$query = "select * from number where year(dt_create) like '%$year%' 
and day(dt_create) = '01'";
$result = mysqli_query( $connect,$query) or die(mysql_error($query));


?>
<?php
if(!$query){ ?>
<script>
alert("선택한 날짜에는 데이터가 존재하지않습니다.");
history.back();
</script>
<?php }
mysqli_close($connect);
?>



?>

<img src="라인.JPG" width="1920" height="5">