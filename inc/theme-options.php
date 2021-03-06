<?php
add_option('protean_theme_options', $original_theme_style);
add_option('protean_theme_presets', $original_preset);

add_action( 'admin_init', 'protean_theme_options_init' );
add_action( 'admin_menu', 'protean_theme_options_add_page' );

$content_width = 580;

// Load up the menu page
function protean_theme_options_add_page() {
	add_theme_page( __( 'Protean Options','protean' ), __( 'Protean Options','protean' ), 'edit_theme_options', 'theme_options', 'protean_theme_options_do_page' );
}
// Init plugin options to white list our options
function protean_theme_options_init(){
	register_setting( 'protean_options', 'protean_theme_options', 'protean_theme_options_validate' );
	if(isset($_GET['page']) && $_GET['page']=='theme_options'){
		// for color picker
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-resizable');
		wp_enqueue_script('jquery_colorpicker', get_template_directory_uri().'/js/colorpicker/js/colorpicker.js' );
		wp_enqueue_style('jquery_colorpicker', get_template_directory_uri().'/js/colorpicker/css/colorpicker.css' );
		
		// for background image selection
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('my-upload');
		wp_enqueue_style('thickbox');
		
		// custom javascript and stylesheet
		wp_enqueue_style('protean_admin', get_template_directory_uri().'/css/admin.css' );
		
		wp_enqueue_script('file_manager', get_template_directory_uri().'/js/file_manager.js' );
		wp_enqueue_script('protean_theme_option', get_template_directory_uri().'/js/theme_option.js' );
		wp_enqueue_script('protean_page_style', get_template_directory_uri().'/js/page_style.js' );
		
		// to include font stylesheets
		$options = get_option('protean_theme_options');
		if(isset($options['fonts'])){
			$fonts = $options['fonts'];
			foreach($fonts as $f){
				wp_enqueue_style($f,GOOGLE_FONT_URL.$f);
			}
		}
	}
}
// show theme options form
function protean_theme_options_do_page() { 
	if ( ! isset( $_REQUEST['settings-updated'] ) )$_REQUEST['settings-updated'] = false;
	wp_tiny_mce( false );
?>
<div class="wrap">
	<h2><?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options','protean' ) . "</h2>"; ?></h2>
	
	<?php if ( false !== $_REQUEST['settings-updated'] ){ ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved' , 'protean' ); ?></strong></p></div>
	<?php } ?>
	
	<?php $options = get_option( 'protean_theme_options' ); ?>
	<form method="post" action="options.php"> 
	<?php settings_fields( 'protean_options' ); ?>
	<div class="metabox-holder">
		<div class="postbox">
			<h3 class="hndle"><span>Protean: default</span></h3>
			<div class="inside">
				<?php $paramname="protean_theme_options"; ?>
				<?php include(get_template_directory().'/inc/page-style.php') ?>
			</div>
		</div>
		<div class="postbox">
			<h3 class="hndle"><span>Protean: options</span></h3>
			<div class="inside">
				<table class="protean_form_table" cellspacing="2" cellpadding="5">
					<tbody>
						<tr>
							<th valign="top" scope="row"><label for="link_image"><strong>Header:</label></th>
							<td>
								<input type="radio" name="protean_theme_options[header]" value="tagline" id="header_tagline" 
								<?php if(isset($options['header']))checked($options['header'],'tagline'); ?> /> 
								<label for="header_tagline"><img src="<?php echo get_template_directory_uri() ?>/images/text.gif" alt="text" class="protean_option_image"  /> 
								Site title and tagline (see <a href="/wp-admin/options-general.php">General Settings</a>)</label>
								<p><input type="radio" name="protean_theme_options[header]" value="emblem" id="header_emblem" 
								<?php if(isset($options['header']))checked($options['header'],'emblem'); ?> /> 
								<label for="header_emblem"><img src="<?php echo get_template_directory_uri() ?>/images/emble.gif" alt="emble" class="protean_option_image"  /> 
								Emblematic</label></p>
							</td>
						</tr>
						<tr>
							<td colspan="2"><hr/></td>
						</tr>
						<tr>
							<th valign="top" scope="row"><label for="link_notes"><strong>Footer:</strong></label></th>
							<td>
								<div>
								<input type="hidden" name="protean_theme_options[showabout]" value="0" />
								<input type="checkbox" name="protean_theme_options[showabout]" id="protean_footer_showabout" 
								<?php if(isset($options['showabout']))checked($options['showabout'],1); ?> value="1" />
								<label for="protean_footer_showabout"> Enable Protean footer with About message:</label>
								</div>
								<div id="protean_about_textbox">
									<textarea id="protean_footer_about" class="protean_footer_about" class="large-text" cols="30" rows="5" name="protean_theme_options[aboutblog]"><?php if(isset($options['aboutblog']))echo stripslashes($options['aboutblog']); ?></textarea>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2"><hr/></td>
						</tr>
						<tr>
							<th valign="top" scope="row"><label for="link_image"><strong>Top Menu:</label></th>
							<td>
								<input type="radio" id="enable_menu" name="protean_theme_options[menu]" value="1" 
								<?php if(isset($options['menu']))checked($options['menu'],1); ?> /> 
								<label for="enable_menu"> Show menu</label>
								<input type="radio" id="disable_menu" name="protean_theme_options[menu]" value="0" 
								<?php if(!isset($options['menu']) ||   $options['menu']=='0')echo 'checked="checked"' ?> /> 
								<label for="disable_menu"> Hide menu</label>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="postbox">
			<h3 class="hndle"><span>Protean: font library - Powered by Google Web Fonts</span></h3>
			<div class="inside">
				<ul id="protean_font_table">
					<?php echo protean_font_manage() ?>
				</ul>
				<div id="protean_font_add">
					<table class="protean_form_table" cellspacing="2" cellpadding="5">
						<tbody>
							<tr class="form-field">
								<th valign="top" scope="row"><label for="link_image"><strong>Add font:</label></th>
								<td>
								<input type="text" placeholder="Cabin+Sketch:bold" id="protean_new_font" style="display:inline;width:300px;" />
								<button id="protean_add_font" class="button-secondary">Add</button>
								<p>Enter an API parameter name from <a href="http://www.google.com/webfonts" target="_blank">Google Web Font [&#x279A;]</a></p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<p><input type="submit" name="Submit" class="button-primary" value="Save Changes" /></p>
	</form>
</div>
<?php
//print_r($options);
}
// validate input. Accepts an array, return an array.
function protean_theme_options_validate( $input ) {

	$input['font'] 			= esc_attr( $input['font'] );
	$input['fontsize'] 		= esc_attr( $input['fontsize'] );
	$input['color'] 		= esc_attr( $input['color'] );
	$input['text'] 			= esc_attr( $input['text'] );
	$input['link'] 			= esc_attr( $input['link'] );
	$input['hover'] 		= esc_attr( $input['hover'] );
	$input['primary_color'] = esc_attr( $input['primary_color'] );
	$input['primary_text'] 	= esc_attr( $input['primary_text'] );
	$input['primary_link'] 	= esc_attr( $input['primary_link'] );
	$input['primary_hover'] = esc_attr( $input['primary_hover'] );
	$input['accent_color'] 	= esc_attr( $input['accent_color'] );
	$input['accent_text'] 	= esc_attr( $input['accent_text'] );
	$input['accent_link'] 	= esc_attr( $input['accent_link'] );
	$input['accent_hover'] 	= esc_attr( $input['accent_hover'] );
	$input['accent_color'] 	= esc_attr( $input['accent_color'] );
	$input['accent_text'] 	= esc_attr( $input['accent_text'] );
	$input['accent_link'] 	= esc_attr( $input['accent_link'] );
	$input['accent_hover'] 	= esc_attr( $input['accent_hover'] );
	$input['bgimage'] 		= esc_url( $input['bgimage'] );
	$input['preset'] 		= esc_attr( $input['preset'] );
	$input['header'] 		= esc_attr( $input['header'] );
	$input['showabout'] 	= esc_attr( $input['showabout'] );
	$input['aboutblog'] 	= wp_kses_post( $input['aboutblog'] );
	$input['menu'] 			= esc_attr( $input['menu'] );
	
	foreach($input['fonts'] as $f){
		$fonts[] = esc_attr($f);
	}
	$input['fonts'] = $fonts;
	return $input;
}
?>