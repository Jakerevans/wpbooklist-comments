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

			// Grab all the settings.
			$this->comments_settings = $wpdb->get_row( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_comments_settings' );

			// Build out various drop-down optioons based on saved settings.
			$this->autoapprove_options = '';
			switch ( $this->comments_settings->autoapprove ) {
				case 'pending':
						$this->autoapprove_options = '
							<option selected >' . $this->trans->trans_44 . '</option>
							<option>' . $this->trans->trans_45 . '</option>';
					break;
				case 'approve':
						$this->autoapprove_options = '
							<option>' . $this->trans->trans_44 . '</option>
							<option selected >' . $this->trans->trans_45 . '</option>';
					break;
				default:
						$this->autoapprove_options = '
							<option selected >' . $this->trans->trans_44 . '</option>
							<option>' . $this->trans->trans_45 . '</option>';
					break;
			}

			// Build out various drop-down options based on saved settings.
			$this->commentorder_options = '';
			switch ( $this->comments_settings->commentorder ) {
				case 'newfirst':
						$this->commentorder_options = '
							<option selected>' . $this->trans->trans_47 . '</option>
										<option>' . $this->trans->trans_48 . '</option>
										<option>' . $this->trans->trans_49 . '</option>
										<option>' . $this->trans->trans_50 . '</option>';
					break;
				case 'oldfirst':
						$this->commentorder_options = '
							<option>' . $this->trans->trans_47 . '</option>
										<option selected>' . $this->trans->trans_48 . '</option>
										<option>' . $this->trans->trans_49 . '</option>
										<option>' . $this->trans->trans_50 . '</option>';
					break;
				case 'mostlikes':
						$this->commentorder_options = '
							<option>' . $this->trans->trans_47 . '</option>
										<option>' . $this->trans->trans_48 . '</option>
										<option selected>' . $this->trans->trans_49 . '</option>
										<option>' . $this->trans->trans_50 . '</option>';
					break;
				case 'leastlikes':
						$this->commentorder_options = '
							<option>' . $this->trans->trans_47 . '</option>
										<option>' . $this->trans->trans_48 . '</option>
										<option>' . $this->trans->trans_49 . '</option>
										<option selected>' . $this->trans->trans_50 . '</option>';
					break;
				default:
						$this->commentorder_options = '
							<option selected>' . $this->trans->trans_47 . '</option>
										<option>' . $this->trans->trans_48 . '</option>
										<option>' . $this->trans->trans_49 . '</option>
										<option>' . $this->trans->trans_50 . '</option>';
					break;
			}

			// Build out various drop-down options based on saved settings.
			$this->archiveafter_options = '';
			switch ( $this->comments_settings->archiveafter ) {
				case '30':
						$this->archiveafter_options = '
							<option selected>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_57 . '</option>';
					break;
				case '60':
						$this->archiveafter_options = '
							<option>' . $this->trans->trans_52 . '</option>
										<option selected>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_57 . '</option>';
					break;
				case '90':
						$this->archiveafter_options = '
							<option>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option selected>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_57 . '</option>';
					break;
				case '180':
						$this->archiveafter_options = '
							<option>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option selected>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_57 . '</option>';
					break;
				case '364':
						$this->archiveafter_options = '
							<option>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option selected>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_57 . '</option>';
					break;
				case '0':
						$this->archiveafter_options = '
							<option>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option selected>' . $this->trans->trans_57 . '</option>';
					break;
				default:
						$this->archiveafter_options = '
							<option selected>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_57 . '</option>';
					break;
			}

			// Build out various drop-down options based on saved settings.
			$this->deleteafter_options = '';
			switch ( $this->comments_settings->deleteafter ) {
				case '30':
						$this->deleteafter_options = '
							<option selected>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_64 . '</option>';
					break;
				case '60':
						$this->deleteafter_options = '
							<option>' . $this->trans->trans_52 . '</option>
										<option selected>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_64 . '</option>';
					break;
				case '90':
						$this->deleteafter_options = '
							<option>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option selected>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_64 . '</option>';
					break;
				case '180':
						$this->deleteafter_options = '
							<option>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option selected>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_64 . '</option>';
					break;
				case '364':
						$this->deleteafter_options = '
							<option>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option selected>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_64 . '</option>';
					break;
				case '0':
						$this->deleteafter_options = '
							<option>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option selected>' . $this->trans->trans_64 . '</option>';
					break;
				default:
						$this->deleteafter_options = '
							<option selected>' . $this->trans->trans_52 . '</option>
										<option>' . $this->trans->trans_53 . '</option>
										<option>' . $this->trans->trans_54 . '</option>
										<option>' . $this->trans->trans_55 . '</option>
										<option>' . $this->trans->trans_56 . '</option>
										<option>' . $this->trans->trans_64 . '</option>';
					break;
			}

			// Build out various drop-down optioons based on saved settings.
			$this->restrictto_options = '';
			switch ( $this->comments_settings->restrictto ) {
				case 'everyone':
						$this->restrictto_options = '
							<option selected>' . $this->trans->trans_69 . '</option>
										<option>' . $this->trans->trans_70 . '</option>';
					break;
				case 'authenticated':
						$this->restrictto_options = '
							<option>' . $this->trans->trans_69 . '</option>
										<option selected>' . $this->trans->trans_70 . '</option>';
					break;
				default:
						$this->restrictto_options = '
							<option selected>' . $this->trans->trans_69 . '</option>
										<option>' . $this->trans->trans_70 . '</option>';
					break;
			}

			// Build out various drop-down optioons based on saved settings.
			$this->allowregistration_options = '';
			switch ( $this->comments_settings->allowregistration ) {
				case 'yes':
						$this->allowregistration_options = '
							<option selected>' . $this->trans->trans_78 . '</option>
										<option>' . $this->trans->trans_79 . '</option>';
					break;
				case 'no':
						$this->allowregistration_options = '
							<option>' . $this->trans->trans_78 . '</option>
										<option selected>' . $this->trans->trans_79 . '</option>';
					break;
				default:
						$this->allowregistration_options = '
							<option selected>' . $this->trans->trans_78 . '</option>
										<option>' . $this->trans->trans_79 . '</option>';
					break;
			}

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
									<img class="wpbooklist-icon-image-question" data-label="book-form-rating" src="' . ROOT_IMG_ICONS_URL . 'question-black.svg">
									<label class="wpbooklist-question-icon-label" for="book-rating">' . $this->trans->trans_43 . '</label>
									<select class="wpbooklist-addbook-select-default" id="wpbooklist-comments-newcomment-behavior">
										' . $this->autoapprove_options . '
									</select>
								</div>
								<div class="wpbooklist-book-form-indiv-attribute-container">
									<img class="wpbooklist-icon-image-question" data-label="book-form-outofprint" src="' . ROOT_IMG_ICONS_URL . 'question-black.svg">
									<label class="wpbooklist-question-icon-label" for="book-form-outofprint">' . $this->trans->trans_46 . '</label>
									<select class="wpbooklist-addbook-select-default" id="wpbooklist-comments-display-order">
										' . $this->commentorder_options . '
									</select>
								</div>
								<div class="wpbooklist-book-form-indiv-attribute-container">
									<img class="wpbooklist-icon-image-question" data-label="book-form-finished" src="' . ROOT_IMG_ICONS_URL . 'question-black.svg">
									<label class="wpbooklist-question-icon-label" for="book-form-finshed">' . $this->trans->trans_51 . '</label>
									<select class="wpbooklist-addbook-select-default" id="wpbooklist-comments-archive-after">
										' . $this->archiveafter_options . '
									</select>
								</div>
								<div class="wpbooklist-book-form-indiv-attribute-container">
									<img class="wpbooklist-icon-image-question" data-label="book-form-signed" src="' . ROOT_IMG_ICONS_URL . 'question-black.svg">
									<label class="wpbooklist-question-icon-label" for="book-form-finshed">' . $this->trans->trans_58 . '</label>
									<select class="wpbooklist-addbook-select-default" id="wpbooklist-comments-delete-after">
										' . $this->deleteafter_options . '
									</select>
								</div>
								<div class="wpbooklist-book-form-indiv-attribute-container">
									<img class="wpbooklist-icon-image-question" data-label="book-form-signed" src="' . ROOT_IMG_ICONS_URL . 'question-black.svg">
									<label class="wpbooklist-question-icon-label" for="book-form-finshed">' . $this->trans->trans_68 . '</label>
									<select class="wpbooklist-addbook-select-default" id="wpbooklist-comments-restrict-to">
										' . $this->restrictto_options . '
									</select>
								</div>
								<div class="wpbooklist-book-form-indiv-attribute-container">
									<img class="wpbooklist-icon-image-question" data-label="book-form-signed" src="' . ROOT_IMG_ICONS_URL . 'question-black.svg">
									<label class="wpbooklist-question-icon-label" for="book-form-finshed">' . $this->trans->trans_77 . '</label>
									<select class="wpbooklist-addbook-select-default" id="wpbooklist-comments-allow-registration">
										' . $this->allowregistration_options . '
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
