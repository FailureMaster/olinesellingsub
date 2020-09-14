<?php
/**
 * Helper functions
 *
 * @package Happy_Addons Pro
 */
defined('ABSPATH') || die();

/**
 * Short Number Format
 * @param $n
 * @param int $precision
 * @return string
 */
function hapro_short_number_format($n, $precision = 1) {
	if ($n < 900) {
		// 0 - 900
		$n_format = number_format($n, $precision);
		$suffix = '';
	} else if ($n < 900000) {
		// 0.9k-850k
		$n_format = number_format($n / 1000, $precision);
		$suffix = 'K';
	} else if ($n < 900000000) {
		// 0.9m-850m
		$n_format = number_format($n / 1000000, $precision);
		$suffix = 'M';
	} else if ($n < 900000000000) {
		// 0.9b-850b
		$n_format = number_format($n / 1000000000, $precision);
		$suffix = 'B';
	} else {
		// 0.9t+
		$n_format = number_format($n / 1000000000000, $precision);
		$suffix = 'T';
	}
	// Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
	// Intentionally does not affect partials, eg "1.50" -> "1.50"
	if ($precision > 0) {
		$dotzero = '.' . str_repeat('0', $precision);
		$n_format = str_replace($dotzero, '', $n_format);
	}
	return $n_format . $suffix;
}

/**
 * Instagram Feed Ajax
 */
