<form name="adressdatenForm" method="post" action="<?php echo remove_query_arg('pawps_galerieReset', add_query_arg (array('pawps_shooting'=> $shooting->id, 'pawps_ordner' => null))); ?>" class="form-inline">
	<div class="row">
		<div class="col-sm-2 text-right">
			<label>Name, Vorname:</label>
		</div>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="lastname" size="20" maxlength="25" value="<?php echo $name; ?>" /> <input class="form-control" type="text" name="firstname" size="20" maxlength="25" value="<?php echo $firstname; ?>" />
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-2 text-right">
			<label>Strasse, Nr:</label>
		</div>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="street" size="30" maxlength="50" value="<?php echo $street; ?>" /> <input class="form-control" type="text" name="number" size="8" maxlength="6" value="<?php echo $number; ?>" />
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-2 text-right">
			<label>Plz, Ort:</label>
		</div>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="plz" size="6" maxlength="6" value="<?php echo $plz; ?>" /> <input class="form-control" type="text" name="ort" size="30" maxlength="40" value="<?php echo $city; ?>" />
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-2 text-right">
			<label>Land:</label>
		</div>
		<div class="col-sm-8">
			<select name="land" class="form-control">
								<?php 
									foreach ($versandlaender as $land) {
										echo "<option value='" . $land->id . "'";
										if ($land->id == $landId) echo " selected";
										echo ">" . $land->title . "</option>";
									}
								?>
							</select>
		</div>
	</div>
	
	<?php
		if ($rechnungsadresse) {
	?>
		<div class="row">
			<div class="col-sm-2 text-right">
				<label>E-Mail:</label>
			</div>
			<div class="col-sm-8">
				<input class="form-control" type="text" name="email" size="40" maxlength="50" value="<?php echo $email; ?>" />
			</div>
		</div>
	
		<div class="row">
			<div class="col-sm-2 text-right">
				<label>Lieferadresse:</label>
			</div>
			<div class="col-sm-8">
				<select name="lieferadresseAbweichend" class="form-control">
					<option value="0">gleich Rechnungsadresse</option>
					<option value="1">abweichend</option>
				</select>
			</div>
		</div>
	<?php
		}
	?>
				
				<?php
					if ($rechnungsadresse) {
				?>
					<input type="submit" name="doAddRechnungsadresse" class="btn btn-default" value="weiter" />
				<?php
					} else {
				?>
					<input type="submit" name="doAddLieferadresse" class="btn btn-default" value="weiter" />
				<?php
					}
				?>					
			</form>