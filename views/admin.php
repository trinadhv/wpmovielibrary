<div id="wpml-settings" class="wrap">
	<h2><?php _e( 'Plugin Options', 'wpml' ); ?></h2>
	
	<?php if ( isset($this->msg_settings) ) { ?>
	<div id="setting-error-settings_updated" class="updated settings-error"> 
		<p><strong><?php echo $this->msg_settings; ?></strong></p>
	</div>
	<?php } ?>

	<?php //if ( ! $this->wpml_get_api_key() ) $this->wpml_activate_notice( null ); ?>

	<div id="wpml-tabs">

		<form method="post">

			<ul>
			    <li><a href="#fragment-1"><h4><span class="ui-icon ui-icon-gear"></span> <?php _e( 'TMDb API', 'wpml' ); ?></h4></a></li>
			    <li><a href="#fragment-2"><h4><span class="ui-icon ui-icon-wrench"></span> <?php _e( 'WPMovieLibrary', 'wpml' ); ?></h4></a></li>
			    <li style="float:right"><a href="#fragment-3"><h4><span class="ui-icon ui-icon-closethick"></span> <?php _e( 'Restore', 'wpml' ); ?></h4></a></li>
			</ul>

			<div id="fragment-1">
				<table class="form-table wpml-settings">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="APIKey"><?php _e( 'API Key', 'wpml' ); ?></label>
							</th>
							<td>
								<input id="APIKey" type="text" name="APIKey" value="<?php echo ( $this->wpml_get_api_key() ? $this->wpml_get_api_key() : '' ); ?>" size="40" maxlength="32" />
								<input id="APIKey_check" type="button" name="APIKey_check" class="button button-secondary button-small" value="<?php _e( 'Check API Key', 'wpml' ); ?>" />
								<p class="description"><?php _e( 'You need a valid TMDb API key in order to fetch informations on the movies you add to WP-Movie-Library. You can get an individual API key by registering on <a href="https://www.themoviedb.org/">TheMovieDB</a>. If you don&rsquo;t want to get your own API Key, WP-Movie-Library will use a built-in key, with restrictions of two movies added per day.', 'wpml' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="lang"><?php _e( 'API Language', 'wpml' ); ?></label>
							</th>
							<td>
								<select id="lang" name="lang">
									<option value="en" <?php selected( $this->wpml_o('tmdb-settings-lang'), 'en' ); ?>><?php _e( 'English', 'wpml' ); ?></option>
									<option value="fr" <?php selected( $this->wpml_o('tmdb-settings-lang'), 'fr' ); ?>><?php _e( 'French', 'wpml' ); ?></option>
								</select>
								<p class="description"><?php _e( 'Default language to use when fetching informations from TMDb. Default is english. You can always change this manually when add a new movie.', 'wpml' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="scheme"><?php _e( 'API Scheme', 'wpml' ); ?></label>
							</th>
							<td>
								<select id="scheme" name="scheme">
									<option value="http" <?php selected( $this->wpml_o('tmdb-settings-scheme'), 'http' ); ?>><?php _e( 'HTTP', 'wpml' ); ?></option>
									<option value="https" <?php selected( $this->wpml_o('tmdb-settings-scheme'), 'https' ); ?>><?php _e( 'HTTPS', 'wpml' ); ?></option>
								</select>
								<p class="description"><?php _e( 'Default scheme used to contact TMDb API. Default is HTTPS.', 'wpml' ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div id="fragment-2">

				<h4><?php _e( 'Poster Settings', 'wpml' ); ?></h4>
				<table class="form-table wpml-settings">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="poster_size"><?php _e( 'Posters Default Size', 'wpml' ); ?></label>
							</th>
							<td>
								<select id="poster_size" name="poster_size">
									<option value="small" <?php selected( $this->wpml_o('tmdb-settings-poster_size'), 'small' ); ?>><?php _e( 'Small', 'wpml' ); ?></option>
									<option value="medium" <?php selected( $this->wpml_o('tmdb-settings-poster_size'), 'medium' ); ?>><?php _e( 'Medium', 'wpml' ); ?></option>
									<option value="full" <?php selected( $this->wpml_o('tmdb-settings-poster_size'), 'full' ); ?>><?php _e( 'Full', 'wpml' ); ?></option>
									<option value="original" <?php selected( $this->wpml_o('tmdb-settings-poster_size'), 'original' ); ?>><?php _e( 'Original', 'wpml' ); ?></option>
								</select>
								<p class="description"><?php _e( 'Movie Poster size. Default is TMDb&rsquo;s original size.', 'wpml' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="poster_featured"><?php _e( 'Add Posters As Thumbnails', 'wpml' ); ?></label>
							</th>
							<td>
								<input type="radio" name="poster_featured" value="1" <?php checked( $this->wpml_o('tmdb-settings-poster_featured'), 1 ); ?>/> <?php _e( 'Use Posters as Movies Thumbnails', 'wpml' ); ?>
								<input type="radio" name="poster_featured" value="0" <?php checked( $this->wpml_o('tmdb-settings-poster_featured'), 0 ); ?>/> <?php _e( 'Don&rsquo;t', 'wpml' ); ?>
								<p class="description"><?php _e( 'Using posters as movies thumbnails will automatically import new movies&rsquo; poster and set them as post featured image.', 'wpml' ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>

				<h4><?php _e( 'Images Settings', 'wpml' ); ?></h4>
				<table class="form-table wpml-settings">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="images_size"><?php _e( 'Images Default Size', 'wpml' ); ?></label>
							</th>
							<td>
								<select id="images_size" name="images_size">
									<option value="small" <?php selected( $this->wpml_o('tmdb-settings-images_size'), 'small' ); ?>><?php _e( 'Small', 'wpml' ); ?></option>
									<option value="medium" <?php selected( $this->wpml_o('tmdb-settings-images_size'), 'medium' ); ?>><?php _e( 'Medium', 'wpml' ); ?></option>
									<option value="full" <?php selected( $this->wpml_o('tmdb-settings-images_size'), 'full' ); ?>><?php _e( 'Full', 'wpml' ); ?></option>
									<option value="original" <?php selected( $this->wpml_o('tmdb-settings-images_size'), 'original' ); ?>><?php _e( 'Original', 'wpml' ); ?></option>
								</select>
								<p class="description"><?php _e( 'Movie Poster size. Default is TMDb&rsquo;s original size.', 'wpml' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="images_max"><?php _e( 'Maximum Images To Fetch', 'wpml' ); ?></label>
							</th>
							<td>
								<input id="images_max" type="text" name="images_max" value="<?php echo $this->wpml_o('tmdb-settings-images_max'); ?>" size="4" maxlength="2" />
								<p class="description"><?php _e( 'Maximum amount of images to fetch. Especially useful if you activated automatic images import. Default is12, set at 0 to fetch all images.', 'wpml' ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div id="fragment-3">

				<h4><?php _e( 'Restore Default Settings', 'wpml' ); ?></h4>
				<p class="update-nag">
					<span class="ui-icon ui-icon-alert"></span>
					<?php _e( 'You may want to restore WPMovieLibrary default settings.', 'wpml' ); ?>
					<?php _e( '<strong>Caution!</strong> Doing this you will erase permanently all your custom settings. Don&rsquo;t do this unless you are positively sure of what you&rsquo;re doing!', 'wpml' ); ?>
				</p>
				<p style="text-align:center">
					<input id="restore_default" type="submit" name="restore_default" class="button button-secondary button-large" value="Restore" />
				</p>
			</div>

			<p class="submit">
				<input type="submit" id="submit" name="submit" class="button-primary" value="Save Changes" />
			</p>

		</form>

	</div>

	<?php include_once $this->plugin_path . 'views/help.php'; ?>

</div>