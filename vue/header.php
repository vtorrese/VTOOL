<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>VTool</title>
		<link rel="stylesheet" type="text/css" href="<?php echo $this->pathWeb('css/style.css'); ?>" />
	</head>
	<body>
	<div id="logo"><img src="../web/images/VTOOL.png">beta</div>
	
	<?php 
	//formulaire de login
		if ($this->getSessionParam('estAutenthifie')==='true')
		{
			
		?>
		<span class="label_orange">Membre connecté : </span><?php echo $this->getSessionParam('login'); ?>
		<form method="post" name='deconnexion' action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=deconnection">
			<input style="display:block; margin:right;float : right;" type="submit" class="myButton" value="Déconnexion" >
		</form>
		
		<?php
		
		}
		?>
		<br>
		
        <?php if($this->erreurMessage != null): ?>
            <div class="erreur"><?php echo $this->erreurMessage; ?></div>
        <?php endif ?>
		<?php if($this->infoMessage != null): ?>
			<div class="info"><?php echo $this->infoMessage; ?></div>
		<?php endif ?>