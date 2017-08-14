<form name="galleriecodeForm" method="post" action="<?php echo remove_query_arg('pawps_galerieReset', add_query_arg (array('pawps_shooting'=> $shooting->id, 'pawps_ordner' => null))); ?>">
	Bitte geben Sie den Ihnen übersendeten Galleriecode ein:
	<input type="text" name="PA_GALLERIECODE" size="25" maxlength="25" >
	<p class="submit">
		<input type="submit" name="checkGalleriecode" class="button-primary" value="Galleriecode pruefen" />
	</p>
</form>