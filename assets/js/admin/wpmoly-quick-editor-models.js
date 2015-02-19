
window.wpmoly = window.wpmoly || {};

(function( $, _, Backbone, wp ) {

	var editor = wpmoly.editor = function() {

		var movies = [];
		_.each( document.querySelectorAll( '#the-list tr' ), function( movie ) {
			var id = movie.id.replace( 'post-', '' ),
			 movie = new editor.Model.Movie;
			movies.push( _.extend( movie, { id: id } ) );
		} );

		editor.models.movies = new editor.Model.Movies();
		editor.models.movies.add( movies );

		editor.frame = new editor.View.EditMovies( {
			frame: 'select',
			library: editor.models.movies.models,
			model: editor.models.movies.get( 24 )
		} );
	};

	_.extend( editor, { controller: {}, models: {}, views: {}, Model: {}, View: {} } );

	_.extend( editor.Model, {

		/**
		 * WPMOLY Backbone Movie Model
		 * 
		 * Model for the metabox movie metadata fields. Holy Grail! That model
		 * is linked to a view containing all the inputs and handles the sync
		 * with the server to search for movies.
		 * 
		 * @since    2.2
		 */
		Movie: Backbone.Model.extend({

			id: '',

			defaults: {
				tmdb_id: '',
				title: '',
				original_title: '',
				tagline: '',
				overview: '',
				release_date: '',
				local_release_date: '',
				runtime: '',
				production_companies: '',
				production_countries: '',
				spoken_languages: '',
				genres: '',
				director: '',
				producer: '',
				cast: '',
				photography: '',
				composer: '',
				author: '',
				writer: '',
				certification: '',
				budget: '',
				revenue: '',
				imdb_id: '',
				adult: '',
				homepage: ''
			},
		})

	} );

	_.extend( editor.Model, {

		/**
		 * WPMOLY Backbone Movie Model
		 * 
		 * Model for movies list collection.
		 * 
		 * @since    2.2
		 */
		Movies: Backbone.Collection.extend({

			url: ajaxurl,

			model: editor.Model.Movie,

			/**
			 * Overload Backbone sync method to fetch our own data and save
			 * them to the server.
			 * 
			 * @since    2.2
			 * 
			 * @param    string    method Are we reading or is it a regular sync?
			 * @param    object    model Current model
			 * @param    object    options Query options
			 * 
			 * @return   mixed
			 */
			sync: function( method, model, options ) {

				if ( 'read' == method ) {

					_.extend( options, {
						context: this,
						data: {
							action: 'wpmoly_fetch_movies',
							//nonce: wpmoly.get_nonce( 'fetch-movies' ),
							data: _.map( this.models, function( model ) {
								//if ( _.isEmpty( _.filter( model.attributes, function( attr ) { return '' != attr; } ) ) )
									return model.id;
							}, this )
						},
						complete: function() {},
						success: function( response ) {
							_.each( response, function( data, id ) {
								var model = this.get( id );
								model.set( _.pick( data, _.keys( model.defaults ) ) );
							}, this );
						}
					});

					wp.ajax.send( options );

				} else if ( 'update' == method ) {

					//TODO: save data

				} else {
					return Backbone.sync.apply( this, arguments );
				}
			},

			/**
			 * Add Models to the Collection.
			 * 
			 * This simply overrides the Backbone Collection add()
			 * method to fetch complete meta when adding new movies
			 * to the collection.
			 * 
			 * @since    2.2
			 * 
			 * @param    array     Array of Attachment Models
			 * @param    object    Options
			 * 
			 * @return   this
			 */
			add: function( models, options ) {

				Backbone.Collection.prototype.add.apply( this, arguments );

				this.fetch();
			}

			
		} )
	} );

}(jQuery, _, Backbone, wp));