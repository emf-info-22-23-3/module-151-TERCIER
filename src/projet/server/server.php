<?php
include_once 'controllers/LoginManager.php';

if (isset($_SERVER['REQUEST_METHOD']))
	{
		switch ($_SERVER['REQUEST_METHOD'])
		{
			case 'GET':
				
				break;
			case 'POST':
				if (isset($_POST['Pass']) and isset($_POST['Nom']))
				{
					$LoginManager = new LoginManager();
					echo $LoginManager->Post_checkLogin($_POST['Nom'], $_POST['Pass']);
				}
				else{
					echo 'Paramètre Pass ou Nom manquant';
				}
				break;
			case 'PUT':
				
				break;
			case 'DELETE':
				
				break;
		}
	}
?>

