<?php
/**
 * Plugin Name:  WP Arena Dashboard Feed
 * Plugin URI: 	 https://wparena.com/
 * Description:  A very simple plugin to grab latest news, tutorials, reviews and more from WPArena.com right inside your WordPress Dashboard. The number of posts displayed, and the category of posts displayed, can both be configured.
 *               It is based on the 'WPCandy Dashboard Feed' plugin by WPCandy, but has been adapted to show feeds from WPArena.com instead.
 * Version: 	 1.1
 * Author: 	 Jazib
 * Author URI: 	 https://wparena.com
 * License: 	 GPL2
 * License 	 URI: http://www.gnu.org/licenses/gpl-2.0.html
**/


// Creates the custom dashboard feed RSS box
function wparena_dashboard_custom_feed_output() {
	
	$widget_options = wparena_dashboard_options();
	
	// Variable for RSS feed
	$wparena_feed = 'https://wparena.com/feed/';			
	
	echo '<div class="rss-widget" id="wparena-rss-widget">';
		wp_widget_rss_output(array(
			'url' => $wparena_feed,
			'title' => 'Latest Posts from WP Arena',
			'items' => $widget_options['posts_number'],
			'show_summary' => 0,
			'show_author' => 0,
			'show_date' => 0
		));
	echo "</div>";
}


// Function used in the action hook
function wparena_add_dashboard_widgets() {	
	wp_add_dashboard_widget('wparena_dashboard_custom_feed', 'Latest Posts from WPArena.com', 'wparena_dashboard_custom_feed_output', 'wparena_dashboard_setup' );
}


function wparena_dashboard_options() {	
	$defaults = array( 'posts_number' => 5 );
	if ( ( !$options = get_option( 'wparena_dashboard_custom_feed' ) ) || !is_array($options) )
		$options = array();
	return array_merge( $defaults, $options );
}


function wparena_dashboard_setup() {
 
	$options = wparena_dashboard_options();
 
	if ( 'post' == strtolower($_SERVER['REQUEST_METHOD']) && isset( $_POST['widget_id'] ) && 'wparena_dashboard_custom_feed' == $_POST['widget_id'] ) {
		foreach ( array( 'posts_number', 'posts_feed' ) as $key )
				$options[$key] = $_POST[$key];
		update_option( 'wparena_dashboard_custom_feed', $options );
	}
 
?>
 
		<p>
			<label for="posts_number"><?php _e('How many items?', 'wparena_dashboard_custom_feed' ); ?>
				<select id="posts_number" name="posts_number">
					<?php for ( $i = 5; $i <= 10; $i = $i + 1 )
						echo "<option value='$i'" . ( $options['posts_number'] == $i ? " selected='selected'" : '' ) . ">$i</option>";
						?>
					</select>
				</label>
 		</p>

 
<?php
 }


// Register the new dashboard widget into the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'wparena_add_dashboard_widgets' );

?>