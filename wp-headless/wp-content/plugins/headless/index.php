<?php
/**
 * Plugin Name:       My Headless Functions
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.0
 * Requires at least: 5.4
 * Requires PHP:      7.2
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my_headless
 * Domain Path:       /languages
 */

// Register Custom Post Type
function products_post_type() {

	$labels = array(
		'name'                  => _x( 'Products', 'Post Type General Name', 'my_headless' ),
		'singular_name'         => _x( 'Product', 'Post Type Singular Name', 'my_headless' ),
		'menu_name'             => __( 'Products', 'my_headless' ),
		'name_admin_bar'        => __( 'Product', 'my_headless' ),
		'archives'              => __( 'Product Archives', 'my_headless' ),
		'attributes'            => __( 'Product Attributes', 'my_headless' ),
		'parent_item_colon'     => __( 'Parent Product:', 'my_headless' ),
		'all_items'             => __( 'All Products', 'my_headless' ),
		'add_new_item'          => __( 'Add New Product', 'my_headless' ),
		'add_new'               => __( 'Add New Product', 'my_headless' ),
		'new_item'              => __( 'New Product', 'my_headless' ),
		'edit_item'             => __( 'Edit Product', 'my_headless' ),
		'update_item'           => __( 'Update Product', 'my_headless' ),
		'view_item'             => __( 'View Product', 'my_headless' ),
		'view_items'            => __( 'View Products', 'my_headless' ),
		'search_items'          => __( 'Search Product', 'my_headless' ),
		'not_found'             => __( 'Not found', 'my_headless' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'my_headless' ),
		'featured_image'        => __( 'Featured Image', 'my_headless' ),
		'set_featured_image'    => __( 'Set featured image', 'my_headless' ),
		'remove_featured_image' => __( 'Remove featured image', 'my_headless' ),
		'use_featured_image'    => __( 'Use as featured image', 'my_headless' ),
		'insert_into_item'      => __( 'Insert into Product', 'my_headless' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Product', 'my_headless' ),
		'items_list'            => __( 'Products list', 'my_headless' ),
		'items_list_navigation' => __( 'Products list navigation', 'my_headless' ),
		'filter_items_list'     => __( 'Filter Products list', 'my_headless' ),
	);
	$args = array(
		'label'                 => __( 'Product', 'my_headless' ),
		'description'           => __( 'Products CPT', 'my_headless' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'trackbacks', 'custom-fields' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	);
	register_post_type( 'product', $args );

}
add_action( 'init', 'products_post_type', 0 );