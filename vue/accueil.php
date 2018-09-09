<!-- BARRE DE CHOIX ET D'EDITION D'UNE SESSION -->
<form method="POST" name="choix_session" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=gestionsession">
<fieldset>
<label>Vos thématiques de recherche</label>
<select name="combosession">
<option></option>


<?php 


foreach ($donnees['session'] as $session):
    ?>
<option value="<?= $session['IDsession'] ?>"><?= $session['lib_session'] ?></option>
<?php endforeach; ?>
</select>
<input name='btn_gene' type="submit" value="Choisir" >
<input name='btn_gene' type="submit" value="Supprimer" >
<input type="text" name="nveau_session" placeholder="Nouvelle thématique..." >
<input name='btn_gene' type="submit" value="Ajouter" >
</fieldset>
</form>