function hapro_instagram_feed_ajax() {

	$security = check_ajax_referer('happy_addons_pro_nonce', 'security');

	if (true == $security && isset($_POST['query_settings'])) :

		$settings = $_POST['query_settings'];
		$loaded_item = $_POST['loaded_item'];
		$item_tag = 'yes' == $settings['show_link'] ? 'a' : 'div';
		$href_target = '';
		$user_id = $settings['user_id'];
		$access_token = $settings['access_token'];
		$transient_key = 'happy_insta_feed_data' . str_replace('.', '_', $user_id).str_replace('.', '_', $access_token);
		$instagram_data = get_transient($transient_key);
		if (false === $instagram_data) {
			$url = 'https://graph.instagram.com/' . esc_html($user_id) . '/media?fields=caption,id,media_type,media_url,permalink,thumbnail_url,timestamp,username&limit=100&access_token=' . esc_html($access_token);
			$instagram_data = wp_remote_retrieve_body(wp_remote_get($url));
			set_transient($transient_key, $instagram_data, 10 * MINUTE_IN_SECONDS); //HOUR_IN_SECONDS
		}
		$instagram_data = json_decode($instagram_data, true);
		switch ($settings['sort_by']) {
			case 'old-posts':
				usort($instagram_data['data'], function ($a, $b) {
					if (strtotime($a['timestamp']) == strtotime($b['timestamp'])) return 0;
					return (strtotime($a['timestamp']) < strtotime($b['timestamp'])) ? -1 : 1;
				});
				break;
			default:
				$instagram_data['data'];
		}
		$instagram_data = array_splice($instagram_data['data'], $loaded_item, $settings['instagram_item']);
		?>
		<?php if ('ha-hover-info' == $settings['view_style']): ?>
		<?php foreach ($instagram_data as $key => $single): ?>
			<?php if ('yes' == $settings['show_link']) {
				$href_target = 'href="' . esc_url($single['permalink']) . '" ' . 'target="' . esc_attr($settings['link_target']) . '"';
			} ?>
			<<?php echo tag_escape($item_tag) . ' class="ha-insta-item loaded" ' . $href_target; ?>>
			<img src="<?php echo esc_url($single['media_url']); ?>" alt="">
			<div class="ha-insta-content">
				<?php if ('yes' == $settings['show_caption'] && !empty($single['caption'])): ?>
					<div class="ha-insta-caption">
						<p><?php echo esc_html($single['caption']); ?></p>
					</div>
				<?php endif; ?>
			</div>
			</<?php echo tag_escape($item_tag); ?>><!-- Item wrap End-->
		<?php endforeach; ?>
	<?php endif; ?>
		<?php if ('ha-hover-push' == $settings['view_style']): ?>
		<?php foreach ($instagram_data as $key => $single): ?>
			<?php if ('yes' == $settings['show_link']) {
				$href_target = 'href="' . esc_url($single['permalink']) . '" ' . 'target="' . esc_attr($settings['link_target']) . '"';
			} ?>
			<<?php echo tag_escape($item_tag) . ' class="ha-insta-item loaded" ' . $href_target; ?>>
			<img src="<?php echo esc_url($single['media_url']); ?>" alt="">
			<div class="ha-insta-likes-comments">
				<?php if ('yes' == $settings['show_caption'] && !empty($single['caption'])): ?>
					<div class="ha-insta-caption">
						<p><?php echo esc_html($single['caption']); ?></p>
					</div>
				<?php endif; ?>
			</div>
			</<?php echo tag_escape($item_tag); ?>>
		<?php endforeach; ?>
	<?php endif; ?>
		<?php if ('ha-feed-view' == $settings['view_style']): ?>
		<?php foreach ($instagram_data as $key => $single): ?>
			<div class="ha-insta-item loaded">
				<?php if ('yes' == $settings['show_user_picture'] || 'yes' == $settings['show_username'] || 'yes' == $settings['show_user_postdate'] || 'yes' == $settings['show_user_insta_icon']): ?>
					<div class="ha-insta-user-info">
						<?php if ('yes' == $settings['show_user_picture'] || 'yes' == $settings['show_username'] || 'yes' == $settings['show_user_postdate']): ?>
							<a class="ha-insta-user"
							   href="<?php echo esc_url('https://www.instagram.com/' . $single['username']); ?>"
							   target="_blank">
								<?php if ('yes' == $settings['show_user_picture'] && !empty($settings['profile_image'])): ?>
									<div class="ha-insta-user-profile-picture">
										<img src="<?php echo esc_url($settings['profile_image']); ?>" alt="">
									</div>
								<?php endif; ?>
								<div class="ha-insta-username-and-postdate">
									<?php if ('yes' == $settings['show_username'] && !empty($settings['user_name'])): ?>
										<span class="ha-insta-user-name"><?php echo esc_html($settings['user_name']) ?></span>
									<?php endif; ?>
									<?php if ('yes' == $settings['show_user_postdate']): ?>
										<span class="ha-insta-postdate"><?php echo esc_html(date("M d Y", strtotime($single['timestamp']))); ?></span>
									<?php endif; ?>
								</div>
							</a>
						<?php endif; ?>
						<?php if ('yes' == $settings['show_user_insta_icon']): ?>
							<a class="ha-insta-feed-icon" href="<?php echo esc_url($single['permalink']); ?>" target="_blank">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1"
								     x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32"
								     style="enable-background:new 0 0 32 32;" xml:space="preserve"><path
											d="M23,32H9c-5,0-9-4-9-9V9c0-5,4-9,9-9h14c5,0,9,4,9,9v14C32,28,28,32,23,32z M9,2C5.1,2,2,5.1,2,9v14c0,3.9,3.1,7,7,7h14  c3.9,0,7-3.1,7-7V9c0-3.9-3.1-7-7-7H9z"></path>
									<path d="M16,24.2c-4.5,0-8.2-3.7-8.2-8.2c0-4.5,3.7-8.2,8.2-8.2c4.5,0,8.2,3.7,8.2,8.2C24.2,20.5,20.5,24.2,16,24.2z M16,9.8  c-3.4,0-6.2,2.8-6.2,6.2s2.8,6.2,6.2,6.2s6.2-2.8,6.2-6.2S19.4,9.8,16,9.8z"></path>
									<circle cx="16" cy="16" r="1.9"></circle></svg>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<a class="ha-insta-image" href="<?php echo esc_url($single['permalink']); ?>" target="_blank">
					<img src="<?php echo esc_url($single['media_url']); ?>" alt="">
				</a>
				<?php if (('yes' == $settings['show_caption']) && !empty($single['caption'])): ?>
					<div class="ha-insta-content">
						<div class="ha-insta-caption">
							<p><?php echo esc_html($single['caption']); ?></p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php
	endif;
	wp_die();
}

add_action('wp_ajax_ha_instagram_feed_action', 'hapro_instagram_feed_ajax');
add_action('wp_ajax_nopriv_ha_instagram_feed_action', 'hapro_instagram_feed_ajax');

/**
 * Check license validity
 *
 * @return bool
 */
function hapro_has_valid_license() {
	return true;
}

/**
 * Contain masking shape list
 * @param $element
 * @return array
 */
function hapro_masking_shape_list( $element ) {
	$dir = HAPPY_ADDONS_PRO_ASSETS . 'imgs/masking-shape/';
	$shape_name = 'shape';
	$extension = '.svg';
	$list = [];
	if ( 'list' == $element ) {
		for ($i = 1; $i <= 39; $i++) {
			$list[$shape_name.$i] = [
				'title' => ucwords($shape_name.' '.$i),
				'url' => $dir . $shape_name . $i . $extension,
			];
		}
	} elseif ( 'url' == $element ) {
		for ($i = 1; $i <= 39; $i++) {
			$list[$shape_name.$i] = $dir . $shape_name . $i . $extension;
		}
	}
	return $list;
}


