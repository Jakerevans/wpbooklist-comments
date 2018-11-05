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
				$string3 = $string3 . '
								<div class="wpbooklist-comments-indiv-wrapper">
									<p>
										' . $comment->status . '
									</p>




								</div>';
			}

			$closing_string = '</div>
						</div>';

			return $string1 . $string2 . $string3 . $closing_string;
		}
	}

endif;
