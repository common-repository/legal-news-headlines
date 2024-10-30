<?php
/*
  Plugin Name: Legal News Headlines
  Plugin URI: 
  Description: Widget that dipslays legal news RSS feed titles from Lawyers.com, along with news feeds from the installed site
  Author: Lawyers.com
  Version: 1.0
  Author URI: http://www.lawyers.com
 */
include_once(ABSPATH . WPINC . '/class-simplepie.php');

/* Class Declaration */

class legal_news_headlines_widget extends WP_Widget{

	var $cachekey = "legal_news_headlines";
	var $tracking_code = array(
							'WT.mc_id' 	=> 'WordPress_LegalNews_Widget',
							'cid'		=> 'soc:103'
						);
	var $legal_feeds = 	array(
							array('name'=>'Current Events', 'url' => 'http://blogs.lawyers.com/category/current-events/feed/'),
							array('name'=>'Bankruptcy', 'url' => 'http://blogs.lawyers.com/tag/bankruptcy/feed/'),
							array('name'=>'Consumer Law', 'url'  => 'http://blogs.lawyers.com/category/consumer-law/feed/'),
							array('name'=>'Criminal', 'url'  => 'http://blogs.lawyers.com/category/criminal/feed/'),
							array('name'=>'Estate Planning', 'url'  => 'http://blogs.lawyers.com/category/estate-planning-2/feed/'),
							array('name'=>'Family Law', 'url'  => 'http://blogs.lawyers.com/category/family-law/feed/'),
							array('name'=>'Gay and Lesbian Issues', 'url'  => 'http://blogs.lawyers.com/category/gay-and-lesbian-issues/feed/'),
							array('name'=>'Gun Laws', 'url'  => 'http://blogs.lawyers.com/tag/gun-laws/feed/'),
							array('name'=>'Immigration', 'url'  => 'http://blogs.lawyers.com/category/immigration/feed/'),
							array('name'=>'Internet Law', 'url'  => 'http://blogs.lawyers.com/category/internet-law/feed/'),
							array('name'=>'Labor and Employment', 'url'  => 'http://blogs.lawyers.com/category/labor-and-employment/feed/'),
							array('name'=>'Marijuana', 'url'  => 'http://blogs.lawyers.com/tag/marijuana/feed/'),
							array('name'=>'Personal Injury', 'url'  => 'http://blogs.lawyers.com/category/personal-injury-2/feed/'),
							array('name'=>'Real Estate', 'url'  => 'http://blogs.lawyers.com/category/real-estate/feed/'),			
							array('name'=>'Small Business Law', 'url'  => 'http://blogs.lawyers.com/category/small-business-law/feed/')
	);
        
	var $defaults = 	array(
               				'widget_title' => 'Legal News Headlines',
               				'blog_feed_items' => 0, // 0-15
               				'blog_feed_url' => '',
               				'show_excerpt' => true,
               				'show_excerpt_characters' => 100,
               				'link_target_type' => '_blank',  // _blank or _top
               				'cache_enable' => true,
               				'cache_duration' => 3600,  // seconds
               				'legal_feed_items' => 20,
               				'enable_feed_0' => true,  // we could build this programmatically in the constructor, but it is a  manual
							'enable_feed_1' => true,  // process to add feeds above, anyway, so it doesn't really matter
							'enable_feed_2' => true,
							'enable_feed_3' => true,
							'enable_feed_4' => true,
							'enable_feed_5' => true,
							'enable_feed_6' => true,
							'enable_feed_7' => true,
							'enable_feed_8' => true,
							'enable_feed_9' => true,
							'enable_feed_10' => true,
							'enable_feed_11' => true,
							'enable_feed_12' => true,
							'enable_feed_13' => true,
							'enable_feed_14' => true
						);
	
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
            
        load_plugin_textdomain( 'legal_news_headlines', false, plugin_dir_path( __FILE__ ) . '/lang/' );
                
        // translate the text in the defaults array into the locale
        $this->defaults['widget_title']=__('Legal News Headlines');
        
        /* insert this blog's default feed URL into the defaults array...to find this, I'm using
         * guidance found at:  http://codex.wordpress.org/WordPress_Feeds
         */
        $site_url=get_site_url();
        // add the ?feed=rss2, but do it reliably...we don't know what the site_url really looks like
		$parsed_url = parse_url($site_url);
		parse_str($parsed_url['query'], $query);
		$query['feed'] .= 'rss2';
		
		$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
		$host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
		$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
		$user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
		$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
		$pass     = ($user || $pass) ? "$pass@" : '';
		$path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
		$query    = '?' . http_build_query($query);
		$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
		$this->defaults['blog_feed_url']="$scheme$user$pass$host$port$path$query$fragment";
        
        
		parent::__construct(
	 		'legal_news_headlines', // Base ID
			'Legal News Headlines', // Name
			array( 'description' => __( 'Legal News Headlines from Lawyers.com')), // widget options
			array() // control options are not used by us right now
		);
		
