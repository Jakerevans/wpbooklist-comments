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

			$string1 = '<p class="wpbooklist-tab-intro-para">' . $this->trans->trans_42 . '</p>';

			
			$string2 = '<div class="wpbooklist-book-form-inner-container-dropdown-fields" style="margin-top:10px;">
							<div class="wpbooklist-book-form-inner-container-dropdown-fields-row" style="margin:20px;">
								<div class="wpbooklist-book-form-indiv-attribute-container">
									<img class="wpbooklist-icon-image-question" data-label="book-form-rating" src="http://localhost/local/wp-content/plugins/wpbooklist/assets/img/icons/question-black.svg">
									<label class="wpbooklist-question-icon-label" for="book-rating">' . $this->trans->trans_43 . '</label>
									<select class="wpbooklist-addbook-select-default" id="wpbooklist-comments-newcomment-behavior">
										<option>' . $this->trans->trans_44 . '</option>
										<option>' . $this->trans->trans_45 . '</option>
									</select>
								</div>
								<div class="wpbooklist-book-form-indiv-attribute-container">
									<img class="wpbooklist-icon-image-question" data-label="book-form-outofprint" src="http://localhost/local/wp-content/plugins/wpbooklist/assets/img/icons/question-black.svg">
									<label class="wpbooklist-question-icon-label" for="book-form-outofprint">' . $this->trans->trans_46 . '</label>
									<select class="wpbooklist-addbook-select-default" id="wpbooklist-comments-display-order">
										<option>' . $this->trans->trans_47 . '</option>
										<option>' . $this->trans->trans_48 . '</option>
										<option>' . $this->trans->trans_49 . '</option>
										<option>' . $this->trans->trans_50 . '</option>
									</select>
								</div>
							</div>
							<div class="wpbooklist-book-form-inner-container-dropdown-fields-row" style="margin:20px;">
								<div class="wpbooklist-book-form-indiv-attribute-container">
									<img class="wpbooklist-icon-image-question" data-label="book-form-finished" src="http://localhost/local/wp-content/plugins/wpbooklist/assets/img/icons/question-black.svg">
									<label class="wpbooklist-question-icon-label" for="book-form-finshed">' . $this->trans->trans_51 . '</label>
									<select class="wpbooklist-addbook-select-default" id="wpbooklist-comments-archive-after">
										<option>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_57 . '</option>
									</select>
								</div>
								<div class="wpbooklist-book-form-indiv-attribute-container">
									<img class="wpbooklist-icon-image-question" data-label="book-form-signed" src="http://localhost/local/wp-content/plugins/wpbooklist/assets/img/icons/question-black.svg">
									<label class="wpbooklist-question-icon-label" for="book-form-finshed">' . $this->trans->trans_58 . '</label>
									<select class="wpbooklist-addbook-select-default" id="wpbooklist-comments-delete-after">
										<option>' . $this->trans->trans_59 . '</option>
										<option>' . $this->trans->trans_60 . '</option>
										<option>' . $this->trans->trans_61 . '</option>
										<option>' . $this->trans->trans_62 . '</option>
										<option>' . $this->trans->trans_63 . '</option>
										<option>' . $this->trans->trans_64 . '</option>
									</select>
								</div>
							</div>
							<button class="wpbooklist-response-success-fail-button wpbooklist-admin-editbook-edit-button" type="button" id="wpbooklist-comments-save-settings">' . $this->trans->trans_8 . '</button>
							<div class="wpbooklist-spinner" id="wpbooklist-spinner-comments-settings"></div>
							<div class="wpbooklist-response-success-fail-response-actual-container" id="wpbooklist-admin-addbook-response-actual-container"></div>
						</div>';
			return $string1 . $string2;
		}
	}

endif;
