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

/*
 * add  the lang attribute to the wp_dropdown_categories agrumenst array to maker sure only country specific terms get displayed
 */
add_filter( 'buddyforms_wp_dropdown_categories_args', 'buddyforms_polylang_wp_dropdown_categories_args', 10, 2 );
function buddyforms_polylang_wp_dropdown_categories_args( $args, $post_id ){
	global $buddyforms;
		$args['lang'] = pll_get_post_language( $post_id, 'slug' );
	return $args;
}

/*
 * Display all languages in the post list?
 */
add_filter( 'buddyforms_user_posts_query_args', 'buddyforms_polylang_user_posts_query_args', 10, 1 );
function buddyforms_polylang_user_posts_query_args( $query_args ){
	global $buddyforms;

	if( isset( $query_args['form_slug']['polylang']['view_all_languages'] ) ){
		$query_args['lang'] = '';
	}

	return $query_args;
}

/*
 * Add a current language and the language switcher to the head of the form.
 */
add_filter( 'buddyforms_form_hero_top', 'buddyforms_polylang_form_hero_top', 10, 2 );
function buddyforms_polylang_form_hero_top( $form_html, $form_slug ){
	global $post_id;

	$translations = pll_the_languages(array('raw'=>1));

	$tmp = __('Post Language: ', 'buddyforms' ) . '<img src="' . $translations[pll_get_post_language( $post_id, 'slug' )]['flag'] . '">';

	$languages = pll_languages_list($post_id);
	$tmp .= __(' Edit different language', 'buddyforms');
		foreach($languages as $language){

			if($language != pll_get_post_language( $post_id, 'slug' ) ){
				$tmp .= ' <img src="' . $translations[$language]['flag'] . '""> ' .apply_filters( 'buddyforms_loop_edit_post_link', buddyforms_get_edit_post_link( pll_get_post($post_id, $language) ), pll_get_post($post_id, $language) );
			}
		}

	return $form_html . $tmp;
}


/*
 * Add the country flag to the posts list
 */
add_action( 'buddyforms_the_loop_item_last', 'buddyforms_polylang_the_loop_item_last' );
function buddyforms_polylang_the_loop_item_last( $post_id ){
	$translations = pll_the_languages(array('raw'=>1));
	echo 'Language: <img src="' . $translations[pll_get_post_language( $post_id, 'slug' )]['flag'] . '">';
}

/*
 * Make the Form Labels Translatable
 */
add_action('admin_init', 'register_form_fields');
function register_form_fields(){
	global $buddyforms;

	if( is_array( $buddyforms ) ){
		foreach ( $buddyforms as $form_slug => $buddyform ){
			if( isset($buddyform['form_fields']) && is_array($buddyform['form_fields'])){
				foreach ( $buddyform['form_fields'] as $field ){
					pll_register_string( 'buddyforms', $field['name'], $form_slug );
					pll_register_string( 'buddyforms', $field['description'], $form_slug );
				}
			}

		}
	}

}

/*
 * Translate Form Fields Name and description
 */
add_filter( 'buddyforms_form_field_name', 'buddyforms_polylang_form_field_name', 10, 2 );
function buddyforms_polylang_form_field_name( $name, $post_id ){
	return pll_translate_string($name, pll_get_post_language( $post_id, 'slug' ));
}
add_filter( 'buddyforms_form_field_description', 'buddyforms_polylang_form_field_description', 10, 2 );
function buddyforms_polylang_form_field_description( $description, $post_id ){
	return pll_translate_string($description, pll_get_post_language( $post_id, 'slug' ));
}