		add_action('wp_enqueue_scripts', array(&$this, 'add_js'));
		
	}

	
	/*
	 * add tracking code to the feed links
	 */
	function add_tracking_code ($url) {

		$parsed_url = parse_url($url);
		parse_str($parsed_url['query'], $query);
		// add our tracking code back in...
		$query=array_merge($query,$this->tracking_code);
		
		$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
		$host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
		$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
		$user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
		$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
		$pass     = ($user || $pass) ? "$pass@" : '';
		$path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
		$query    = '?' . http_build_query($query);
		$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
		$returned_url="$scheme$user$pass$host$port$path$query$fragment";
		
		return($returned_url);
	}
	
	function add_js () {
		if ( is_active_widget(false, false, $this->id_base, true) ) {
			// enqueue our scroller script for the frontend
			wp_enqueue_script('legal_news_headlines_js', plugin_dir_url( __FILE__ ).'js/legal-news-headlines.js', array('jquery'));
			wp_enqueue_style('legal_news_headlines_css', plugin_dir_url( __FILE__ ).'includes/legal-news-headlines.css');
		}
	}
	
	/**
 	* Outputs the content of the widget.
 	*
 	* @args The array of form elements
  	* @instance
 	*/
	function widget( $args, $instance ) {
		

		extract( $args, EXTR_SKIP );
		echo $before_widget;
    
        $widget_title = empty($instance['widget_title']) ? '' : apply_filters('widget_title', $instance['widget_title']);
        
        // Rationalize our cache versus the built-in Wordpress-wide cache for rss feeds and SimplePie objects
        // we either disable the cache entirely, or we turn set it to our cache_duration setting
        $global_cache_duration=0;
        if ($instance['cache_enable']===false) {
        	$global_cache_duration=0;
        } else {
        	$global_cache_duration=$instance['cache_duration'];
        }
        
        $newfunc=create_function('$a', 'return '.$global_cache_duration.';');
        
        add_filter( 'wp_feed_cache_transient_lifetime' , $newfunc);
        
        if (($instance['cache_enable']===false) || (false === ($headline_results = get_transient($this->cachekey)))) {
        	echo '<!--fresh data--->';
        	// do a fresh retrieval if the cache is disabled or if we get a false from our get_transient inquiry
        	$headline_results=$this->fetch_rss_results($instance);
        	// now if we have sufficient data, save into cache
        	if (($instance['cache_enable']===true) && (is_array($headline_results))) {
        		// to avoid issues w/ incomplete objects, we need to serialize our data BEFORE we cache it and unserialize after
        		set_transient($this->cachekey, $headline_results, $instance['cache_duration']);
        	}

        } else {
        	echo '<!--cached data-->';
        }
        
        // we should now have what we need in $headline_results, format is assoc array, with array sub-elements 
        
		// Display the widget
		include( plugin_dir_path(__FILE__) . '/includes/widget.php' );
		
		echo $after_widget;
		
		// remove our temporary cache setter filter
		remove_filter( 'wp_feed_cache_transient_lifetime' , $newfunc);
		
		
	}
	
	/**
	 * Fetches the latest headlines
	 *
	 * @instance The instance of values to be generated via the update.
	 * returns a multidim array w/ the results
	 */
	function fetch_rss_results($instance) {
		// results structure is an array of associative arrays, with components title, description, link, date, and type (blog_feed or legal_feed_x)
		// in order legal feed stuff, then our blog stuff 
		$results=array();
		
		// legal feeds
		for ($i=0; $i<count($this->legal_feeds); $i++) {
			$varname='enable_feed_'.$i;
			if ($instance[$varname]===true) {
				$rss = fetch_feed($this->legal_feeds[$i]['url']);
				if (!is_wp_error($rss)) { 
					// Figure out how many total items there are, but limit it to the configured amount.
					$maxitems = $rss->get_item_quantity($instance['legal_feed_items']);
					// Build an array of all the items, starting with element 0 (first element).
					$rss_items = $rss->get_items(0, $maxitems);
					foreach ($rss_items as $item) {
						$results[]=array('title'=>$item->get_title(), 'description'=>$item->get_description(),'link'=>$this->add_tracking_code($item->get_permalink()),'date'=>$item->get_date('j F Y H:s'),'feed'=>'legal_feed_'.$i);
					}
				}
			}
		}
		
		// do we try to get our own rss feed?
		if ($instance['blog_feed_items']>0 && !empty($instance['blog_feed_url'])) {
			$rss = fetch_feed($instance['blog_feed_url']);
			if (!is_wp_error($rss)) {
				// Figure out how many total items there are, but limit it to the configured amount.
				$maxitems = $rss->get_item_quantity($instance['blog_feed_items']);
				// Build an array of all the items, starting with element 0 (first element).
				$rss_items = $rss->get_items(0, $maxitems);
				foreach ($rss_items as $item) {
					$results[]=array('title'=>$item->get_title(), 'description'=>$item->get_description(),'link'=>$item->get_permalink(),'date'=>$item->get_date('j F Y H:s'),'feed'=>'blog_feed');
				}				
			}
		}
		

		echo '<!-- '.count($results).' total entries pulled -->';
		return($results);
	}
	
 	/**
  	* Processes the widget's options to be saved.
  	*
  	* @new_instance The new instance of values to be generated via the update.
  	* @old_instance The previous instance of values before the update.
  	*/
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
        delete_transient( $this->cachekey );

  
        // validate # of blog items
        $blog_items=(int)$new_instance['blog_feed_items'];
        if ($blog_items<0) { 
        	$blog_items=0;
        }
        if ($blog_items>5) {
        	$blog_items=5;
        }
        
        // TODO: validate excerpt_characters
        
        // TODO: validate cache duration
        
        // TODO: validate blog_feed_url
        
        $instance['widget_title']=strip_tags($new_instance['widget_title']);
		$instance['blog_feed_items']=$blog_items;
		$instance['blog_feed_url']=$new_instance['blog_feed_url'];
		$instance['show_excerpt']=isset($new_instance['show_excerpt']);
		$instance['show_excerpt_characters']=(int)$new_instance['show_excerpt_characters'];
		
		if (isset($new_instance['link_target_type'])) {
			if (strcmp($new_instance['link_target_type'],'_top')==0) {
				$instance['link_target_type']='_top';
			} else {
				$instance['link_target_type']='_blank';
			}
		} else {
			// shouldn't happen
			$instance['link_target_type']=$this->defaults['link_target_type'];
		}
		
		$instance['cache_enable']=isset($new_instance['cache_enable']);
		$instance['cache_duration']=(int)$new_instance['cache_duration'];
		$instance['legal_feed_items']=(int)$new_instance['legal_feed_items'];
		for ( $i = 0; $i < count($this->legal_feeds); $i++ ) {
			$label="enable_feed_".$i;
			$instance[$label]=isset($new_instance[$label]);
		}
		
		return $instance;
	} 

  	/**
  	* Generates the administration form for the widget.
  	*
  	* @instance The array of keys and values for the widget.
  	*/
	function form($instance) {
		// merge incoming arguments w/ our defaults...
		$instance = wp_parse_args((array) $instance, $this->defaults);
	
		// Display the admin form
        include( plugin_dir_path(__FILE__) . '/includes/admin.php' );
		
	} 
	
	// truncate helper function, adapted from http://snippets.jc21.com/snippets/php/truncate-a-long-string-and-add-ellipsis/
	function truncate($string, $length) {
		//truncates a string to a certain char length, stopping on a word boundary
		if (strlen($string) > $length) {
			//limit hit!
			$string = substr($string,0,$length);
			//stop on a word.
			$string = substr($string,0,strrpos($string,' ')).'&hellip;';
		}
		return $string;
	}
	
	// shows rss headline (a SimplePie object)
	function show_headline($rss_headline, $link_target_type, $show_description, $description_length) {
		$output='<li class="legal_news_headlines_listitem"><a class="legal_news_headlines_link" href="'.$rss_headline['link'].'" target="'.$link_target_type.'">'.$rss_headline['title'].'</a>';
		if ($show_description && ($description_length>0)) {
			$output.='<span class="legal_news_headlines_excerpt">';
			$output.=$this->truncate($rss_headline['description'],$description_length);
			$output.='</span>';
		}
		$output.='<!--posted: '.$rss_headline['date'].' feed: '.$rss_headline['feed'].'-->';
		$output.='</li>';
		echo $output;
	}	

}

/* hook callback
 * 
 */
function legal_news_load_admin_scripts ($hook) {
	if ($hook=='widgets.php') {
		// only load on the widgets appearance page
		wp_enqueue_script('legal_news_headlines_admin_js', plugin_dir_url( __FILE__ ).'js/legal-news-headlines-admin.js', array('jquery'));
		wp_enqueue_style('legal_news_headlines_admin_css', plugin_dir_url( __FILE__ ).'includes/legal-news-headlines-admin.css');
	}
}

add_action( 'widgets_init', create_function( '', 'register_widget("legal_news_headlines_widget");' ) ); 
add_action('admin_enqueue_scripts', 'legal_news_load_admin_scripts');

?>