<?php
/**
 * Template for single pd_doc
 */

use J7\PowerDocs\Plugin;

get_header();

global $post;

Plugin::get($post->post_parent ? 'doc-detail' : 'doc-landing');

get_footer();
