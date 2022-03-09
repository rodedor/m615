<?php

$id = 0;		// id работника
$pph = 0;		// Ставка работника
$name = "Вася";		// ну так, на всякий случай
$sum_hours = 0;		// Сумма часов по табелю
$sum_paid = 0;		// Итого выдано
$timesheet[0] = [0,0,0];// Табель отработанного времени
$paid[0] = [0,0,0];	// Все выплаты

$path = pathinfo(__FILE__);
$dir = $path['dirname'];
include_once "$dir/maincode.php";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Данные по работе">
    <link rel="stylesheet" href="//m615.tvolt.ru/static/build/styles/app.css">
 <title><?php echo $name;?></title>
</head>

<body>
  <header class="header">
    <div class="header__container container">
      <h1 class="header__title">Табель для сотрудника <?php echo $name;?></h1>
    </div>
  </header>

  <div class='table__container container'>
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
<?php foreach($timesheet as $row) 
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
	       </tr>"); ?>
      <tr class='table__body-row'>
	<td class='table__body-col'>
	 Всего:
	</td>
	<td colspan=2 class='table__body-col text-rigth font-weight-bold'>
	 <?php echo $sum_hours; ?>
	</td>
      </tr>
    </tbody>
    </table>
		</div>

<div class='info'>
        <div class='info__container container'>
          <div class='info__body'>
            <p class='info__text'>Оплата за час: <?php echo $pph; ?>р</p>
            <p class='info__text'>Оплата за 8 часов: <?php echo $pph*8; ?>р</p>
						<p class='info__text'>Всего начислено: <?php echo $pph*$sum_hours; ?>р</p>
          </div>
    </div>
  </div>
<br>

  <div class='table__container container'>
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

<?php foreach($paid as $row) 
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
	       </tr>"); ?>
	<tr class='table__body-row'>
	<td class='table__body-col'>
	 Всего:
	</td>
	<td colspan=2 class='table__body-col text-rigth font-weight-bold'>
	 <?php echo $sum_paid; ?>
	</td>
        </tr>

    </tbody>
    </table>
		</div>
<div class='info'>
        <div class='info__container container'>
          <div class='info__body'>
            <p class='info__text'>Остаток: <?php echo $pph*$sum_hours - $sum_paid ?>р.</p>
          </div>
        </div>
      </div>

</body>
</html>

<?php
$mysqli->close();
?>