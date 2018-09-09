<script src="../web/librairies/js/Chart.js"></script>
	
	
	
	<div id="camembert_fluxrss">
	<?php 

	//var_dump($data['compter']['NBref']);
	$nbtheoriquelien = substr($data['compter'][0]['NBref']/($data['nblien'][0]['NBlien']+1),0,4);
	$txtheoriquelien = substr((($nbtheoriquelien/$data['compter'][0]['NBref'])*100),0,4);
	?>
	<p style="text-align : left">Nb théorique de ref./Flux RSS : <span class="label_orange"><?php echo $nbtheoriquelien; ?></span><br>
	Répartition  des ref./Flux RSS : <span class="label_orange"><?php echo $txtheoriquelien." %"; ?></span><br>
	<?php 
	$lable = "";
	$datx = "";
	foreach($data['txlien'] as $len) {
		$nb = $len['NB'];
		$nom = parse_url($len['nom'], PHP_URL_HOST);
		
		$lable .= ",'$nom'";
		$datx .= ",$nb";
	}
	$lable = substr($lable,1);
	$datx = substr($datx,1);

	?>
	<p style="font-size : small"><span class="label_orange">NB eff. références/Flux RSS</span></p>
	<div class="container">
	<div>
    <canvas id="myChart"></canvas>
	</div>
	</div>
	</div>
		
<script>
var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: [<?php echo $lable; ?>],
    datasets: [{

      data: [<?php echo $datx; ?>]
    }]
  },
      options: {
        legend: {
            display: false,
            labels: {
                display: false
            }
        },
		title: {
            display: false,
            text: 'NB eff. références/Flux RSS'
        }
}
	});
</script>


			
			
		<?php

	$nbtheoriquemtclf = substr($data['compter'][0]['NBref']/$data['nbmtclf'][0]['NBmtclf'],0,4);
	$txtheoriquemtclf = substr((($nbtheoriquemtclf/$data['compter'][0]['NBref'])*100),0,4);
	?>
	<div id="camembert_mtclf">
	<p style="text-align : left">Nb théorique de ref./mots-clefs : <span class="label_orange"><?php echo $nbtheoriquemtclf; ?></span><br>
	Répartition  des ref./mots-clefs <span class="label_orange"><?php echo $txtheoriquemtclf." %"; ?></span><br>
	<?php 
	$lable = "";
	$datx = "";
	foreach($data['txmtclf'] as $mtc) {
	$nb = $mtc['FREQUENCE'];
	$nom = $mtc['nom'];
	$lable .= ",'$nom'";
	$datx .= ",$nb";
	}
	$lable = substr($lable,1);
	$datx = substr($datx,1);
	?>
	</p>
	
	<p style="font-size : small"><span class="label_orange">NB eff. références/Mots-clefs</span></p>
	<div class="container">
	<div>
    <canvas id="myChart2"></canvas>
	</div>
	</div>

	</div>
	
	
	
	
	
<script>
var ctx = document.getElementById("myChart2").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: [<?php echo $lable; ?>],
    datasets: [{
      
      data: [<?php echo $datx; ?>]
    }]
  },
      options: {
        legend: {
            display: false,
            labels: {
                display: false
            }
        },
		title: {
            display: false,
            text: 'NB eff. références/Mots-clefs'
        }
}
	});
</script>