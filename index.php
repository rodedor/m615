<?php	// Выясняем для кого выводить данные, его имя, ставку
$path = pathinfo($_SERVER['REQUEST_URI']);
$link = str_replace("/","",$path['dirname']);
$path = pathinfo(__FILE__);
$dir = $path['dirname'];
//echo $dir, "<br>";
include_once "$dir/conn.php";
date_default_timezone_set('Europe/Moscow');
$sql = "SELECT `id` FROM `worker` WHERE `link`=\"$link\";";
if ($result = $mysqli->query($sql)) {
	$row = $result->fetch_array(MYSQLI_NUM);
	$id = $row[0];		// id работника
	$result->free();} 
else {
	echo("Ошибка выполнения запроса");
	$mysqli->close();}
/*
echo "<br>link=$link<br>";
echo $sql;
echo $id;
*/
$sql = "SELECT `name`,`pph` FROM `worker` WHERE `id`=$id;";
$name = "Вася";		// ну так, на всякий случай
if ($result = $mysqli->query($sql)) {
	$row = $result->fetch_array(MYSQLI_NUM);
	$name = $row[0];	// Имя работника
	$pph = $row[1];		// Ставка его
	$result->free();} 
else {
	echo("Ошибка выполнения запроса");}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
 <meta charset="UTF-8">
 <title><?php echo $name;?></title>
 <meta name="description" content="Данные по работе">
</head>

<body>
<h1><?php echo $name;?></h1>

<?php  // Таблица "Табель" тут формируется
$sql = "SELECT DATE_FORMAT(`timesheet`.`date`,'%d.%m.%Y'), `timesheet`.`time`, `comments`.`comment` FROM `timesheet` "
 . "LEFT JOIN (`worker`,`comments`)"
 . " ON (`timesheet`.`worker_id`=`worker`.`id` AND `timesheet`.`comment_id`=`comments`.`id`) WHERE `worker`.`id`=$id;";
if ($result = $mysqli->query($sql)) {
	$sum_hours = 0;
	echo("
	<table border=1>
	<caption><h2>Табель</h2></caption>
	
	<thead>
	 <tr> 
	  <th>Дата</th>
	  <th>Часы</th>
	  <th>Примечание</th>
	 </tr>
	</thead>
	
	<tbody>
	");
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		echo(" <tr>
			<td>
			 $row[0]
			</td>
			<td>
			 $row[1]
			</td>
			<td>
			 $row[2]
			</td>
		       </tr>");
		$sum_hours = $sum_hours + $row[1];
	}
/*	$sum_text=number_format($sum, 2, '.', ' ');*/
	echo(" <tr>
		<td>
		 Всего:
		</td>
		<td colspan=2>
		 $sum_hours
		</td>
	       </tr>
	</tbody>
	</table>
	");
    $result->free();} 
else {
	echo("Ошибка выполнения запроса <br>");
	printf("Сообщение ошибки: <br> %s <br>", $mysqli->error);}
?>

<br>
Оплата за час: <?php echo $pph; ?>р
<br>
Оплата за 8 часов: <?php echo $pph*8; ?>р
<br><br>
<b>Всего начислено: <?php echo $pph*$sum_hours; ?>р</b>

<?php	// Таблица "Выдано" тут формируется
$sql = "SELECT DATE_FORMAT(`paid`.`date`,'%d.%m.%Y'), `paid`.`amount`, `comments`.`comment` FROM `paid` "
 . "LEFT JOIN (`worker`,`comments`)"
 . " ON (`paid`.`worker_id`=`worker`.`id` AND `paid`.`comment_id`=`comments`.`id`) WHERE `worker`.`id`=$id;";
/*echo "sql=$sql<br>";*/
if ($result = $mysqli->query($sql)) {
	$sum_paid = 0;
	echo("
	<table border=1>
	<caption><h2>Выдано</h2></caption>
	
	<thead>
	 <tr> 
	  <th>Дата</th>
	  <th>Сумма</th>
	  <th>Примечание</th>
	 </tr>
	</thead>
	
	<tbody>
	");
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		echo(" <tr>
			<td>
			 $row[0]
			</td>
			<td>
			 $row[1]
			</td>
			<td>
			 $row[2]
			</td>
		       </tr>");
		$sum_paid = $sum_paid + $row[1];
	}
/*	$sum_text=number_format($sum, 2, '.', ' ');*/
	echo(" <tr>
		<td>
		 Всего:
		</td>
		<td colspan=2>
		 $sum_paid
		</td>
	       </tr>
	</tbody>
	</table>
	");
    $result->free();} 
else {
	echo("Ошибка выполнения запроса <br>");
	printf("Сообщение ошибки: <br> %s <br>", $mysqli->error);}
?>

<b>
Остаток: 
<?php
echo $pph*$sum_hours - $sum_paid;
?>
р.
</b>

</body>
</html>

<?php
$mysqli->close();
?>
