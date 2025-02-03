<?php 
	include_once('workers/SkieurBDManager.php');
	include_once('beans/Skieur.php');
    if (isset($_SERVER['REQUEST_METHOD']))
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET')
		{
			$bdReader = new skieurBDManager();
			echo $bdReader->getInXML($_GET['paysId']);
		}
	}

?>