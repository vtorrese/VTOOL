<?php include('menu_maj.php'); ?>


<!-- formulaire de recherche -->
<fieldset>
<h4>Comment effectuer une veille ? </h4>
<div id="tutoriel">
<span class="label_orange">I. Créer et structurer une thématique</span>
<blockquote>
<p>Une fois votre compte créé, vous accédez au <b>menu accueil</b> où vous allez pouvoir <span class="label_orange">Ajouter</span>, <span class="label_orange">Supprimer</span> ou simplement <span class="label_orange">Consulter</span> vos veilles thématiques.</p>
<p>Une thématique a besoin d'être nommée (Attention, ce nom ne peut pas être modifié par la suite) puis structurée à partir d'un panel de <span class="label_orange">flux RSS</span> et de <span class="label_orange">mots-clefs</span>. Cette structuration est importante, vous allez pouvoir la contrôler dans le <b>Analyse de la veille</b>.</p>
<p>Concernant les <span class="label_orange">flux RSS</span>, comme les mots-clefs, un minimum est requis. Pour exemple, voici les liens nécessaires pour une veille technologique dans le domaine de l'informatique :

<table>
	<tr>
		<th>Flux RSS</th>
	</tr>
		<?php foreach ($donnees['lien'] as $mc):?>
			<tr><td style="text-align : left"><?= $mc['url_lien'] ?><input type="hidden" name="idmtclf" value="<?= $mc['IDlien'] ?>"><input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>"></td></tr>

<?php endforeach; ?>	
</table>
<p>Concernant les <span class="label_orange">mots-clefs</span>, vous pouvez en modifier le nombre et le contenu au fur et à mesure du temps. On les fixe progressivement à partir des <b>occurrences</b> les plus fréquentes obtenues après quelques mises à jour.</p>

<p>A noter que la veille va également être complétée par une <span class="label_orange">recherche Google</span> à partir de vos <span class="label_orange">mots-clefs</span>.
</blockquote>
<hr>
<span class="label_orange">II. Mettre à jour la thématique</span>
<blockquote>
<p>Mettre à jour une thématique signifie récupérer les articles des flux RSS de la thématique qui contiennent les mots-clefs choisis. Le cas échéant, les dits articles vous sont rapidement présentés pour être sélectionnés ou non dans la veille.</p>
<p>Ensuite, pour chacun des articles sélectionnés, un certain nombre d'<span class="label_orange">occurrences</span> sont proposées (parfois aucune n'est disponible), celles-ci doivent en principe synthétiser au mieux l'article. Vous pouvez faire le choix de n'en retenir aucune, puisqu'il est possible de les éditer par la suite.</p>
<p>Procédez ainsi pour tous les articles que vous avez sélectionnés lors de votre mise à jour.</p>
</blockquote>
<hr>
<span class="label_orange">III. Consulter et éditer les références de la thématique</span>
<blockquote>
<p>Vous pouvez <span class="label_orange">consulter</span> et <span class="label_orange">editer</span> chacunes des <span class="label_orange">références</span> (c'est comme cela que nous nommons les articles une fois intégrés à la veille) de manière à optimiser votre veille. Un <span class="label_orange">lien html</span> vers la page contenant la référence permet d'en faire une lecture et de vérifier la qualité du référencement et de l'indexation.</p>
<p><b>La référenciation et l'indexation des articles : </b></p>
<p>Chaque référence comprend : </p>
<span class="label_orange">obligatoirement</span>
<ul>
<li>Un titre</li>
<li>Une date de parution</li>
<li>Un mot-clef (ou <span class="label_orange">parent</span>)</li>
</ul>
<span class="label_orange">de manière facultative</span>
<ul>
<li>Un résumé, une description</li>
<li>Un autre mot-clef (ou <span class="label_orange">enfant</span>)</li>
<li>Un autre mot-clef (ou <span class="label_orange">petit-enfant</span>)</li>
<li>d'autres mots-clefs</li>
</ul>
<p>Il est recommandé d'affecter à chaque référence à minima trois mots-clefs. C'est ce qui permet d'affiner le <span class="label_orange">ratio</span> (moyen et pondéré) de qualité des références, c'est-à-dire la force avec laquelle elles contribuent à l' <span class="label_orange">homogénéité</span> de la veille. L'indexation des mots-clefs s'effectue automatiquement sur la base de leur fréquence relative.</p>
<p>Chaque référence peut être <span class="label_orange">supprimée</span> (définitivement), ou bien <span class="label_orange">désactivée</span> (avec possibilité de la réintégrer à tout moment). Il est enfin possible d' <span class="label_orange">exporter</span> les références de la veille, soit de manière unique soit multiple, en format <span class="label_orange">Txt</span>, <span class="label_orange">PDF</span> et <span class="label_orange">CSV</span>.</p>
</blockquote>
<hr>
<span class="label_orange">IV. Effectuer des recherches</span>
<blockquote>
<p>Il est possible de procéder à des <span class="label_orange">recherches avançées</span> dans la base des références de la thématique. Cela devient utile lorsque l'on vise à optimiser la veille, par exemple en rapprochant certaines références par des occurrences communes.</p>
</blockquote>
<hr>
<span class="label_orange">V. Visualiser les résultats de la veille</span>
<blockquote>
<p>Le menu <b>résultats</b> permet d'accéder à un ensemble de sous-menus : </p>
<ul>
<li>Arborescence</li>
<li>Timeline</li>
<li>Table des occurrences</li>
<li>Analyse de la veille</li>
</ul>
<p>L' <span class="label_orange">Arborescence</span> donne une image à plat de la hiérarchie des références en fonction de l'articulation ordonnée entre <span class="label_orange">parent/enfant/petit-enfant</span>. Il est possible d'interagir avec elle de manière à faire apparaître/disparaître des branches de sous-thématiques.</p>
<p>La <span class="label_orange">Timeline</span> permet de replacer sur une échelle temporelle l'apparition de chaque occurrence propre à cette veille. C'est donc un outil indispensable pour connaître les tendances chronologiques d'une thématique donnée.</p>
<p>La <span class="label_orange">Table des occurrences</span> est quasiment un outil de recherche. En l'état, elle donne la hiérarchisation des occurrences, et regroupe les références en fonction de l'articulation ordonnée entre <span class="label_orange">parent/enfant/petit-enfant</span>.</p>
<p>L' <span class="label_orange">analyse de la veille</span> est un ecran de contrôle de la façon dont la veille est structurée et évolue. Certaines recommandations sont proposées au sujet des mots-clefs et des flux RSS qui ont été sélectionnés.</p>

</blockquote>

<hr>
<span class="label_orange">V. VTool...</span>
<blockquote>
<p>Ceci est une version <span class="label_orange">Beta</span> qui ne demande qu'à être testée et améliorée.</p>
<p>VTool a été propulsé avec PHP (POO MVC), CSS, HTML, Javascript, Chart.js, 3D.js, Fpdf</p>
</blockquote>
</div>
</fieldset>