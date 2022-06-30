<?php
/*
Plugin Name:  Reading Wednesday
Description:  Getting book link and thumbnail front cover for Reading Wednesday posts
Version:      1.0
Author:       nicm42
Author URI:   https://www.nicm42.co.uk
*/

/*
Code from https://www.milessebesta.com/web-design/how-to-publish-google-sheets-data-to-a-wordpress-site/?cn-reloaded=1
How to use shortcodes from https://www.wp-tweaks.com/display-a-single-cell-from-google-sheets-wordpress/
*/

function get_book_data($atts) {
  wp_enqueue_style( 'reading_wednesday', plugin_dir_url(__FILE__) . 'reading_wednesday.css' );

  $title = $atts['title'];
  $author = $atts['author'];
  $data = false;
  $link = "https://openlibrary.org/search.json?q=$title&author=$author";
  $response = wp_remote_get(esc_url_raw($link));
  $data = json_decode(wp_remote_retrieve_body($response), true);
  $isbn = $data[docs][0][isbn][0];
  $cover = "<img src='https://covers.openlibrary.org/b/isbn/$isbn-S.jpg' alt=''>";
  $bookLink = "<a href='https://openlibrary.org/isbn/$isbn'>$title by $author</a>";

  # Only show image if it's got an ISBN
  # If link doesn't have an ISBN then show the search instead
  if (!$isbn) {
    $cover = "";
    $bookLink = "<a href='https://openlibrary.org/search?q=$title&author=$author'>$title by $author</a>";
  }

  return "<span class='book'>$cover $bookLink</span>";
}

add_shortcode('book_data', 'get_book_data');

# Shortcode example: [book_data title="Dirk Gently" author="Douglas Adams"]
?>
