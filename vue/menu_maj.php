<form name="menu_maj" class="menu" method="POST" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=menu_recherchesession">

<input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>">

<input type="submit" name="btn_gene" value="retour thématique">
<input type="submit" name="btn_gene" value="retour accueil">
</form>