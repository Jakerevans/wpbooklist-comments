<?php
/**
 * Class Comments_Ajax_Functions - class-wpbooklist-ajax-functions.php
 *
 * @author   Jake Evans
 * @category Admin
 * @package  Includes
 * @version  6.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Comments_Ajax_Functions', false ) ) :
	/**
	 * Comments_Ajax_Functions class. Here we'll do things like enqueue scripts/css, set up menus, etc.
	 */
	class Comments_Ajax_Functions {

		/**
		 * Class Constructor - Simply calls the Translations
		 */
		public function __construct() {

			// Require the Transients file.
			require_once ROOT_WPBL_TRANSIENTS_DIR . 'class-wpbooklist-transients.php';
			$this->transients = new WPBookList_Transients();

			// Set the date.
			require_once ROOT_WPBL_UTILITIES_DIR . 'class-wpbooklist-utilities-date.php';
			$utilities_date = new WPBookList_Utilities_Date();
			$this->date     = $utilities_date->wpbooklist_get_date_via_current_time( 'mysql' );

			// Get Translations.
			require_once COMMENTS_CLASS_TRANSLATIONS_DIR . 'class-wpbooklist-comments-translations.php';
			$this->trans = new WPBookList_Comments_Translations();
			$this->trans->trans_strings();

		}

		/**
		 * Callback function for saving the Comment Settings.
		 */
		public function wpbooklist_comments_submit_settings_action_callback() {

			global $wpdb;

			check_ajax_referer( 'wpbooklist_comments_submit_settings_action_callback', 'security' );

			if ( isset( $_POST['commentsarrive'] ) ) {
				$commentsarrive = filter_var( wp_unslash( $_POST['commentsarrive'] ), FILTER_SANITIZE_STRING );

				if ( $this->trans->trans_44 === $commentsarrive ) {
					$commentsarrive = 'pending';
				} else {
					$commentsarrive = 'approve';
				}
			}

			if ( isset( $_POST['displayorder'] ) ) {
				$displayorder = filter_var( wp_unslash( $_POST['displayorder'] ), FILTER_SANITIZE_STRING );
				if ( $this->trans->trans_47 === $displayorder ) {
					$displayorder = 'newfirst';
				} elseif ( $this->trans->trans_48 === $displayorder ) {
					$displayorder = 'oldfirst';
				} elseif ( $this->trans->trans_49 === $displayorder ) {
					$displayorder = 'mostlikes';
				} else {
					$displayorder = 'leastlikes';
				}
			}

			if ( isset( $_POST['archiveafter'] ) ) {
				$archiveafter = filter_var( wp_unslash( $_POST['archiveafter'] ), FILTER_SANITIZE_STRING );
				if ( $this->trans->trans_52 === $archiveafter ) {
					$archiveafter = '30';
				} elseif ( $this->trans->trans_53 === $archiveafter ) {
					$archiveafter = '60';
				} elseif ( $this->trans->trans_54 === $archiveafter ) {
					$archiveafter = '90';
				} elseif ( $this->trans->trans_55 === $archiveafter ) {
					$archiveafter = '180';
				} elseif ( $this->trans->trans_56 === $archiveafter ) {
					$archiveafter = '364';
				} else {
					$archiveafter = '0';
				}
			}

			if ( isset( $_POST['deleteafter'] ) ) {
				$deleteafter = filter_var( wp_unslash( $_POST['deleteafter'] ), FILTER_SANITIZE_STRING );
				if ( $this->trans->trans_59 === $deleteafter ) {
					$deleteafter = '30';
				} elseif ( $this->trans->trans_60 === $deleteafter ) {
					$deleteafter = '60';
				} elseif ( $this->trans->trans_61 === $deleteafter ) {
					$deleteafter = '90';
				} elseif ( $this->trans->trans_62 === $deleteafter ) {
					$deleteafter = '180';
				} elseif ( $this->trans->trans_63 === $deleteafter ) {
					$deleteafter = '364';
				} else {
					$deleteafter = '0';
				}
			}

			if ( isset( $_POST['restrictto'] ) ) {
				$restrictto = filter_var( wp_unslash( $_POST['restrictto'] ), FILTER_SANITIZE_STRING );

				if ( $this->trans->trans_69 === $restrictto ) {
					$restrictto = 'everyone';
				} else {
					$restrictto = 'authenticated';
				}
			}

			// Now we'll save the comment.
			$settings_array = array(
				'autoapprove'  => $commentsarrive,
				'commentorder' => $displayorder,
				'archiveafter' => $archiveafter,
				'deleteafter'  => $deleteafter,
				'restrictto'   => $restrictto,
			);

			// Building mask array to add to DB.
			$db_mask_insert_array = array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			);

			$where        = array( 'ID' => 1 );
			$where_format = array( '%d' );
			$result = $wpdb->update( $wpdb->prefix . 'wpbooklist_comments_settings', $settings_array, $where, $db_mask_insert_array, $where_format );

			wp_die( $result );
		}

		/**
		 * Callback function for incrementing the Comment Likes.
		 */
		public function wpbooklist_comments_like_action_callback() {

			global $wpdb;

			check_ajax_referer( 'wpbooklist_comments_like_action_callback', 'security' );

			if ( isset( $_POST['commentid'] ) ) {
				$commentid = filter_var( wp_unslash( $_POST['commentid'] ), FILTER_SANITIZE_STRING );
			}

			if ( isset( $_POST['newlikes'] ) ) {
				$newlikes = filter_var( wp_unslash( $_POST['newlikes'] ), FILTER_SANITIZE_NUMBER_INT );
			}

			if ( isset( $_POST['bookuid'] ) ) {
				$bookuid = filter_var( wp_unslash( $_POST['bookuid'] ), FILTER_SANITIZE_STRING );
			}

			$data         = array(
				'likes' => $newlikes,
			);
			$format       = array( '%d' );
			$where        = array( 'ID' => $commentid );
			$where_format = array( '%s' );
			$result = $wpdb->update( $wpdb->prefix . 'wpbooklist_comments', $data, $where, $format, $where_format );

			// Now attempting to delete the existing Transient for this comment, as the data has changed.
			$transient_name   = 'wpbl_' . md5( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_comments' . ' WHERE bookuid = ' . $bookuid );
			$transient_delete_api_data_result = $this->transients->delete_transient( $transient_name );

			// End the function.
			wp_die( ' Like DB result is ' . $result . ' and Transient deletion is ' . $transient_delete_api_data_result );

		}

		/**
		 * Callback function for that allows the user to log in from the Comments section.
		 */
		public function wpbooklist_comments_login_action_callback() {

			global $wpdb;

			check_ajax_referer( 'wpbooklist_comments_login_action_callback', 'security' );

			if ( isset( $_POST['username'] ) ) {
				$username = filter_var( wp_unslash( $_POST['username'] ), FILTER_SANITIZE_STRING );
			}

			if ( isset( $_POST['password'] ) ) {
				$password = filter_var( wp_unslash( $_POST['password'] ), FILTER_SANITIZE_STRING );
			}

			$creds = array(
				'user_login'    => $username,
				'user_password' => $password,
				'remember'      => true,
			);

			$user = wp_signon( $creds, false );
			$response = '';
			if ( is_wp_error( $user ) ) {
				$response = $user->get_error_message();
			}

			// End the function.
			wp_die( $response );

		}

		/**
		 * Callback function for submitting a new comment.
		 */
		public function wpbooklist_comments_submit_action_callback() {

			global $wpdb;

			check_ajax_referer( 'wpbooklist_comments_submit_action_callback', 'security' );

			if ( isset( $_POST['bookid'] ) ) {
				$bookid = filter_var( wp_unslash( $_POST['bookid'] ), FILTER_SANITIZE_STRING );
			}

			if ( isset( $_POST['booktitle'] ) ) {
				$booktitle = filter_var( wp_unslash( $_POST['booktitle'] ), FILTER_SANITIZE_STRING );
			}

			if ( isset( $_POST['bookuid'] ) ) {
				$bookuid = filter_var( wp_unslash( $_POST['bookuid'] ), FILTER_SANITIZE_STRING );
			}

			if ( isset( $_POST['library'] ) ) {
				$library = filter_var( wp_unslash( $_POST['library'] ), FILTER_SANITIZE_STRING );
			}

			if ( isset( $_POST['rating'] ) ) {
				$rating = filter_var( wp_unslash( $_POST['rating'] ), FILTER_SANITIZE_STRING );
			}

			if ( isset( $_POST['commentactual'] ) ) {
				$commentactual = filter_var( wp_unslash( $_POST['commentactual'] ), FILTER_SANITIZE_STRING );
			}

			$status = 'pending';
			$dateapproved = null;

			$current_user = wp_get_current_user();
			$submitter = null;
			if ( ( $current_user instanceof WP_User ) ) {
				$submitter = $current_user->ID;
			}

			// Now get the Comments Settings to see if we'll set the status of this comment to the default of Pending, or if user want's to auto-approve, meaning the status will be set to Approved.
			$this->comment_settings = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'wpbooklist_comments_settings' );

			// Set the status of the comment.
			if ( 'approve' === $this->comment_settings->autoapprove ) {
				$status = 'approved';
				$dateapproved = $this->date;
			} else {
				$status = 'pending';
			}

			// Now we'll save the comment.
			$comment_array = array(
				'bookid'        => $bookid,
				'booktitle'     => $booktitle,
				'bookuid'       => $bookuid,
				'library'       => $library,
				'rating'        => $rating,
				'comment'       => $commentactual,
				'status'        => $status,
				'submitter'     => $submitter,
				'likes'         => 0,
				'datesubmitted' => $this->date,
				'dateapproved'  => $dateapproved,
			);

			// Building mask array to add to DB.
			$db_mask_insert_array = array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%f',
				'%s',
				'%s',
				'%d',
				'%d',
				'%s',
				'%s',
			);

			$result = $wpdb->insert( $wpdb->prefix . 'wpbooklist_comments', $comment_array, $db_mask_insert_array );

			// Now attempting to delete the existing Transient for this comment, as the data has changed.
			$transient_name   = 'wpbl_' . md5( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_comments' . ' WHERE bookuid = ' . $bookuid );
			$transient_delete_api_data_result = $this->transients->delete_transient( $transient_name );

			// End the function.
			wp_die( $result . '-' . $status );
		}

		/**
		 * Callback function for submitting a new comment.
		 */
		public function wpbooklist_comments_approve_action_callback() {

			global $wpdb;

			check_ajax_referer( 'wpbooklist_comments_approve_action_callback', 'security' );

			if ( isset( $_POST['commentid'] ) ) {
				$commentid = filter_var( wp_unslash( $_POST['commentid'] ), FILTER_SANITIZE_NUMBER_INT );
			}

			if ( isset( $_POST['rating'] ) ) {
				$rating = filter_var( wp_unslash( $_POST['rating'] ), FILTER_SANITIZE_STRING );
			}

			if ( isset( $_POST['commentactual'] ) ) {
				$commentactual = filter_var( wp_unslash( $_POST['commentactual'] ), FILTER_SANITIZE_STRING );
			}

			$data         = array(
				'comment' => $commentactual,
				'rating'  => $rating,
				'status'  => 'approved',
			);

			$format       = array( '%s', '%s', '%s' );
			$where        = array( 'ID' => $commentid );
			$where_format = array( '%d' );
			$result = $wpdb->update( $wpdb->prefix . 'wpbooklist_comments', $data, $where, $format, $where_format );

			// Now attempting to delete the existing Transient for this comment, as the data has changed.
			$transient_name = 'wpbl_' . md5( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_comments' . ' WHERE bookuid = ' . $bookuid );
			$transient_delete_api_data_result = $this->transients->delete_transient( $transient_name );

			// End the function.
			wp_die( $result );
		}

		/**
		 * Callback function for submitting a new comment.
		 */
		public function wpbooklist_comments_edit_action_callback() {

			global $wpdb;

			check_ajax_referer( 'wpbooklist_comments_edit_action_callback', 'security' );

			if ( isset( $_POST['commentid'] ) ) {
				$commentid = filter_var( wp_unslash( $_POST['commentid'] ), FILTER_SANITIZE_NUMBER_INT );
			}

			if ( isset( $_POST['commentactual'] ) ) {
				$commentactual = filter_var( wp_unslash( $_POST['commentactual'] ), FILTER_SANITIZE_STRING );
			}

			if ( isset( $_POST['rating'] ) ) {
				$rating = filter_var( wp_unslash( $_POST['rating'] ), FILTER_SANITIZE_STRING );
			}

			if ( isset( $_POST['bookuid'] ) ) {
				$bookuid = filter_var( wp_unslash( $_POST['bookuid'] ), FILTER_SANITIZE_STRING );
			}

			$data         = array(
				'comment' => $commentactual,
				'rating'  => $rating,
			);
			$format       = array( '%s' );
			$where        = array( 'ID' => $commentid );
			$where_format = array( '%d' );
			$result = $wpdb->update( $wpdb->prefix . 'wpbooklist_comments', $data, $where, $format, $where_format );

			// Now attempting to delete the existing Transient for this comment, as the data has changed.
			$transient_name = 'wpbl_' . md5( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_comments' . ' WHERE bookuid = ' . $bookuid );
			$transient_delete_api_data_result = $this->transients->delete_transient( $transient_name );

			// End the function.
			wp_die( $result );
		}


		/**
		 * Callback function for submitting a new comment.
		 */
		public function wpbooklist_comments_delete_action_callback() {

			global $wpdb;

			check_ajax_referer( 'wpbooklist_comments_delete_action_callback', 'security' );

			if ( isset( $_POST['commentid'] ) ) {
				$commentid = filter_var( wp_unslash( $_POST['commentid'] ), FILTER_SANITIZE_NUMBER_INT );
			}

			if ( isset( $_POST['bookuid'] ) ) {
				$bookuid = filter_var( wp_unslash( $_POST['bookuid'] ), FILTER_SANITIZE_STRING );
			}

			$result = $wpdb->delete( $wpdb->prefix . 'wpbooklist_comments', array( 'ID' => $commentid ), array( '%d' ) );

			// Now attempting to delete the existing Transient for this comment, as the data has changed.
			$transient_name = 'wpbl_' . md5( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_comments' . ' WHERE bookuid = ' . $bookuid );
			$transient_delete_api_data_result = $this->transients->delete_transient( $transient_name );

			// End the function.
			wp_die( $result );
		}

		/**
		 * Callback function for submitting a new comment.
		 */
		public function wpbooklist_comments_maniparchived_action_callback() {

			global $wpdb;

			check_ajax_referer( 'wpbooklist_comments_maniparchived_action_callback', 'security' );

			if ( isset( $_POST['viewdelete'] ) ) {
				$viewdelete = filter_var( wp_unslash( $_POST['viewdelete'] ), FILTER_SANITIZE_STRING );
			}

			if ( isset( $_POST['timeframe'] ) ) {
				$timeframe = filter_var( wp_unslash( $_POST['timeframe'] ), FILTER_SANITIZE_STRING );
			}

			// If the inputs aren't null...
			$final_html = '';
			if ( null !== $timeframe && null !== $viewdelete ) {

				// Handling the Viewing of archived comments.
				if ( $this->trans->trans_21 === $viewdelete ) {

					// Now determine the Timeframe.
					$days = '0';
					switch ( $timeframe ) {
						case $this->trans->trans_25:
							$days = '60';
							break;
						case $this->trans->trans_26:
							$days = '90';
							break;
						case $this->trans->trans_27:
							$days = '120';
							break;
						case $this->trans->trans_28:
							$days = '0';
							break;
						default:
							# code...
							break;
					}

					// Figuring out the date to compare to, based on the user's selection in the timeframe dropdown.
					$date = explode( '-', $this->date );
					$date = date_create( $date[2] . '-' . $date[0] . '-' . $date[1] );
					date_sub( $date, date_interval_create_from_date_string( $days . ' days' ) );
					$newdate = date_format( $date, 'Y-m-d' );

					// Grab all archived comment from DB.
					$this->commentsarchived = $wpdb->get_results( 'SELECT * from ' . $wpdb->prefix . "wpbooklist_comments WHERE status = 'archived'");

					foreach ( $this->commentsarchived as $key => $archivedcomment ) {

						// Converting date from DB to use in comparison with the strtotime() functions.
						$archivedcomment->datesubmitted = explode( '-', $archivedcomment->datesubmitted );
						$archivedcomment->datesubmitted =  $archivedcomment->datesubmitted[2] . '-' . $archivedcomment->datesubmitted[0] . '-' . $archivedcomment->datesubmitted[1];

						// If date is newer that what user specified, remove from array.
						if ( strtotime( $archivedcomment->datesubmitted ) >= strtotime( $newdate ) ) {
							unset($this->commentsarchived[$key]);
						}
					}

					// Build HTML to return to browser.
					foreach ( $this->commentsarchived as $key => $value ) {

						// Building the Star Rating drop-down.
						$rating_options = '';
						$rating_img = '';
						switch ( $value->rating ) {
							case 5:
								$rating_options = '<option value="5" selected default>' . $this->trans->trans_10 . '</option>
												   <option value="4.5">' . $this->trans->trans_11 . '</option>
												   <option value="4">' . $this->trans->trans_12 . '</option>
												   <option value="3.5">' . $this->trans->trans_13 . '</option>
												   <option value="3">' . $this->trans->trans_14 . '</option>
												   <option value="2.5">' . $this->trans->trans_15 . '</option>
												   <option value="2">' . $this->trans->trans_16 . '</option>
												   <option value="1.5">' . $this->trans->trans_17 . '</option>
												   <option value="1">' . $this->trans->trans_18 . '</option>
												   <option value="0.5">' . $this->trans->trans_19 . '</option>';
								$rating_img = '5star.jpg';
								break;
							case 4.5:
								$rating_options = '<option value="5">' . $this->trans->trans_10 . '</option>
												   <option value="4.5" selected default>' . $this->trans->trans_11 . '</option>
												   <option value="4">' . $this->trans->trans_12 . '</option>
												   <option value="3.5">' . $this->trans->trans_13 . '</option>
												   <option value="3">' . $this->trans->trans_14 . '</option>
												   <option value="2.5">' . $this->trans->trans_15 . '</option>
												   <option value="2">' . $this->trans->trans_16 . '</option>
												   <option value="1.5">' . $this->trans->trans_17 . '</option>
												   <option value="1">' . $this->trans->trans_18 . '</option>
												   <option value="0.5">' . $this->trans->trans_19 . '</option>';
								$rating_img = '4halfstar.jpg';
								break;
							case 4:
								$rating_options = '<option value="5">' . $this->trans->trans_10 . '</option>
												   <option value="4.5">' . $this->trans->trans_11 . '</option>
												   <option value="4" selected default>' . $this->trans->trans_12 . '</option>
												   <option value="3.5">' . $this->trans->trans_13 . '</option>
												   <option value="3">' . $this->trans->trans_14 . '</option>
												   <option value="2.5">' . $this->trans->trans_15 . '</option>
												   <option value="2">' . $this->trans->trans_16 . '</option>
												   <option value="1.5">' . $this->trans->trans_17 . '</option>
												   <option value="1">' . $this->trans->trans_18 . '</option>
												   <option value="0.5">' . $this->trans->trans_19 . '</option>';
								$rating_img = '4star.jpg';
								break;
							case 3.5:
								$rating_options = '<option value="5">' . $this->trans->trans_10 . '</option>
												   <option value="4.5">' . $this->trans->trans_11 . '</option>
												   <option value="4">' . $this->trans->trans_12 . '</option>
												   <option value="3.5" selected default>' . $this->trans->trans_13 . '</option>
												   <option value="3">' . $this->trans->trans_14 . '</option>
												   <option value="2.5">' . $this->trans->trans_15 . '</option>
												   <option value="2">' . $this->trans->trans_16 . '</option>
												   <option value="1.5">' . $this->trans->trans_17 . '</option>
												   <option value="1">' . $this->trans->trans_18 . '</option>
												   <option value="0.5">' . $this->trans->trans_19 . '</option>';
								$rating_img = '3halfstar.jpg';
								break;
							case 3:
								$rating_options = '<option value="5">' . $this->trans->trans_10 . '</option>
												   <option value="4.5">' . $this->trans->trans_11 . '</option>
												   <option value="4">' . $this->trans->trans_12 . '</option>
												   <option value="3.5">' . $this->trans->trans_13 . '</option>
												   <option value="3" selected default>' . $this->trans->trans_14 . '</option>
												   <option value="2.5">' . $this->trans->trans_15 . '</option>
												   <option value="2">' . $this->trans->trans_16 . '</option>
												   <option value="1.5">' . $this->trans->trans_17 . '</option>
												   <option value="1">' . $this->trans->trans_18 . '</option>
												   <option value="0.5">' . $this->trans->trans_19 . '</option>';
								$rating_img = '3star.jpg';
								break;
							case 2.5:
								$rating_options = '<option value="5">' . $this->trans->trans_10 . '</option>
												   <option value="4.5">' . $this->trans->trans_11 . '</option>
												   <option value="4">' . $this->trans->trans_12 . '</option>
												   <option value="3.5">' . $this->trans->trans_13 . '</option>
												   <option value="3">' . $this->trans->trans_14 . '</option>
												   <option value="2.5" selected default>' . $this->trans->trans_15 . '</option>
												   <option value="2">' . $this->trans->trans_16 . '</option>
												   <option value="1.5">' . $this->trans->trans_17 . '</option>
												   <option value="1">' . $this->trans->trans_18 . '</option>
												   <option value="0.5">' . $this->trans->trans_19 . '</option>';
								$rating_img = '2halfstar.jpg';
								break;
							case 2:
								$rating_options = '<option value="5">' . $this->trans->trans_10 . '</option>
												   <option value="4.5">' . $this->trans->trans_11 . '</option>
												   <option value="4">' . $this->trans->trans_12 . '</option>
												   <option value="3.5">' . $this->trans->trans_13 . '</option>
												   <option value="3">' . $this->trans->trans_14 . '</option>
												   <option value="2.5">' . $this->trans->trans_15 . '</option>
												   <option value="2" selected default>' . $this->trans->trans_16 . '</option>
												   <option value="1.5">' . $this->trans->trans_17 . '</option>
												   <option value="1">' . $this->trans->trans_18 . '</option>
												   <option value="0.5">' . $this->trans->trans_19 . '</option>';
								$rating_img = '2star.jpg';
								break;
							case 1.5:
								$rating_options = '<option value="5">' . $this->trans->trans_10 . '</option>
												   <option value="4.5">' . $this->trans->trans_11 . '</option>
												   <option value="4">' . $this->trans->trans_12 . '</option>
												   <option value="3.5">' . $this->trans->trans_13 . '</option>
												   <option value="3">' . $this->trans->trans_14 . '</option>
												   <option value="2.5">' . $this->trans->trans_15 . '</option>
												   <option value="2">' . $this->trans->trans_16 . '</option>
												   <option value="1.5" selected default>' . $this->trans->trans_17 . '</option>
												   <option value="1">' . $this->trans->trans_18 . '</option>
												   <option value="0.5">' . $this->trans->trans_19 . '</option>';
								$rating_img = '1halfstar.jpg';
								break;
							case 1:
								$rating_options = '<option value="5">' . $this->trans->trans_10 . '</option>
												   <option value="4.5">' . $this->trans->trans_11 . '</option>
												   <option value="4">' . $this->trans->trans_12 . '</option>
												   <option value="3.5">' . $this->trans->trans_13 . '</option>
												   <option value="3">' . $this->trans->trans_14 . '</option>
												   <option value="2.5">' . $this->trans->trans_15 . '</option>
												   <option value="2">' . $this->trans->trans_16 . '</option>
												   <option value="1.5">' . $this->trans->trans_17 . '</option>
												   <option value="1" selected default>' . $this->trans->trans_18 . '</option>
												   <option value="0.5">' . $this->trans->trans_19 . '</option>';
								$rating_img = '1star.jpg';
								break;
							case 0.5:
								$rating_options = '<option value="5">' . $this->trans->trans_10 . '</option>
												   <option value="4.5">' . $this->trans->trans_11 . '</option>
												   <option value="4">' . $this->trans->trans_12 . '</option>
												   <option value="3.5">' . $this->trans->trans_13 . '</option>
												   <option value="3">' . $this->trans->trans_14 . '</option>
												   <option value="2.5">' . $this->trans->trans_15 . '</option>
												   <option value="2">' . $this->trans->trans_16 . '</option>
												   <option value="1.5">' . $this->trans->trans_17 . '</option>
												   <option value="1">' . $this->trans->trans_18 . '</option>
												   <option value="0.5" selected default>' . $this->trans->trans_19 . '</option>';
								$rating_img = 'halfstar.jpg';
								break;

							default:
								# code...
								break;
						}

						$final_html = $final_html . '<div class="wpbooklist-comments-indiv-wrapper wpbooklist-edit-book-indiv-div-class">
									<div class="wpbooklist-comments-number-wrapper">
										<p class="wpbooklist-comments-number-title"> ' . $this->trans->trans_66 . ' ' . $this->trans->trans_4 . ' ' . $this->trans->trans_5 . ( $key + 1 ) . ' </p>
										<p class="wpbooklist-comments-number-title-date">' . $value->datesubmitted . '</p>
										<p class="wpbooklist-edit-book-title wpbooklist-show-book-colorbox" data-booktable="' . $value->library . '" data-bookid="' . $value->bookid . '">' . $value->booktitle . '</p>
									</div>
									<div class="wpbooklist-comments-actual-wrapper">
										<div class="wpbooklist-comments-star-rating-wrapper">
											<select class="wpbooklist-comments-star-dropdown">
												' . $rating_options . '
											</select>
											<img class="wpbooklist-comments-star-rating-img" src="' . ROOT_IMG_URL . $rating_img . '" />
											<div class="wpbooklist-comments-status-wrapper">
												<img class="wpbooklist-comments-approve-pending-img" src="' . ROOT_IMG_ICONS_URL . 'happy.svg" />
												<p>' . $this->trans->trans_66 . '</p>
											</div>
										</div>
										<textarea>' . $value->comment . '</textarea>
									</div>
								<div class="wpbooklist-comments-control-div-wrapper">
									<div class="wpbooklist-comments-control-button-edit" data-commentid="' . $value->ID . '" data-bookuid="' . $value->bookuid . '">
										<p>' . $this->trans->trans_8 . '
											<img class="wpbooklist-edit-book-icon wpbooklist-edit-book-icon-button" src="' . ROOT_IMG_ICONS_URL . 'pencil.svg"> 
										</p>
									</div>
									<div class="wpbooklist-comments-control-button-remove" data-commentid="' . $value->ID . '" data-bookuid="' . $value->bookuid . '"> 
										<p>' . $this->trans->trans_9 . '
											<img class="wpbooklist-edit-book-icon wpbooklist-edit-book-icon-button" src="' . ROOT_IMG_ICONS_URL . 'garbage-bin.svg">
										</p>
									</div>
									<div class="wpbooklist-spinner wpbooklist-spinner-comments" id="wpbooklist-spinner-' . ( $key + 1 ) . '"></div>
								</div>
							</div>';
					}
				} else {

					// Now determine the Timeframe.
					$days = '0';
					switch ( $timeframe ) {
						case $this->trans->trans_25:
							$days = '60';
							break;
						case $this->trans->trans_26:
							$days = '90';
							break;
						case $this->trans->trans_27:
							$days = '120';
							break;
						case $this->trans->trans_28:
							$days = '0';
							break;
						default:
							# code...
							break;
					}

					// Figuring out the date to compare to, based on the user's selection in the timeframe dropdown.
					$date = explode( '-', $this->date );
					$date = date_create( $date[2] . '-' . $date[0] . '-' . $date[1] );
					date_sub( $date, date_interval_create_from_date_string( $days . ' days' ) );
					$newdate = date_format( $date, 'Y-m-d' );

					// Grab all archived comment from DB.
					$this->commentsarchived = $wpdb->get_results( 'SELECT * from ' . $wpdb->prefix . "wpbooklist_comments WHERE status = 'archived'");

					foreach ( $this->commentsarchived as $key => $archivedcomment ) {

						// Converting date from DB to use in comparison with the strtotime() functions.
						$archivedcomment->datesubmitted = explode( '-', $archivedcomment->datesubmitted );
						$archivedcomment->datesubmitted =  $archivedcomment->datesubmitted[2] . '-' . $archivedcomment->datesubmitted[0] . '-' . $archivedcomment->datesubmitted[1];

						// If date is newer that what user specified, remove from array.
						if ( strtotime( $archivedcomment->datesubmitted ) >= strtotime( $newdate ) ) {
							unset($this->commentsarchived[$key]);
						}
					}

					foreach ( $this->commentsarchived as $key => $value ) {

						$result = $wpdb->delete( $wpdb->prefix . 'wpbooklist_comments', array( 'ID' => $value->ID ), array( '%d' ) );
						# code...
					}

				}
			}

			// End the function.
			wp_die( $final_html );
		}

	}
endif;

/*



function wpbooklist_comments_settings_action_javascript() { 
	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {

  		$("#wpbooklist-comments-img-remove-1").click(function(event){
  			$('#wpbooklist-comments-preview-img-1').attr('src', '<?php echo ROOT_IMG_ICONS_URL ?>'+'book-placeholder.svg');
  		});

  		$("#wpbooklist-comments-img-remove-2").click(function(event){
  			$('#wpbooklist-comments-preview-img-2').attr('src', '<?php echo ROOT_IMG_ICONS_URL ?>'+'book-placeholder.svg');
  		});



	  	$("#wpbooklist-comments-save-settings").click(function(event){

	  		$('#wpbooklist-comments-success-div').html('');
	  		$('#wpbooklist-spinner-storfront-lib').animate({'opacity':'1'});

	  		var callToAction = $('#wpbooklist-comments-call-to-action-input').val();
	  		var libImg = $('#wpbooklist-comments-preview-img-1').attr('src');
	  		var bookImg = $('#wpbooklist-comments-preview-img-2').attr('src');

		  	var data = {
				'action': 'wpbooklist_comments_settings_action',
				'security': '<?php echo wp_create_nonce( "wpbooklist_comments_settings_action_callback" ); ?>',
				'calltoaction':callToAction,
				'libimg':libImg,
				'bookimg':bookImg			
			};
			console.log(data);

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {

			    	$('#wpbooklist-spinner-storfront-lib').animate({'opacity':'0'});
			    	$('#wpbooklist-comments-success-div').html('<span id="wpbooklist-add-book-success-span">Success!</span><br/><br/> You\'ve saved your Comments Settings!<div id="wpbooklist-addstylepak-success-thanks">Thanks for using WPBooklist! If you happen to be thrilled with WPBookList, then by all means, <a id="wpbooklist-addbook-success-review-link" href="https://wordpress.org/support/plugin/wpbooklist/reviews/?filter=5">Feel Free to Leave a 5-Star Review Here!</a><img id="wpbooklist-smile-icon-1" src="http://evansclienttest.com/wp-content/plugins/wpbooklist/assets/img/icons/smile.png"></div>')
			    	console.log(response);
			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});

			event.preventDefault ? event.preventDefault() : event.returnValue = false;
	  	});
	});
	</script>
	<?php
}


function wpbooklist_comments_settings_action_callback(){
	global $wpdb;
	check_ajax_referer( 'wpbooklist_comments_settings_action_callback', 'security' );
	$call_to_action = filter_var($_POST['calltoaction'],FILTER_SANITIZE_STRING);
	$lib_img = filter_var($_POST['libimg'],FILTER_SANITIZE_URL);
	$book_img = filter_var($_POST['bookimg'],FILTER_SANITIZE_URL);
	$table_name = COMMENTS_PREFIX.'wpbooklist_jre_comments_options';

	if($lib_img == '' || $lib_img == null || strpos($lib_img, 'placeholder.svg') !== false){
		$lib_img = 'Purchase Now!';
	}

	if($book_img == '' || $book_img == null || strpos($book_img, 'placeholder.svg') !== false){
		$book_img = 'Purchase Now!';
	}

	$data = array(
        'calltoaction' => $call_to_action, 
        'libraryimg' => $lib_img, 
        'bookimg' => $book_img 
    );
    $format = array( '%s','%s','%s'); 
    $where = array( 'ID' => 1 );
    $where_format = array( '%d' );
    echo $wpdb->update( $table_name, $data, $where, $format, $where_format );


	wp_die();
}


function wpbooklist_comments_save_default_action_javascript() { 

	$trans1 = __("Success!", 'wpbooklist');
	$trans2 = __("You've saved your default Comments WooCommerce Settings!", 'wpbooklist');
	$trans6 = __("Thanks for using WPBookList, and", 'wpbooklist');
	$trans7 = __("be sure to check out the WPBookList Extensions!", 'wpbooklist');
	$trans8 = __("If you happen to be thrilled with WPBookList, then by all means,", 'wpbooklist');
	$trans9 = __("Feel Free to Leave a 5-Star Review Here!", 'wpbooklist');

	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {
	  	$("#wpbooklist-comments-woo-settings-button").click(function(event){

	  		$('#wpbooklist-comments-woo-set-success-div').html('');
	  		$('.wpbooklist-spinner').animate({'opacity':'1'});

	  		var salePrice = $( "input[name='book-woo-sale-price']" ).val();
			var regularPrice = $( "input[name='book-woo-regular-price']" ).val();
			var stock = $( "input[name='book-woo-stock']" ).val();
			var length = $( "input[name='book-woo-length']" ).val();
			var width = $( "input[name='book-woo-width']" ).val();
			var height = $( "input[name='book-woo-height']" ).val();
			var weight = $( "input[name='book-woo-weight']" ).val();
			var sku = $("#wpbooklist-addbook-woo-sku" ).val();
			var virtual = $("input[name='wpbooklist-woocommerce-vert-yes']").prop('checked');
			var download = $("input[name='wpbooklist-woocommerce-download-yes']").prop('checked');
			var salebegin = $('#wpbooklist-addbook-woo-salebegin').val();
			var saleend = $('#wpbooklist-addbook-woo-saleend').val();
			var purchasenote = $('#wpbooklist-addbook-woo-note').val();
			var productcategory = $('#wpbooklist-woocommerce-category-select').val();
			var reviews = $('#wpbooklist-woocommerce-review-yes').prop('checked');
			var upsells = $('#select2-upsells').val();
			var crosssells = $('#select2-crosssells').val();

			var upsellString = '';
			var crosssellString = '';

			// Making checks to see if Comments extension is active
			if(upsells != undefined){
				for (var i = '0'; i < upsells.length; i++) {
					upsellString = upsellString+','+upsells[i];
				};
			}

			if(crosssells != undefined){
				for (var i = '0'; i < crosssells.length; i++) {
					crosssellString = crosssellString+','+crosssells[i];
				};
			}

			if(salebegin != undefined && saleend != undefined){
				// Flipping the sale date start
				if(salebegin.indexOf('-')){
					var finishedtemp = salebegin.split('-');
					salebegin = finishedtemp[0]+'-'+finishedtemp[1]+'-'+finishedtemp[2]
				}

				// Flipping the sale date end
				if(saleend.indexOf('-')){
					var finishedtemp = saleend.split('-');
					saleend = finishedtemp[0]+'-'+finishedtemp[1]+'-'+finishedtemp[2]
				}	
			}

		  	var data = {
				'action': 'wpbooklist_comments_save_action_default',
				'security': '<?php echo wp_create_nonce( "wpbooklist_comments_save_default_action_callback" ); ?>',
				'saleprice':salePrice,
				'regularprice':regularPrice,
				'stock':stock,
				'length':length,
				'width':width,
				'height':height,
				'weight':weight,
				'sku':sku,
				'virtual':virtual,
				'download':download,
				'salebegin':salebegin,
				'saleend':saleend,
				'purchasenote':purchasenote,
				'productcategory':productcategory,
				'reviews':reviews,
				'upsells':upsellString,
				'crosssells':crosssellString
			};
			console.log(data);

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {
			    	console.log(response);


			    	$('#wpbooklist-comments-woo-set-success-div').html("<span id='wpbooklist-add-book-success-span'><?php echo $trans1 ?></span><br/><br/>&nbsp;<?php echo $trans2 ?><div id='wpbooklist-addtemplate-success-thanks'><?php echo $trans6 ?>&nbsp;<a href='http://wpbooklist.com/index.php/extensions/'><?php echo $trans7 ?></a><br/><br/>&nbsp;<?php echo $trans8 ?> &nbsp;<a id='wpbooklist-addbook-success-review-link' href='https://wordpress.org/support/plugin/wpbooklist/reviews/?filter=5'><?php echo $trans9 ?></a><img id='wpbooklist-smile-icon-1' src='http://evansclienttest.com/wp-content/plugins/wpbooklist/assets/img/icons/smile.png'></div>");

			    	$('.wpbooklist-spinner').animate({'opacity':'0'});

			    	$('html, body').animate({
				        scrollTop: $("#wpbooklist-comments-woo-set-success-div").offset().top-100
				    }, 1000);
			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});

			event.preventDefault ? event.preventDefault() : event.returnValue = false;
	  	});
	});
	</script>
	<?php
}

// Callback function for creating backups
function wpbooklist_comments_save_default_action_callback(){
	global $wpdb;
	check_ajax_referer( 'wpbooklist_comments_save_default_action_callback', 'security' );
	$saleprice = filter_var($_POST['saleprice'],FILTER_SANITIZE_STRING);
	$regularprice = filter_var($_POST['regularprice'],FILTER_SANITIZE_STRING);
	$stock = filter_var($_POST['stock'],FILTER_SANITIZE_STRING);
	$length = filter_var($_POST['length'],FILTER_SANITIZE_STRING);
	$width = filter_var($_POST['width'],FILTER_SANITIZE_STRING);
	$height = filter_var($_POST['height'],FILTER_SANITIZE_STRING);
	$weight = filter_var($_POST['weight'],FILTER_SANITIZE_STRING);
	$sku = filter_var($_POST['sku'],FILTER_SANITIZE_STRING);
	$virtual = filter_var($_POST['virtual'],FILTER_SANITIZE_STRING);
	$download = filter_var($_POST['download'],FILTER_SANITIZE_STRING);
	$woofile = filter_var($_POST['woofile'],FILTER_SANITIZE_STRING);
	$salebegin = filter_var($_POST['salebegin'],FILTER_SANITIZE_STRING);
	$saleend = filter_var($_POST['saleend'],FILTER_SANITIZE_STRING);
	$purchasenote = filter_var($_POST['purchasenote'],FILTER_SANITIZE_STRING);
	$productcategory = filter_var($_POST['productcategory'],FILTER_SANITIZE_STRING);
	$reviews = filter_var($_POST['reviews'],FILTER_SANITIZE_STRING);
	$crosssells = filter_var($_POST['crosssells'],FILTER_SANITIZE_STRING);
	$upsells = filter_var($_POST['upsells'],FILTER_SANITIZE_STRING);


	$data = array(
		'defaultsaleprice' => $saleprice,
		'defaultprice' => $regularprice,
		'defaultstock' => $stock,
		'defaultlength' => $length,
		'defaultwidth' => $width,
		'defaultheight' => $height,
		'defaultweight' => $weight,
		'defaultsku' => $sku,
		'defaultvirtual' => $virtual,
		'defaultdownload' => $download,
		'defaultsalebegin' => $salebegin,
		'defaultsaleend' => $saleend,
		'defaultnote' => $purchasenote,
		'defaultcategory' => $productcategory,
		'defaultreviews' => $reviews,
		'defaultcrosssell' => $crosssells,
		'defaultupsell' => $upsells
	);

 	$table = $wpdb->prefix."wpbooklist_jre_comments_options";
   	$format = array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'); 
    $where = array( 'ID' => 1 );
    $where_format = array( '%d' );
    $result = $wpdb->update( $table, $data, $where, $format, $where_format );

	echo $result;



	wp_die();
}


function wpbooklist_comments_upcross_pop_action_javascript() { 
	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {

		  	var data = {
				'action': 'wpbooklist_comments_upcross_pop_action',
				'security': '<?php echo wp_create_nonce( "wpbooklist_comments_upcross_pop_action_callback" ); ?>',
			};

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {
			    	response = response.split('–sep-seperator-sep–');
			    	var upsellstitles = '';
			    	var crosssellstitles = '';


			    	if(response[0] != 'null'){
				    	upsellstitles = response[0];
				    	if(upsellstitles.includes(',')){
				    		var upsellArray = upsellstitles.split(',');
				    	} else {
				    		var upsellArray = upsellstitles;
				    	}

				    	$("#select2-upsells").val(upsellArray).trigger('change');
			    	}

			    	if(response[1] != 'null'){
				    	crosssellstitles = response[1];
				    	if(crosssellstitles.includes(',')){
				    		var upsellArray = crosssellstitles.split(',');
				    	} else {
				    		var upsellArray = crosssellstitles;
				    	}

				    	$("#select2-crosssells").val(upsellArray).trigger('change');
			    	}


			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});


	});
	</script>
	<?php
}

// Callback function for creating backups
function wpbooklist_comments_upcross_pop_action_callback(){
	global $wpdb;
	check_ajax_referer( 'wpbooklist_comments_upcross_pop_action_callback', 'security' );
		
	// Get saved settings
    $settings_table = $wpdb->prefix."wpbooklist_jre_comments_options";
    $settings = $wpdb->get_row("SELECT * FROM $settings_table");

    echo $settings->defaultupsell.'–sep-seperator-sep–'.$settings->defaultcrosssell;

	wp_die();
}

/*
// For adding a book from the admin dashboard
add_action( 'admin_footer', 'wpbooklist_comments_action_javascript' );
add_action( 'wp_ajax_wpbooklist_comments_action', 'wpbooklist_comments_action_callback' );
add_action( 'wp_ajax_nopriv_wpbooklist_comments_action', 'wpbooklist_comments_action_callback' );


function wpbooklist_comments_action_javascript() { 
	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {
	  	$("#wpbooklist-admin-addbook-button").click(function(event){

		  	var data = {
				'action': 'wpbooklist_comments_action',
				'security': '<?php echo wp_create_nonce( "wpbooklist_comments_action_callback" ); ?>',
			};
			console.log(data);

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {
			    	console.log(response);
			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});

			event.preventDefault ? event.preventDefault() : event.returnValue = false;
	  	});
	});
	</script>
	<?php
}

// Callback function for creating backups
function wpbooklist_comments_action_callback(){
	global $wpdb;
	check_ajax_referer( 'wpbooklist_comments_action_callback', 'security' );
	//$var1 = filter_var($_POST['var'],FILTER_SANITIZE_STRING);
	//$var2 = filter_var($_POST['var'],FILTER_SANITIZE_NUMBER_INT);
	echo 'hi';
	wp_die();
}*/



