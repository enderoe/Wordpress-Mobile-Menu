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



add_action('admin_menu', 'adminmenu_func');
function adminmenu_func(){
	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
	add_menu_page( 'Mobile Menu', 'Mobiele menu', 'manage_options', 'mobilemenu-plugin', 'adminmenu_func_init' );
}

function register_mysettings() {
	//register our settings
	register_setting( 'baw-settings-group', 'new_option_name' );
	register_setting( 'baw-settings-group', 'mobiele_menu' );
	register_setting( 'baw-settings-group', 'option_etc' );
}




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
</div>
<?php



/*?> 

		<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
						<ul class="dl-menu">
							<li>
								<a href="#">Fashion</a>
								<ul class="dl-submenu">
									<li>
										<a href="#">Men</a>
										<ul class="dl-submenu">
											<li><a href="#">Shirts</a></li>
											<li><a href="#">Jackets</a></li>
											<li><a href="#">Chinos &amp; Trousers</a></li>
											<li><a href="#">Jeans</a></li>
											<li><a href="#">T-Shirts</a></li>
											<li><a href="#">Underwear</a></li>
										</ul>
									</li>
									<li>
										<a href="#">Women</a>
										<ul class="dl-submenu">
											<li><a href="#">Jackets</a></li>
											<li><a href="#">Knits</a></li>
											<li><a href="#">Jeans</a></li>
											<li><a href="#">Dresses</a></li>
											<li><a href="#">Blouses</a></li>
											<li><a href="#">T-Shirts</a></li>
											<li><a href="#">Underwear</a></li>
										</ul>
									</li>
									<li>
										<a href="#">Children</a>
										<ul class="dl-submenu">
											<li><a href="#">Boys</a></li>
											<li><a href="#">Girls</a></li>
										</ul>
									</li>
								</ul>
							</li>
							<li>
								<a href="#">Electronics</a>
								<ul class="dl-submenu">
									<li><a href="#">Camera &amp; Photo</a></li>
									<li><a href="#">TV &amp; Home Cinema</a></li>
									<li><a href="#">Phones</a></li>
									<li><a href="#">PC &amp; Video Games</a></li>
								</ul>
							</li>
							<li>
								<a href="#">Furniture</a>
								<ul class="dl-submenu">
									<li>
										<a href="#">Living Room</a>
										<ul class="dl-submenu">
											<li><a href="#">Sofas &amp; Loveseats</a></li>
											<li><a href="#">Coffee &amp; Accent Tables</a></li>
											<li><a href="#">Chairs &amp; Recliners</a></li>
											<li><a href="#">Bookshelves</a></li>
										</ul>
									</li>
									<li>
										<a href="#">Bedroom</a>
										<ul class="dl-submenu">
											<li>
												<a href="#">Beds</a>
												<ul class="dl-submenu">
													<li><a href="#">Upholstered Beds</a></li>
													<li><a href="#">Divans</a></li>
													<li><a href="#">Metal Beds</a></li>
													<li><a href="#">Storage Beds</a></li>
													<li><a href="#">Wooden Beds</a></li>
													<li><a href="#">Children's Beds</a></li>
												</ul>
											</li>
											<li><a href="#">Bedroom Sets</a></li>
											<li><a href="#">Chests &amp; Dressers</a></li>
										</ul>
									</li>
									<li><a href="#">Home Office</a></li>
									<li><a href="#">Dining &amp; Bar</a></li>
									<li><a href="#">Patio</a></li>
								</ul>
							</li>
							<li>
								<a href="#">Jewelry &amp; Watches</a>
								<ul class="dl-submenu">
									<li><a href="#">Fine Jewelry</a></li>
									<li><a href="#">Fashion Jewelry</a></li>
									<li><a href="#">Watches</a></li>
									<li>
										<a href="#">Wedding Jewelry</a>
										<ul class="dl-submenu">
											<li><a href="#">Engagement Rings</a></li>
											<li><a href="#">Bridal Sets</a></li>
											<li><a href="#">Women's Wedding Bands</a></li>
											<li><a href="#">Men's Wedding Bands</a></li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
		
		</div><!--End of Wrapper -->
		
	
	
	<?php */
}	
		
add_action( 'wp_enqueue_scripts', 'my_plugin_enqueue' );
function my_plugin_enqueue() {
	    wp_enqueue_style( 'footertransparant',plugins_url('/social_brothers_mmenu/style.css'), '.css', NULL, NULL, 'all' );
		wp_enqueue_script('sbnavjs', plugins_url( 'jquery.dlmenu.js', __FILE__ ),array('jquery'), '1.1', true);
		wp_enqueue_script('sbscripts', plugins_url( 'scripts.js', __FILE__ ),array('jquery'), '1.1', true);
		wp_enqueue_script('sbmodernizrcustomjs', plugins_url( 'modernizr.custom.js', __FILE__ ),array(), false, false);
		
		

}

  ?>

