<?php
/*
 * Plugin Name: Hidden Tags
 * Version: 0.1.1
 * Plugin URI: http://atastypixel.com/blog/wordpress/plugins/hidden-tags
 * Description: Hide particular tags
 * Author: Michael Tyson
 * Author URI: http://atastypixel.com/blog
 */


/**
 * Filter for terms (tags/categories)
 *
 *  Filters out hidden tags/categories
 *
 * @param List of tags/categories
 * @return Filtered tags/categories
 * @author Michael Tyson
 * @package Hidden Tags
 * @since 0.1
 */
function hidden_tags_get_terms($terms) {
    global $user_ID;
    if ( $user_ID && get_option('hidden_tags_visibility','admin') == 'admin' ) {
        // Logged in - show all terms
        return $terms;
    }
    
    // Trim out hidden terms
    $term_array = preg_split('/\s*,\s*/', strtolower(get_option('hidden_tags')));
    $terms_out = array();
    foreach ( $terms as $term ) {
        if ( !in_array($term->slug, $term_array) ) {
            $terms_out[] = $term;
        }
    }

    return $terms_out;
}


/**
 * Filter for terms for a given object
 *
 *  Filters out hidden tags/categories
 *
 * @param List of tags/categories
 * @return Filtered tags/categories
 * @author Michael Tyson
 * @package Hidden Tags
 * @since 0.1
 */
function hidden_tags_get_object_terms($terms, $object_ids, $taxonomies) {
    return hidden_tags_get_terms($terms);
}





// =======================
// =       Options       =
// =======================

/**
 * Settings page
 *
 * @author Michael Tyson
 * @package Hidden Tags
 * @since 0.1
 **/
function hidden_tags_options_page() {
    ?>
	<div class="wrap">
	<h2>Hidden Tags</h2>
	
	<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
	
	<table class="form-table">

		<tr valign="top">
    		<th scope="row"><?php _e('Hidden Tags:') ?></th>
    		<td>
    			<input type="text" id="hidden_tags" name="hidden_tags" value="<?php echo get_option('hidden_tags') ?>" size="100" /><br />
    			<?php echo _e('Separate multiple tags with commas', 'hidden-tags'); ?>
    		</td>
    	</tr>
	
		<tr valign="top">
    		<th scope="row"><?php _e('Visibility:') ?></th>
    		<td>
    			<input type="radio" id="hidden_tags_visibility_admin" name="hidden_tags_visibility" value="admin" <?php echo (get_option('hidden_tags_visibility','admin')=='admin' ? 'checked="checked"' : '') ?> /> <label for="hidden_tags_visibility_admin">Hidden tags are visible to logged-in users</label><br />
    			<input type="radio" id="hidden_tags_visibility_noone" name="hidden_tags_visibility" value="noone" <?php echo (get_option('hidden_tags_visibility','admin')=='noone' ? 'checked="checked"' : '') ?> /> <label for="hidden_tags_visibility_noone">Hidden tags are visible to no-one</label>
    		</td>
    	</tr>
	
	</table>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="hidden_tags, hidden_tags_visibility" />
	
	<p class="submit">
	<input type="submit" name="Submit" value="<?php _e('Save Changes', 'hidden-tags') ?>" />
	</p>
	
	</form>
	</div>
	<?php
}

/**
 * Set up administration
 *
 * @author Michael Tyson
 * @package Hidden Tags
 * @since 0.1
 */
function hidden_tags_setup_admin() {
	add_options_page( 'Hidden Tags', 'Hidden Tags', 5, __FILE__, 'hidden_tags_options_page' );
}

add_filter( 'get_terms', 'hidden_tags_get_terms' );
add_filter( 'wp_get_object_terms', 'hidden_tags_get_object_terms', 10, 3 );
add_action( 'admin_menu', 'hidden_tags_setup_admin' );
add_option( 'hidden_tags', '' );

?>