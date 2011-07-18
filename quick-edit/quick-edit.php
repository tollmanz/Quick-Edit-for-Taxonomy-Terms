<?php
/**
 Plugin Name: WP Tuts 
 Plugin URI: #
 Description: Test center for the tutorial  
 Version: 1.0
*/


function my_taxonomies() 
{
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name' => _x('Presidents', 'taxonomy general name', 'my_plugin'),
		'singular_name' => _x('President', 'taxonomy singular name', 'my_plugin'),
		'search_items' =>  __('Search Presidents', 'my_plugin'),
		'all_items' => __('All Presidents', 'my_plugin'),
		'parent_item' => __('Parent President', 'my_plugin'),
		'parent_item_colon' => __('Parent President:', 'my_plugin'),
		'edit_item' => __('Edit President', 'my_plugin'), 
		'update_item' => __('Update President', 'my_plugin'),
		'add_new_item' => __('Add New President', 'my_plugin'),
		'new_item_name' => __('New President Name', 'my_plugin'),
		'menu_name' => __('Presidents', 'my_plugin'),
	); 	

	register_taxonomy(
		'president',
		array('post'), 
		array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 
				'slug' => 'president'
			)
		)
	);
}
add_action('init', 'my_taxonomies', 10, 1);

// Adds columns for taxonomy pages
function my_column_header($columns)
{
	$columns['start-date'] = __('Start Date');
	$columns['end-date'] = __('End Date');
	return $columns;
}
add_filter('manage_edit-president_columns', 'my_column_header', 10, 1);

function my_column_value($empty = '', $custom_column, $term_id) 
{
	return esc_html(get_term_meta($term_id, $custom_column, true));		
}
add_filter('manage_president_custom_column', 'my_column_value', 10, 3);

// Set up form elements in quick edit
function my_quick_edit_custom_box($column_name, $screen, $name)
{	
	// Only add fields in the right context
	if($name != 'president' && ($column_name != 'start-date' || $column_name != 'end-date')) return false;
?>
	<fieldset>
		<div id="my-custom-content" class="inline-edit-col">
			<label>
				<span class="title"><?php if($column_name == 'start-date') _e('Start Date'); else _e('End Date'); ?></span>
				<span class="input-text-wrap"><input type="text" name="<?php echo $column_name; ?>" class="ptitle" value=""></span>
			</label>
		</div>
	</fieldset>
<?php
}
add_action('quick_edit_custom_box', 'my_quick_edit_custom_box', 10, 3);

// Save data on submit
function my_save_term_meta($term_id)
{
	$allowed_html = array(
		'b' => array(),
		'em' => array (), 
		'i' => array (),
		'strike' => array(),
		'strong' => array(),
	);
    if(isset($_POST['start-date'])) 
		update_term_meta($term_id, 'start-date', wp_kses($_POST['start-date'], $allowed_html)); 
	if(isset($_POST['end-date'])) 
		update_term_meta($term_id, 'end-date', wp_kses($_POST['end-date'], $allowed_html)); 	
}
add_action('edited_president', 'my_save_term_meta', 10, 1);

// Add JS
function my_add_admin_scripts()
{
	global $pagenow;
	
	// Only enqueue JS if on the category taxonomy page
	if($pagenow == 'edit-tags.php' && (isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'president'))
	{
		// Register JS
		wp_register_script(
			'quick-edit-js',
			plugins_url('/js/quick-edit.js', __FILE__),
			array('jquery')
		);
		wp_enqueue_script('quick-edit-js');
	}
}
add_action('admin_enqueue_scripts', 'my_add_admin_scripts');
?>