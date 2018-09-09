<form name="menu_session" class="menu" method="POST" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=editsession">

<input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>">

<input type="submit" name="btn_gene" value="mettre à jour">
<input type="submit" name="btn_gene" value="consulter">
<input type="submit" name="btn_gene" value="rechercher">
<input type="submit" name="btn_gene" value="résultats">
<input type="submit" name="btn_gene" value="tutoriel">
<input type="submit" name="btn_gene" value="retour accueil">
</form>