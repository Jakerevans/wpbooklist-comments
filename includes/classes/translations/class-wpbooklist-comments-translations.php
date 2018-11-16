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
			$this->trans_1  = __( 'Here you can Approve, Deny, and Edit Comments, as well as view older Archived Comments.', 'wpbooklist-textdomain' );
			$this->trans_2  = __( 'Pending', 'wpbooklist-textdomain' );
			$this->trans_3  = __( 'Approved', 'wpbooklist-textdomain' );
			$this->trans_4  = __( 'Comment', 'wpbooklist-textdomain' );
			$this->trans_5  = __( '#', 'wpbooklist-textdomain' );
			$this->trans_6  = __( 'Approve', 'wpbooklist-textdomain' );
			$this->trans_7  = __( 'Deny', 'wpbooklist-textdomain' );
			$this->trans_8  = __( 'Save Changes', 'wpbooklist-textdomain' );
			$this->trans_9  = __( 'Delete', 'wpbooklist-textdomain' );
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
			$this->trans_37 = __( 'Add Your Comment', 'wpbooklist-textdomain' );
			$this->trans_38 = __( 'Enter Your Comment Here...', 'wpbooklist-textdomain' );
			$this->trans_39 = __( 'Submit Rating & Comment', 'wpbooklist-textdomain' );
			$this->trans_40 = __( 'Rate This Title', 'wpbooklist-textdomain' );
			$this->trans_41 = __( 'Be the first to add a Rating & Comment for this Title below!', 'wpbooklist-textdomain' );
			$this->trans_42 = __( 'Here you can change the settings of the WPBookList Comments Extension. Just make the changes you\'d like, and click the \'Save Changes\' button at the bottom of this page.', 'wpbooklist-textdomain' );
			$this->trans_43 = __( 'When Comments Arrive...', 'wpbooklist-textdomain' );
			$this->trans_44 = __( 'Set Status as Pending', 'wpbooklist-textdomain' );
			$this->trans_45 = __( 'Auto-Approve New Comments', 'wpbooklist-textdomain' );
			$this->trans_46 = __( 'Comment Display Order', 'wpbooklist-textdomain' );
			$this->trans_47 = __( 'Newest Comments First', 'wpbooklist-textdomain' );
			$this->trans_48 = __( 'Oldest Comments First', 'wpbooklist-textdomain' );
			$this->trans_49 = __( 'Comments with Most Likes First', 'wpbooklist-textdomain' );
			$this->trans_50 = __( 'Comments with Least Likes First', 'wpbooklist-textdomain' );
			$this->trans_51 = __( 'Archive Comments After...', 'wpbooklist-textdomain' );
			$this->trans_52 = __( '30 days', 'wpbooklist-textdomain' );
			$this->trans_53 = __( '60 days', 'wpbooklist-textdomain' );
			$this->trans_54 = __( '90 days', 'wpbooklist-textdomain' );
			$this->trans_55 = __( '180 days', 'wpbooklist-textdomain' );
			$this->trans_56 = __( '1 Year', 'wpbooklist-textdomain' );
			$this->trans_57 = __( 'Never Archive Comments', 'wpbooklist-textdomain' );
			$this->trans_58 = __( 'Delete Comments After...', 'wpbooklist-textdomain' );
			$this->trans_59 = __( '30 days', 'wpbooklist-textdomain' );
			$this->trans_60 = __( '60 days', 'wpbooklist-textdomain' );
			$this->trans_61 = __( '90 days', 'wpbooklist-textdomain' );
			$this->trans_62 = __( '180 days', 'wpbooklist-textdomain' );
			$this->trans_63 = __( '1 Year', 'wpbooklist-textdomain' );
			$this->trans_64 = __( 'Never Delete Comments', 'wpbooklist-textdomain' );
			$this->trans_65 = __( 'Thanks for your Rating & Comment!', 'wpbooklist-textdomain' );
			$this->trans_66 = __( 'Archived', 'wpbooklist-textdomain' );
			$this->trans_67 = __( 'You\'ve saved your settings!', 'wpbooklist-textdomain' );
			$this->trans_68 = __( 'Restrict Comments To...', 'wpbooklist-textdomain' );
			$this->trans_69 = __( 'Everyone', 'wpbooklist-textdomain' );
			$this->trans_70 = __( 'Logged-in Users Only', 'wpbooklist-textdomain' );
			$this->trans_71 = __( 'Log in to Comment', 'wpbooklist-textdomain' );
			$this->trans_72 = __( 'Username / E-Mail', 'wpbooklist-textdomain' );
			$this->trans_73 = __( 'Password', 'wpbooklist-textdomain' );
			$this->trans_74 = __( 'Log In', 'wpbooklist-textdomain' );
			$this->trans_75 = __( 'You\'re Logged in! The Page Will now Reload.' , 'wpbooklist-textdomain' );
			$this->trans_76 = __( 'Whoops! Looks Like your Username or Password were entered incorrectly! Please try again.' , 'wpbooklist-textdomain' );
			$this->trans_77 = __( 'Allow User Registration?', 'wpbooklist-textdomain' );
			$this->trans_78 = __( 'Yes', 'wpbooklist-textdomain' );
			$this->trans_79 = __( 'No', 'wpbooklist-textdomain' );
			$this->trans_80 = __( 'Not a User Yet? Register Below to Comment:', 'wpbooklist-textdomain' );
			$this->trans_81 = __( 'Enter E-Mail Address', 'wpbooklist-textdomain' );
			$this->trans_82 = __( 'Verify E-Mail Address', 'wpbooklist-textdomain' );
			$this->trans_83 = __( 'Enter Password', 'wpbooklist-textdomain' );
			$this->trans_84 = __( 'Verify Password', 'wpbooklist-textdomain' );
			$this->trans_85 = __( 'Register', 'wpbooklist-textdomain' );
			$this->trans_86 = __( 'Whoops! Looks like you forgot to enter an E-Mail Address!', 'wpbooklist-textdomain' );
			$this->trans_87 = __( 'Whoops! Looks like you forgot to verify your E-Mail Address! Please enter your E-Mail address again.', 'wpbooklist-textdomain' );
			$this->trans_88 = __( 'Whoops! Looks like you forgot to enter a password', 'wpbooklist-textdomain' );
			$this->trans_89 = __( 'Whoops! Looks like you forgot to verify your password! Please enter your password again.', 'wpbooklist-textdomain' );
			$this->trans_90 = __( 'Whoops! Looks like your E-Mail Addresses Don\'t match!', 'wpbooklist-textdomain' );
			$this->trans_91 = __( 'Whoops! Looks like your Passwords Don\'t match!', 'wpbooklist-textdomain' );
			$this->trans_92 = __( 'Whoops! Looks like there\'s already a registered user with this E-Mail address! Try using a different E-Mail Address.', 'wpbooklist-textdomain' );
			$this->trans_93 = __( 'You\'re now a Registered User! Reload the page to leave your Comment.', 'wpbooklist-textdomain' );
			$this->trans_94 = __( 'Thanks for your Rating & Comment! An Admin will review your submission shortly.', 'wpbooklist-textdomain' );

			// The array of translation strings.
			$translation_array = array(
				'trans1'  => $this->trans_1,
				'trans2'  => $this->trans_2,
				'trans3'  => $this->trans_3,
				'trans4'  => $this->trans_4,
				'trans5'  => $this->trans_5,
				'trans6'  => $this->trans_6,
				'trans7'  => $this->trans_7,
				'trans8'  => $this->trans_8,
				'trans9'  => $this->trans_9,
				'trans10' => $this->trans_10,
				'trans11' => $this->trans_11,
				'trans12' => $this->trans_12,
				'trans13' => $this->trans_13,
				'trans14' => $this->trans_14,
				'trans15' => $this->trans_15,
				'trans16' => $this->trans_16,
				'trans17' => $this->trans_17,
				'trans18' => $this->trans_18,
				'trans19' => $this->trans_19,
				'trans20' => $this->trans_20,
				'trans21' => $this->trans_21,
				'trans22' => $this->trans_22,
				'trans23' => $this->trans_23,
				'trans24' => $this->trans_24,
				'trans25' => $this->trans_25,
				'trans26' => $this->trans_26,
				'trans27' => $this->trans_27,
				'trans28' => $this->trans_28,
				'trans29' => $this->trans_29,
				'trans30' => $this->trans_30,
				'trans31' => $this->trans_31,
				'trans32' => $this->trans_32,
				'trans33' => $this->trans_33,
				'trans34' => $this->trans_34,
				'trans35' => $this->trans_35,
				'trans36' => $this->trans_36,
				'trans37' => $this->trans_37,
				'trans38' => $this->trans_38,
				'trans39' => $this->trans_39,
				'trans40' => $this->trans_40,
				'trans41' => $this->trans_41,
				'trans42' => $this->trans_42,
				'trans43' => $this->trans_43,
				'trans44' => $this->trans_44,
				'trans45' => $this->trans_45,
				'trans46' => $this->trans_46,
				'trans47' => $this->trans_47,
				'trans48' => $this->trans_48,
				'trans49' => $this->trans_49,
				'trans50' => $this->trans_50,
				'trans51' => $this->trans_51,
				'trans52' => $this->trans_52,
				'trans53' => $this->trans_53,
				'trans54' => $this->trans_54,
				'trans55' => $this->trans_55,
				'trans56' => $this->trans_56,
				'trans57' => $this->trans_57,
				'trans58' => $this->trans_58,
				'trans59' => $this->trans_59,
				'trans60' => $this->trans_60,
				'trans61' => $this->trans_61,
				'trans62' => $this->trans_62,
				'trans63' => $this->trans_63,
				'trans64' => $this->trans_64,
				'trans65' => $this->trans_65,
				'trans66' => $this->trans_66,
				'trans67' => $this->trans_67,
				'trans68' => $this->trans_68,
				'trans69' => $this->trans_69,
				'trans70' => $this->trans_70,
				'trans71' => $this->trans_71,
				'trans72' => $this->trans_72,
				'trans73' => $this->trans_73,
				'trans74' => $this->trans_74,
				'trans75' => $this->trans_75,
				'trans76' => $this->trans_76,
				'trans77' => $this->trans_77,
				'trans78' => $this->trans_78,
				'trans79' => $this->trans_79,
				'trans80' => $this->trans_80,
				'trans81' => $this->trans_81,
				'trans82' => $this->trans_82,
				'trans83' => $this->trans_83,
				'trans84' => $this->trans_84,
				'trans85' => $this->trans_85,
				'trans86' => $this->trans_86,
				'trans87' => $this->trans_87,
				'trans88' => $this->trans_88,
				'trans89' => $this->trans_89,
				'trans90' => $this->trans_90,
				'trans91' => $this->trans_91,
				'trans92' => $this->trans_92,
				'trans93' => $this->trans_93,
				'trans94' => $this->trans_94,
			);

			return $translation_array;
		}
	}
endif;
