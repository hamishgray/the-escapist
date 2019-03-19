<div class="wrap">
<h2>Google Tag Manager with Data Layer</h2>

<p>In order to add the Google Tag Manager code to your blog, just paste it below:
</p>
<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<?php settings_fields('gtmCode'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row">Google Tag Manager Code:</th>
<td><textarea name="gtmCodePadi" rows="10" cols="100"> <?php echo get_option('gtmCodePadi'); ?></textarea></td>
</tr>

</table>

<input type="hidden" name="action" value="update" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
<h3>How to get you Google Tag Manager code</h2>
<iframe width="560" height="315" src="http://www.youtube.com/embed/YeBwJb0YLRI" frameborder="0" allowfullscreen></iframe>
</div>
