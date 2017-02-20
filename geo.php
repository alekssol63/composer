<?php

//require 'C:\OpenServer\vendor\autoload.php';
ini_set('display_errors','Off');
require __DIR__ .'/vendor/autoload.php';
function query_Address($adress){
	$api->setQuery($adress);
	$api->setLang(\Yandex\Geo\Api::LANG_RU)->load();
	$response = $api->getResponse();
	$api->setLimit($response->getFoundCount())->load();
	$response = $api->getResponse();	
	$collection=$response->getlist();
	return $response;
}


$show=false;
$adress=null;
$Latitude = null; 
$Longitude = null;
$itemadress = null;

$api = new \Yandex\Geo\Api();
if(!(empty($_GET))){	
	$Lat=$_GET['Latitude'];
	$Long=$_GET['Longitude'];
	$itemadr=$_GET['itemadr'];
	$api->setQuery($_GET['adress']);
	$api->setLang(\Yandex\Geo\Api::LANG_RU)->load();
	$response = $api->getResponse();
	$api->setLimit($response->getFoundCount())->load();//показываем все записи
	$response = $api->getResponse();
	$collection=$response->getlist();
	$adress = $_GET['adress'];
	$show=true;
}
if(!(empty($_POST['adress']))){ 
	$api->setQuery($_POST['adress']);
	$api->setLang(\Yandex\Geo\Api::LANG_RU)->load();
	$response = $api->getResponse();
	$api->setLimit($response->getFoundCount())->load();
	$response = $api->getResponse();
	
	$collection=$response->getlist();
	
	foreach ($collection as $item) {
		$Lat = $item->getLatitude(); 
		$Long = $item->getLongitude(); 
		$itemadr = $item->getAddress();
		break;
	}
	$adress=$_POST['adress'];
	$show=true;
	
}

?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <style>
	table {
    border-collapse: collapse;	
	}
	th{
	background: gray;
	}
	td, th{
    border: 1px solid black;
	}
	
</style>
 <script src="https://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU" type="text/javascript"></script>  
    
</head>
<form method="post">
<input name="adress" type="text" value="<?php echo $adress; ?>">	
<button type="submit" name="submit">OK</button>
<table>
<tr>
	<th>Адрес</th>
	<th>Широта</th>
	<th>Долгота</th>
</tr>
<?php if ($show == true){
	foreach($collection as $item){ ?>
<tr>
<?php 
	$Latitude = $item->getLatitude(); 
	$Longitude = $item->getLongitude();
	
?>
	<td><a href="?<?php echo 'Latitude='.$Latitude.'&Longitude='.$Longitude.'&adress='.$adress.'&itemadr='.$item->getAddress(); ?>"> <?php echo  $item->getAddress(); ?></a> </td>
	<td><?php echo  $item->getLatitude(); ?></td>
	<td><?php echo  $item->getLongitude(); ?></td>
</tr>
<?php } ?>
<?php } ?>
</table>

<script type="text/javascript">
        ymaps.ready(init);
        var myMap, 
            myPlacemark;

        function init(){ 
            myMap = new ymaps.Map ("map", {
                center: [ <?php echo $Lat; ?>, <?php echo $Long;?>] ,
                zoom: "15"
            }); 
            
           // myPlacemark = new ymaps.Placemark([55.76, 37.64], {
			   myPlacemark = new ymaps.Placemark([<?php echo $Lat; ?>, <?php echo $Long;?>], {
			  
                hintContent: '<?php echo $itemadr; ?>',
                balloonContent: '<?php echo $itemadr; ?>'
            });
            
            myMap.geoObjects.add(myPlacemark);
        }
    </script>
<div id="map" style="width: 600px; height: 400px"></div>

</form>
</html>