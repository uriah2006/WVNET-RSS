<?php

/*
Plugin Name: WVNET support widget
Plugin URI: http://wvnet.edu.com/wordpress-plugins/my-plugin
Description: A plugin to add posts from a support RSS feed to client dashboards and remove all default dashboard widgets
Version: 1.0
Author: Uriah Sypolt
Author URI: http://­mediaservices.wvnet.e­du/about-us/­uriah-sypolt
License: GPLv2
*/

add_action( 'wp_dashboard_setup', 'boj_dashboard_example_widgets' );

function boj_dashboard_example_widgets() {

	//create a custom dashboard widget
		wp_add_dashboard_widget( 'dashboard_custom_feed', 'WVNET Support Information',
			'boj_dashboard_example_display', 'boj_dashboard_example_setup' );
}

function boj_dashboard_example_setup() {

//check if option is set before saving
	if ( isset( $_POST['boj_rss_feed'] ) ) {
		//retrieve the option value from the form
		$boj_rss_feed = esc_url_raw( $_POST['boj_rss_feed'] );

		//save the value as an option
		update_option( 'boj_dashboard_widget_rss', $boj_rss_feed );
	}

	//load the saved feed if it exists
	$boj_rss_feed = get_option( 'boj_dashboard_widget_rss ');

	?>
	<label for="feed">
		RSS Feed URL: <input type="text" name="boj_rss_feed" id="boj_rss_feed"
			value="<?php echo esc_url( $boj_rss_feed ); ?>" size="50" />
	</label>
<?php
}

function boj_dashboard_example_display()
{
	//load our widget option
	$boj_option = get_option( 'boj_dashboard_widget_rss ');

	//if option is empty set a default
	$boj_rss_feed = ( $boj_option ) ? $boj_option : 'http://wordpress.org/news/feed/';

	//retrieve the RSS feed and display it
	echo '<div class="rss-widget">';

	wp_widget_rss_output( array(
		'url' => $boj_rss_feed,
        'title' => 'WVNET Support',
        'items' => 10,
        'show_summary' => 1,
        'show_author' => 0,
        'show_date' => 1
    ) );

	echo '</div>';
}

/* remove default dashboard widgets */

function remove_dashboard_widgets() {
	remove_meta_box( 'dashboard_right_now', 'dashboard-network', 'normal' );   // Right Now
	remove_meta_box( 'dashboard_recent_comments', 'dashboard-network', 'normal' ); // Recent Comments
	remove_meta_box( 'dashboard_incoming_links', 'dashboard-network', 'normal' );  // Incoming Links
	remove_meta_box( 'dashboard_plugins', 'dashboard-network', 'normal' );   // Plugins
	remove_meta_box( 'dashboard_quick_press', 'dashboard-network', 'side' );  // Quick Press
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard-network', 'side' );  // Recent Drafts
	remove_meta_box( 'dashboard_primary', 'dashboard-network', 'side' );   // WordPress blog
	remove_meta_box( 'dashboard_secondary', 'dashboard-network', 'side' );   // Other WordPress News
	// use 'dashboard-network' as the second parameter to remove widgets from a network dashboard.
}
add_action( 'wp_dashboard_setup', 'remove_dashboard_widgets' );

// Remove Yoast SEO Dashboard Widget
function remove_wpseo_dashboard_overview() {
remove_meta_box( 'wpseo-dashboard-overview', 'dashboard-network', 'side' );
}
add_action('wp_dashboard_setup', 'remove_wpseo_dashboard_overview' );

?>