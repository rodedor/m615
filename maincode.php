<?php	// Выясняем для кого выводить данные, его имя, ставку

$path = pathinfo($_SERVER['REQUEST_URI']);
$link = str_replace("/","",$path['dirname']);
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
$sql = "SELECT `name`,`pph` FROM `worker` WHERE `id`=$id;";	// Заполняем переменные по работнику
if ($result = $mysqli->query($sql)) {
	$row = $result->fetch_array(MYSQLI_NUM);
	$name = $row[0];	
	$pph = $row[1];		
	$result->free();}


// Таблица "Табель" тут формируется
$sql = "SELECT DATE_FORMAT(`timesheet`.`date`,'%d.%m.%Y'), `timesheet`.`time`, `comments`.`comment` FROM `timesheet` "
 . "LEFT JOIN (`worker`,`comments`)"
 . " ON (`timesheet`.`worker_id`=`worker`.`id` AND `timesheet`.`comment_id`=`comments`.`id`) WHERE `worker`.`id`=$id;";
if ($result = $mysqli->query($sql)) {
	unset($timesheet);
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		$timesheet[] = $row;
		$sum_hours = $sum_hours + $row[1];
	}
	$result->free();}


// Таблица "Выдано" тут формируется
$sql = "SELECT DATE_FORMAT(`paid`.`date`,'%d.%m.%Y'), `paid`.`amount`, `comments`.`comment` FROM `paid` "
 . "LEFT JOIN (`worker`,`comments`)"
 . " ON (`paid`.`worker_id`=`worker`.`id` AND `paid`.`comment_id`=`comments`.`id`) WHERE `worker`.`id`=$id;";
/*echo "sql=$sql<br>";*/
if ($result = $mysqli->query($sql)) {
	unset($paid);
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		$paid[] = $row;
		$sum_paid = $sum_paid + $row[1];
	}
    $result->free();}
/*else {
    echo("<div class='info'>
        <div class='info__container container'>
          <div class='info__body'>
            <div class='info__text'>Ошибка выполнения запроса</div>
            <div class='info__text'>Сообщение ошибки:'{$mysqli->error}'</div>
          </div>
        </div>
      </div>");}*/

?>


