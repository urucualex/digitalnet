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
				    							data-type="media"
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
				    						<a href="<?=(array_key_exists('file', $media) and !empty($media['file'])) ? '/media/download/'.$media['file'] : '#'?>" id="play_media" target="_blank" class="btn btn-default">
				    							<span class="glyphicon glyphicon-play"></span>
				    						</a>
				    						<label for="file" class="btn btn-default">
				    							<span class="glyphicon glyphicon-upload"></span>
				    						</label>
			    							<div style="visibility: hidden; display: inline-block; width: 20px;">
			    								<input 	id="file"
			    										type="file"
			    										name="filename"
			    										action="/media/upload/"
			    										value-holder="input[name=file]"
			    										data-ajax="true"
			    										on-success="mediaUploaded"
			    								/>
			    							</div>
				    					</div>
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Lungime</label>
				    					<div class="col-sm-2">
				    						<input 	type="text"
				    								readonly=""
				    								class="form-control duration-display"
				    								value="<?=(array_key_exists('duration', $media)) ? toHHMMSS($media['duration']) : '00:00'?>"
				    						/>
				    						<input 	type="hidden"
				    								name="duration"
				    								value="<?=(array_key_exists('duration', $media)) ? $media['duration'] : '0'?>"
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

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Observatii</label>
				    					<div class="col-sm-8">
				    						<textarea 	class="form-control"
				    									name="comment"
				    									placeholder="Bla bla bla...."
				    									rows="10"
				    						><?=(array_key_exists('comment', $media)) ? $media['comment'] : ''?></textarea>
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
					    		<a href="#" class="btn btn-danger" data-action="remove-players-from-media">
					    			<span class="glyphicon glyphicon-remove"></span>
					    			Elimina
					    		</a>
					    	</div>
				    	</div>
						<table id="media-players-table" class="table table-striped" media-id="<?=(array_key_exists('mediaId', $media)) ? $media['mediaId'] : ''?>">
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
							</tbody>
						</table>
				    </div>
			  	</div>
			</div>
		</div>
	</div>
</div> <!-- container -->

<?php
    $this->load->view('footer');
