<?php
/**
 * Class WPBookList_Comments_Translations - class-wpbooklist-translations.php
 *
 * @author   Jake Evans
 * @category Translations
 * @package  Includes/Classes/Translations
 * @version  0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPBookList_Comments_Translations', false ) ) :
	/**
	 * WPBookList_Comments_Translations class. This class will house all the translations we may ever need...
	 */
	class WPBookList_Comments_Translations {

		/**
		 * Class Constructor - Simply calls the one function to return all Translated strings.
		 */
		public function __construct() {
			$this->trans_strings();
		}

		/**
		 * All the Translations.
		 */
		public function trans_strings() {
			$this->trans_1 = __( 'Here you can Approve, Deny, and Edit Comments, as well as view older Archived Comments.', 'wpbooklist-textdomain' );
			$this->trans_2 = __( 'Pending', 'wpbooklist-textdomain' );
			$this->trans_3 = __( 'Approved', 'wpbooklist-textdomain' );
			$this->trans_4 = __( 'Comment', 'wpbooklist-textdomain' );
			$this->trans_5 = __( '#', 'wpbooklist-textdomain' );
			$this->trans_6 = __( 'Approve', 'wpbooklist-textdomain' );
			$this->trans_7 = __( 'Deny', 'wpbooklist-textdomain' );
			$this->trans_8 = __( 'Save Changes', 'wpbooklist-textdomain' );
			$this->trans_9 = __( 'Delete', 'wpbooklist-textdomain' );
			$this->trans_10 = __( '5 Stars', 'wpbooklist-textdomain' );
			$this->trans_11 = __( '4.5 Stars', 'wpbooklist-textdomain' );
			$this->trans_12 = __( '4 Stars', 'wpbooklist-textdomain' );
			$this->trans_13 = __( '3.5 Stars', 'wpbooklist-textdomain' );
			$this->trans_14 = __( '3 Stars', 'wpbooklist-textdomain' );
			$this->trans_15 = __( '2.5 Stars', 'wpbooklist-textdomain' );
			$this->trans_16 = __( '2 Stars', 'wpbooklist-textdomain' );
			$this->trans_17 = __( '1.5 Stars', 'wpbooklist-textdomain' );
			$this->trans_18 = __( '1 Star', 'wpbooklist-textdomain' );
			$this->trans_19 = __( '0.5 Stars', 'wpbooklist-textdomain' );
			$this->trans_20 = __( 'Here you can View and Delete Archived Comments. Just select an action from the Drop-Down Box on the left, and then a Time Frame from the Drop-Down Box on the right, and click the Submit button.', 'wpbooklist-textdomain' );
			$this->trans_21 = __( 'View Archived Comments', 'wpbooklist-textdomain' );
			$this->trans_22 = __( 'Select an Action...', 'wpbooklist-textdomain' );
			$this->trans_23 = __( 'Select a Time Frame...', 'wpbooklist-textdomain' );
			$this->trans_24 = __( 'Delete Archived Comments', 'wpbooklist-textdomain' );
			$this->trans_25 = __( 'Comments Older Than 60 Days', 'wpbooklist-textdomain' );
			$this->trans_26 = __( 'Comments Older Than 90 Days', 'wpbooklist-textdomain' );
			$this->trans_27 = __( 'Comments Older Than 120 Days', 'wpbooklist-textdomain' );
			$this->trans_28 = __( 'All Archived Comments', 'wpbooklist-textdomain' );
			$this->trans_29 = __( 'Submit', 'wpbooklist-textdomain' );
			$this->trans_30 = __( 'Ratings & Comments', 'wpbooklist-textdomain' );
			$this->trans_31 = __( 'This title hasn\'t been rated yet!', 'wpbooklist-textdomain' );
			$this->trans_32 = __( 'Comments', 'wpbooklist-textdomain' );
			$this->trans_33 = __( 'Average Rating', 'wpbooklist-textdomain' );
			$this->trans_34 = __( 'Stars', 'wpbooklist-textdomain' );
			$this->trans_35 = __( 'By', 'wpbooklist-textdomain' );
			$this->trans_36 = __( 'Likes', 'wpbooklist-textdomain' );




			// The array of translation strings.
			$translation_array = array(
				'trans1' => $this->trans_1,
			);

			return $translation_array;
		}
	}
endif;
