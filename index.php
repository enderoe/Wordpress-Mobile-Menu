<?php
/**
 * Plugin Name: Social Brothers Mobile Menu plugin
 * Plugin URI: http://socialbrothers.nl
 * Description: This plugin allows the creation of mobile menu's
 * Version: 1.0
 * Author: Andrew Ho
 * Author URI: http://socialbrothers.nl
 * License: Social Brothers VOF
 */
 
// include( plugin_dir_path( __FILE__ ) . '/widget_docent_uitnodigen.php');

  
  // Register Script
// Hook into the 'wp_enqueue_scripts' action



add_action('admin_menu', 'sbmadminmenu_func');
function sbmadminmenu_func(){
	//call register settings function
	add_action( 'admin_init', 'register_sbmenusettings' );
	add_menu_page( 'Mobile Menu', 'Mobiele menu', 'manage_options', 'mobilemenu-plugin', 'adminmenu_func_init' );
}

function register_sbmenusettings() {
	//register our settings
	register_setting( 'baw-settings-group', 'menu_color' );
	register_setting( 'baw-settings-group', 'mobiele_menu' );
	register_setting( 'baw-settings-group', 'menu_logo' );
	register_setting( 'baw-settings-group', 'menu_logoswitch' );

}

//MEDIA LIBRARY INLADEN
function wp_gear_manager_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_enqueue_script('jquery');
}

function wp_gear_manager_admin_styles() {
wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'wp_gear_manager_admin_scripts');
add_action('admin_print_styles', 'wp_gear_manager_admin_styles');


//COLOR PICKER INLADEN
add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );
function mw_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('my-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}


//ANDERE DINGEN
function adminmenu_func_init(){
//echo "Hello World! ";
$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
//echo esc_attr( get_option('mobiele_menu') );

?>
<div class="wrap">
<h2>SB Mobile Menu</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'baw-settings-group' ); ?>
    <?php do_settings_sections( 'baw-settings-group' ); ?>
    <table class="form-table">
        <!--<tr valign="top">
        <th scope="row">New Option Name</th>
        <td><input type="text" name="new_option_name" value="<?php echo esc_attr( get_option('new_option_name') ); ?>" /></td>
        </tr>-->
         
        <tr valign="top">
        <th scope="row">Selecteer een menu</th>
        <td>
        	<select name="mobiele_menu">
        		<?php 
        		foreach ( $menus as $menu ) { ?>

						<option value="<?php echo $menu->term_id; ?>"<?php if($menu->term_id == esc_attr( get_option('mobiele_menu') ) ) { echo "selected";} ?>><?php echo $menu->name ?></option>
			<?php	}
        		
        		?>        		
        	</select>
        </tr>

		<tr>
			<th scope="row">Logo gebruiken</th>
			<td>
				<input type="radio" name="menu_logoswitch" value="1" <?php if(1 == esc_attr( get_option('menu_logoswitch') ) ) { echo "checked";} ?>> Ja<BR>
				<input type="radio" name="menu_logoswitch" value="0" <?php if(0 == esc_attr( get_option('menu_logoswitch') ) ) { echo "checked";} ?>> Nee
			</td>
		</tr>



        <script language="JavaScript">
		jQuery(document).ready(function() {
		jQuery('#upload_image_button').click(function() {
		formfield = jQuery('#upload_image').attr('name');
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		return false;
		});
		
		window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		jQuery('#upload_image').val(imgurl);
		tb_remove();
		}
		
		});
		</script>
		
		<tr valign="top">
			<th scope="row">Upload afbeelding</th>
			<td><label for="upload_image">
				<input id="upload_image" type="text" size="36" name="menu_logo" value="<?php echo esc_attr( get_option('menu_logo') ) ?>" />
				<input id="upload_image_button" type="button" value="Kies of Upload een afbeelding" />
				<br />Geef een url op of upload een logo voor in het menu
				</label>
			</td>
		</tr>
		
		
		<tr valign="top">
			<th scope="row">Achtergrond kleur</th>
			<td>
				<input type="text" value="<?php echo esc_attr( get_option('menu_color')); ?>" class="my-color-field" name="menu_color" />
			</td>
		</tr>
        <script language="JavaScript">
			jQuery(document).ready(function($){
			    $('.my-color-field').wpColorPicker();
			});
		</script>
        <!--
        <tr valign="top">
        <th scope="row">Options, Etc.</th>
        <td><input type="text" name="option_etc" value="<?php echo esc_attr( get_option('option_etc') ); ?>" /></td>
        </tr>-->
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php


}

