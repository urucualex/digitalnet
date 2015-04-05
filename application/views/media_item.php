<?php
    $this->load->view('header');    
    $this->load->view('menu');
?>


<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">	
			<div role="tabpanel" class="panel">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#properties" aria-controls="properties" role="tab" data-toggle="tab">Date campanie</a></li>
					<li role="presentation"><a href="#players" aria-controls="players" role="tab" data-toggle="tab">Statii</a></li>
				</ul>
			  	<!-- Tab panes -->
			  	<div class="tab-content">	
				    <div role="tabpanel" class="tab-pane active" id="properties">
						<div class="row">
				    		<div class="col-sm-12">
				    			<form class="form-horizontal">
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Fisier</label>
				    					<div class="col-sm-7">
				    						<input type="text" readonly="readonly" class="form-control" name="filename"/>
				    					</div>	    					
				    					<div class="col-sm-1">
				    						<label for="file" class="btn btn-default">
				    							<span class="glyphicon glyphicon-upload"></span>
				    						</label>
			    							<div style="visibility: hidden; display: inline-block">
			    								<input id="file" type="file" name="file"/>
			    							</div>
				    					</div>
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Lungime</label>
				    					<div class="col-sm-1">
				    						<input type="text" readonly="" class="form-control" name="length"/>
				    					</div>	    					
				    				</div>
				    				
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label"><input type="checkbox" name="use_section">Sectiune</label>
				    					<div class="col-sm-2">
				    						<input type="time" class="form-control" name="section_start"/>
				    					</div>	    					
				    					<div class="col-sm-2">
				    						<input type="time" class="form-control" name="section_end"/>
				    					</div>	    					
				    				</div>
				    				
				    				
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label"><input type="checkbox" name="use_section">Perioada de afisare</label>
				    					<div class="col-sm-2">
				    						<input type="date" class="form-control" name="campaign_start"/>
				    					</div>	    					
				    					<div class="col-sm-2">
				    						<input type="date" class="form-control" name="campaign_end"/>
				    					</div>	    					
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Nume</label>
				    					<div class="col-sm-8">
				    						<input type="text" placeholder="ex: Oscilococcinum" class="form-control" name="location"/>
				    					</div>	    					
				    				</div>
				    				
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Etichete</label>
				    					<div class="col-sm-8">
				    						<input type="text" placeholder="ex: reclama, medicament, homeopatic, iunie, 2015" class="form-control" name="location"/>
				    					</div>	    					
				    				</div>
				    				
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Tip</label>
				    					<div class="col-sm-8">
				    						<input type="text" placeholder="ex: reclama" class="form-control" name="location"/>
				    					</div>	    					
				    				</div>
				    				
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Client</label>
				    					<div class="col-sm-8">
				    						<input type="text" placeholder="ex: Boiron" class="form-control" name="location"/>
				    					</div>	    					
				    				</div>
				    					  
				    				<div class="col-sm-10 col-sm-offset-1">
				    					<button class="btn btn-primary"> <span class="glyphicon glyphicon-ok">	</span> OK</button>
				    				</div>
				    			</form>
				    		</div>
				    	</div>	
				    </div>
				    <div role="tabpanel" class="tab-pane" id="players">
				    	<div class="row">
					    	<div class="col-sm-2">
					    		<a href="#" class="btn btn-primary">
					    			<span class="glyphicon glyphicon-plus"></span>
					    			Adauga playere
					    		</a>
					    	</div>
					    	<div class="col-sm-2">
					    		<a href="#" class="btn btn-danger">
					    			<span class="glyphicon glyphicon-remove"></span>
					    			Sterge playere
					    		</a>
					    	</div>											    		
				    	</div>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>Selecteaza</th>
									<th>Nume</th>
									<th>Label</th>
									<th>Judet</th>
									<th>Oras</th>
									<th>Ultimul mesaj</th>
									<th>Ultimul update</th>
									<th>Spoturi astazi</th>
									<th>Lungime playlist</th>
									<th>Observatii</th>
			 					</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>
										<input type="checkbox" name=""/>
									</td>
									<td>Spitalul Elias 1</td>
									<td>1, spital, maternitate</td>						
									<td>Bucuresti</td>						
									<td>Sector 1</td>						
									<td>14-mar-2015 14:23</td>						
									<td>14-mar-2015 09:00</td>						
									<td>23</td>						
									<td>45:00</td>						
									<td></td>					
								</tr>
								<tr>
									<td>1</td>
									<td>
										<input type="checkbox" name=""/>
									</td>
									<td>Spitalul Elias 1</td>
									<td>1, spital, maternitate</td>						
									<td>Bucuresti</td>						
									<td>Sector 1</td>						
									<td>14-mar-2015 14:23</td>						
									<td>14-mar-2015 09:00</td>						
									<td>23</td>						
									<td>45:00</td>						
									<td></td>					
								</tr>
								<tr>
									<td>1</td>
									<td>
										<input type="checkbox" name=""/>
									</td>
									<td>Spitalul Elias 1</td>
									<td>1, spital, maternitate</td>						
									<td>Bucuresti</td>						
									<td>Sector 1</td>						
									<td>14-mar-2015 14:23</td>						
									<td>14-mar-2015 09:00</td>						
									<td>23</td>						
									<td>45:00</td>						
									<td></td>					
								</tr>
								<tr>
									<td>1</td>
									<td>
										<input type="checkbox" name=""/>
									</td>
									<td>Spitalul Elias 1</td>
									<td>1, spital, maternitate</td>						
									<td>Bucuresti</td>						
									<td>Sector 1</td>						
									<td>14-mar-2015 14:23</td>						
									<td>14-mar-2015 09:00</td>						
									<td>23</td>						
									<td>45:00</td>						
									<td></td>					
								</tr>
														
							</tbody>
						</table>			  
				    </div>
			  	</div>
			</div>
		</div>	
	</div>
</div> <!-- container -->





<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">	
	
		</div>
	</div>
</div> <!-- container -->

        
<?php
    $this->load->view('footer');