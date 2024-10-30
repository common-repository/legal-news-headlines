<?php 
echo '<div class="legal_news_headlines_block">';

if(strlen(trim($widget_title)) > 0) {
	echo '<h3 class="widget-title legal_news_headlines_title">'.$widget_title.'</h3>';
}

if(isset($headline_results) && is_array($headline_results)){

	echo '<div class="legal_news_headlines_list_container"><ul class="legal_news_headlines_list">';
    
	foreach($headline_results as $headline) {
		$this->show_headline($headline,$instance['link_target_type'],$instance['show_excerpt'],$instance['show_excerpt_characters']);
	}
	       
    echo '</ul></div>';
}

echo '</div>';

?>