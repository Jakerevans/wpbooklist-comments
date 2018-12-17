<?php
/**
 * Class Comments_General_Functions - class-comments-general-functions.php
 *
 * @author   Jake Evans
 * @category Admin
 * @package  Includes
 * @version  6.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Comments_General_Functions', false ) ) :
	/**
	 * Comments_General_Functions class. Here we'll do things like enqueue scripts/css, set up menus, etc.
	 */
	class Comments_General_Functions {

		/**
		 *  Class Constructor.
		 */
		public function __construct( ) {

			// Get Translations.
			require_once COMMENTS_CLASS_TRANSLATIONS_DIR . 'class-wpbooklist-comments-translations.php';
			$this->trans = new WPBookList_Comments_Translations();
			$this->trans->trans_strings();

		}

		/**
		 * Verifies that the core WPBookList plugin is installed and activated - otherwise, the Extension doesn't load and a message is displayed to the user.
		 */
		public function wpbooklist_comments_core_plugin_required() {

			// Require core WPBookList Plugin.
			if ( ! is_plugin_active( 'wpbooklist/wpbooklist.php' ) && current_user_can( 'activate_plugins' ) ) {

				// Stop activation redirect and show error.
				wp_die( 'Whoops! This WPBookList Extension requires the Core WPBookList Plugin to be installed and activated! <br><a target="_blank" href="https://wordpress.org/plugins/wpbooklist/">Download WPBookList Here!</a><br><br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
			}
		}


		/** Functions that loads up the menu page entry for this Extension.
		 *
		 *  @param array $submenu_array - The array that contains submenu entries to add to.
		 */
		public function wpbooklist_comments_submenu( $submenu_array ) {
			$extra_submenu = array(
				'Comments',
			);

			// Combine the two arrays.
			$submenu_array = array_merge( $submenu_array, $extra_submenu );
			return $submenu_array;
		}

		/**
		 *  Here we take the Constant defined in wpbooklist.php that holds the values that all our nonces will be created from, we create the actual nonces using wp_create_nonce, and the we define our new, final nonces Constant, called WPBOOKLIST_FINAL_NONCES_ARRAY.
		 */
		public function wpbooklist_comments_create_nonces() {

			$temp_array = array();
			foreach ( json_decode( COMMENTS_NONCES_ARRAY ) as $key => $noncetext ) {
				$nonce              = wp_create_nonce( $noncetext );
				$temp_array[ $key ] = $nonce;
			}

			// Defining our final nonce array.
			define( 'COMMENTS_FINAL_NONCES_ARRAY', wp_json_encode( $temp_array ) );

		}

		/**
		 *  Runs once upon extension activation and adds it's version number to the 'extensionversions' column in the 'wpbooklist_jre_user_options' table of the core plugin.
		 */
		public function wpbooklist_comments_record_extension_version() {
			global $wpdb;
			$existing_string = $wpdb->get_row( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_jre_user_options' );

			// Check to see if Extension is already registered.
			if ( false !== strpos( $existing_string->extensionversions, 'comments' ) ) {
				$split_string = explode( 'comments', $existing_string->extensionversions );
				$first_part   = $split_string[0];
				$last_part    = substr( $split_string[1], 5 );
				$new_string   = $first_part . 'comments' . WPBOOKLIST_COMMENTS_VERSION_NUM . $last_part;
			} else {
				$new_string = $existing_string->extensionversions . 'comments' . WPBOOKLIST_COMMENTS_VERSION_NUM;
			}

			$data         = array(
				'extensionversions' => $new_string,
			);
			$format       = array( '%s' );
			$where        = array( 'ID' => 1 );
			$where_format = array( '%d' );
			$wpdb->update( $wpdb->prefix . 'wpbooklist_jre_user_options', $data, $where, $format, $where_format );

		}

		/**
		 *  Function to run the compatability code in the Compat class for upgrades/updates, if stored version number doesn't match the defined global in wpbooklist-comments.php
		 */
		public function wpbooklist_comments_update_upgrade_function() {

			// Get current version #.
			global $wpdb;
			$existing_string = $wpdb->get_row( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_jre_user_options' );

			// Check to see if Extension is already registered and matches this version.
			if ( false !== strpos( $existing_string->extensionversions, 'comments' ) ) {
				$split_string = explode( 'comments', $existing_string->extensionversions );
				$version      = substr( $split_string[1], 0, 5 );

				// If version number does not match the current version number found in wpbooklist.php, call the Compat class and run upgrade functions.
				if ( WPBOOKLIST_COMMENTS_VERSION_NUM !== $version ) {
					require_once COMMENTS_CLASS_COMPAT_DIR . 'class-comments-compat-functions.php';
					$compat_class = new Comments_Compat_Functions();
				}
			}
		}

		/**
		 * Adding the admin js file
		 */
		public function wpbooklist_comments_admin_js() {

			wp_register_script( 'wpbooklist_comments_adminjs', COMMENTS_JS_URL . 'wpbooklist_comments_admin.min.js', array( 'jquery' ), WPBOOKLIST_VERSION_NUM, true );

			// Next 4-5 lines are required to allow translations of strings that would otherwise live in the wpbooklist-admin-js.js JavaScript File.
			require_once COMMENTS_CLASS_TRANSLATIONS_DIR . 'class-wpbooklist-comments-translations.php';
			$trans = new WPBookList_Comments_Translations();

			// Localize the script with the appropriate translation array from the Translations class.
			$translation_array1 = $trans->trans_strings();

			// Now grab all of our Nonces to pass to the JavaScript for the Ajax functions and merge with the Translations array.
			$final_array_of_php_values = array_merge( $translation_array1, json_decode( COMMENTS_FINAL_NONCES_ARRAY, true ) );

			// Adding some other individual values we may need.
			$final_array_of_php_values['COMMENTS_ROOT_IMG_ICONS_URL'] = COMMENTS_ROOT_IMG_ICONS_URL;
			$final_array_of_php_values['COMMENTS_ROOT_IMG_URL']       = COMMENTS_ROOT_IMG_URL;
			$final_array_of_php_values['FOR_TAB_HIGHLIGHT']                         = admin_url() . 'admin.php';
			$final_array_of_php_values['SAVED_ATTACHEMENT_ID']                      = get_option( 'media_selector_attachment_id', 0 );

			// Now registering/localizing our JavaScript file, passing all the PHP variables we'll need in our $final_array_of_php_values array, to be accessed from 'wphealthtracker_php_variables' object (like wphealthtracker_php_variables.nameofkey, like any other JavaScript object).
			wp_localize_script( 'wpbooklist_comments_adminjs', 'wpbooklistCommentsPhpVariables', $final_array_of_php_values );

			wp_enqueue_script( 'wpbooklist_comments_adminjs' );

			return $final_array_of_php_values;

		}

		/**
		 * Adding the frontend js file
		 */
		public function wpbooklist_comments_frontend_js() {

			wp_register_script( 'wpbooklist_comments_frontendjs', COMMENTS_JS_URL . 'wpbooklist_comments_frontend.min.js', array( 'jquery' ), WPBOOKLIST_COMMENTS_VERSION_NUM, true );

			// Next 4-5 lines are required to allow translations of strings that would otherwise live in the wpbooklist-admin-js.js JavaScript File.
			require_once COMMENTS_CLASS_TRANSLATIONS_DIR . 'class-wpbooklist-comments-translations.php';
			$trans = new WPBookList_Comments_Translations();

			// Localize the script with the appropriate translation array from the Translations class.
			$translation_array1 = $trans->trans_strings();

			// Now grab all of our Nonces to pass to the JavaScript for the Ajax functions and merge with the Translations array.
			$final_array_of_php_values = array_merge( $translation_array1, json_decode( COMMENTS_FINAL_NONCES_ARRAY, true ) );

			// Adding some other individual values we may need.
			$final_array_of_php_values['COMMENTS_ROOT_IMG_ICONS_URL'] = COMMENTS_ROOT_IMG_ICONS_URL;
			$final_array_of_php_values['COMMENTS_ROOT_IMG_URL']       = COMMENTS_ROOT_IMG_URL;

			// Now registering/localizing our JavaScript file, passing all the PHP variables we'll need in our $final_array_of_php_values array, to be accessed from 'wphealthtracker_php_variables' object (like wphealthtracker_php_variables.nameofkey, like any other JavaScript object).
			wp_localize_script( 'wpbooklist_comments_frontendjs', 'wpbooklistCommentsPhpVariables', $final_array_of_php_values );

			wp_enqueue_script( 'wpbooklist_comments_frontendjs' );

			return $final_array_of_php_values;

		}

		/**
		 * Adding the admin css file
		 */
		public function wpbooklist_comments_admin_style() {

			wp_register_style( 'wpbooklist_comments_adminui', COMMENTS_CSS_URL . 'wpbooklist-comments-main-admin.css', null, WPBOOKLIST_COMMENTS_VERSION_NUM );
			wp_enqueue_style( 'wpbooklist_comments_adminui' );

		}

		/**
		 * Adding the frontend css file
		 */
		public function wpbooklist_comments_frontend_style() {

			wp_register_style( 'wpbooklist_comments_frontendui', COMMENTS_CSS_URL . 'wpbooklist-comments-main-frontend.css', null, WPBOOKLIST_COMMENTS_VERSION_NUM );
			wp_enqueue_style( 'wpbooklist_comments_frontendui' );

		}

		/**
		 *  Function to add table names to the global $wpdb.
		 */
		public function wpbooklist_comments_register_table_name() {
			global $wpdb;
			$wpdb->wpbooklist_comments = "{$wpdb->prefix}wpbooklist_comments";
			$wpdb->wpbooklist_comments_settings = "{$wpdb->prefix}wpbooklist_comments_settings";
		}

		/**
		 *  Function that calls the Style and Scripts needed for displaying of admin pointer messages.
		 */
		public function wpbooklist_comments_admin_pointers_javascript() {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_script( 'utils' );
		}

		/**
		 *  Runs once upon plugin activation and creates the table that holds info on WPBookList Pages & Posts.
		 */
		public function wpbooklist_comments_create_tables() {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			global $wpdb;
			global $charset_collate;

			// Call this manually as we may have missed the init hook.
			$this->wpbooklist_comments_register_table_name();

			$sql_create_table1 = "CREATE TABLE {$wpdb->wpbooklist_comments}
			(
				ID bigint(190) auto_increment,
				bookuid varchar(255),
				bookid bigint(255),
				likes bigint(255),
				booktitle varchar(255),
				library varchar(255),
				rating FLOAT,
				datesubmitted varchar(255),
				dateapproved varchar(255),
				submitter bigint(255),
				status varchar(255),
				comment MEDIUMTEXT,
				PRIMARY KEY  (ID),
				KEY bookuid (bookuid)
			) $charset_collate; ";
			dbDelta( $sql_create_table1 );

			$sql_create_table2 = "CREATE TABLE {$wpdb->wpbooklist_comments_settings}
			(
				ID bigint(190) auto_increment,
				autoapprove varchar(255) NOT NULL DEFAULT 'false',
				commentorder varchar(255),
				archiveafter varchar(255),
				deleteafter varchar(255),
				restrictto varchar(255),
				allowregistration varchar(255) NOT NULL DEFAULT 'yes',
				usermessage MEDIUMTEXT,
				registerurl varchar(255),
				registerurltext varchar(255),
				PRIMARY KEY  (ID),
				KEY autoapprove (autoapprove)
			) $charset_collate; ";

			// If table doesn't exist, create table and add initial data to it.
			$test_name = $wpdb->prefix . 'wpbooklist_comments_settings';
			if ( $test_name !== $wpdb->get_var( "SHOW TABLES LIKE '$test_name'" ) ) {
				dbDelta( $sql_create_table2 );
				$table_name = $wpdb->prefix . 'wpbooklist_comments_settings';
				$wpdb->insert( $table_name, array( 'ID' => 1 ) );
			}

			
		}

		/**  The function that outputs the actual comment and rating HTML.
		 *
		 *  @param array $comments_array - The array that contains the book info.
		 */
		public function wpbooklist_append_to_colorbox_comments_func( $comments_array ) {

			global $wpdb;
			$comments_table = $wpdb->prefix . 'wpbooklist_comments';

			// Require the Transients file.
			require_once ROOT_WPBL_TRANSIENTS_DIR . 'class-wpbooklist-transients.php';
			$this->transients = new WPBookList_Transients();

			$transient_name   = 'wpbl_' . md5( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_comments' . ' WHERE bookuid = ' . $comments_array[2] );
			$transient_exists = $this->transients->existing_transient_check( $transient_name );
			if ( $transient_exists ) {
				$all_comments = $transient_exists;
			} else {
				$query = $wpdb->prepare( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_comments WHERE bookuid = %s', $comments_array[2] );
				$all_comments = $this->transients->create_transient( $transient_name, 'wpdb->get_results', $query, MONTH_IN_SECONDS );
			}

			// Grab all the settings.
			$comments_settings = $wpdb->get_row( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_comments_settings' );

			// Now loop through all returned comments and build final HTML.
			$final_html     = '';
			$opening_html   = '';
			$comments_html  = '';
			$ratings_total  = 0;
			$ratings_count  = 0;
			$average_rating = null;
			if ( 0 !== count( $all_comments ) ) {

				// The opening HTML.
				$opening_html = '
					<div id="wpbooklist_desc_id">
						<p class="wpbooklist_description_p" id="wpbooklist-desc-title-id">' . $this->trans->trans_30 . '</p>
					</div>
					<div class="wpbooklist_desc_p_class wpbooklist-comments-actual-wrapper">
						<div class="wpbooklist-comments-actual-inner-scroll-wrapper">';

				// The loop that will build some final values and the HTML of each individual comment itself.
				foreach ( $all_comments as $key => $comment ) {

					if ( 'pending' !== $comment->status ) {

						// Builds totals from all comments & ratings.
						if ( null !== $comment->rating ) {
							$ratings_total = $ratings_total + $comment->rating;
							$ratings_count++;
						}

						// Get the User's name that left this comment, if they are a registered WordPress/WPBookList User by their WP User ID.
						$submitter = '';
						if ( null !== $comment->submitter ) {

							// Set the current WordPress user.
							$user      = get_user_by( 'ID', $comment->submitter );
							if ( is_object( $user ) ) {
								$submitter = $this->trans->trans_35 . ' ' . $user->first_name . ' ' . $user->last_name . ' - ';

								// If user didn't have a first or last name specified...
								if ( $this->trans->trans_35 . '   - ' === $submitter ) {
									$submitter = $this->trans->trans_35 . ' ' . $user->display_name . ' - ';
								}
							}
						}

						// Build this user's rating image.
						$users_rating_img = '';
						switch ( $comment->rating ) {
							case 5:
								$users_rating_img = '5star.jpg';
								break;
							case 4.5:
								$users_rating_img = '4halfstar.jpg';
								break;
							case 4:
								$users_rating_img = '4star.jpg';
								break;
							case 3.5:
								$users_rating_img = '3halfstar.jpg';
								break;
							case 3:
								$users_rating_img = '3star.jpg';
								break;
							case 2.5:
								$users_rating_img = '2halfstar.jpg';
								break;
							case 2:
								$users_rating_img = '2star.jpg';
								break;
							case 1.5:
								$users_rating_img = '1halfstar.jpg';
								break;
							case 1:
								$users_rating_img = '1star.jpg';
								break;
							case 0.5:
								$users_rating_img = 'halfstar.jpg';
								break;
							default:
								break;
						}

						// Builds the actual individual comments HTML.
						$comments_html = $comments_html .
							'<div class="wpbooklist-comments-indiv-comment-wrapper">
								<p class="wpbooklist-comments-username-p">' . $submitter . '
									<img class="wpbooklist-comments-users-rating-img" src="' . ROOT_IMG_URL . $users_rating_img . '"/>
								</p>
								<p class="wpbooklist-comments-comment-actual-p">' . $comment->comment . '</p>
								<div class="wpbooklist-comments-likes-wrapper">
									<div class="wpbooklist-comments-likes-thumb-img-wrapper" data-likes="' . $comment->likes . '" data-commentid="' . $comment->ID . '" data-bookuid="' . $comments_array[2] . '">
										<img class="wpbooklist-comments-likes-thumb-img" src="' . COMMENTS_ROOT_IMG_ICONS_URL . 'like.svg" />
									</div>
									<p class="wpbooklist-comments-total-likes-p">' . $comment->likes . ' ' . $this->trans->trans_36 . '</p>
								</div>
							</div>';
					}
				}

				// Now finish up some of the final calculations needed.
				if ( 0 !== $ratings_total && 0 !== $ratings_count ) {
					$average_rating = $ratings_total / $ratings_count;
				}

				// Adjust average rating amount if there was no ratings for this title.
				if ( null === $average_rating ) {
					$average_rating = $this->trans->trans_31;
				}

				// If there is just one comment, adjust text accordingly.
				$plural_text = '';
				if ( 1 === $ratings_count ) {
					$plural_text = $this->trans->trans_4;
				} else {
					$plural_text = $this->trans->trans_32;
				}

				// Round the Avergae Rating up to the next 0.5.
				$whole_number = (int) $average_rating;
				$decimal      = $average_rating - $whole_number;
				if ( 0 === $decimal ) {
					$average_rating = $whole_number;
				} elseif ( .5 >= $decimal ) {
					$average_rating = $whole_number + .5;
				} elseif ( .5 < $decimal ) {
					$average_rating = $whole_number + 1;
				}

				// Now configure which star rating image to use.
				$rating_img = '';
				switch ( $average_rating ) {
					case 5:
						$rating_img = '5star.jpg';
						break;
					case 4.5:
						$rating_img = '4halfstar.jpg';
						break;
					case 4:
						$rating_img = '4star.jpg';
						break;
					case 3.5:
						$rating_img = '3halfstar.jpg';
						break;
					case 3:
						$rating_img = '3star.jpg';
						break;
					case 2.5:
						$rating_img = '2halfstar.jpg';
						break;
					case 2:
						$rating_img = '2star.jpg';
						break;
					case 1.5:
						$rating_img = '1halfstar.jpg';
						break;
					case 1:
						$rating_img = '1star.jpg';
						break;
					case 0.5:
						$rating_img = 'halfstar.jpg';
						break;
					default:
						break;
				}

				// Put together the HTML for displaying total number of comments and the average rating and it's associated star image.
				$totals_html = '
					<div class="wpbooklist-comments-totals-wrapper">
						<div class="wpbooklist-comments-total"><span>' . $ratings_count . '</span> ' . $plural_text . '</div>
						<div class="wpbooklist-comments-average-rating">
							<div class="wpbooklist-comments-average-rating-text">' . $this->trans->trans_33 . ': ' . $average_rating . ' ' . $this->trans->trans_34 . '</div>';

				// If there are no ratings, then don't display any stars.
				if ( '' === $rating_img ) {
					$totals_html = $totals_html . '
						</div>
					</div>';
				} else {
					$totals_html = $totals_html . '<img class="wpbooklist-comments-average-rating-img" src="' . ROOT_IMG_URL . $rating_img . '" />
						</div>
					</div>';
				}

				// A conditional that will test for whether of not the user must be authenticated before displaying the 'Submit New Comment' HTML.
				$comment_addition = '';
				if ( 'everyone' === $comments_settings->restrictto ) {

					$comment_addition = '
					</div>
					<div class="wpbooklist-comments-add-comment-wrapper">
						<p class="wpbooklist-comments-add-comment-title">' . $this->trans->trans_37 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
						<div class="wpbooklist-comments-add-comment-actual-wrapper">
							<textarea id="wpbooklist-comments-add-comment-actual" placeholder="' . $this->trans->trans_38 . '"></textarea>
							<p class="wpbooklist-comments-add-comment-rating-title">' . $this->trans->trans_40 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
							<select id="wpbooklist-comments-add-comment-rating-actual">
								<option value="5">' . $this->trans->trans_10 . '</option>
								<option value="4.5">' . $this->trans->trans_11 . '</option>
								<option value="4">' . $this->trans->trans_12 . '</option>
								<option value="3.5">' . $this->trans->trans_13 . '</option>
								<option value="3">' . $this->trans->trans_14 . '</option>
								<option value="2.5">' . $this->trans->trans_15 . '</option>
								<option value="2">' . $this->trans->trans_16 . '</option>
								<option value="1.5">' . $this->trans->trans_17 . '</option>
								<option value="1">' . $this->trans->trans_18 . '</option>
								<option value="0.5">' . $this->trans->trans_19 . '</option>
							</select>
							<img class="wpbooklist-comments-add-comment-rating-img" src="' . ROOT_IMG_URL . '4halfstar.jpg" />
						</div>
						<div class="wpbooklist-comments-add-comment-submit-wrapper">
							<button class="wpbooklist-comments-add-comment-submit-button" data-title="' . $comments_array[3] . '" data-bookid="' . $comments_array[0] . '" data-library="' . $comments_array[1] . '" data-bookuid="' . $comments_array[2] . '">' . $this->trans->trans_39 . '</button>
						</div>
						<div class="wpbooklist-spinner" id="wpbooklist-spinner-comments"></div>';

					$usermessage = '';
					if ( null !== $comments_settings->usermessage && '' !== $comments_settings->usermessage ) {
						$usermessage = '<p class="wpbooklist-comments-add-comment-title">' . $comments_settings->usermessage . '</p>';
					}

					$registerurltext = '';
					if ( null !== $comments_settings->registerurltext && '' !== $comments_settings->registerurltext ) {
						$registerurltext = '<a class="wpbooklist-comments-add-registerurl" href="' . $comments_settings->registerurl . '">' . $comments_settings->registerurltext . '</a>';
					}

					$comment_addition = $comment_addition . $usermessage . $registerurltext . '</div><div id="wpbooklist-colorbox-comments-response-div"></div>';

				} else {

					// Determine if user is already logged in...
					$current_user = wp_get_current_user();
					if ( 0 !== $current_user->ID ) {

						$comment_addition = '
							</div>
							<div class="wpbooklist-comments-add-comment-wrapper">
								<p class="wpbooklist-comments-add-comment-title">' . $this->trans->trans_37 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
								<div class="wpbooklist-comments-add-comment-actual-wrapper">
									<textarea id="wpbooklist-comments-add-comment-actual" placeholder="' . $this->trans->trans_38 . '"></textarea>
									<p class="wpbooklist-comments-add-comment-rating-title">' . $this->trans->trans_40 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
									<select id="wpbooklist-comments-add-comment-rating-actual">
										<option value="5">' . $this->trans->trans_10 . '</option>
										<option value="4.5">' . $this->trans->trans_11 . '</option>
										<option value="4">' . $this->trans->trans_12 . '</option>
										<option value="3.5">' . $this->trans->trans_13 . '</option>
										<option value="3">' . $this->trans->trans_14 . '</option>
										<option value="2.5">' . $this->trans->trans_15 . '</option>
										<option value="2">' . $this->trans->trans_16 . '</option>
										<option value="1.5">' . $this->trans->trans_17 . '</option>
										<option value="1">' . $this->trans->trans_18 . '</option>
										<option value="0.5">' . $this->trans->trans_19 . '</option>
									</select>
									<img class="wpbooklist-comments-add-comment-rating-img" src="' . ROOT_IMG_URL . '4halfstar.jpg" />
								</div>
								<div class="wpbooklist-comments-add-comment-submit-wrapper">
									<button class="wpbooklist-comments-add-comment-submit-button" data-title="' . $comments_array[3] . '" data-bookid="' . $comments_array[0] . '" data-library="' . $comments_array[1] . '" data-bookuid="' . $comments_array[2] . '">' . $this->trans->trans_39 . '</button>
								</div>
								<div class="wpbooklist-spinner" id="wpbooklist-spinner-comments"></div>';

							$usermessage = '';
							if ( null !== $comments_settings->usermessage && '' !== $comments_settings->usermessage ) {
								$usermessage = '<p class="wpbooklist-comments-add-comment-title">' . $comments_settings->usermessage . '</p>';
							}

							$registerurltext = '';
							if ( null !== $comments_settings->registerurltext && '' !== $comments_settings->registerurltext ) {
								$registerurltext = '<a class="wpbooklist-comments-add-registerurl" href="' . $comments_settings->registerurl . '">' . $comments_settings->registerurltext . '</a>';
							}

							$comment_addition = $comment_addition . $usermessage . $registerurltext . '<div id="wpbooklist-colorbox-comments-response-div"></div></div>';

					} else {

						$registration_string = '';
						if ( 'yes' === $comments_settings->allowregistration ) {
							$registration_string = '
								<div class="wpbooklist-comments-register-user-wrapper">
									<p class="wpbooklist-comments-add-comment-title">' . $this->trans->trans_80 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
									<div class="wpbooklist-comments-add-comment-actual-wrapper">
										<label>' . $this->trans->trans_81 . '</label>
										<input type="text" id="wpbooklist-comments-register-username-actual" placeholder="' . $this->trans->trans_81 . '"></textarea>
										<label>' . $this->trans->trans_82 . '</label>
										<input type="text" id="wpbooklist-comments-register-usernameverify-actual" placeholder="' . $this->trans->trans_82 . '"></textarea>
									</div>
									<div class="wpbooklist-comments-add-comment-actual-wrapper">
										<label>' . $this->trans->trans_83 . '</label>
										<input type="password" id="wpbooklist-comments-register-password-actual" placeholder="' . $this->trans->trans_83 . '"></textarea>
										<label>' . $this->trans->trans_84 . '</label>
										<input type="password" id="wpbooklist-comments-register-passwordverify-actual" placeholder="' . $this->trans->trans_84 . '"></textarea>
									</div>
									<div class="wpbooklist-comments-add-comment-submit-wrapper">
										<button class="wpbooklist-comments-register-submit-button" data-title="' . $comments_array[3] . '" data-bookid="' . $comments_array[0] . '" data-library="' . $comments_array[1] . '" data-bookuid="' . $comments_array[2] . '">' . $this->trans->trans_85 . '</button>
									</div>
									<div class="wpbooklist-spinner" id="wpbooklist-spinner-comments-register"></div>
									<div id="wpbooklist-colorbox-comments-register-response-div"></div>
								</div>';
						}

						$comment_addition = '
							</div>
							<div class="wpbooklist-comments-add-comment-wrapper">
								<p class="wpbooklist-comments-add-comment-title">' . $this->trans->trans_71 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
								<div class="wpbooklist-comments-add-comment-actual-wrapper">
									<label>' . $this->trans->trans_81 . '</label>
									<input type="text" id="wpbooklist-comments-login-username-actual" placeholder="' . $this->trans->trans_72 . '"></textarea>
									<label>' . $this->trans->trans_83 . '</label>
									<input type="password" id="wpbooklist-comments-login-password-actual" placeholder="' . $this->trans->trans_73 . '"></textarea>
								</div>
								<div class="wpbooklist-comments-add-comment-submit-wrapper">
									<button class="wpbooklist-comments-login-submit-button" data-title="' . $comments_array[3] . '" data-bookid="' . $comments_array[0] . '" data-library="' . $comments_array[1] . '" data-bookuid="' . $comments_array[2] . '">' . $this->trans->trans_74 . '</button>
								</div>
								<div class="wpbooklist-spinner" id="wpbooklist-spinner-comments"></div>
								' . $registration_string;

						$usermessage = '';
						if ( null !== $comments_settings->usermessage && '' !== $comments_settings->usermessage ) {
							$usermessage = '<p class="wpbooklist-comments-add-comment-title">' . $comments_settings->usermessage . '</p>';
						}

						$registerurltext = '';
						if ( null !== $comments_settings->registerurltext && '' !== $comments_settings->registerurltext ) {
							$registerurltext = '<a class="wpbooklist-comments-add-registerurl" href="' . $comments_settings->registerurl . '">' . $comments_settings->registerurltext . '</a>';
						}

						$comment_addition = $comment_addition . $usermessage . $registerurltext . '<div id="wpbooklist-colorbox-comments-response-div"></div></div>';
					}
				}

				$closing_html = '
					</div>';

				$final_html = $opening_html . $totals_html . $comments_html . $comment_addition . $closing_html;
			} else {

				// If there are no Comments, just output the 'Add a Comment' Section.
				$opening_html = '
					<div id="wpbooklist_desc_id">
						<p class="wpbooklist_description_p" id="wpbooklist-desc-title-id">' . $this->trans->trans_30 . '</p>
					</div>
					<div class="wpbooklist_desc_p_class wpbooklist-comments-actual-wrapper">
						<div class="wpbooklist-comments-actual-inner-scroll-nocomments-wrapper">
							<p class="wpbooklist-comments-no-comments-yet">' . $this->trans->trans_41 . '</p>';

				// A conditional that will test for whether of not the user must be authenticated before displaying the 'Submit New Comment' HTML.
				$comment_addition = '';
				if ( 'everyone' === $comments_settings->restrictto ) {

					$registration_string = '';
					if ( 'yes' === $comments_settings->allowregistration ) {
						$registration_string = '
								<div class="wpbooklist-comments-register-user-wrapper">
									<p class="wpbooklist-comments-add-comment-title">' . $this->trans->trans_80 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
									<div class="wpbooklist-comments-add-comment-actual-wrapper">
										<label>' . $this->trans->trans_81 . '</label>
										<input type="text" id="wpbooklist-comments-register-username-actual" placeholder="' . $this->trans->trans_81 . '"></textarea>
										<label>' . $this->trans->trans_82 . '</label>
										<input type="text" id="wpbooklist-comments-register-usernameverify-actual" placeholder="' . $this->trans->trans_82 . '"></textarea>
									</div>
									<div class="wpbooklist-comments-add-comment-actual-wrapper">
										<label>' . $this->trans->trans_83 . '</label>
										<input type="password" id="wpbooklist-comments-register-password-actual" placeholder="' . $this->trans->trans_83 . '"></textarea>
										<label>' . $this->trans->trans_84 . '</label>
										<input type="password" id="wpbooklist-comments-register-passwordverify-actual" placeholder="' . $this->trans->trans_84 . '"></textarea>
									</div>
									<div class="wpbooklist-comments-add-comment-submit-wrapper">
										<button class="wpbooklist-comments-register-submit-button" data-title="' . $comments_array[3] . '" data-bookid="' . $comments_array[0] . '" data-library="' . $comments_array[1] . '" data-bookuid="' . $comments_array[2] . '">' . $this->trans->trans_85 . '</button>
									</div>
									<div class="wpbooklist-spinner" id="wpbooklist-spinner-comments-register"></div>
									<div id="wpbooklist-colorbox-comments-register-response-div"></div>
								</div>';
					}

					$comment_addition = '
					</div>
					<div class="wpbooklist-comments-add-comment-wrapper">
						<p class="wpbooklist-comments-add-comment-title">' . $this->trans->trans_37 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
						<div class="wpbooklist-comments-add-comment-actual-wrapper">
							<textarea id="wpbooklist-comments-add-comment-actual" placeholder="' . $this->trans->trans_38 . '"></textarea>
							<p class="wpbooklist-comments-add-comment-rating-title">' . $this->trans->trans_40 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
							<select id="wpbooklist-comments-add-comment-rating-actual">
								<option value="5">' . $this->trans->trans_10 . '</option>
								<option value="4.5">' . $this->trans->trans_11 . '</option>
								<option value="4">' . $this->trans->trans_12 . '</option>
								<option value="3.5">' . $this->trans->trans_13 . '</option>
								<option value="3">' . $this->trans->trans_14 . '</option>
								<option value="2.5">' . $this->trans->trans_15 . '</option>
								<option value="2">' . $this->trans->trans_16 . '</option>
								<option value="1.5">' . $this->trans->trans_17 . '</option>
								<option value="1">' . $this->trans->trans_18 . '</option>
								<option value="0.5">' . $this->trans->trans_19 . '</option>
							</select>
							<img class="wpbooklist-comments-add-comment-rating-img" src="' . ROOT_IMG_URL . '4halfstar.jpg" />
						</div>
						<div class="wpbooklist-comments-add-comment-submit-wrapper">
							<button class="wpbooklist-comments-add-comment-submit-button" data-title="' . $comments_array[3] . '" data-bookid="' . $comments_array[0] . '" data-library="' . $comments_array[1] . '" data-bookuid="' . $comments_array[2] . '">' . $this->trans->trans_39 . '</button>
						</div>
						<div class="wpbooklist-spinner" id="wpbooklist-spinner-comments"></div>
						' . $registration_string;

					$usermessage = '';
					if ( null !== $comments_settings->usermessage && '' !== $comments_settings->usermessage ) {
						$usermessage = '<p class="wpbooklist-comments-add-comment-title">' . $comments_settings->usermessage . '</p>';
					}

					$registerurltext = '';
					if ( null !== $comments_settings->registerurltext && '' !== $comments_settings->registerurltext ) {
						$registerurltext = '<a class="wpbooklist-comments-add-registerurl" href="' . $comments_settings->registerurl . '">' . $comments_settings->registerurltext . '</a>';
					}

					$comment_addition = $comment_addition . $usermessage . $registerurltext . '<div id="wpbooklist-colorbox-comments-response-div"></div></div>';

				} else {

					// Determine if user is already logged in...
					$current_user = wp_get_current_user();
					if ( 0 !== $current_user->ID ) {

						$comment_addition = '
							</div>
							<div class="wpbooklist-comments-add-comment-wrapper">
								<p class="wpbooklist-comments-add-comment-title">' . $this->trans->trans_37 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
								<div class="wpbooklist-comments-add-comment-actual-wrapper">
									<textarea id="wpbooklist-comments-add-comment-actual" placeholder="' . $this->trans->trans_38 . '"></textarea>
									<p class="wpbooklist-comments-add-comment-rating-title">' . $this->trans->trans_40 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
									<select id="wpbooklist-comments-add-comment-rating-actual">
										<option value="5">' . $this->trans->trans_10 . '</option>
										<option value="4.5">' . $this->trans->trans_11 . '</option>
										<option value="4">' . $this->trans->trans_12 . '</option>
										<option value="3.5">' . $this->trans->trans_13 . '</option>
										<option value="3">' . $this->trans->trans_14 . '</option>
										<option value="2.5">' . $this->trans->trans_15 . '</option>
										<option value="2">' . $this->trans->trans_16 . '</option>
										<option value="1.5">' . $this->trans->trans_17 . '</option>
										<option value="1">' . $this->trans->trans_18 . '</option>
										<option value="0.5">' . $this->trans->trans_19 . '</option>
									</select>
									<img class="wpbooklist-comments-add-comment-rating-img" src="' . ROOT_IMG_URL . '4halfstar.jpg" />
								</div>
								<div class="wpbooklist-comments-add-comment-submit-wrapper">
									<button class="wpbooklist-comments-add-comment-submit-button" data-title="' . $comments_array[3] . '" data-bookid="' . $comments_array[0] . '" data-library="' . $comments_array[1] . '" data-bookuid="' . $comments_array[2] . '">' . $this->trans->trans_39 . '</button>
								</div>
								<div class="wpbooklist-spinner" id="wpbooklist-spinner-comments"></div>';

							$usermessage = '';
							if ( null !== $comments_settings->usermessage && '' !== $comments_settings->usermessage ) {
								$usermessage = '<p class="wpbooklist-comments-add-comment-title">' . $comments_settings->usermessage . '</p>';
							}

							$registerurltext = '';
							if ( null !== $comments_settings->registerurltext && '' !== $comments_settings->registerurltext ) {
								$registerurltext = '<a class="wpbooklist-comments-add-registerurl" href="' . $comments_settings->registerurl . '">' . $comments_settings->registerurltext . '</a>';
							}

							$comment_addition = $comment_addition . $usermessage . $registerurltext . '<div id="wpbooklist-colorbox-comments-response-div"></div></div>';

					} else {

						$registration_string = '';
						if ( 'yes' === $comments_settings->allowregistration ) {
							$registration_string = '
									<div class="wpbooklist-comments-register-user-wrapper">
										<p class="wpbooklist-comments-add-comment-title">' . $this->trans->trans_80 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
										<div class="wpbooklist-comments-add-comment-actual-wrapper">
											<label>' . $this->trans->trans_81 . '</label>
											<input type="text" id="wpbooklist-comments-register-username-actual" placeholder="' . $this->trans->trans_81 . '"></textarea>
											<label>' . $this->trans->trans_82 . '</label>
											<input type="text" id="wpbooklist-comments-register-usernameverify-actual" placeholder="' . $this->trans->trans_82 . '"></textarea>
										</div>
										<div class="wpbooklist-comments-add-comment-actual-wrapper">
											<label>' . $this->trans->trans_83 . '</label>
											<input type="password" id="wpbooklist-comments-register-password-actual" placeholder="' . $this->trans->trans_83 . '"></textarea>
											<label>' . $this->trans->trans_84 . '</label>
											<input type="password" id="wpbooklist-comments-register-passwordverify-actual" placeholder="' . $this->trans->trans_84 . '"></textarea>
										</div>
										<div class="wpbooklist-comments-add-comment-submit-wrapper">
											<button class="wpbooklist-comments-register-submit-button" data-title="' . $comments_array[3] . '" data-bookid="' . $comments_array[0] . '" data-library="' . $comments_array[1] . '" data-bookuid="' . $comments_array[2] . '">' . $this->trans->trans_85 . '</button>
										</div>
										<div class="wpbooklist-spinner" id="wpbooklist-spinner-comments-register"></div>
										<div id="wpbooklist-colorbox-comments-register-response-div"></div>
									</div>';
						}

						$comment_addition = '
							</div>
							<div class="wpbooklist-comments-add-comment-wrapper">
								<p class="wpbooklist-comments-add-comment-title">' . $this->trans->trans_71 . '</p><span class="wpbooklist-comments-for-php-string-mod" style="display:none"></span>
								<div class="wpbooklist-comments-add-comment-actual-wrapper">
									<label>' . $this->trans->trans_81 . '</label>
									<input type="text" id="wpbooklist-comments-login-username-actual" placeholder="' . $this->trans->trans_72 . '"></textarea>
									<label>' . $this->trans->trans_83 . '</label>
									<input type="password" id="wpbooklist-comments-login-password-actual" placeholder="' . $this->trans->trans_73 . '"></textarea>
								</div>
								<div class="wpbooklist-comments-add-comment-submit-wrapper">
									<button class="wpbooklist-comments-login-submit-button" data-title="' . $comments_array[3] . '" data-bookid="' . $comments_array[0] . '" data-library="' . $comments_array[1] . '" data-bookuid="' . $comments_array[2] . '">' . $this->trans->trans_74 . '</button>
								</div>
								<div class="wpbooklist-spinner" id="wpbooklist-spinner-comments"></div>
								' . $registration_string;

						$usermessage = '';
						if ( null !== $comments_settings->usermessage && '' !== $comments_settings->usermessage ) {
							$usermessage = '<p class="wpbooklist-comments-add-comment-title">' . $comments_settings->usermessage . '</p>';
						}

						$registerurltext = '';
						if ( null !== $comments_settings->registerurltext && '' !== $comments_settings->registerurltext ) {
							$registerurltext = '<a class="wpbooklist-comments-add-registerurl" href="' . $comments_settings->registerurl . '">' . $comments_settings->registerurltext . '</a>';
						}

						$comment_addition = $comment_addition . $usermessage . $registerurltext . '<div id="wpbooklist-colorbox-comments-response-div"></div></div>';
					}
				}

				$closing_html = '
					</div>';

				$final_html = $opening_html . $comment_addition . $closing_html;
			}

			return $final_html;
		}

	}
endif;
