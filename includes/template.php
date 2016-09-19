<?php

//Direct access not allowed.
if ( ! defined("ABSPATH"))
{
	die;
}

?>

<div class="wrap">
	<h1>Power Staging Plugin</h1>
	<form action="" method="POST">
		<table class="form-table">
			<tbody>
				<tr>
					<th>
						<label for="pwst_new_siteurl">New Site URL</label>
					</th>
					<td>
						<input type="text" id="pwst_new_siteurl" name="pwst_new_siteurl" class="regular-text" placeholder="Enter New Site URL" required>
					</td>
					<th>
						<span>Current Site URL</span>
					</th>
					<td>
						<input type="text" value="<?php echo $pwst_siteurl; ?>" class="regular-text code" disabled>
					</td>
				</tr>
				<tr>
					<th>
						<label for="pwst_new_home_folder">New Home Folder</label>
					</th>
					<td>
						<input type="text" id="pwst_new_home_folder" name="pwst_new_home_folder" class="regular-text" placeholder="Enter New Home Folder" required>
					</td>
					<th>
						<span>Current Home Folder</span>
					</th>
					<td>
						<input type="text" value="<?php echo $pwst_home_folder; ?>"  class="regular-text" disabled>
					</td>
				</tr>
				<tr>
					<th>
						<span>Staging to Windows</span>
					</th>
					<td colspan="3">
						<fieldset>
							<legend class="screen-reader-text">
								<span>Staging to Windows</span>
							</legend>
							<label for="pwst_windows_staging">
								<input type="checkbox" id="pwst_windows_staging" name="pwst_windows_staging" <?php if ($pwst_windows_staging) echo "checked"; ?>>
								Check this if the staging is going to be installed on Windows
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th colspan="4">
						<button onclick="pwstPowerStaging.addCustomField(); return false;" class="button-secondary">Add custom field</button>
					</th>
				</tr>
				<?php if (isset($staging_file)) { ?>
					<tr>
						<td colspan="4">
							<a href="<?php echo $staging_file; ?>" download="database_staging.sql" class="button-secondary">Download current staging database</a>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<table class="form-table" id="pwst_custom_fields"></table>
		<p class="submit">
			<input type="submit" value="Generate Staging DB" class="button-primary">
		</p>
	</form>
</div>