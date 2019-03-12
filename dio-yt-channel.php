<?php
/**
* Plugin Name: MM-YT Channel
* Plugin URI: https://github.com/LordMachacon/yt-plugin
* Description: Plugin para canal de Youtube.
* Version: 1.0 En desarrollo
* Author: Manuel Machacon Cantillo
* Author URI: https://github.com/LordMachacon
**/

add_action('admin_menu', 'menu_dio_yt');
add_action('admin_init', 'dio_yt_settings');


function dio_yt_settings(){
	register_setting('dio_yt_settings', 'dio-yt-api-key');
	register_setting('dio_yt_settings', 'dio-yt-channel-id');
	register_setting('dio_yt_settings', 'dio-yt-max-videos');

}

function menu_dio_yt(){
	add_menu_page('DIO YT Channel','DIO YT Channel', 'manage_options', __FILE__, 'dio_yt_admin_page');
}

function dio_yt_admin_page(){
	?>
		<div class="wrap">
		  <h1>DIO YT Channel</h1>
		  <p></p>
		  <form action="options.php" method="post">
		  
		  	 <?php settings_fields( 'dio_yt_settings' ); ?>
    		 <?php do_settings_sections( 'dio_yt_settings' ); ?>

		  YouTube API Key: <input style="width : 320px" type="text" name="dio-yt-api-key" value="<?php echo get_option('dio-yt-api-key'); ?>">
		  		<a target="_blank" href="https://developers.google.com/youtube/registering_an_application?hl=es-419">¿Cómo obtener la API Key?</a>
		  <br />
		  YouTube Channel ID: <input style="width : 320px" name="dio-yt-channel-id" type="text" value="<?php echo get_option('dio-yt-channel-id'); ?>" />
		  		<a target="_blank" href="https://support.google.com/youtube/answer/3250431?hl=es-419">¿Cómo obtener el ID de tu canal?</a>
		  <br />
		   Número máximo de videos: <input style="width : 320px" name="dio-yt-max-videos" type="number" max="50" min="1" value="<?php echo get_option('dio-yt-max-videos'); ?>" />
		  <br />
		 
		  <?php submit_button(); ?>
		  </form>
		</div>
	<?php
}


function get_dio_yt_player(){
	
	$API_key = get_option('dio-yt-api-key');
	$channel_id = get_option('dio-yt-channel-id');

	$max_results = get_option('dio-yt-max-videos');

	$video_list = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.$channel_id.'&maxResults='.$max_results.'&key='.$API_key.''));


	?>
	
	<div class="yt-video-container">
		<div class="yt-player-container">
			<?php
			echo '<iframe  src="https://youtube.com/embed/'.$video_list->items[0]->id->videoId.'" frameborder="0" allowfullscreen data-video-ready width="100%"></iframe>';
			?>
		</div>
		<div class="yt-list-container">
			<ul style="list-style: none">
				<?php
				foreach($video_list->items as $item):
					echo "<li onclick='start_player(this)' yt_video_link='".$item->id->videoId."'>";
					if(isset($item->id->videoId)):
						echo '<div class="yt-img-container"><img class="yt-thumbnail" src="'.$item->snippet->thumbnails->medium->url.'" ></div>';
						echo '<div class="yt-title-container">
								<p class="yt-list-title">'.$item->snippet->title.'</p>
							</div>';
						
					endif;
					echo '</li>';
				endforeach;
				?>
			</ul>
		</div><!-- yt-list-container-->
	</div><!-- yt-video-container -->
	

	<?php
}

function dio_yt_register_shortcode(){
	add_shortcode('dio-yt-player', 'get_dio_yt_player');
}

add_action( 'init', 'dio_yt_register_shortcode');

function dio_yt_add_scripts(){
	wp_register_script( 'dio_yt_script', plugins_url( 'js/dio_script.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'dio_yt_script' );
 
	wp_enqueue_style( 'dio_yt_my_styles', plugins_url( 'css/dio_styles.css', __FILE__ ), '', '1.0' );
}

add_action( 'wp_enqueue_scripts', 'dio_yt_add_scripts' );
