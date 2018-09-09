<form name="menu_resultat" class="menu" method="POST" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=consultresult">

<input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>">
<input type="submit" name="btn_gene" value="Arborescence">
<input type="submit" name="btn_gene" value="Timeline">
<input type="submit" name="btn_gene" value="Table des Occurrences">
<input type="submit" name="btn_gene" value="Analyse de la veille">
<input type="submit" name="btn_gene" value="retour thématique">
<input type="submit" name="btn_gene" value="retour accueil">
</form>