/**
 * Compare value.
 *
 * Compare two values based on Comparison operator
 *
 * @param mixed $left_value  First value to compare.
 * @param mixed $right_value  Second value to compare.
 * @param string $operator  Comparison operator.
 * @return bool
 */
function hapro_compare( $left_value, $right_value, $operator ) {
	switch ( $operator ) {
		case 'is':
			return $left_value == $right_value;
		case 'not':
			return $left_value != $right_value;
		default:
			return $left_value === $right_value;
	}
}

/**
 * Get User Browser name
 *
 * @param $user_agent
 * @return string
 */
function hapro_get_browser_name ( $user_agent ) {

	if ( strpos( $user_agent, 'Opera' ) || strpos( $user_agent, 'OPR/' ) ) return 'opera';
	elseif ( strpos( $user_agent, 'Edge' ) ) return 'edge';
	elseif ( strpos( $user_agent, 'Chrome' ) ) return 'chrome';
	elseif ( strpos( $user_agent, 'Safari' ) ) return 'safari';
	elseif ( strpos( $user_agent, 'Firefox' ) ) return 'firefox';
	elseif ( strpos( $user_agent, 'MSIE' ) || strpos( $user_agent, 'Trident/7' ) ) return 'ie';
	return 'other';
}

/**
 * Get Client Site Time
 * @param string $format
 * @return string
 */
function hapro_get_local_time ( $format = 'Y-m-d h:i:s A' ) {
	$local_time_zone = isset($_COOKIE['HappyLocalTimeZone']) && !empty($_COOKIE['HappyLocalTimeZone'])? str_replace('GMT ','GMT+',$_COOKIE['HappyLocalTimeZone']): date_default_timezone_get();
	$now_date = new \DateTime('now',new \DateTimeZone( $local_time_zone ) );
	$today = $now_date->format($format);
	return $today;
}

/**
 * Get Server Time
 * @param string $format
 * @return string
 */
function hapro_get_server_time ( $format = 'Y-m-d h:i:s A' ) {
	$today 	= date( $format , strtotime("now") + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) );
	return $today;
}

/**
 * Check elementor version
 *
 * @param string $version
 * @param string $operator
 * @return bool
 */
function hapro_is_elementor_version( $operator = '>=', $version = '2.8.0' ) {
	return defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, $version, $operator );
}

/**
 * Get the list of all section templates
 *
 * @return array
 */
function hapro_get_section_templates() {
	$items = ha_elementor()->templates_manager->get_source( 'local' )->get_items( ['type' => 'section'] );

	if ( ! empty( $items ) ) {
		$items = wp_list_pluck( $items, 'title', 'template_id' );
		return $items;
	}

	return [];
}

if ( ! function_exists( 'ha_get_section_icon' ) ) {
	/**
	 * Get happy addons icon for panel section heading
	 *
	 * @return string
	 */
	function ha_get_section_icon() {
		return '<i style="float: right" class="hm hm-happyaddons"></i>';
	}
}


/**
 * Facebook Feed ajax call
 *
 * @return array
 */
