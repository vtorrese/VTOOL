<form name="menu_reference" class="menu" method="POST" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=consultsession">

<input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>">

<input type="submit" name="btn_gene" value="rechercher">
<input type="submit" name="btn_gene" value="résultats">
<input type="submit" name="btn_gene" value="retour thématique">
<input type="submit" name="btn_gene" value="retour accueil">
</form>