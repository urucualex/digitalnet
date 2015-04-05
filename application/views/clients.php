<?php
    $this->load->view('header');    
    $this->load->view('menu');
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-2 sidebar">
			<ul class="nav nav-sidebar">
				<li>
					<a href="/add"><span class="glyphicon glyphicon-plus"></span> Adauga</a>
				</li>
				<li>
					<a href="/add"><span class="glyphicon glyphicon-file"></span> Raport</a>
				</li>
				<li class="checkbox">
				 	<label><input type="checkbox" name="update">Aplica schimbari</label>
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
				<li>
					<a href="#">Online: 32/255</a>
				</li>
			</ul>
		</div>

		<div class="col-sm-10 col-sm-offset-2 main"> <!-- Content -->
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Actiuni</th>
						<th>Nume</th>
						<th>Label</th>
						<th>Judet</th>
						<th>Oras</th>
						<th>Spot curent</th>
						<th>Ultimul mesaj</th>
						<th>Ultimul update</th>
						<th>Spoturi astazi</th>
						<th>Lungime playlist</th>
						<th>Ultima eroare</th>
						<th>Observatii</th>
 					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>
							<div class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></div>
						</td>
						<td>Spitalul Elias 1</td>
						<td>1, spital, maternitate</td>						
						<td>Bucuresti</td>						
						<td>Sector 1</td>						
						<td>spot test</td>						
						<td>14-mar-2015 14:23</td>						
						<td>14-mar-2015 09:00</td>						
						<td>23</td>						
						<td>45:00</td>						
						<td>10-mar-2015 no internet</td>	
						<td></td>					
					</tr>
					<tr>
						<td>1</td>
						<td>
							<div class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></div>
						</td>
						<td>Spitalul Elias 1</td>
						<td>1, spital, maternitate</td>						
						<td>Bucuresti</td>						
						<td>Sector 1</td>						
						<td>spot test</td>						
						<td>14-mar-2015 14:23</td>						
						<td>14-mar-2015 09:00</td>						
						<td>23</td>						
						<td>45:00</td>						
						<td>10-mar-2015 no internet</td>						
						<td></td>					
					</tr>
					<tr>
						<td>1</td>
						<td>
							<div class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></div>
						</td>
						<td>Spitalul Elias 1</td>
						<td>1, spital, maternitate</td>						
						<td>Bucuresti</td>						
						<td>Sector 1</td>						
						<td>spot test</td>						
						<td>14-mar-2015 14:23</td>						
						<td>14-mar-2015 09:00</td>						
						<td>23</td>						
						<td>45:00</td>						
						<td>10-mar-2015 no internet</td>						
						<td></td>					
					</tr>
					<tr>
						<td>1</td>
						<td>
							<div class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></div>
						</td>
						<td>Spitalul Elias 1</td>
						<td>1, spital, maternitate</td>						
						<td>Bucuresti</td>						
						<td>Sector 1</td>						
						<td>spot test</td>						
						<td>14-mar-2015 14:23</td>						
						<td>14-mar-2015 09:00</td>						
						<td>23</td>						
						<td>45:00</td>						
						<td>10-mar-2015 no internet</td>						
						<td></td>					
					</tr>															
				</tbody>
			</table>
		</div> <!-- Content -->
	</div>
</div>

        
<?php
    $this->load->view('footer');