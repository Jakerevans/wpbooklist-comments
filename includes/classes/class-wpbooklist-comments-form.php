<?php
/**
 * WPBookList WPBookList_Comments_Form Submenu Class
 *
 * @author   Jake Evans
 * @category Admin
 * @package  Includes/Classes
 * @version  0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPBookList_Comments_Form', false ) ) :
	/**
	 * WPBookList_Comments_Form Class.
	 */
	class WPBookList_Comments_Form {

		/**
		 * Class Constructor - Simply calls the Translations
		 */
		public function __construct() {

			global $wpdb;

			// Get Translations.
			require_once COMMENTS_CLASS_TRANSLATIONS_DIR . 'class-wpbooklist-comments-translations.php';
			$this->trans = new WPBookList_Comments_Translations();
			$this->trans->trans_strings();

			// Let's grab all comments from the DB that have a status of anything but Archived. Comments past a certain date (specified by the User on the 'Comment Settings' tab) will be archived. The function that changes a comment's status to 'archived' is in the class-wpbooklist-comment-settings-form.php file, and is ran from it's constructor.
			$this->comments = $wpdb->get_results( 'SELECT * from ' . $wpdb->prefix . "wpbooklist_comments WHERE status != 'archived' ORDER BY status DESC" );

		}

		/**
		 * Outputs all HTML elements on the page .
		 */
		public function output_comments_form() {

			global $wpdb;

			$string1 = '<p class="wpbooklist-tab-intro-para">' . $this->trans->trans_1 . '</p>';

			$string2 = '<div class="wpbooklist-comments-top-wrapper">
							<div class="wpbooklist-comments-inner-wrapper">';
			$string3 = '';
			foreach ( $this->comments as $key => $comment ) {

				// Building the Star Rating drop-down.
				$rating_options = '';
				$rating_img = '';
				switch ( $comment->rating ) {
					case 5:
						$rating_options = '<option selected default>' . $this->trans->trans_10 . '</option>
										   <option>' . $this->trans->trans_11 . '</option>
										   <option>' . $this->trans->trans_12 . '</option>
										   <option>' . $this->trans->trans_13 . '</option>
										   <option>' . $this->trans->trans_14 . '</option>
										   <option>' . $this->trans->trans_15 . '</option>
										   <option>' . $this->trans->trans_16 . '</option>
										   <option>' . $this->trans->trans_17 . '</option>
										   <option>' . $this->trans->trans_18 . '</option>
										   <option>' . $this->trans->trans_19 . '</option>';
						$rating_img = '5star.jpg';
						break;
					case 4.5:
						$rating_options = '<option>' . $this->trans->trans_10 . '</option>
										   <option selected default>' . $this->trans->trans_11 . '</option>
										   <option>' . $this->trans->trans_12 . '</option>
										   <option>' . $this->trans->trans_13 . '</option>
										   <option>' . $this->trans->trans_14 . '</option>
										   <option>' . $this->trans->trans_15 . '</option>
										   <option>' . $this->trans->trans_16 . '</option>
										   <option>' . $this->trans->trans_17 . '</option>
										   <option>' . $this->trans->trans_18 . '</option>
										   <option>' . $this->trans->trans_19 . '</option>';
						$rating_img = '4halfstar.jpg';
						break;
					case 4:
						$rating_options = '<option>' . $this->trans->trans_10 . '</option>
										   <option>' . $this->trans->trans_11 . '</option>
										   <option selected default>' . $this->trans->trans_12 . '</option>
										   <option>' . $this->trans->trans_13 . '</option>
										   <option>' . $this->trans->trans_14 . '</option>
										   <option>' . $this->trans->trans_15 . '</option>
										   <option>' . $this->trans->trans_16 . '</option>
										   <option>' . $this->trans->trans_17 . '</option>
										   <option>' . $this->trans->trans_18 . '</option>
										   <option>' . $this->trans->trans_19 . '</option>';
						$rating_img = '4star.jpg';
						break;
					case 3.5:
						$rating_options = '<option>' . $this->trans->trans_10 . '</option>
										   <option>' . $this->trans->trans_11 . '</option>
										   <option>' . $this->trans->trans_12 . '</option>
										   <option selected default>' . $this->trans->trans_13 . '</option>
										   <option>' . $this->trans->trans_14 . '</option>
										   <option>' . $this->trans->trans_15 . '</option>
										   <option>' . $this->trans->trans_16 . '</option>
										   <option>' . $this->trans->trans_17 . '</option>
										   <option>' . $this->trans->trans_18 . '</option>
										   <option>' . $this->trans->trans_19 . '</option>';
						$rating_img = '3halfstar.jpg';
						break;
					case 3:
						$rating_options = '<option>' . $this->trans->trans_10 . '</option>
										   <option>' . $this->trans->trans_11 . '</option>
										   <option>' . $this->trans->trans_12 . '</option>
										   <option>' . $this->trans->trans_13 . '</option>
										   <option selected default>' . $this->trans->trans_14 . '</option>
										   <option>' . $this->trans->trans_15 . '</option>
										   <option>' . $this->trans->trans_16 . '</option>
										   <option>' . $this->trans->trans_17 . '</option>
										   <option>' . $this->trans->trans_18 . '</option>
										   <option>' . $this->trans->trans_19 . '</option>';
						$rating_img = '3star.jpg';
						break;
					case 2.5:
						$rating_options = '<option>' . $this->trans->trans_10 . '</option>
										   <option>' . $this->trans->trans_11 . '</option>
										   <option>' . $this->trans->trans_12 . '</option>
										   <option>' . $this->trans->trans_13 . '</option>
										   <option>' . $this->trans->trans_14 . '</option>
										   <option selected default>' . $this->trans->trans_15 . '</option>
										   <option>' . $this->trans->trans_16 . '</option>
										   <option>' . $this->trans->trans_17 . '</option>
										   <option>' . $this->trans->trans_18 . '</option>
										   <option>' . $this->trans->trans_19 . '</option>';
						$rating_img = '2halfstar.jpg';
						break;
					case 2:
						$rating_options = '<option>' . $this->trans->trans_10 . '</option>
										   <option>' . $this->trans->trans_11 . '</option>
										   <option>' . $this->trans->trans_12 . '</option>
										   <option>' . $this->trans->trans_13 . '</option>
										   <option>' . $this->trans->trans_14 . '</option>
										   <option>' . $this->trans->trans_15 . '</option>
										   <option selected default>' . $this->trans->trans_16 . '</option>
										   <option>' . $this->trans->trans_17 . '</option>
										   <option>' . $this->trans->trans_18 . '</option>
										   <option>' . $this->trans->trans_19 . '</option>';
						$rating_img = '2star.jpg';
						break;
					case 1.5:
						$rating_options = '<option>' . $this->trans->trans_10 . '</option>
										   <option>' . $this->trans->trans_11 . '</option>
										   <option>' . $this->trans->trans_12 . '</option>
										   <option>' . $this->trans->trans_13 . '</option>
										   <option>' . $this->trans->trans_14 . '</option>
										   <option>' . $this->trans->trans_15 . '</option>
										   <option>' . $this->trans->trans_16 . '</option>
										   <option selected default>' . $this->trans->trans_17 . '</option>
										   <option>' . $this->trans->trans_18 . '</option>
										   <option>' . $this->trans->trans_19 . '</option>';
						$rating_img = '1halfstar.jpg';
						break;
					case 1:
						$rating_options = '<option>' . $this->trans->trans_10 . '</option>
										   <option>' . $this->trans->trans_11 . '</option>
										   <option>' . $this->trans->trans_12 . '</option>
										   <option>' . $this->trans->trans_13 . '</option>
										   <option>' . $this->trans->trans_14 . '</option>
										   <option>' . $this->trans->trans_15 . '</option>
										   <option>' . $this->trans->trans_16 . '</option>
										   <option>' . $this->trans->trans_17 . '</option>
										   <option selected default>' . $this->trans->trans_18 . '</option>
										   <option>' . $this->trans->trans_19 . '</option>';
						$rating_img = '1star.jpg';
						break;
					case 0.5:
						$rating_options = '<option selected default>' . $this->trans->trans_10 . '</option>
										   <option>' . $this->trans->trans_11 . '</option>
										   <option>' . $this->trans->trans_12 . '</option>
										   <option>' . $this->trans->trans_13 . '</option>
										   <option>' . $this->trans->trans_14 . '</option>
										   <option>' . $this->trans->trans_15 . '</option>
										   <option>' . $this->trans->trans_16 . '</option>
										   <option>' . $this->trans->trans_17 . '</option>
										   <option>' . $this->trans->trans_18 . '</option>
										   <option selected default>' . $this->trans->trans_19 . '</option>';
						$rating_img = 'halfstar.jpg';
						break;

					default:
						# code...
						break;
				}

				if ( $this->trans->trans_2 === $comment->status || 'pending' === $comment->status ) {
					$string3 = $string3 . '
								<div class="wpbooklist-comments-indiv-wrapper wpbooklist-edit-book-indiv-div-class">
									<div class="wpbooklist-comments-number-wrapper">
										<p class="wpbooklist-comments-number-title">' . $this->trans->trans_4 . ' ' . $this->trans->trans_5 . ( $key + 1 ) . ' </p>
										<p class="wpbooklist-comments-number-title-date">' . $comment->datesubmitted . '</p>
										<p class="wpbooklist-edit-book-title wpbooklist-show-book-colorbox" data-booktable="' . $comment->library . '" data-bookid="' . $comment->bookid . '">' . $comment->booktitle . '</p>
									</div>
									<div class="wpbooklist-comments-actual-wrapper">
										<div class="wpbooklist-comments-star-rating-wrapper">
											<select class="wpbooklist-comments-star-dropdown">
												' . $rating_options . '
											</select>
											<img class="wpbooklist-comments-star-rating-img" src="' . ROOT_IMG_URL . $rating_img . '" />
											<div class="wpbooklist-comments-status-wrapper wpbooklist-comments-status-pending-wrapper">
												<img class="wpbooklist-comments-approve-pending-img" src="' . ROOT_IMG_ICONS_URL . 'shocked.svg" />
												<p>' . $this->trans->trans_2 . '</p>
											</div>
										</div>
										<textarea>' . $comment->comment . '</textarea>
									</div>
								<div class="wpbooklist-comments-control-div-wrapper">
									<div class="wpbooklist-edit-actions-edit-button wpbooklist-comments-control-button-update" data-key="0" data-table="wp_wpbooklist_jre_saved_book_log" data-book-id="1">
										<p>' . $this->trans->trans_6 . '
											<img class="wpbooklist-edit-book-icon wpbooklist-edit-book-icon-button" src="http://localhost/local/wp-content/plugins/wpbooklist/assets/img/icons/pencil.svg"> 
										</p>
									</div>
									<div class="wpbooklist-edit-actions-delete-button wpbooklist-comments-control-button-remove" data-key="0" data-table="wp_wpbooklist_jre_saved_book_log" data-book-id="1"> 
										<p>' . $this->trans->trans_7 . '
											<img class="wpbooklist-edit-book-icon wpbooklist-edit-book-icon-button" src="http://localhost/local/wp-content/plugins/wpbooklist/assets/img/icons/garbage-bin.svg">
										</p>
									</div>
									<div class="wpbooklist-spinner wpbooklist-spinner-comments" id="wpbooklist-spinner-' . ( $key + 1 ) . '"></div>
								</div>
							</div>';
				}

				if ( $this->trans->trans_3 === $comment->status || 'approved' === $comment->status ) {
					$string3 = $string3 . '
								<div class="wpbooklist-comments-indiv-wrapper wpbooklist-edit-book-indiv-div-class">
									<div class="wpbooklist-comments-number-wrapper">
										<p class="wpbooklist-comments-number-title">' . $this->trans->trans_4 . ' ' . $this->trans->trans_5 . ( $key + 1 ) . ' </p>
										<p class="wpbooklist-comments-number-title-date">' . $comment->datesubmitted . '</p>
										<p class="wpbooklist-edit-book-title wpbooklist-show-book-colorbox" data-booktable="' . $comment->library . '" data-bookid="' . $comment->bookid . '">' . $comment->booktitle . '</p>
									</div>
									<div class="wpbooklist-comments-actual-wrapper">
										<div class="wpbooklist-comments-star-rating-wrapper">
											<select class="wpbooklist-comments-star-dropdown">
												' . $rating_options . '
											</select>
											<img class="wpbooklist-comments-star-rating-img" src="' . ROOT_IMG_URL . $rating_img . '" />
											<div class="wpbooklist-comments-status-wrapper">
												<img class="wpbooklist-comments-approve-pending-img" src="' . ROOT_IMG_ICONS_URL . 'happy.svg" />
												<p>' . $this->trans->trans_3 . '</p>
											</div>
										</div>
										<textarea>' . $comment->comment . '</textarea>
									</div>
								<div class="wpbooklist-comments-control-div-wrapper">
									<div class="wpbooklist-edit-actions-edit-button wpbooklist-comments-control-button-update" data-key="0" data-table="wp_wpbooklist_jre_saved_book_log" data-book-id="1">
										<p>' . $this->trans->trans_8 . '
											<img class="wpbooklist-edit-book-icon wpbooklist-edit-book-icon-button" src="http://localhost/local/wp-content/plugins/wpbooklist/assets/img/icons/pencil.svg"> 
										</p>
									</div>
									<div class="wpbooklist-edit-actions-delete-button wpbooklist-comments-control-button-remove" data-key="0" data-table="wp_wpbooklist_jre_saved_book_log" data-book-id="1"> 
										<p>' . $this->trans->trans_9 . '
											<img class="wpbooklist-edit-book-icon wpbooklist-edit-book-icon-button" src="http://localhost/local/wp-content/plugins/wpbooklist/assets/img/icons/garbage-bin.svg">
										</p>
									</div>
									<div class="wpbooklist-spinner wpbooklist-spinner-comments" id="wpbooklist-spinner-' . ( $key + 1 ) . '"></div>
								</div>
							</div>';
				}
			}

			$archived_string = '
				<div class="wpbooklist-comments-archived-wrapper">
					<div class="wpbooklist-comments-archived-title-wrapper">
						<p>Archived Comments</p>
					</div>
					<div class="wpbooklist-comments-archived-selection-wrapper">
						<div class="wpbooklist-comments-archived-selection-instructions-wrapper">
							<p class="wpbooklist-tab-intro-para">' . $this->trans->trans_20 . '</p>
						</div>
						<div class="wpbooklist-comments-archived-selection-controls-wrapper">
							<select>
								<option selected default disabled>' . $this->trans->trans_22 . '</option>
								<option>' . $this->trans->trans_21 . '</option>
								<option>' . $this->trans->trans_24 . '</option>
							<select>
							<select>
								<option selected default disabled>' . $this->trans->trans_23 . '</option>
								<option>' . $this->trans->trans_25 . '</option>
								<option>' . $this->trans->trans_26 . '</option>
								<option>' . $this->trans->trans_27 . '</option>
								<option>' . $this->trans->trans_28 . '</option>
							<select>
							<div>
								<button class="wpbooklist-response-success-fail-button" id="wpbooklist-comments-archived-submit">' . $this->trans->trans_29 . '</button>
								<div class="wpbooklist-spinner" id="wpbooklist-spinner-archived"></div>
							</div>
						</div>
					</div>
				</div>';

			$closing_string = '</div>
						</div>';

			return $string1 . $string2 . $string3 . $archived_string . $closing_string;
		}
	}

endif;
