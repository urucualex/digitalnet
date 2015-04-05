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
					<li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profil</a></li>
					<li role="presentation"><a href="#accounts" aria-controls="accounts" role="tab" data-toggle="tab">Conturi</a></li>
				</ul>
			  	<!-- Tab panes -->
			  	<div class="tab-content">	
				    <div role="tabpanel" class="tab-pane active" id="profile">
				    	<div class="row">
				    		<div class="col-sm-12">
				    			<form class="form-horizontal">
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Nume</label>
				    					<div class="col-sm-8">
				    						<input type="text" placeholder="ex: Mihai Bors" class="form-control" name="name"/>
				    					</div>	    					
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Email</label>
				    					<div class="col-sm-8">
				    						<input type="text" placeholder="ex: it@info-sanatate.ro" class="form-control" name="email"/>
				    					</div>	    					
				    				</div>
				    				
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Telefon</label>
				    					<div class="col-sm-8">
				    						<input type="text" placeholder="ex: 0720123456" class="form-control" name="phone"/>
				    					</div>	    					
				    				</div>
				    				
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Parola</label>
				    					<div class="col-sm-8">
				    						<input type="password" placeholder="ex: parola curenta" class="form-control" name="password"/>
				    					</div>	    					
				    				</div>
				    				
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Parola noua</label>
				    					<div class="col-sm-8">
				    						<input type="password" placeholder="ex: parola noua" class="form-control" name="newpassword"/>
				    					</div>	    					
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Repeta parola</label>
				    					<div class="col-sm-8">
				    						<input type="password" placeholder="ex: parola noua" class="form-control" name="newpassword2"/>
				    					</div>	    					
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">
				    							<input type="checkbox" checked="checked" name="active"> Activ
				    					</label>    					
				    				</div>

				    				<div class="col-sm-10 col-sm-offset-1">
				    					<button class="btn btn-primary"> <span class="glyphicon glyphicon-ok">	</span> OK</button>
				    				</div>
				    			</form>
				    		</div>
				    	</div>
				    </div>
				    <div role="tabpanel" class="tab-pane" id="accounts">
						<div class="row">
				    		<div class="col-sm-12">
								<table class="table table-striped">
									<thead>
										<tr>
											<th>#</th>
											<th>Actiuni</th>
											<th>Nume</th>
											<th>Email</th>
											<th>Telefon</th>
											<th>Activ</th>
					 					</tr>
									</thead>
									<tbody>
										<tr>
											<td>1</td>
											<td>
												<a href="/users" target="_blank" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
												<a href="/users/delete/1" target="_blank" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
											</td>
											<td>Mihai Bors</td>
											<td>it@info-sanatate.ro</td>						
											<td>074123456</td>	
											<td><span class="glyphicon glyphicon-ok"></span></td>										
										</tr>
										<tr>
											<td>1</td>
											<td>
												<a href="/users" target="_blank" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
												<a href="/users/delete/1" target="_blank" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
											</td>
											<td>Mihai Bors</td>
											<td>it@info-sanatate.ro</td>						
											<td>074123456</td>	
											<td><span class="glyphicon glyphicon-ok"></span></td>										
										</tr>
										<tr>
											<td>1</td>
											<td>
												<a href="/users" target="_blank" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
												<a href="/users/delete/1" target="_blank" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
											</td>
											<td>Mihai Bors</td>
											<td>it@info-sanatate.ro</td>						
											<td>074123456</td>	
											<td><span class="glyphicon glyphicon-ok"></span></td>										
										</tr>																		
									</tbody>
								</table>
				    		</div>
				    	</div>
				    </div>														
			  	</div>
			</div>
		</div>	
	</div>
</div> <!-- container -->

        
<?php
    $this->load->view('footer');