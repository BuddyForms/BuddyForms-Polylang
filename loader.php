<?php

/**
 * Plugin Name: BuddyForms Polylang
 * Plugin URI: http://buddyforms.com/
 * Description: Multilingual Forms with Polylang
 * Version: 1.0
 * Author: Sven Lehnert
 * Author URI: https://profiles.wordpress.org/svenl77
 * License: GPLv2 or later
 * Network: false
 *
 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */

add_filter( 'buddyforms_user_posts_query_args', 'buddyforms_polylang_user_posts_query_args', 10, 1 );


function buddyforms_polylang_user_posts_query_args( $query_args ){

	$query_args['lang'] = '';

	return $query_args;
}

add_filter( 'buddyforms_form_hero_top', 'buddyforms_polylang_form_hero_top', 10, 2 );

function buddyforms_polylang_form_hero_top( $form_html, $form_slug ){

	global $post_id;

	echo $post_id . '<br>';

	$languages = pll_languages_list($post_id);
	echo 'All Registered Languages<br>';
	echo '<pre>';
	print_r($languages);
	echo '</pre>';
	echo 'current language: ' . pll_get_post_language( $post_id, 'slug' );


	$translations = pll_the_languages(array('raw'=>1));

	echo '<br>Translations<br>';
	foreach($languages as $language){

		echo $language . ' - ' . pll_get_post($post_id, $language) . ' <img src="' . $translations[$language]['flag'] . '"> <br>';

	}

	echo pll_translate_string('Svenson', 'en');

	return $form_html;

}

add_action('admin_init', 'register_form_fields');

function register_form_fields(){
	pll_register_string('buddyforms', 'Firmenname');
}
