<form name="gaestekennwortForm" method="post" action="<?php echo remove_query_arg('pawps_galerieReset', add_query_arg (array('pawps_shooting'=> $shooting->id, 'pawps_ordner' => null))); ?>">
	Der Zugriff wird durch ein Gaestekennwort geschuetzt. Bitte geben Sie hier das Ihnen übersendete Kennwort ein:
	<input type="text" name="PA_GUESTPASSWORD" size="25" maxlength="25" >
	<p class="submit">
		<input type="submit" name="checkGuestPassword" class="button-primary" value="Kennwort pruefen" />
	</p>
</form>