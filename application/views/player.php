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
					<li role="presentation" class="active"><a href="#properties" aria-controls="properties" role="tab" data-toggle="tab">Date</a></li>
					<li role="presentation"><a href="#playlist" aria-controls="playlist" role="tab" data-toggle="tab">Playlist</a></li>
					<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Setari</a></li>
				</ul>
			  	<!-- Tab panes -->
			  	<div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="properties">
				    	<div class="row">
				    		<div class="col-sm-12">
				    			<form class="form-horizontal" action="/players/update" data-ajax="true">
				    				<input 	type="hidden"
				    						name="id"
				    						data-type="player"
				    						value="<?=(array_key_exists('playerId', $player) and ($player['playerId'] > 0))? $player['playerId'] : ''?>"
				    				/>
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Nume</label>
				    					<div class="col-sm-8">
				    						<input 	type="text"
				    								placeholder="ex: Spitalul Judetean Maternitate"
				    								class="form-control"
				    								name="playerName"
				    								value="<?=(array_key_exists('playerName', $player)) ? $player['playerName'] : ''?>"
				    						/>
				    					</div>
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Etichete</label>
				    					<div class="col-sm-8">
				    						<input 	type="text"
				    								placeholder="ex: spital, maternitate, ardeal"
				    								class="form-control"
				    								name="playerLabels"
				    								value="<?=(array_key_exists('playerLabels', $player)) ? $player['playerLabels'] : ''?>"
				    						/>
				    					</div>
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Judet</label>
				    					<div class="col-sm-8">
				    						<input 	type="text"
				    								placeholder="ex: Timis"
				    								class="form-control"
				    								name="county"
				    								value="<?=(array_key_exists('county', $player)) ? $player['county'] : ''?>"
				    						/>
				    					</div>
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Oras</label>
				    					<div class="col-sm-8">
				    						<input 	type="text"
				    								placeholder="ex: Timisoara"
				    								class="form-control"
				    								name="city"
				    								value="<?=(array_key_exists('city', $player)) ? $player['city'] : ''?>"
				    						/>
				    					</div>
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Localizare</label>
				    					<div class="col-sm-8">
				    						<input 	type="text"
				    								placeholder="ex: sala de asteptare, etaj 1"
				    								class="form-control"
				    								name="location"
				    								value="<?=(array_key_exists('location', $player)) ? $player['location'] : ''?>"
				    						/>
				    					</div>
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Observatii</label>
				    					<div class="col-sm-8">
				    						<textarea 	class="form-control"
				    									name="comment"
				    									placeholder="Bla bla bla...."
				    									rows="6"
				    						><?=(array_key_exists('comment', $player)) ? $player['comment'] : ''?></textarea>
				    					</div>
				    				</div>

				    				<div class="form-group">
				    					<input type="hidden" name="playerActive" value="0"/>
				    					<label class="col-sm-2 control-label">
				    							<input type="checkbox" name="playerActive" value="1" <?=(array_key_exists('playerActive', $player) and ($player['playerActive'] > 0)) ? 'checked="checked"' : ''?>> Activ
				    					</label>
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-offset-1 col-sm-2 control-label">
				    							<input type="checkbox" name="restart_player"> Restart player
				    					</label>
				    					<label class="col-sm-2 control-label">
				    							<input type="checkbox" name="force_update"> Update imediat
				    					</label>
				    					<label class="col-sm-2 control-label">
				    							<input type="checkbox" name="player_update"> Update player
				    					</label>
				    				</div>
				    				<div class="col-sm-10 col-sm-offset-1">
				    					<button class="btn btn-primary"> <span class="glyphicon glyphicon-ok">	</span> OK</button>
				    				</div>
				    			</form>
				    		</div>
				    	</div>
				    </div>
				    <div role="tabpanel" class="tab-pane" id="playlist">
						<div class="row">
							<div class="col-sm-5">
								<button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Adauga in playlist</button>
								<button class="btn btn-danger" data-action="remove-media-from-playlist"><span class="glyphicon glyphicon-remove"></span> Scoate din playlist</button>
							</div>
							<div class="col-sm-7">
								<form class="form-horizontal">
				    				<div class="form-group">
				    					<label class="col-sm-7 control-label">Data</label>
				    					<div class="col-sm-5">
				    						<input 	type="date"
				    								class="form-control"
				    								name="name"
				    								id="playlist-date"
				    								value="<?=iso_date_now()?>"
				    						/>
				    					</div>
				    				</div>
								</form>
							</div>
						</div>
						<div class="row">
				    		<div class="col-sm-12">
								<table class="table table-striped" id="playlist-table" player-id="<?=(array_key_exists('playerId', $player) and ($player['playerId'] > 0))? $player['playerId'] : ''?>">
									<thead>
									</thead>
									<tbody>
									</tbody>
								</table>
				    		</div>
				    	</div>
				    </div>
				    <div role="tabpanel" class="tab-pane" id="settings">
						<div class="row">
				    		<div class="col-sm-12">
				    			<form class="form-horizontal" action="/players/update" method="post" data-ajax="true">
				    				<input 	type="hidden"
				    						name="id"
				    						value="<?=(array_key_exists('playerId', $player)) ? $player['playerId'] : ''?>"
				    				/>
				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Code</label>
				    					<div class="col-sm-8">
				    						<input 	type="text"
				    								class="form-control"
				    								name="code"
				    								value="<?=(array_key_exists('code', $player)) ? $player['code'] : ''?>"
				    						/>
				    					</div>
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Luni - Vineri</label>
				    					<div class="col-sm-2">
				    						<input 	type="number"
				    								min="0"
				    								max="23"
				    								class="form-control"
				    								name="mfStart"
				    								value="<?=(array_key_exists('mfStart', $player)) ? $player['mfStart'] : '8'?>"
				    						/>
				    					</div>
				    					<div class="col-sm-2">
				    						<input 	type="number"
				    								min="0"
				    								max="23"
				    								class="form-control"
				    								name="mfEnd"
				    								value="<?=(array_key_exists('mfEnd', $player)) ? $player['mfEnd'] : '20'?>"
				    						/>
				    					</div>
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Sambata</label>
				    					<div class="col-sm-2">
				    						<input 	type="number"
				    								min="0"
				    								max="23"
				    								class="form-control"
				    								name="satStart"
				    								value="<?=(array_key_exists('satStart', $player)) ? $player['satStart'] : '8'?>"
				    						/>
				    					</div>
				    					<div class="col-sm-2">
				    						<input 	type="number"
				    								min="0"
				    								max="23"
				    								class="form-control"
				    								name="satEnd"
				    								value="<?=(array_key_exists('satEnd', $player)) ? $player['satEnd'] : '20'?>"
				    						/>
				    					</div>
				    				</div>

				    				<div class="form-group">
				    					<label class="col-sm-2 control-label">Duminica</label>
				    					<div class="col-sm-2">
				    						<input 	type="number"
				    								min="0"
				    								max="23"
				    								class="form-control"
				    								name="sunStart"
				    								value="<?=(array_key_exists('sunStart', $player)) ? $player['sunStart'] : '8'?>"
				    						/>
				    					</div>
				    					<div class="col-sm-2">
				    						<input 	type="number"
				    								min="0"
				    								max="23"
				    								class="form-control"
				    								name="sunEnd"
				    								value="<?=(array_key_exists('sunEnd', $player)) ? $player['sunEnd'] : '20'?>"
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
			  	</div>
			</div>
		</div>
	</div>
</div> <!-- container -->


<?php
    $this->load->view('footer');
