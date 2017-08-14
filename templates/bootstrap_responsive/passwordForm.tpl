<?php

  $formAction = add_query_arg('pawps_ordner', null);
  if (isset($shooting)) {
  	$formAction = add_query_arg('pawps_shooting', $shooting->id, $formAction);
  }
  $formAction = remove_query_arg('pawps_galerieReset', $formAction);

?>

<form name="gaestekennwortForm" method="post" action="<?php echo $formAction; ?>">
	Der Zugriff wird durch ein Gaestekennwort geschuetzt. Bitte geben Sie hier das Ihnen übersendete Kennwort ein:
	<input type="text" name="PA_GUESTPASSWORD" size="25" maxlength="25">
	<p class="submit">
		<input type="submit" name="checkGuestPassword" class="button-primary" value="Kennwort pruefen" />
	</p>
</form>