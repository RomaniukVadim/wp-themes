<?php
	if ( comments_open() || get_comments_number() != 0 ) {
		comments_template();
	}
?>
