<div class="wrap">
	<h2>aitch ref!</h2>
	
	<?php echo $messages; ?>

	<form method="post">
		<?php wp_nonce_field( 'aitch-ref-admin', '_wpnonce', FALSE, TRUE ); ?> 

		<h3>Site URLs</h3>
		<textarea class="aitch-ref has-ref" name="aitchref[urls]"><?php echo esc_textarea( $urls ); ?></textarea>
		<p class="description">possible urls seperated by space or new line (include http/s, no trailing slash)</p>

		<h3>Absolute</h3>
		<textarea class="aitch-ref filters" name="aitchref[filters_absolute]"><?php echo esc_textarea( $filters_absolute ); ?></textarea>

		<h3>Relative</h3>
		<textarea class="aitch-ref filters" name="aitchref[filters_relative]"><?php echo esc_textarea( $filters_relative ); ?></textarea>

		<div>
			<input type="submit" value="Update"/>
		</div>
	</form>

	<a href="http://www.flickr.com/photos/avinashkunnath/2402114514/in/photostream/" style="font-size:.6em">photo by Avinash Kunnath</a>
</div>