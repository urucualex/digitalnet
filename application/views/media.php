<?php
    $this->load->view('header');    
    $this->load->view('menu');
?>


<div class="container-fluid">
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
				    			<form class="form-horizontal" action="/media/update" method="post" data-ajax="true">
				    				<div class="form-group">
				    					<input 	type="hidden"
				    							name="id"
			    								value="<?=(array_key_exists('mediaId', $media)) ? $media['mediaId'] : ''?>"
				    					/>
				    					<label class="col-sm-2 control-label">Fisier</label>
				    					<div class="col-sm-4">
				    						<input 	type="text" 
				    								readonly="readonly" 
				    								class="form-control" 
				    								name="file" 
				    								value="<?=(array_key_exists('file', $media)) ? $media['file'] : ''?>"
				    						/>
				    					</div>	    	
				    					<div class="col-sm-2">
				    						<button type="button" class="btn btn-default">
				    							<span class="glyphicon glyphicon-play"></span>
				    						</button>
				    						<label for="file" class="btn btn-default">
				    							<span class="glyphicon glyphicon-upload"></span>
				    						</label>
			    							<div style="visibility: hidden; display: inline-block; width: 20px;">
			    								<input 	id="file" 
			    										type="file" 
			    										name="filename"
			    								/>
			    							</div>
				    					</div>
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Lungime</label>
				    					<div class="col-sm-2">
				    						<input 	type="text" 
				    								readonly="" 
				    								value="00:12:00" 
				    								class="form-control" 
				    								name="duration"
				    								value="<?=(array_key_exists('duration', $media)) ? $media['duration'] : ''?>"
				    						/>
				    					</div>	    					
				    				</div>
				    				
				    				<!--div class="form-group">
				    					<input type="hidden" name="useSection" value="0">
				    					<label class="col-sm-2 control-label">
				    						<input 	type="checkbox" 
				    								name="useSection"
				    								value="1"
				    								<?=(array_key_exists('useSection', $media) and ($media['useSection'] > 0)) ? 'checked="checked"' : ''?>
				    						/> Sectiune
				    					</label>
				    					<div class="col-sm-2">
				    						<input 	type="time" 
				    								class="form-control" 
				    								name="playStart"
				    								value="<?=(array_key_exists('playStart', $media)) ? $media['playStart'] : ''?>"
				    						/>
				    					</div>	    					
				    					<div class="col-sm-2">
				    						<input 	type="time" 
				    								class="form-control" 
				    								name="playEnd"
				    								value="<?=(array_key_exists('playEnd', $media)) ? $media['playEnd'] : ''?>"
				    						/>
				    					</div>	    					
				    				</div-->
				    				
				    				
				    				<div class="form-group">
				    					<input type="hidden" name="useDateInterval" value="0">				    				
				    					<label class="col-sm-2 control-label">
				    						<input 	type="checkbox" 
				    								name="useDateInterval" 
				    								value="1"
				    								<?=(array_key_exists('useDateInterval', $media) and ($media['useDateInterval'] > 0)) ? 'checked="checked"' : ''?>
				    						/>Perioada de afisare
				    					</label>
				    					<div class="col-sm-2">
				    						<input 	type="date"
				    								class="form-control" 
				    								name="startDate"
				    								value="<?=(array_key_exists('startDate', $media)) ? $media['startDate'] : ''?>"
				    						/>
				    					</div>	    					
				    					<div class="col-sm-2">
				    						<input 	type="date" 
				    								class="form-control" 
				    								name="endDate"
				    								value="<?=(array_key_exists('endDate', $media)) ? $media['endDate'] : ''?>"
				    						/>
				    					</div>	    					
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Nume</label>
				    					<div class="col-sm-8">
				    						<input 	type="text" 
				    								placeholder="ex: Oscilococcinum" 
				    								class="form-control" 
				    								name="mediaName"
				    								value="<?=(array_key_exists('mediaName', $media)) ? $media['mediaName'] : ''?>"
				    						/>
				    					</div>	    					
				    				</div>
				    				
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Etichete</label>
				    					<div class="col-sm-8">
				    						<input 	type="text" 
				    								placeholder="ex: reclama, medicament, homeopatic, iunie, 2015" 
				    								class="form-control" 
				    								name="mediaLabels"
				    								value="<?=(array_key_exists('mediaLabels', $media)) ? $media['mediaLabels'] : ''?>"
				    						/>
				    					</div>	    					
				    				</div>
				    				
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Tip</label>
				    					<div class="col-sm-8">
				    						<input 	type="text" 
				    								placeholder="ex: reclama" 
				    								class="form-control" 
				    								name="type"
				    								value="<?=(array_key_exists('type', $media)) ? $media['type'] : ''?>"
				    						/>
				    					</div>	    					
				    				</div>
				    				
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Client</label>
				    					<div class="col-sm-8">
				    						<input 	type="text" 
				    								placeholder="ex: Boiron" 
				    								class="form-control" 
				    								name="client"
				    								value="<?=(array_key_exists('client', $media)) ? $media['client'] : ''?>"
				    						/>
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
					    	<div class="col-sm-4">
					    		<a href="#" class="btn btn-primary">
					    			<span class="glyphicon glyphicon-plus"></span>
					    			Adauga
					    		</a>
					    		<a href="#" class="btn btn-danger">
					    			<span class="glyphicon glyphicon-remove"></span>
					    			Elimina
					    		</a>
					    	</div>											    		
				    	</div>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>Selecteaza</th>
									<th>Spot upload-at</th>
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
									<td><span class="glyphicon glyphicon-ok"></span></td>
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
									<td><span class="glyphicon glyphicon-ok"></span></td>
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
									<td></td>
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
									<td></td>
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