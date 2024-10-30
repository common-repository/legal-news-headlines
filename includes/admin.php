<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * admin.php - widget admin settings form
 *
 * @package Legal News Headlines
 * @subpackage includes
 * @author 
 */
?>
<a href="http://www.lawyers.com" target="_blank" class="legal_news_headlines_admin_ldc"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/250x150v3.jpg';?>" class="legal_news_headlines_admin_headerimage"/></a>
<p>
     <label for="<?php print $this->get_field_id('widget_title'); ?>">
          <?php _e('Display name for the widget:', 'legal_news_headlines'); ?>
     </label>
     <input id="<?php print $this->get_field_id('widget_title'); ?>" name="<?php print $this->get_field_name('widget_title'); ?>" class="legal_news_headlines_admin_title" type="text" value="<?php print $instance['widget_title']; ?>" />
</p>

<h3><?php _e('Legal Headline Sources', 'legal_news_headlines'); ?></h3>
<p>
     <label for="<?php print $this->get_field_id('legal_feed_items'); ?>"><?php _e('Show up to ', 'legal_news_headlines'); ?></label>
     <select name="<?php print $this->get_field_name('legal_feed_items'); ?>" id="<?php print $this->get_field_id('legal_feed_items'); ?>">
          <?php
          for ( $i = 5; $i <= 20; $i++ ) print "<option value='$i' " . selected($instance['legal_feed_items'], $i, true) . ">$i</option>";
          ?>
     </select>
     <?php _e('items from each of these legal feed sources:', 'legal_news_headlines'); ?><BR>
     
     <?php
          for ( $i = 0; $i < count($this->legal_feeds); $i++ ) {
          	$field='enable_feed_'.$i;
          	$fieldname=$this->get_field_name($field);
          	$fieldid=$this->get_field_id($field);
          	$out='<input type="checkbox" value="1" name="'.$fieldname.'" id="'.$fieldid.'" '.checked($instance[$field], true, false).'/>';
          	$out.='<label for="'.$fieldid.'">';
            $out.=' <a href="'.$this->legal_feeds[$i]['url'].'" target="_blank">'.__($this->legal_feeds[$i]['name'], 'legal_news_headlines').'</a></label><BR>';
			echo $out;
          }
     ?>
     
</p>

<h3><?php _e('Your Feed', 'legal_news_headlines'); ?></h3>
<p>
     <label for="<?php print $this->get_field_id('blog_feed_items'); ?>"><?php _e('Show up to', 'legal_news_headlines'); ?></label>
     <select name="<?php print $this->get_field_name('blog_feed_items'); ?>" id="<?php print $this->get_field_id('blog_feed_items'); ?>">
          <?php
          for ( $i = 0; $i <= 15; $i++ ) print "<option value='$i' " . selected($instance['blog_feed_items'], $i, false) . ">$i</option>";
          ?>
     </select>
     <label for="<?php print $this->get_field_id('blog_feed_url'); ?>">
          <?php _e('items from your feed:', 'legal_news_headlines'); ?>
     </label>
     <input id="<?php print $this->get_field_id('blog_feed_url'); ?>" name="<?php print $this->get_field_name('blog_feed_url'); ?>" class="legal_news_headlines_admin_blogurl" type="text" value="<?php print $instance['blog_feed_url']; ?>" />
</p>

<h3><?php _e('General Settings', 'legal_news_headlines'); ?></h3>

<p>
     <input name="<?php print $this->get_field_name('show_excerpt'); ?>" type="checkbox" id="<?php print $this->get_field_id('show_excerpt'); ?>" value="1" <?php checked($instance['show_excerpt'], true, true); ?> />
     <label for="<?php print $this->get_field_id('show_excerpt'); ?>">
          <?php _e('Show a content excerpt of', 'legal_news_headlines'); ?>
     </label>
     <input size="4" id="<?php print $this->get_field_id('show_excerpt_characters'); ?>" name="<?php print $this->get_field_name('show_excerpt_characters'); ?>" type="text" value="<?php print $instance['show_excerpt_characters']; ?>" />

     <label for="<?php print $this->get_field_id('show_excerpt_characters'); ?>">
          <?php _e('characters.', 'legal_news_headlines'); ?>
     </label>
</p>
<p>
     <label for="<?php print $this->get_field_id('link_target'); ?>">
          <?php _e('Open links in:', 'legal_news_headlines'); ?>
     </label>
     <select name="<?php print $this->get_field_name('link_target'); ?>" id="<?php print $this->get_field_id('link_target'); ?>">
          <option value="_blank" <?php selected($instance['link_target'], '_blank', true); ?>><?php _e('New Window', 'legal_news_headlines'); ?></option>
          <option value="_top" <?php selected($instance['link_target'], '_top', true); ?>><?php _e('Current Window', 'legal_news_headlines'); ?></option>
     </select>
</p>

<h3><?php _e('Cache Settings', 'legal_news_headlines'); ?></h3>
<p>
     <input type="checkbox" value="1" name="<?php print $this->get_field_name('cache_enable'); ?>" id="<?php print $this->get_field_id('cache_enable'); ?>" <?php checked($instance['cache_enable'], true, true); ?> />
     <label for="<?php print $this->get_field_id('cache_enable'); ?>">
          <?php _e('Enable Cache?', 'legal_news_headlines'); ?>
     </label>
</p>
<p>
     <label for="<?php print $this->get_field_id('cache_duration'); ?>">
          <?php _e('Cache Duration (seconds)', 'legal_news_headlines'); ?>
     </label>
     <input size="4" id="<?php print $this->get_field_id('cache_duration'); ?>" name="<?php print $this->get_field_name('cache_duration'); ?>" type="text" value="<?php print $instance['cache_duration']; ?>" />
	<?php _e('seconds', 'legal_news_headlines'); ?>.
</p>
