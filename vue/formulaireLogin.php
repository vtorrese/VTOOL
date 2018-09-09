<form method="post" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=traitementLogin">
	<div id="connection">
	<fieldset>
		<h2>Connexion </h2>
		<div id="text">
			<input class="myInput" type="text" name="login" placeholder="Identifiant" required>
			<input class="myInput" type="password" name="motdepasse" placeholder="Mot de passe" required>
		</div>
		<br>
		<input class = "myButton" name='btn_gene' type="submit" value="Se connecter" >	
		<input class = "myButton" name='btn_gene' type="submit" value="S'inscrire" >	
	</form>
	</fieldset>
	</div>