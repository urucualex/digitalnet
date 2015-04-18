<?php
    $this->load->view('header');    
    $this->load->view('menu');
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-2 sidebar">
			<ul class="nav nav-sidebar">
				<li>
					<label>Data: <input type="date" class="form-control" value="2015-05-15"></label>
				</li>
				<li>
					<a href="/media/item"><span class="glyphicon glyphicon-plus"></span> Adauga</a>
				</li>
				<li>
					<a href="/media/copy"><span class="glyphicon glyphicon-duplicate"></span> Copiaza</a>
				</li>
				<li>
					<a href="/media/add_label"><span class="glyphicon glyphicon-tags"></span> Label</a>
				</li>
				<li>
					<hr>
				</li>
				<li>
					<a href="#"><span class="glyphicon glyphicon-plus-sign"></span> Adauga in statii</a>
				</li>
				<li>
					<a href="#"><span class="glyphicon glyphicon-list"></span> Salveaza ordinea curenta</a>
				</li>
				<li>
					<hr>
				</li>				
				<li>
					<a href="#"><span class="glyphicon glyphicon-trash"></span> Sterge</a>
				</li>
				<li>
					<a href="#"><span class="glyphicon glyphicon-remove"></span> Scoate din toti clientii</a>
				</li>
				<li>
					<a href="#"><span class="glyphicon glyphicon-stop"></span> Termina campania</a>
				</li>
				<li>
					<hr>
				</li>
				<li>
					<h3>Filtre</h3>
					<div class="filter-list-box">
						<ul>
							<li class="checkbox">
								<label>
									<input type="checkbox">Spital
								</label>
							</li>
							<li class="checkbox">
								<label>
									<input type="checkbox">Clinica
								</label>
							</li>
							<li class="checkbox">
								<label>
									<input type="checkbox">Maternitate
								</label>
							</li>
							<li class="checkbox">
								<label>
									<input type="checkbox">Farmacie
								</label>
							</li>																				
					</div>
				</li>
				<li>
					<hr/>
				</li>
			</ul>
		</div>

		<div class="col-sm-10 col-sm-offset-2 main"> <!-- Content -->
			<table id="main-media-table" class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Pozitia in playlist</th>
						<th>Nume</th>
						<th>Label</th>
						<th>Fisier</th>
						<th>Durata</th>
						<th>Minutul afisarii</th>
						<th>Numar de statii</th>
						<th>Client</th>
						<th>Tip</th>
						<th>Data de inceput</th>
						<th>Data de sfarsit</th>
						<th>Numar zile de afisare</th>
						<th>Observatii</th>
 					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>1</td>
						<td>Oscilococcinum iunie 2015</td>						
						<td>reclama, medicamet, homeopatic</td>						
						<td>oscilococcinum iunie 2015.wmv</td>						
						<td>00:20</td>						
						<td>00:00</td>
						<td>33</td>
						<td>Boiron</td>						
						<td>Reclama</td>						
						<td>1 iunie 2015</td>						
						<td>30 iunie 2015</td>						
						<td>30</td>	
						<td>observatii</td>					
					</tr>
					<tr>
						<td>1</td>
						<td>1</td>
						<td>Oscilococcinum iunie 2015</td>						
						<td>reclama, medicamet, homeopatic</td>						
						<td>oscilococcinum iunie 2015.wmv</td>						
						<td>00:20</td>						
						<td>00:00</td>
						<td>33</td>
						<td>Boiron</td>						
						<td>Reclama</td>						
						<td>1 iunie 2015</td>						
						<td>30 iunie 2015</td>						
						<td>30</td>	
						<td>observatii</td>					
					</tr>																			
					<tr>
						<td>1</td>
						<td>1</td>
						<td>Oscilococcinum iunie 2015</td>						
						<td>reclama, medicamet, homeopatic</td>						
						<td>oscilococcinum iunie 2015.wmv</td>						
						<td>00:20</td>						
						<td>00:00</td>
						<td>33</td>
						<td>Boiron</td>						
						<td>Reclama</td>						
						<td>1 iunie 2015</td>						
						<td>30 iunie 2015</td>						
						<td>30</td>	
						<td>observatii</td>					
					</tr>																			
					<tr>
						<td>1</td>
						<td>1</td>
						<td>Oscilococcinum iunie 2015</td>						
						<td>reclama, medicamet, homeopatic</td>						
						<td>oscilococcinum iunie 2015.wmv</td>						
						<td>00:20</td>						
						<td>00:00</td>
						<td>33</td>
						<td>Boiron</td>						
						<td>Reclama</td>						
						<td>1 iunie 2015</td>						
						<td>30 iunie 2015</td>						
						<td>30</td>	
						<td>observatii</td>					
					</tr>																			
				</tbody>
			</table>
		</div> <!-- Content -->
	</div>
</div>

        
<?php
    $this->load->view('footer');
