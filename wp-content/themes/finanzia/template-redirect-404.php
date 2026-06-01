<?php
/**
 * Template name: Redirect 404 template
 */

global $wp_query;
$wp_query->set_404();
status_header( 404 );