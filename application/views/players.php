<?php
    $this->load->view('header');    
    $this->load->view('menu');
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-2 sidebar">
			<ul class="nav nav-sidebar">
				<li>
					<a href="/players/item"><span class="glyphicon glyphicon-plus"></span> Adauga</a>
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
			<table class="table table-striped" id="main-players-table">
				<thead>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div> <!-- Content -->
	</div>
</div>

        
<?php
    $this->load->view('footer');