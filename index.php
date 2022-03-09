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
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Данные по работе">
	<link rel="stylesheet" href="static/build/styles/app.css">
 <title><?php echo $name;?></title>
</head>

<body>
	<header class="header">
    <div class="header__container container">
      <h1 class="header__title">Табель для сотрудника <?php echo $name;?></h1>
    </div>
  </header>

<?php  // Таблица "Табель" тут формируется
$sql = "SELECT DATE_FORMAT(`timesheet`.`date`,'%d.%m.%Y'), `timesheet`.`time`, `comments`.`comment` FROM `timesheet` "
 . "LEFT JOIN (`worker`,`comments`)"
 . " ON (`timesheet`.`worker_id`=`worker`.`id` AND `timesheet`.`comment_id`=`comments`.`id`) WHERE `worker`.`id`=$id;";
if ($result = $mysqli->query($sql)) {
	$sum_hours = 0;
	echo("
	<table class='table'>
	<caption><h2>Табель</h2></caption>

	<thead class='table__header'>
	 <tr class='table__header-row'>
	  <th class='table__header-col'>Дата</th>
	  <th class='table__header-col'>Сумма</th>
	  <th class='table__header-col'>Примечание</th>
	 </tr>
	</thead>

	<tbody class='table__body'>
	");
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		echo(" <tr class='table__body-row'>
			<td class='table__body-col'>
			 $row[0]
			</td>
			<td class='table__body-col'>
			 $row[1]
			</td>
			<td class='table__body-col'>
			 $row[2]
			</td>
		       </tr>");
		$sum_hours = $sum_hours + $row[1];
	}
/*	$sum_text=number_format($sum, 2, '.', ' ');*/
	echo(" <tr class='table__body-row'>
		<td class='table__body-col'>
		 Всего:
		</td>
		<td colspan=2 class='table__body-col text-rigth font-weight-bold'>
		 $sum_hours
		</td>
	       </tr>
	</tbody>
	</table>
	");
    $result->free();}
else {
	echo("<div class='info'>
        <div class='info__container container'>
          <div class='info__body'>
            <div class='info__text'>Ошибка выполнения запроса</div>
            <div class='info__text'>Сообщение ошибки:'{$mysqli->error}'</div>
          </div>
        </div>
      </div>")
?>
<div class='info'>
        <div class='info__container container'>
          <div class='info__body'>
            <div class='info__text'>Оплата за час: <?php echo $pph; ?>р</div>
            <div class='info__text'>Оплата за 8 часов: <?php echo $pph*8; ?>р</div>
						<div class='info__text'>Всего начислено: <?php echo $pph*$sum_hours; ?>р</div>
          </div>
    </div>
  </div>
<br>
<?php	// Таблица "Выдано" тут формируется
$sql = "SELECT DATE_FORMAT(`paid`.`date`,'%d.%m.%Y'), `paid`.`amount`, `comments`.`comment` FROM `paid` "
 . "LEFT JOIN (`worker`,`comments`)"
 . " ON (`paid`.`worker_id`=`worker`.`id` AND `paid`.`comment_id`=`comments`.`id`) WHERE `worker`.`id`=$id;";
/*echo "sql=$sql<br>";*/
if ($result = $mysqli->query($sql)) {
	$sum_paid = 0;
	echo("
	<table class='table'>
	<caption><h2>Табель</h2></caption>

	<thead class='table__header'>
	 <tr class='table__header-row'>
	  <th class='table__header-col'>Дата</th>
	  <th class='table__header-col'>Сумма</th>
	  <th class='table__header-col'>Примечание</th>
	 </tr>
	</thead>

	<tbody class='table__body'>
	");
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		echo(" <tr class='table__body-row'>
			<td class='table__body-col'>
			 $row[0]
			</td>
			<td class='table__body-col'>
			 $row[1]
			</td>
			<td class='table__body-col'>
			 $row[2]
			</td>
		       </tr>");
		$sum_paid = $sum_paid + $row[1];
	}
/*	$sum_text=number_format($sum, 2, '.', ' ');*/
	echo(" <tr class='table__body-row>
		<td class='table__body-col'>
		 Всего:
		</td>
		<td colspan=2 class='table__body-col text-rigth font-weight-bold'>
		 $sum_paid
		</td>
	       </tr>
	</tbody>
	</table>
	");
    $result->free();}
else {
	echo("<div class='info'>
        <div class='info__container container'>
          <div class='info__body'>
            <div class='info__text'>Ошибка выполнения запроса</div>
            <div class='info__text'>Сообщение ошибки:'{$mysqli->error}'</div>
          </div>
        </div>
      </div>")}
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