function ha_facebook_feed_ajax() {

	$security = check_ajax_referer('happy_addons_pro_nonce', 'security');

	if ( true == $security && isset( $_POST['query_settings'] ) ) :
		$settings = $_POST['query_settings'];
		$loaded_item = $_POST['loaded_item'];

		$ha_facebook_feed_cash = '_' . $settings['widget_id'] . '_facebook_cash';
		$transient_key = $settings['page_id'] . $ha_facebook_feed_cash;
		$facebook_feed_data = get_transient($transient_key);

		if ( false === $facebook_feed_data ) {
			$url_queries = 'fields=status_type,created_time,from,message,story,full_picture,permalink_url,attachments.limit(1){type,media_type,title,description,unshimmed_url},comments.summary(total_count),reactions.summary(total_count)';
			$url = "https://graph.facebook.com/v4.0/{$settings['page_id']}/posts?{$url_queries}&access_token={$settings['access_token']}";
			$data = wp_remote_get( $url );
			$facebook_feed_data = json_decode( wp_remote_retrieve_body( $data ), true );
			set_transient( $transient_key, $facebook_feed_data, 0 );
		}
		if ( $settings['remove_cash'] == 'yes' ) {
			delete_transient( $transient_key );
		}

		switch ($settings['sort_by']) {
			case 'old-posts':
				usort($facebook_feed_data['data'], function ($a,$b) {
					if ( strtotime($a['created_time']) == strtotime($b['created_time']) ) return 0;
					return ( strtotime($a['created_time']) < strtotime($b['created_time']) ? -1 : 1 );
				});
				break;
			case 'like_count':
				usort($facebook_feed_data['data'], function ($a,$b){
					if ($a['reactions']['summary'] == $b['reactions']['summary']) return 0;
					return ($a['reactions']['summary'] > $b['reactions']['summary']) ? -1 : 1 ;
				});
				break;
			case 'comment_count':
				usort($facebook_feed_data['data'], function ($a,$b){
					if ($a['comments']['summary'] == $b['comments']['summary']) return 0;
					return ($a['comments']['summary'] > $b['comments']['summary']) ? -1 : 1 ;
				});
				break;
			default:
				$facebook_feed_data;
		}


		$items = array_splice($facebook_feed_data['data'], $loaded_item, $settings['post_limit'] );

		foreach ($items as $item) :

			$page_url = "https://facebook.com/{$item['from']['id']}";
			$avatar_url = "https://graph.facebook.com/v4.0/{{$item['from']['id']}/picture";

			$description = explode( ' ', $item['message'] );
			if ( !empty( $settings['description_word_count'] ) && count( $description ) > $settings['description_word_count'] ) {
				$description_shorten = array_slice( $description, 0, $settings['description_word_count'] );
				$description = implode( ' ', $description_shorten ) . '...';
			} else {
				$description = $item['message'];
			}
			?>

			<div class="ha-facebook-item">

				<?php if ( $settings['show_feature_image'] == 'yes' && !empty( $item['full_picture'] ) ) : ?>
					<div class="ha-facebook-feed-feature-image">
						<a href="<?php echo esc_url( $item['permalink_url'] ); ?>" target="_blank">
							<img src="<?php echo esc_url( $item['full_picture'] ); ?>" alt="<?php esc_url( $item['from']['name'] ); ?>">
						</a>
					</div>
				<?php endif ?>

				<div class="ha-facebook-inner-wrapper">

					<?php if ( $settings['show_facebook_logo'] == 'yes' ) : ?>
						<div class="ha-facebook-feed-icon">
							<i class="fa fa-facebook-square"></i>
						</div>
					<?php endif; ?>

					<div class="ha-facebook-author">
						<?php if ( $settings['show_user_image'] == 'yes' ) : ?>
							<a href="<?php echo esc_url( $page_url ); ?>">
								<img
									src="<?php echo esc_url( $avatar_url ); ?>"
									alt="<?php echo esc_attr( $item['from']['name'] ); ?>"
									class="ha-facebook-avatar"
								>
							</a>
						<?php endif; ?>

						<div class="ha-facebook-user">
							<?php if ( $settings['show_name'] == 'yes' ) : ?>
								<a href="<?php echo esc_url( $page_url ); ?>" class="ha-facebook-author-name">
									<?php echo esc_html( $item['from']['name'] ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>

					<div class="ha-facebook-content">
						<p>
							<?php
							echo esc_html( $description );
							if ( $settings['read_more'] == 'yes' ) :
								?>
								<a href="<?php echo esc_url( $item['permalink_url'] ); ?>" target="_blank">
									<?php echo esc_html( $settings['read_more_text'] ); ?>
								</a>
							<?php endif; ?>
						</p>

						<?php if ( $settings['show_date'] == 'yes' ) : ?>
							<div class="ha-facebook-date">
								<?php echo esc_html( date("M d Y", strtotime( $item['created_time'] ) ) ); ?>
							</div>
						<?php endif; ?>
					</div>

				</div>

				<?php if ( $settings['show_likes'] == 'yes' || $settings['show_comments'] == 'yes' ) : ?>
					<div class="ha-facebook-footer-wrapper">
						<div class="ha-facebook-footer">

							<div class="ha-facebook-meta">
								<?php if ( $settings['show_likes'] == 'yes' ) : ?>
									<div class="ha-facebook-likes">
										<?php echo esc_html( $item['reactions']['summary']['total_count'] ); ?>
										<i class="fa fa-thumbs-up"></i>
									</div>
								<?php endif; ?>

								<?php if ( $settings['show_comments'] == 'yes' ) : ?>
									<div class="ha-facebook-comments">
										<?php echo esc_html( $item['comments']['summary']['total_count'] ); ?>
										<i class="fa fa-comment"></i>
									</div>
								<?php endif; ?>
							</div>

						</div>
					</div>
				<?php endif; ?>

			</div>

		<?php
		endforeach;

	endif;
	wp_die();
}
add_action( 'wp_ajax_ha_facebook_feed_action', 'ha_facebook_feed_ajax' );
add_action( 'wp_ajax_nopriv_ha_facebook_feed_action', 'ha_facebook_feed_ajax' );
