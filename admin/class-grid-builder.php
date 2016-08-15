<?php
/**
 * Define the Grid Builder class.
 *
 * @link       http://wpmovielibrary.com
 * @since      3.0
 *
 * @package    WPMovieLibrary
 * @subpackage WPMovieLibrary/admin
 */

namespace wpmoly\Admin;

use wpmoly\Node\Grid;
use wpmoly\Core\Loader;
use wpmoly\Core\PublicTemplate;

/**
 * Provide a tool to create, build, and save grids.
 * 
 * Currently supports movies, actors and genres.
 *
 * @package    WPMovieLibrary
 * @subpackage WPMovieLibrary/admin
 * @author     Charlie Merland <charlie@caercam.org>
 */
class GridBuilder {

	/**
	 * Grid Post Type metaboxes.
	 * 
	 * @var    array
	 */
	private $metaboxes = array();

	/**
	 * Grid instance.
	 * 
	 * @var    Grid
	 */
	private $grid;

	/**
	 * Class constructor.
	 * 
	 * @since    3.0
	 */
	public function __construct() {

		// Load the Grid
		$this->grid = new Grid( get_the_ID() );

		$metaboxes = array(
			'type' => array(
				'id'            => 'wpmoly-grid-type',
				'title'         => __( 'Type', 'wpmovielibrary' ),
				'callback'      => array( $this, 'type_metabox' ),
				'screen'        => 'grid',
				'context'       => 'side',
				'priority'      => 'high',
				'callback_args' => null
			)
		);

		/**
		 * Filter metaboxes for the grid builder.
		 * 
		 * @since    3.0
		 * 
		 * @param    array     $metaboxes Default metaboxes.
		 * @param    object    GridBuilder instance.
		 */
		$this->metaboxes = apply_filters( 'wpmoly/filter/grid/metaboxes', $metaboxes, $this );

		$settings = array(
			'movie-grid-settings' => array(
				'label'     => esc_html__( 'Réglages', 'wpmovielibrary' ),
				'post_type' => 'grid',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					'movie-grid-presets' => array(
						'label'    => esc_html__( 'Presets', 'wpmovielibrary' ),
						'icon'     => 'wpmolicon icon-cogs',
						'settings' => array(
							'movie-grid-preset' => array(
								'type'    => 'radio-image',
								'section' => 'movie-grid-presets',
								'label'   => esc_html__( 'Grid preset', 'wpmovielibrary' ),
								'description' => esc_html__( 'Select a preset to apply to the grid. Presets override any filters and ordering settings you might define, be sure to select "Custom" for those settings to be used.', 'wpmovielibrary' ),
								'attr'    => array( 'class' => 'visible-labels half-col' ),
								'choices' => array(
									'alphabetical-movies' => array(
										'label' => esc_html__( 'Alpabetical Movies', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/alphabetical-movies.png'
									),
									'unalphabetical-movies' => array(
										'label' => esc_html__( 'Alpabetical Movies', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/unalphabetical-movies.png'
									),
									'current-year-movies' => array(
										'label' => esc_html__( 'This Year Movies', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/current-year-movies.png'
									),
									'last-year-movies' => array(
										'label' => esc_html__( 'Last Year Movies', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/last-year-movies.png'
									),
									'last-added-movies' => array(
										'label' => esc_html__( 'Latest Added Movies', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/last-added-movies.png'
									),
									'first-added-movies' => array(
										'label' => esc_html__( 'Earliest Added Movies', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/first-added-movies.png'
									),
									'last-released-movies' => array(
										'label' => esc_html__( 'Latest Released Movies', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/last-released-movies.png'
									),
									'first-released-movies' => array(
										'label' => esc_html__( 'Earliest Released Movies', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/first-released-movies.png'
									),
									'incoming-movies' => array(
										'label' => esc_html__( 'Incoming Movies', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/incoming-movies.png'
									),
									'most-rated-movies' => array(
										'label' => esc_html__( 'Most Rated Movies', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/most-rated-movies.png'
									),
									'least-rated-movies' => array(
										'label' => esc_html__( 'Least Rated Movies', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/least-rated-movies.png'
									),
									'custom' => array(
										'label' => esc_html__( 'Custom', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/custom.png'
									)
								),
								'sanitize' => 'esc_attr'
							)
						)
					),
					'movie-grid-content' => array(
						'label'    => esc_html__( 'Content', 'wpmovielibrary' ),
						'icon'     => 'dashicons-filter',
						'settings' => array(
							'movie-grid-total' => array(
								'type'     => 'text',
								'section'  => 'movie-grid-content',
								'label'    => esc_html__( 'Number of movies', 'wpmovielibrary' ),
								'description' => esc_html__( 'Number of movies for the grid. Setting a number of movie will result in the rows number to be ignored. Default is 5.', 'wpmovielibrary' ),
								'attr'     => array( 'size' => '2' ),
								'sanitize' => 'intval',
								'default'  => 5
							),
							'text' => array(
								'type'     => 'text',
								'section'  => 'movie-grid-content',
								'label'    => esc_html__( 'Text input', 'wpmovielibrary' ),
								'attr'     => array( 'class' => 'widefat' ),
								'sanitize' => 'wp_filter_nohtml_kses'
							)
						)
					),
					'movie-grid-ordering' => array(
						'label' => esc_html__( 'Ordering', 'wpmovielibrary' ),
						'icon'  => 'dashicons-randomize',
						'settings' => array(
							'movie-grid-order-by' => array(
								'type'     => 'select',
								'section'  => 'movie-grid-ordering',
								'label'    => esc_html__( 'Order By…', 'wpmovielibrary' ),
								'attr'     => array( 'class' => 'half-col' ),
								'choices' => array(
									'post-date'           => esc_html__( 'Post Date', 'wpmovielibrary' ),
									'released-date'       => esc_html__( 'Release Date', 'wpmovielibrary' ),
									'local-released-date' => esc_html__( 'Local Release Date', 'wpmovielibrary' ),
									'rating'              => esc_html__( 'Rating', 'wpmovielibrary' ),
									'alpabetical'         => esc_html__( 'Alpabetically', 'wpmovielibrary' ),
									'random'              => esc_html__( 'Random', 'wpmovielibrary' ),
								),
								'sanitize' => 'esc_attr'
							),
							'movie-grid-order' => array(
								'type'     => 'select',
								'section'  => 'movie-grid-ordering',
								'label'    => esc_html__( 'Order', 'wpmovielibrary' ),
								'attr'     => array( 'class' => 'half-col' ),
								'choices' => array(
									'asc'  => esc_html__( 'Ascendingly', 'wpmovielibrary' ),
									'desc' => esc_html__( 'Descendingly', 'wpmovielibrary' ),
								),
								'sanitize' => 'esc_attr'
							)
						)
					),
					'movie-grid-appearance' => array(
						'label' => esc_html__( 'Appearance', 'wpmovielibrary' ),
						'icon'  => 'dashicons-admin-appearance',
						'settings' => array(
							'movie-grid-columns' => array(
								'type'     => 'text',
								'section'  => 'movie-grid-appearance',
								'label'    => esc_html__( 'Number of rows', 'wpmovielibrary' ),
								'description' => esc_html__( 'Number of rows for the grid. Default is 5.', 'wpmovielibrary' ),
								'attr'     => array( 'class' => 'half-col', 'size' => '2' ),
								'sanitize' => 'intval',
								'default'  => 5
							),
							'movie-grid-rows' => array(
								'type'     => 'text',
								'section'  => 'movie-grid-appearance',
								'label'    => esc_html__( 'Number of columns', 'wpmovielibrary' ),
								'description' => esc_html__( 'Number of columns for the grid. Default is 4.', 'wpmovielibrary' ),
								'attr'     => array( 'class' => 'half-col', 'size' => '2' ),
								'sanitize' => 'intval',
								'default'  => 4
							),
							'movie-grid-column-width' => array(
								'type'     => 'text',
								'section'  => 'movie-grid-appearance',
								'label'    => esc_html__( 'Movie Poster ideal width', 'wpmovielibrary' ),
								'description' => esc_html__( 'Ideal width for posters. Grid columns will never exceed that width. Default is 160.', 'wpmovielibrary' ),
								'attr'     => array( 'class' => 'half-col', 'size' => '2' ),
								'sanitize' => 'intval',
								'default'  => 160
							),
							'movie-grid-row-height' => array(
								'type'     => 'text',
								'section'  => 'movie-grid-appearance',
								'label'    => esc_html__( 'Movie Poster ideal height', 'wpmovielibrary' ),
								'description' => esc_html__( 'Ideal height for posters. Grid rows will never exceed that height. Tip: that value should be equal to ideal width times 1.5. Default is 240.', 'wpmovielibrary' ),
								'attr'     => array( 'class' => 'half-col', 'size' => '2' ),
								'sanitize' => 'intval',
								'default'  => 240
							),
						)
					),
					'movie-grid-controls' => array(
						'label' => esc_html__( 'User Control', 'wpmovielibrary' ),
						'icon'  => 'dashicons-admin-tools',
						'settings' => array(
							'movie-grid-show-menu' => array(
								'type'     => 'checkbox',
								'section'  => 'movie-grid-controls',
								'label'    => esc_html__( 'Show Menu', 'wpmovielibrary' ),
								'description' => esc_html__( 'Enable the grid menu. Visitors will be able to change some settings to alter the grid appearance to their liking. The changes are local not persitent and will never be stored anywhere on your site. Default is enabled.', 'wpmovielibrary' ),
								'attr'     => array(),
								'sanitize' => '_is_bool',
								'default'  => 1
							),
							'movie-grid-mode-control' => array(
								'type'     => 'checkbox',
								'section'  => 'movie-grid-controls',
								'label'    => esc_html__( 'Grid Mode', 'wpmovielibrary' ),
								'description' => esc_html__( 'Allow visitors can change the grid mode. Default is disabled.', 'wpmovielibrary' ),
								'attr'     => array( 'class' => 'half-col', 'data-parent' => 'movie-grid-show-menu' ),
								'sanitize' => '_is_bool',
								'default'  => 0
							),
							'movie-grid-content-control' => array(
								'type'     => 'checkbox',
								'section'  => 'movie-grid-controls',
								'label'    => esc_html__( 'Grid Content', 'wpmovielibrary' ),
								'description' => esc_html__( 'Allow visitors can change the grid content, ie. number of movies, rows, columns… Default is disabled.', 'wpmovielibrary' ),
								'attr'     => array( 'class' => 'half-col', 'data-parent' => 'movie-grid-show-menu' ),
								'sanitize' => '_is_bool',
								'default'  => 0
							),
							'movie-grid-display-control' => array(
								'type'     => 'checkbox',
								'section'  => 'movie-grid-controls',
								'label'    => esc_html__( 'Grid Display', 'wpmovielibrary' ),
								'description' => esc_html__( 'Allow visitors can change the grid display, ie. showing/hiding titles, ratings, genres… Default is disabled.', 'wpmovielibrary' ),
								'attr'     => array( 'class' => 'half-col', 'data-parent' => 'movie-grid-show-menu' ),
								'sanitize' => '_is_bool',
								'default'  => 0
							),
							'movie-grid-order-control' => array(
								'type'     => 'checkbox',
								'section'  => 'movie-grid-controls',
								'label'    => esc_html__( 'Grid Ordering', 'wpmovielibrary' ),
								'description' => esc_html__( 'Allow visitors can change the grid ordering, ie. the sorting and ordering settings. Default is enabled.', 'wpmovielibrary' ),
								'attr'     => array( 'class' => 'half-col', 'data-parent' => 'movie-grid-show-menu' ),
								'sanitize' => '_is_bool',
								'default'  => 1
							),
							'movie-grid-show-pagination' => array(
								'type'     => 'checkbox',
								'section'  => 'movie-grid-controls',
								'label'    => esc_html__( 'Show Pagination', 'wpmovielibrary' ),
								'description' => esc_html__( 'Enable the pagination menu for visitors. Default is enabled.', 'wpmovielibrary' ),
								'attr'     => array( 'class' => 'half-col' ),
								'sanitize' => '_is_bool',
								'default'  => 1
							)
						)
					)
				)
			),
			'actor-grid-settings' => array(
				'label'     => esc_html__( 'Réglages', 'wpmovielibrary' ),
				'post_type' => 'grid',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					'actor-grid-presets' => array(
						'label'    => esc_html__( 'Presets', 'wpmovielibrary' ),
						'icon'     => 'wpmolicon icon-cogs',
						'settings' => array(
							'actor-grid-preset' => array(
								'type'    => 'radio-image',
								'section' => 'actor-grid-presets',
								'label'   => esc_html__( 'Grid preset', 'wpmovielibrary' ),
								'description' => esc_html__( 'Select a preset to apply to the grid. Presets override any filters and ordering settings you might define, be sure to select "Custom" for those settings to be used.', 'wpmovielibrary' ),
								'attr'    => array( 'class' => 'visible-labels half-col' ),
								'choices' => array(
									'alphabetical-actors' => array(
										'label' => esc_html__( 'Alpabetical Actors', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/alphabetical-movies.png'
									),
									'unalphabetical-movies' => array(
										'label' => esc_html__( 'Alpabetical Actors', 'wpmovielibrary' ),
										'url'   => WPMOLY_URL . 'admin/img/unalphabetical-movies.png'
									)
								),
								'sanitize' => 'esc_attr'
							)
						)
					)
				)
			)
		);

		/**
		 * Filter grid settings for the grid builder.
		 * 
		 * @since    3.0
		 * 
		 * @param    array     $settings Default settings.
		 * @param    object    GridBuilder instance.
		 */
		$this->settings = apply_filters( 'wpmoly/filter/grid/settings', $settings, $this );
	}

	/**
	 * Register metaboxes.
	 * 
	 * @since    3.0
	 * 
	 * @return   void
	 */
	public function add_metaboxes() {

		/**
		 * Fires before starting to register metaboxes.
		 * 
		 * @since    3.0
		 * 
		 * @param    object    GridBuilder instance.
		 */
		do_action( 'wpmoly/action/grid/before/add_metaboxes', $this );

		foreach ( $this->metaboxes as $metabox ) {
			$metabox = (object) $metabox;
			foreach ( (array) $metabox->screen as $screen ) {
				add_action( "add_meta_boxes_{$screen}", function() use ( $metabox ) {
					add_meta_box( $metabox->id . '-metabox', $metabox->title, $metabox->callback, $metabox->screen, $metabox->context, $metabox->priority, $metabox->callback_args );
				} );
			}
		}

		/**
		 * Fires when all metaboxes have been registered.
		 * 
		 * @since    3.0
		 * 
		 * @param    object    GridBuilder instance.
		 */
		do_action( 'wpmoly/action/grid/after/add_metaboxes', $this );
	}

	/**
	 * Grid Type Metabox callback.
	 * 
	 * @since    3.0
	 * 
	 * @param    object    $post Current Post instance.
	 * 
	 * @return   void
	 */
	public function type_metabox( $post ) {

		if ( 'grid' !== $post->post_type ) {
			return false;
		}

		$types = $this->grid->get_supported_types();
		$modes = $this->grid->get_supported_modes();
		$themes = $this->grid->get_supported_themes();
		var_dump( $this->grid->type, $this->grid->mode, $this->grid->theme );
?>
		<div class="grid-builder-separator">
			<div class="button separator-label"><?php _e( 'Type' ); ?></div>
		</div>

		<div id="grid-types" class="supported-grid-types">
<?php
		foreach ( $types as $type_id => $type ) :
?>
			<button type="button" data-action="grid-type" data-value="<?php echo $type_id; ?>" title="<?php echo $type['label']; ?>" class="<?php echo $type_id == $this->grid->type ? 'active' : ''; ?>"><span class="<?php echo $type['icon']; ?>"></span></button>
<?php
		endforeach;
?>
			<div class="clear"></div>
		</div>

		<div class="grid-builder-separator">
			<div class="button separator-label"><?php _e( 'Mode' ); ?></div>
		</div>

<?php
		foreach ( $types as $type_id => $type ) :
?>
		<div id="<?php echo $type_id; ?>-grid-modes" class="supported-grid-modes<?php echo $type_id == $this->grid->type ? ' active' : ''; ?>">
<?php
			foreach ( $modes[ $type_id ] as $mode_id => $mode ) :
?>
			<button type="button" data-action="grid-mode" data-value="<?php echo $mode_id; ?>" title="<?php echo $mode['label']; ?>" class="<?php echo $type_id == $this->grid->type && $mode_id == $this->grid->mode ? ' active' : ''; ?>"><span class="<?php echo $mode['icon']; ?>"></span></button>
<?php
			endforeach;
?>
			<div class="clear"></div>
		</div>
<?php
		endforeach;
?>

		<div class="grid-builder-separator">
			<div class="button separator-label"><?php _e( 'Theme' ); ?></div>
		</div>
<?php
		foreach ( $types as $type_id => $type ) :
			foreach ( $modes[ $type_id ] as $mode_id => $mode ) :
?>
		<div id="<?php echo $type_id; ?>-grid-<?php echo $mode_id; ?>-mode-themes" class="supported-grid-themes<?php echo $type_id == $this->grid->type && $mode_id == $this->grid->mode ? ' active' : ''; ?>">
<?php
				foreach ( $themes[ $type_id ][ $mode_id ] as $theme_id => $theme ) :
?>
			<button type="button" data-action="grid-theme" data-value="<?php echo $theme_id; ?>" title="<?php echo $theme['label']; ?>" class="<?php echo $type_id == $this->grid->type && $mode_id == $this->grid->mode && $theme_id == $this->grid->theme ? 'active' : ''; ?>"><span class="<?php echo $theme['icon']; ?>"></span></button>
<?php
				endforeach;
?>
			<div class="clear"></div>
		</div>
<?php
			endforeach;
		endforeach;
	}

	/**
	 * Grid Builder container opening.
	 * 
	 * Open the grid builder container and show a couple of useful snippets.
	 * 
	 * @since    3.0
	 * 
	 * @param    object    $post Current Post instance.
	 * 
	 * @return   void
	 */
	public function header( $post ) {

		if ( 'grid' !== $post->post_type ) {
			return false;
		}
?>
		<div id="grid-builder-container">

			<div id="wpmoly-grid-builder-shortcuts">
				<div id="wpmoly-grid-builder-id">Id: <code><?php the_ID(); ?></code></div>
				<div id="wpmoly-grid-builder-shortcode">ShortCode: <code>[movies id=<?php the_ID(); ?>]</code></div>
			</div>
<?php
	}

	/**
	 * Grid Preview editor toolbox.
	 * 
	 * @since    3.0
	 * 
	 * @param    object    $post Current Post instance.
	 * 
	 * @return   void
	 */
	public function preview( $post ) {

		if ( 'grid' !== $post->post_type ) {
			return false;
		}

		// Grid template setup
		$template = new PublicTemplate( 'shortcodes/movies-' . $this->grid->mode . '.php' );
		$template->set_data( array(
			'grid'   => $this->grid,
			'movies' => $this->grid->items
		) );

?>
		<div id="wpmoly-grid-builder" class="wpmoly">
			<div class="grid-builder-separator">
				<button type="button" data-action="toggle-preview" class="button separator-label"><?php _e( 'Preview' ); ?></button>
			</div>
			<div id="wpmoly-grid-builder-preview">
				<?php $template->render( 'always', $echo = true ); ?>
			</div>
			<div class="grid-builder-separator"><button type="button" class="button separator-label"><?php _e( 'Settings' ); ?></button></div>
		</div>
<?php
	}

	/**
	 * Load ButterBean if needed.
	 * 
	 * @since    3.0
	 * 
	 * @return   void
	 */
	public function load() {

		// Bail if not our post type.
		if ( 'grid' !== get_current_screen()->post_type ) {
			return;
		}

		require_once WPMOLY_PATH . 'vendor/butterbean/butterbean.php';
	}

	/**
	 * Register ButterBean's metabox settings.
	 * 
	 * @since    3.0
	 * 
	 * @param    object    $butterbean ButterBean instance.
	 * @param    string    $post_type Current Post Type.
	 * 
	 * @return   void
	 */
	public function register_butterbean( $butterbean, $post_type ) {

		foreach ( $this->settings as $id => $setting ) {

			$setting = (object) $setting;
			$butterbean->register_manager(
				$id,
				array(
					'label'     => $setting->label,
					'post_type' => $setting->post_type,
					'context'   => $setting->context,
					'priority'  => $setting->priority
				)
			);

			$manager = $butterbean->get_manager( $id );

			foreach ( $setting->sections as $section_id => $section ) {

				$section = (object) $section;
				$manager->register_section(
					$section_id,
					array(
						'label' => $section->label,
						'icon'  => $section->icon
					)
				);

				foreach ( $section->settings as $control_id => $control ) {

					$control_id = '_wpmoly_' . str_replace( '-', '_', $control_id );

					$control = (object) $control;
					$manager->register_control(
						$control_id,
						array(
							'section'     => $section_id,
							'type'        => isset( $control->type )        ? $control->type        : false,
							'label'       => isset( $control->label )       ? $control->label       : false,
							'attr'        => isset( $control->attr )        ? $control->attr        : false,
							'choices'     => isset( $control->choices )     ? $control->choices     : false,
							'description' => isset( $control->description ) ? $control->description : false
						)
					);

					$manager->register_setting(
						$control_id,
						array(
							'sanitize_callback' => isset( $control->sanitize ) ? $control->sanitize : false,
							'default'           => isset( $control->default )  ? $control->default  : false,
							'value'             => isset( $control->value )    ? $control->value    : '',
						)
					);
				}
			}
		}
	}

	/**
	 * Grid Builder container closing.
	 * 
	 * @since    3.0
	 * 
	 * @param    object    $post Current Post instance.
	 * 
	 * @return   void
	 */
	public function footer( $post ) {

		if ( 'grid' !== $post->post_type ) {
			return false;
		}
?>
		</div><!-- /#grid-builder-container -->
<?php
	}

}