function sbmenu_init() {
	add_action('wp_footer', 'sbmob_menufunc', 100);
} 

add_action('init', 'sbmenu_init');


function sbmob_menufunc() {
	
$args = array(
        'order'                  => 'ASC',
        'orderby'                => 'menu_order',
        'post_type'              => 'nav_menu_item',
        'post_status'            => 'publish',
        'output'                 => ARRAY_A,
        'output_key'             => 'menu_order',
        'nopaging'               => true,
        'update_post_term_cache' => false );
$items = wp_get_nav_menu_items(esc_attr( get_option('mobiele_menu') )); 

$logoswitch = esc_attr( get_option('menu_logoswitch') );
$achtergrondkleur = esc_attr( get_option('menu_color') );
$logourl = esc_attr( get_option('menu_logo') );

//print_r($items);

foreach($items as $item){
		if(!$item->menu_item_parent){
			//Menu item
			$menuitems[] = $item;
		} else {
			//Submenu item
			$submenuitems[] = $item;
		}
	}

?>
<style>
	.dl-menuwrapper { 
		<?php if($achtergrondkleur != ""){ ?>
			background: <?php echo $achtergrondkleur; ?> !important;
		<?php } ?>
	}
	.dl-menuwrapper button {
		<?php if($achtergrondkleur != ""){ ?>
			background: <?php echo $achtergrondkleur; ?> !important;
		<?php } ?>
	}
</style>

<div id="dl-menu" class="dl-menuwrapper">
<button class="dl-trigger">Open Menu</button>
	<ul class="dl-menu">
			
	<?php
		foreach($menuitems as $menuitem){
			echo '<li>';
			echo '<a href="' . $menuitem->url . '">' . $menuitem->title . '</a>';
			unset($dezesubmenuitems);
		    foreach ($submenuitems as $submenuitem) {
				if($submenuitem->menu_item_parent == $menuitem->ID){
					//is submenuitem van huidig hoofdmenu
					$dezesubmenuitems[] = $submenuitem;
				}
		    }
			if(isset($dezesubmenuitems)){
				echo '<ul class="dl-submenu">';
				foreach($dezesubmenuitems as $submenuitem){
					echo '<li><a href="' . $submenuitem->url . '">' . $submenuitem->title . '</a></li>';
				}
				echo '</ul>';
			}		
			echo '</li>';
		}
	?>

	</ul>
	<?php
		if($logoswitch == 1){
			?>
			<a href="<?php echo get_home_url(); ?>">
				<img src="<?php echo $logourl;  ?>" class="mobielmenulogo">
			</a>
			<?php
		}
	?>
</div>
<?php


}	
		
add_action( 'wp_enqueue_scripts', 'my_mmenu_sb_styles' );
function my_mmenu_sb_styles() {
	    wp_enqueue_style( 'footertransparant',plugins_url('/social_brothers_mmenu/style.css'), '.css', NULL, NULL, 'all' );
		wp_enqueue_script('sbnavjs', plugins_url( 'jquery.dlmenu.js', __FILE__ ),array('jquery'), '1.1', true);
		wp_enqueue_script('sbscripts', plugins_url( 'scripts.js', __FILE__ ),array('jquery'), '1.1', true);
		wp_enqueue_script('sbmodernizrcustomjs', plugins_url( 'modernizr.custom.js', __FILE__ ),array(), false, false);
		
		

}

  ?>

