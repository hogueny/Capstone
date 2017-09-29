
<?php
echo $_POST['dt_create'];
?>

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
         <tr><input type="submit" value="조회" /></tr> 
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

	
$sql2="
select * from (
SELECT DATE_FORMAT( dt_create,  '%Y년 %m월 %d일 %h시 %i분 %s초') as dt_create , COUNT( * ) cnt, SUM( g_num ) , 
round(SUM( g_num ) / COUNT( * ))  as g_num
FROM  number 
WHERE  day(dt_create) = '01' and year(dt_create) = '2017'
GROUP BY DATE_FORMAT( dt_create,  '%Y%m%d%H' )
order by dt_create desc

) gas
order by gas.dt_create
";

$result2 = mysqli_query( $conn,$sql2 )or die(mysqli_error($conn));
 
 

$str_date2="";
$str_num2=0;
$num=0; 
$num2=0;

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

$sql3="select * from (
SELECT DATE_FORMAT( dt_create,  '%Y년 %m월 %d일 %h시 %i분 %s초') as dt_create, temper
FROM temp
WHERE  day(dt_create) = '01' and year(dt_create) = '2017'
GROUP BY DATE_FORMAT( dt_create,  '%Y%m%d%H' )
order by dt_create desc

) gas
order by gas.dt_create
";


$result3 = mysqli_query($conn,$sql3) or die(mysqli_error($conn));


$str_date3="";
$str_num3="";



while ($row = mysqli_fetch_array($result3)){


 $str_date3 .="'".$row['dt_create']."',";
 $str_num3 .="".$row['temper'].",";

}
 
// 오른쪽 공백 제거
$str_date3= substr($str_date3,0,-1);
$str_num3= substr($str_num3,0,-1);
 
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
       
},{
            name: '온도',
            text: 'Rainfall',
            yAxis: 1,
            data: [<?php echo $str_num3?>]
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
WHERE  day(dt_create) = '01' and year(dt_create) = '2017'
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
    <td width='50%' align='center'  bgcolor='#00FF99'>요금</td>

    </tr>
    </body>
    </html>
");
$a=array();
$b=array();
$c=array();
 $i=0;
 $j=0;
 $k=0;
while($row = mysqli_fetch_row($result))
    {
		
		$b[j] =($row[3]-$a[i])*4; 
         
		  $a[i] = $row[3];
		
		$c[k] =$b[j];	

		 $i++;
		 
		 $j++;
	
	
$result4 = mysqli_query($conn,"SELECT * FROM member "); 
while($row = mysqli_fetch_array($result4)){ 
 //echo $row['fee'];  
 //echo "<br>";
//$count[$i] = $b[j];
//echo $count[$i];

if($row['fee'] < $b[j])
{
	$f = $row['fee'];
	$fee = $b[j]-$f;
	
	echo "<script>alert('$i 월 요금이 ".$fee."원 초과하였습니다.!!!!')</script>";
    
	$count[$i] = $b[j];
	
//메일보내기	
   $to = $row['email'];
   $subject = "ARM 가스 검침 요금 초고 메일 입니다..";
   $content = "'$i'월 요금이 ".$fee."원 초과했습니다..";
   $headers = "From:hogueny@gmail.com";
mail($to,$subject,$content,$headers);	
	
	
	//echo $count[$i];
}	
echo("
    <html>
    <head><title>데이터 </title></head>
    <body>
    <center>

    <table width='1000' border='1' >
    <tr>
    <td width='50%' align='center' >$i 월</td>
    <td width='50%' align='center'>$b[j]원</td>

    </tr>
    </body>
    </html>
	
	
");

$k++;

	    }
	}
	
	?>