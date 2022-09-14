<?php

if ( !is_admin() )
{
    print 'Direct access not allowed.';
    exit;
}

// Required scripts
require_once CP_CALCULATEDFIELDSF_BASE_PATH.'/inc/cpcff_templates.inc.php';

check_admin_referer( 'cff-form-settings', '_cpcff_nonce' );

// Load resources
wp_enqueue_media();
if(function_exists('wp_enqueue_code_editor')) wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
wp_enqueue_style('cff-chosen-css', plugins_url('/vendors/chosen/chosen.min.css', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH));
wp_enqueue_script('cff-chosen-js', plugins_url('/vendors/chosen/chosen.jquery.min.js', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH),array("jquery"));

if (!defined('CP_CALCULATEDFIELDSF_ID'))
    define ('CP_CALCULATEDFIELDSF_ID',intval($_GET["cal"]));

$cpcff_main = CPCFF_MAIN::instance();
$form_obj = $cpcff_main->get_form(intval($_GET["cal"]));

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['cpcff_revision_to_apply'] ) )
{
	$revision_id = @intval($_POST['cpcff_revision_to_apply']);
	if($revision_id)
	{
		$form_obj->apply_revision($revision_id);
	}
}

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['cp_calculatedfieldsf_post_options'] ) )
    echo "<div id='setting-error-settings_updated' class='updated settings-error'> <p><strong>".__( 'Settings saved', 'calculated-fields-form' )."</strong></p></div>";

global $cpcff_default_texts_array;
$cpcff_texts_array = $form_obj->get_option( 'vs_all_texts', $cpcff_default_texts_array );
$cpcff_texts_array = CPCFF_AUXILIARY::array_replace_recursive(
        $cpcff_default_texts_array,
        ( is_string( $cpcff_texts_array ) && is_array( unserialize( $cpcff_texts_array ) ) )
            ? unserialize( $cpcff_texts_array )
            : ( ( is_array( $cpcff_texts_array ) ) ? $cpcff_texts_array : array() )
    );

?>
<div class="wrap">
<h1 class="cff-form-name"><?php
	print __( 'Calculated Fields Form', 'calculated-fields-form' ).' <span class="cff-form-name-shortcode">(<b>'.__('Form', 'calculated-fields-form').' '.CP_CALCULATEDFIELDSF_ID.' - '.$form_obj->get_option( 'form_name', '').'</b>) Shortcode: [CP_CALCULATED_FIELDS id="'.CP_CALCULATEDFIELDSF_ID.'"]</span>';

	if(get_option('CP_CALCULATEDFIELDSF_DIRECT_FORM_ACCESS', CP_CALCULATEDFIELDSF_DIRECT_FORM_ACCESS))
	{
		$url = CPCFF_AUXILIARY::site_url();
		$url .= (strpos($url, '?') === false) ? '?'	: '&';
		$url .= 'cff-form='.CP_CALCULATEDFIELDSF_ID;
		print '<br><span style="font-size:14px;font-style:italic;">'.__('Direct form URL', 'calculated-fields-form').': <a href="'.esc_attr($url).'" target="_blank">'.$url.'</a></span>';
	}
?></h1>
<input type="button" name="backbtn" value="<?php esc_attr_e( 'Back to items list...', 'calculated-fields-form' ); ?>" onclick="document.location='admin.php?page=cp_calculated_fields_form';" class="button-secondary" />
<br /><br />
<?php $_cpcff_nonce = wp_create_nonce( 'cff-form-settings' );?>
<form method="post" action="" id="cpformconf" name="cpformconf" class="cff_form_builder">
<input type="hidden" name="_cpcff_nonce" value="<?php echo $_cpcff_nonce; ?>" />
<input name="cp_calculatedfieldsf_post_options" type="hidden" value="1" />
<input name="cp_calculatedfieldsf_id" type="hidden" value="<?php echo CP_CALCULATEDFIELDSF_ID; ?>" />

<div id="normal-sortables" class="meta-box-sortables">

 <h2><?php _e( 'Form Settings', 'calculated-fields-form' ); ?>:</h2>
 <hr />
 <div><?php _e( '* Different form styles available on the tab Form Settings &gt;&gt; Form Template', 'calculated-fields-form' ); ?></div>
 <div id="metabox_form_structure" class="postbox" >
  <div class="hndle">
	<h3 style="padding:5px;display:inline-block;"><span><?php _e( 'Form Builder', 'calculated-fields-form' ); ?></span></h3>
	<div class="cff-revisions-container">
		<?php _e('Revisions','calculated-fields-form'); ?>
		<select name="cff_revision_list">

			<?php
				print '<option value="0">'.esc_html(__('Select a revision', 'calculated-fields-form')).'</option>';
				$revisions_obj = $form_obj->get_revisions();
				$revisions = $revisions_obj->revisions_list();
				foreach($revisions as $revision_id => $revision_data)
				{
					print '<option value="'.esc_attr($revision_id).'">'.esc_html($revision_data['time']).'</option>';
				}
			?>
		</select>
		<input type="button" name="cff_apply_revision" value="<?php print esc_attr('Load Revision', 'calculated-fields-form'); ?>" class="button" style="float:none;" />
		<input type="button" name="previewbtn" id="previewbtn2" class="button-primary" value="<?php esc_attr_e( 'Preview', 'calculated-fields-form' ); ?>" onclick="jQuery.fbuilder.preview( this );" title="Saves the form's structure only, and opens a preview windows" />
	</div>
  </div>
  <div class="inside">
	 <div class="form-builder-error-messages"><?php
        global $cff_structure_error;
        if( !empty( $cff_structure_error ) )
        {
            echo $cff_structure_error;
        }
     ?></div>
     <p style="border:1px solid #F0AD4E;background:#FBE6CA;padding:10px;"><span style="font-weight:bold;"><?php _e('If the form is not loading in the public website, go to the settings page of the plugin through the menu option: "Settings/Calculated Fields Form", select the "Classic" option for the attribute: "Script load method", and press the "Update" button.','calculated-fields-form'); ?></span><br /><?php _e( 'If you need also the form to be sent to the server side for processing (for example to deliver emails) then the <a href="https://cff.dwbooster.com/download" target="_blank">Commercial versions</a> of the plugin are required.', 'calculated-fields-form' ); ?></p>
	 <input type="hidden" name="form_structure" id="form_structure" value="<?php print esc_attr(preg_replace('/&quot;/i', '&amp;quot;', json_encode($form_obj->get_option( 'form_structure', CP_CALCULATEDFIELDSF_DEFAULT_form_structure )))); ?>" />
     <input type="hidden" name="templates" id="templates" value="<?php print esc_attr( json_encode( CPCFF_TEMPLATES::load_templates() ) ); ?>" />
     <link href="<?php print esc_attr(plugins_url('/vendors/jquery-ui/jquery-ui.min.css', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH)); ?>" type="text/css" rel="stylesheet" property="stylesheet" />
	<pre style="display:none;">
	<script type="text/javascript">
		try
		{
			function calculatedFieldsFormReady()
			{
				/* Revisions code */
				$calculatedfieldsfQuery('[name="cff_apply_revision"]').click(
					function(){
						var revision = $calculatedfieldsfQuery('[name="cff_revision_list"]').val();
						if(revision*1)
						{
							result = window.confirm('<?php print esc_js(__('The action will load the revision selected, the data are not stored will be lose. Do you want continue?', 'calculated-fields-form'));?>');
							if(result)
							{
								$calculatedfieldsfQuery('<form method="post" action="" id="cpformconf" name="cpformconf" class="cff_form_builder"><input type="hidden" name="_cpcff_nonce" value="<?php echo $_cpcff_nonce; ?>" /><input name="cp_calculatedfieldsf_id" type="hidden" value="<?php echo CP_CALCULATEDFIELDSF_ID; ?>" /><input type="hidden" name="cpcff_revision_to_apply" value="'+revision+'"></form>').appendTo('body').submit();
							}
						}
					}
				);

				// Form builder code

				var f;
				function run_fbuilder($)
				{
					f = $("#fbuilder").fbuilder();
					window['cff_form'] = f;
					f.fBuild.loadData( "form_structure", "templates" );
				};

				if(!('fbuilder' in $calculatedfieldsfQuery.fn))
				{
					$calculatedfieldsfQuery.getScript(
						location.protocol + '//' + location.host + location.pathname+'?page=cp_calculated_fields_form&cp_cff_resources=admin',
						function(){run_fbuilder(fbuilderjQuery);}
					);
				}
				else
				{
					run_fbuilder($calculatedfieldsfQuery);
				}

				$calculatedfieldsfQuery(".itemForm").click(function() {
				   f.fBuild.addItem($calculatedfieldsfQuery(this).attr("id"));
				});

				jQuery(".metabox_disabled_section")
				.click( function(){
				  if(confirm("<?php _e( 'These features aren\'t available in this version. Do you want to open the plugin\'s page to check other versions?', 'calculated-fields-form' ); ?>"))
					  document.location = 'https://cff.dwbooster.com/';
				})
				.find('*')
				.prop('disabled', true);
			};
		}
		catch( err ){}
        try{$calculatedfieldsfQuery = jQuery.noConflict();} catch ( err ) {}
	    if (typeof $calculatedfieldsfQuery == 'undefined')
        {
			 if(window.addEventListener){
				window.addEventListener('load', function(){
					try{$calculatedfieldsfQuery = jQuery.noConflict();} catch ( err ) {return;}
					calculatedFieldsFormReady();
				});
			}else{
				window.attachEvent('onload', function(){
					try{$calculatedfieldsfQuery = jQuery.noConflict();} catch ( err ) {return;}
					calculatedFieldsFormReady();
				});
			}
	    }
		else
		{
			$calculatedfieldsfQuery(document).ready( calculatedFieldsFormReady );
		}
     </script>
	 </pre>
     <div style="background:#fafafa;" class="form-builder">

         <div class="column ctrlsColumn">
             <div id="tabs">
				<span class="ui-icon ui-icon-triangle-1-e expand-shrink"></span>
     			<ul>
     				<li><a href="#tabs-1"><?php _e( 'Add a Field', 'calculated-fields-form' ); ?></a></li>
     				<li><a href="#tabs-2"><?php _e( 'Field Settings', 'calculated-fields-form' ); ?></a></li>
     				<li><a href="#tabs-3"><?php _e( 'Form Settings', 'calculated-fields-form' ); ?></a></li>
     			</ul>
     			<div id="tabs-1"></div>
     			<div id="tabs-2"></div>
     			<div id="tabs-3"></div>
     		</div>
         </div>
         <div class="columnr dashboardColumn padding10" id="fbuilder">
             <div id="formheader"></div>
             <div id="fieldlist"></div>
         </div>
         <div class="clearer"></div>

     </div>

  </div>
 </div>

 <p class="submit">
	<input type="submit" name="save" id="save" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'calculated-fields-form' ); ?>"  title="Saves the form's structure and settings and creates a revision" />
	<input type="button" name="previewbtn" id="previewbtn" class="button-primary" value="<?php esc_attr_e( 'Preview', 'calculated-fields-form' ); ?>" onclick="jQuery.fbuilder.preview( this );" title="Saves the form's structure only, and opens a preview windows" />
	| <input type="checkbox" name="cff-revisions-in-preview" <?php if(get_option('CP_CALCULATEDFIELDSF_REVISIONS_IN_PREVIEW', true)) print 'CHECKED'; ?> /><?php _e('Generate revisions in the form preview as well', 'calculated-fields-form'); ?>
</p>
 <div style="margin-bottom:20px;">
    <a href="#metabox_define_texts"><?php _e('Texts definition', 'calculated-fields-form'); ?></a>&nbsp;|&nbsp;
    <a href="#metabox_define_validation_texts"><?php _e('Error texts', 'calculated-fields-form'); ?></a>&nbsp;|&nbsp;
    <a href="#metabox_payment_settings"><?php _e('General payment settings', 'calculated-fields-form'); ?></a>&nbsp;|&nbsp;
    <a href="#metabox_paypal_integration"><?php _e('PayPal integration', 'calculated-fields-form'); ?></a>&nbsp;|&nbsp;
    <a href="#metabox_notification_email"><?php _e('Notification email', 'calculated-fields-form'); ?></a>&nbsp;|&nbsp;
    <a href="#metabox_email_copy_to_user"><?php _e('Email copy to user', 'calculated-fields-form'); ?></a>&nbsp;|&nbsp;
    <a href="#metabox_captcha_settings"><?php _e('Captcha settings', 'calculated-fields-form'); ?></a>
 </div>
 <div id="metabox_define_texts" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Define Texts', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( 'Previous button label (text)', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_previousbtn" class="width75" value="<?php $label = esc_attr($form_obj->get_option('vs_text_previousbtn', 'Previous')); echo ($label==''?'Previous':$label); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Next button label (text)', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_nextbtn" class="width75" value="<?php $label = esc_attr($form_obj->get_option('vs_text_nextbtn', 'Next')); echo ($label==''?'Next':$label); ?>" /></td>
        </tr>
        <tr valign="top">
        <td colspan="2">
        <?php _e( '- The styles can be applied into any of the CSS files of your theme or into the CSS file <em>"calculated-fields-form\css\stylepublic.css"</em>.', 'calculated-fields-form' ); ?><br />
        <?php _e( '- For general CSS styles modifications to the form and samples <a href="https://cff.dwbooster.com/faq#q82" target="_blank">check this FAQ</a>.', 'calculated-fields-form' ); ?>
        </tr>
        <?php
         // Display all other text fields
         foreach( $cpcff_texts_array as $cpcff_text_index => $cpcff_text_attr )
         {
			if( $cpcff_text_index !== 'errors' && isset($cpcff_text_attr[ 'label' ]) )
			{
				print '
				<tr valign="top">
					<th scope="row">'.$cpcff_text_attr[ 'label' ].':</th>
					<td><input type="text" name="cpcff_text_array['.$cpcff_text_index.'][text]" class="width75" value="'. esc_attr( $cpcff_text_attr[ 'text' ] ).'" /></td>
				</tr>
				';
			}
         }
        ?>
     </table>
     <div class="cff-goto-top"><a href="#cpformconf"><?php _e('Up to form structure', 'calculated-fields-form'); ?></a></div>
  </div>
 </div>

 <div id="metabox_define_validation_texts" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Validation Settings', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( '"is required" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_is_required" class="width75" value="<?php echo esc_attr($form_obj->get_option('vs_text_is_required', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_is_required)); ?>" /></td>
        </tr>
         <tr valign="top">
        <th scope="row"><?php _e( '"is email" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_is_email" class="width75" value="<?php echo esc_attr($form_obj->get_option('vs_text_is_email', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_is_email)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"is valid captcha" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="cv_text_enter_valid_captcha" class="width75" value="<?php echo esc_attr($form_obj->get_option('cv_text_enter_valid_captcha', CP_CALCULATEDFIELDSF_DEFAULT_cv_text_enter_valid_captcha)); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( '"is valid date (mm/dd/yyyy)" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_datemmddyyyy" class="width75" value="<?php echo esc_attr($form_obj->get_option('vs_text_datemmddyyyy', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_datemmddyyyy)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"is valid date (dd/mm/yyyy)" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_dateddmmyyyy" class="width75" value="<?php echo esc_attr($form_obj->get_option('vs_text_dateddmmyyyy', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_dateddmmyyyy)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"is number" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_number" class="width75" value="<?php echo esc_attr($form_obj->get_option('vs_text_number', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_number)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"only digits" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_digits" class="width75" value="<?php echo esc_attr($form_obj->get_option('vs_text_digits', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_digits)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"under maximum" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_max" class="width75" value="<?php echo esc_attr($form_obj->get_option('vs_text_max', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_max)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"over minimum" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_min" class="width75" value="<?php echo esc_attr($form_obj->get_option('vs_text_min', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_min)); ?>" /></td>
        </tr>
		<?php
		// Display all other text fields
		if( !empty( $cpcff_texts_array[ 'errors' ] ) )
		{
			foreach( $cpcff_texts_array[ 'errors' ] as $cpcff_text_index => $cpcff_text_attr )
			{
				if(isset($cpcff_text_attr[ 'label' ]))
                {
                    print '
                    <tr valign="top">
                        <th scope="row">'.$cpcff_text_attr[ 'label' ].':</th>
                        <td><input type="text" name="cpcff_text_array[errors]['.$cpcff_text_index.'][text]" class="width75" value="'. esc_attr( $cpcff_text_attr[ 'text' ] ).'" /></td>
                    </tr>
                    ';
                }
			}
		}
        ?>
     </table>
     <div class="cff-goto-top"><a href="#cpformconf"><?php _e('Up to form structure', 'calculated-fields-form'); ?></a></div>
  </div>
 </div>

<div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Note', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">
   <?php _e( 'To insert this form in a post/page, use the dedicated icon', 'calculated-fields-form' ); ?>
   <?php print '<a href="javascript:cp_calculatedfieldsf_insertForm();" title="'.esc_attr_e( 'Insert Calculated Fields Form', 'calculated-fields-form' ).'"><img hspace="5" src="'.plugins_url('/images/cp_form.gif', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH).'" alt="'.esc_attr_e( 'Insert Calculated Fields Form', 'calculated-fields-form' ).'" /></a>';     ?>
   <?php _e( 'which has been added to your Upload/Insert Menu, just below the title of your Post/Page.', 'calculated-fields-form' ); ?>
   <br /><br />
  </div>
</div>

 <p class="submit">
	<input type="submit" name="save" id="save" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'calculated-fields-form' ); ?>" title="Saves the form's structure and settings and creates a revision" />
</p>

 [<a href="https://cff.dwbooster.com/customization" target="_blank"><?php _e( 'Request Custom Modifications', 'calculated-fields-form' ); ?></a>] | [<a href="https://wordpress.org/support/plugin/calculated-fields-form#new-post" target="_blank"><?php _e( 'Help', 'calculated-fields-form' ); ?></a>]

<br /><br /><br />
<style>@media screen and (min-width:710px){.cff-plugin-promote{width: calc( 100% - 180px );}} @media screen and (max-width:710px){.cff-plugin-logo-promote{display:none;}}</style>
<div id="cff-upgrade-frame" style="border:1px solid #F0AD4E;background:#FBE6CA;padding:10px;color:#3c434a;">
    <a href="https://cff.dwbooster.com/download" target="_blank" style="text-decoration:none;float:left;" class="cff-plugin-logo-promote"><img src="https://ps.w.org/calculated-fields-form/assets/icon-256x256.jpg" style="width:160px;border:2px solid white;margin-right:10px;margin-bottom:10px;"></a>
    <div style="float:left;" class="cff-plugin-promote">
        <div style="font-weight:500;font-size:20px;line-height:28px;"><?php _e( 'The following features are available in the commercial version of the <a href="https://cff.dwbooster.com/download" target="_blank" style="text-decoration:none;">"Calculated Fields Form"</a>', 'calculated-fields-form' ); ?></div>
        <div style="text-transform: uppercase; font-weight:700; font-size:24px;margin-top:15px;margin-bottom:15px;line-height:28px;"><a href="https://cff.dwbooster.com/download" target="_blank" style="text-decoration:none;color:#3c434a;text-shadow:1px 1px 2px white;"><?php _e( 'Pay only ONCE, use it FOREVER', 'calculated-fields-form' ); ?></a></div>
        <div style="font-size:18px; font-weight:400;line-height:28px;">No additional charges, <span style="background:white;display:inline-block;padding:0 5px;"><a href="https://cff.dwbooster.com/terms" target="_blank" style="text-decoration:none;">lifetime updates</a></span>, one copy for all your websites.</div>
        <div style="font-size:16px; font-weight:400; font-style: italic;">And you get notification emails, payment gateways integration, data and forms exportation, advanced operations and more...</div>
        <?php
            print get_option('cff-t-t', '<div style="text-align:right; font-size:16px; font-weight:600;margin-top:15px;">To test some of the commercial features of the "Calculated Fields Form" plugin, you can <a class="button-primary" href="admin.php?page=cp_calculated_fields_form&cal='.CP_CALCULATEDFIELDSF_ID.'&_cpcff_nonce='.$_cpcff_nonce.'&cff-install-trial=1#cff-upgrade-frame">install the trial version</a></div>');
        ?>
    </div>
    <div style="clear:both;"></div>
</div>
 <h2><?php _e( 'Form Processing and Payment Settings', 'calculated-fields-form' ); ?>:</h2>
 <hr />

  <div id="metabox_payment_settings" class="postbox metabox_disabled_section" style="position:relative;">
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Payment Settings', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">

    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( 'Request cost', 'calculated-fields-form' ); ?></th>
        <td><select name="request_cost" id="request_cost" class="width75"></select></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Currency', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="currency" value="<?php echo esc_attr($form_obj->get_option('currency',CP_CALCULATEDFIELDSF_DEFAULT_CURRENCY)); ?>" /></td>
        </tr>

		<tr valign="top">
        <th scope="row"><?php _e( 'Base amount', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="paypal_base_amount" value="<?php echo esc_attr($form_obj->get_option( 'paypal_base_amount', '0.01' ) ); ?>" /><br><i style="font-size:11px;"><?php _e( 'Minimum amount to charge. If the final price is lesser than this number, the base amount will be applied.', 'calculated-fields-form' ); ?></i>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Paypal product name', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="paypal_product_name" class="width75" value="<?php echo esc_attr($form_obj->get_option('paypal_product_name',CP_CALCULATEDFIELDSF_DEFAULT_PRODUCT_NAME)); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Discount Codes', 'calculated-fields-form' ); ?></th>
        <td>
           <div id="dex_nocodes_availmsg"><?php _e( 'This feature isn\'t available in this version.', 'calculated-fields-form' ); ?></div>

           <br />
           <strong><?php _e( 'Add new discount code', 'calculated-fields-form' ); ?>:</strong>
           <br />
           <nobr><?php _e( 'Code', 'calculated-fields-form' ); ?>: <input type="text" name="dex_dc_code" id="dex_dc_code" value="" /></nobr> &nbsp; &nbsp; &nbsp;
           <nobr><?php _e( 'Discount', 'calculated-fields-form' ); ?>: <input type="text" size="3" name="dex_dc_discount" id="dex_dc_discount"  value="25" /><select name="dex_dc_discounttype" id="dex_dc_discounttype">
                   <option value="0"><?php _e( 'Percent', 'calculated-fields-form' ); ?></option>
                   <option value="1"><?php _e( 'Fixed Value', 'calculated-fields-form' ); ?></option>
                 </select></nobr>
                    &nbsp; &nbsp;
           <nobr><?php _e( 'Valid until', 'calculated-fields-form' ); ?>: <input type="text"  size="10" name="dex_dc_expires" id="dex_dc_expires" value="" /></nobr>&nbsp; &nbsp; &nbsp;
           <input type="button" name="dex_dc_subccode" id="dex_dc_subccode" value="<?php esc_attr_e( 'Add', 'calculated-fields-form' ); ?>" onclick="alert('This feature ins\'t available in this version');" class="button-secondary" />
           <br />
           <em style="font-size:11px;"><?php _e( 'Note: Expiration date based in server time. Server time now is', 'calculated-fields-form' ); ?> <?php echo date("Y-m-d H:i"); ?></em>
        </td>
        </tr>
     </table>
     <div class="cff-goto-top"><a href="#cpformconf"><?php _e('Up to form structure', 'calculated-fields-form'); ?></a></div>
  </div>
</div>

 <div id="metabox_paypal_integration" class="postbox metabox_disabled_section">
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Paypal Payment Configuration', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">

    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( 'Enable Paypal Payments?', 'calculated-fields-form' ); ?></th>
        <td><select name="enable_paypal">
             <option value="0"><?php _e( 'No', 'calculated-fields-form' ); ?></option>
            </select>
            <br /><i style="font-size:11px;"><?php _e( 'Note: If "Optional" is selected, a radiobutton will appear in the form to select if the payment will be made with PayPal or not.', 'calculated-fields-form' ); ?></i>
            <div id="cff_paypal_options_label" style="margin-top:10px;background:#EEF5FB;border: 1px dotted #888888;padding:10px;width:260px;">
              <?php _e( 'Label for the "<strong>Pay with PayPal</strong>" option', 'calculated-fields-form' ); ?>:<br />
              <input type="text" size="40" style="width:250px;" />
            </div></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Paypal Mode', 'calculated-fields-form' ); ?></th>
        <td><select name="paypal_mode" class="width75">
             <option value="production" <?php if ($form_obj->get_option('paypal_mode',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_MODE) != 'sandbox') echo 'selected'; ?>><?php _e( 'Production - real payments processed', 'calculated-fields-form' ); ?></option>
             <option value="sandbox" <?php if ($form_obj->get_option('paypal_mode',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_MODE) == 'sandbox') echo 'selected'; ?>><?php _e( 'SandBox - PayPal testing sandbox area', 'calculated-fields-form' ); ?></option>
            </select>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Paypal email', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="paypal_email" class="width75" value="<?php echo esc_attr($form_obj->get_option('paypal_email',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_EMAIL)); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'A $0 amount to pay means', 'calculated-fields-form' ); ?>:</th>
        <td><select name="paypal_zero_payment" class="width75">
             <option value="0" <?php if ($form_obj->get_option('paypal_zero_payment',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_ZERO_PAYMENT) != '1') echo 'selected'; ?>><?php _e( 'Let the user enter any amount at PayPal (ex: for a donation)', 'calculated-fields-form' ); ?></option>
             <option value="1" <?php if ($form_obj->get_option('paypal_zero_payment',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_ZERO_PAYMENT) == '1') echo 'selected'; ?>><?php _e( 'Don\'t require any payment. Form is submitted skiping the PayPal page.', 'calculated-fields-form' ); ?></option>
            </select>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Paypal language', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="paypal_language" value="<?php echo esc_attr($form_obj->get_option('paypal_language',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_LANGUAGE)); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Payment frequency', 'calculated-fields-form' ); ?></th>
        <td><select name="paypal_recurrent" class="width75">
             <option value="0" <?php if ($form_obj->get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '0' ||
                                         $form_obj->get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == ''
                                        ) echo 'selected'; ?>><?php _e( 'One time payment (default option, user is billed only once)', 'calculated-fields-form' ); ?></option>
             <option value="1" <?php if ($form_obj->get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '1') echo 'selected'; ?>><?php _e( 'Bill the user every 1 month', 'calculated-fields-form' ); ?></option>
             <option value="3" <?php if ($form_obj->get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '3') echo 'selected'; ?>><?php _e( 'Bill the user every 3 months', 'calculated-fields-form' ); ?></option>
             <option value="6" <?php if ($form_obj->get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '6') echo 'selected'; ?>><?php _e( 'Bill the user every 6 months', 'calculated-fields-form' ); ?></option>
             <option value="12" <?php if ($form_obj->get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '12') echo 'selected'; ?>><?php _e( 'Bill the user every 12 months', 'calculated-fields-form' ); ?></option>
            </select>
        </td>
        </tr>
		<tr valign="top">
        <th scope="row"><?php _e( 'Paypal prompt buyers for shipping address', 'calculated-fields-form' ); ?></th>
        <td>
			<?php $paypal_address = $form_obj->get_option('paypal_address', 1); ?>
			<select name="paypal_address" class="width75">
				<option value="1" <?php if($paypal_address == 1) print 'SELECTED'; ?>><?php _e('Do not prompt for an address', 'calculated-fields-form'); ?></option>
				<option value="0" <?php if($paypal_address == 0) print 'SELECTED'; ?>><?php _e('Prompt for an address, but do not require one', 'calculated-fields-form'); ?></option>
				<option value="2" <?php if($paypal_address == 2) print 'SELECTED'; ?>><?php _e('Prompt for an address and require one', 'calculated-fields-form'); ?></option>
			</select>
		</td>
        </tr>
     </table>
     <div class="cff-goto-top"><a href="#cpformconf"><?php _e('Up to form structure', 'calculated-fields-form'); ?></a></div>
  </div>
 </div>

 <div id="metabox_notification_email" class="postbox metabox_disabled_section" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Form Processing / Email Settings', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( '"From" email', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="fp_from_email" class="width75" value="<?php echo esc_attr($form_obj->get_option('fp_from_email', CP_CALCULATEDFIELDSF_DEFAULT_fp_from_email)); ?>" /><br><b><em style="font-size:11px;">Ex: admin@<?php echo str_replace('www.','',$_SERVER["HTTP_HOST"]); ?></em></b><br><em style="font-size:11px;"><?php _e( 'This email is required if the "From fixed email address" option is selected, or it is enabled the email copy to the user.', 'calculated-fields-form' ); ?></em></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Destination emails (comma separated)', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="fp_destination_emails" class="width75" value="<?php echo esc_attr($form_obj->get_option('fp_destination_emails', CP_CALCULATEDFIELDSF_DEFAULT_fp_destination_emails)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Email subject', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="fp_subject" class="width75" value="<?php echo esc_attr($form_obj->get_option('fp_subject', CP_CALCULATEDFIELDSF_DEFAULT_fp_subject)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Include additional information?', 'calculated-fields-form' ); ?></th>
        <td>
          <?php $option = $form_obj->get_option('fp_inc_additional_info', CP_CALCULATEDFIELDSF_DEFAULT_fp_inc_additional_info); ?>
          <select name="fp_inc_additional_info">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e( 'Yes', 'calculated-fields-form' ); ?></option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e( 'No', 'calculated-fields-form' ); ?></option>
          </select>&nbsp;<em style="font-size:11px;"><?php _e('If the "No" option is selected the plugin won\'t capture the IP address of users.','calculated-fields-form'); ?></em>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Thank you page (after sending the message)', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="fp_return_page" class="width75" value="<?php echo esc_attr($form_obj->get_option('fp_return_page', CP_CALCULATEDFIELDSF_DEFAULT_fp_return_page)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Email format?', 'calculated-fields-form' ); ?></th>
        <td>
          <?php $option = $form_obj->get_option('fp_emailformat', CP_CALCULATEDFIELDSF_DEFAULT_email_format); ?>
          <select name="fp_emailformat" class="width75">
           <option value="text"<?php if ($option != 'html') echo ' selected'; ?>><?php _e( 'Plain Text (default)', 'calculated-fields-form' ); ?></option>
           <option value="html"<?php if ($option == 'html') echo ' selected'; ?>><?php _e( 'HTML (use html in the textarea below)', 'calculated-fields-form' ); ?></option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Message', 'calculated-fields-form' ); ?></th>
        <td><textarea type="text" name="fp_message" rows="6" class="width75"><?php echo $form_obj->get_option('fp_message', CP_CALCULATEDFIELDSF_DEFAULT_fp_message); ?></textarea></td>
        </tr>
     </table>
        <div class="cff-goto-top"><a href="#cpformconf"><?php _e('Up to form structure', 'calculated-fields-form'); ?></a></div>
  </div>
 </div>


 <div id="metabox_email_copy_to_user" class="postbox metabox_disabled_section" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Email Copy to User', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( 'Send confirmation/thank you message to user?', 'calculated-fields-form' ); ?></th>
        <td>
          <?php $option = $form_obj->get_option('cu_enable_copy_to_user', CP_CALCULATEDFIELDSF_DEFAULT_cu_enable_copy_to_user); ?>
          <select name="cu_enable_copy_to_user">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e( 'Yes', 'calculated-fields-form' ); ?></option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e( 'No', 'calculated-fields-form' ); ?></option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Email field on the form', 'calculated-fields-form' ); ?></th>
        <td><select id="cu_user_email_field" name="cu_user_email_field" def="<?php echo esc_attr($form_obj->get_option('cu_user_email_field', CP_CALCULATEDFIELDSF_DEFAULT_cu_user_email_field)); ?>" class="width75"></select></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Email subject', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="cu_subject" class="width75" value="<?php echo esc_attr($form_obj->get_option('cu_subject', CP_CALCULATEDFIELDSF_DEFAULT_cu_subject)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Email format?', 'calculated-fields-form' ); ?></th>
        <td>
          <?php $option = $form_obj->get_option('cu_emailformat', CP_CALCULATEDFIELDSF_DEFAULT_email_format); ?>
          <select name="cu_emailformat" class="width75">
           <option value="text"<?php if ($option != 'html') echo ' selected'; ?>><?php _e( 'Plain Text (default)', 'calculated-fields-form' ); ?></option>
           <option value="html"<?php if ($option == 'html') echo ' selected'; ?>><?php _e( 'HTML (use html in the textarea below)', 'calculated-fields-form' ); ?></option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Message', 'calculated-fields-form' ); ?></th>
        <td><textarea type="text" name="cu_message" rows="6" class="width75"><?php echo $form_obj->get_option('cu_message', CP_CALCULATEDFIELDSF_DEFAULT_cu_message); ?></textarea></td>
        </tr>
     </table>
     <div class="cff-goto-top"><a href="#cpformconf"><?php _e('Up to form structure', 'calculated-fields-form'); ?></a></div>
  </div>
 </div>

 <div id="metabox_captcha_settings" class="postbox metabox_disabled_section" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Captcha Verification', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( 'Use Captcha Verification?', 'calculated-fields-form' ); ?></th>
        <td colspan="5">
          <?php $option = $form_obj->get_option('cv_enable_captcha', CP_CALCULATEDFIELDSF_DEFAULT_cv_enable_captcha); ?>
          <select name="cv_enable_captcha">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e( 'Yes', 'calculated-fields-form' ); ?></option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e( 'No', 'calculated-fields-form' ); ?></option>
          </select>
        </td>
        </tr>

        <tr valign="top">
         <th scope="row"><?php _e( 'Width', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_width" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_width', CP_CALCULATEDFIELDSF_DEFAULT_cv_width)); ?>"  onblur="generateCaptcha();"  /></td>
         <th scope="row"><?php _e( 'Height', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_height" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_height', CP_CALCULATEDFIELDSF_DEFAULT_cv_height)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row"><?php _e( 'Chars', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_chars" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_chars', CP_CALCULATEDFIELDSF_DEFAULT_cv_chars)); ?>" onblur="generateCaptcha();"  /></td>
        </tr>

        <tr valign="top">
         <th scope="row"><?php _e( 'Min font size', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_min_font_size" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_min_font_size', CP_CALCULATEDFIELDSF_DEFAULT_cv_min_font_size)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row"><?php _e( 'Max font size', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_max_font_size" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_max_font_size', CP_CALCULATEDFIELDSF_DEFAULT_cv_max_font_size)); ?>" onblur="generateCaptcha();"  /></td>
         <td colspan="2" rowspan="">
           <?php _e( 'Preview', 'calculated-fields-form' ); ?>:<br />
             <br />
            <img src="<?php echo plugins_url('/captcha/captcha.php', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH); ?>"  id="captchaimg" alt="<?php esc_attr_e( 'security code', 'calculated-fields-form' ); ?>" border="0" class="skip-lazy" />
         </td>
        </tr>


        <tr valign="top">
         <th scope="row"><?php _e( 'Noise', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_noise" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_noise', CP_CALCULATEDFIELDSF_DEFAULT_cv_noise)); ?>" onblur="generateCaptcha();" /></td>
         <th scope="row"><?php _e( 'Noise Length', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_noise_length" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_noise_length', CP_CALCULATEDFIELDSF_DEFAULT_cv_noise_length)); ?>" onblur="generateCaptcha();" /></td>
        </tr>

        <tr valign="top">
         <th scope="row"><?php _e( 'Background', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_background" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_background', CP_CALCULATEDFIELDSF_DEFAULT_cv_background)); ?>" onblur="generateCaptcha();" /></td>
         <th scope="row">Border:</th>
         <td><input type="text" readonly=readonly name="cv_border" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_border', CP_CALCULATEDFIELDSF_DEFAULT_cv_border)); ?>" onblur="generateCaptcha();" /></td>
        </tr>

        <tr valign="top">
         <th scope="row"><?php _e( 'Font', 'calculated-fields-form' ); ?>:</th>
         <td>
            <select name="cv_font" onchange="generateCaptcha();" >
              <option value="font-1.ttf"<?php if ("font-1.ttf" == $form_obj->get_option('cv_font', CP_CALCULATEDFIELDSF_DEFAULT_cv_font)) echo " selected"; ?>>Font 1</option>
              <option value="font-2.ttf"<?php if ("font-2.ttf" == $form_obj->get_option('cv_font', CP_CALCULATEDFIELDSF_DEFAULT_cv_font)) echo " selected"; ?>>Font 2</option>
              <option value="font-3.ttf"<?php if ("font-3.ttf" == $form_obj->get_option('cv_font', CP_CALCULATEDFIELDSF_DEFAULT_cv_font)) echo " selected"; ?>>Font 3</option>
              <option value="font-4.ttf"<?php if ("font-4.ttf" == $form_obj->get_option('cv_font', CP_CALCULATEDFIELDSF_DEFAULT_cv_font)) echo " selected"; ?>>Font 4</option>
            </select>
         </td>
        </tr>
     </table>
     <div class="cff-goto-top"><a href="#cpformconf"><?php _e('Up to form structure', 'calculated-fields-form'); ?></a></div>
  </div>
 </div>
 <a id="metabox_addons_section"></a>
 <?php
	do_action('cpcff_form_settings', CP_CALCULATEDFIELDSF_ID);
 ?>
</div>


<p class="submit">
	<input type="submit" name="save" id="save" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'calculated-fields-form' ); ?>"  title="Saves the form's structure and settings" />
</p>

[<a href="https://cff.dwbooster.com/customization" target="_blank"><?php _e( 'Request Custom Modifications', 'calculated-fields-form' ); ?></a>] | [<a href="https://wordpress.org/support/plugin/calculated-fields-form#new-post" target="_blank"><?php _e( 'Help', 'calculated-fields-form' ); ?></a>]
</form>
</div>
<script type="text/javascript">
	function generateCaptcha()
	{
	   var 	d=new Date(),
			f = document.cpformconf,
			qs = "?width="+f.cv_width.value;

	   qs += "&height="+f.cv_height.value;
	   qs += "&letter_count="+f.cv_chars.value;
	   qs += "&min_size="+f.cv_min_font_size.value;
	   qs += "&max_size="+f.cv_max_font_size.value;
	   qs += "&noise="+f.cv_noise.value;
	   qs += "&noiselength="+f.cv_noise_length.value;
	   qs += "&bcolor="+f.cv_background.value;
	   qs += "&border="+f.cv_border.value;
	   qs += "&font="+f.cv_font.options[f.cv_font.selectedIndex].value;
	   qs += "&rand="+d;

	   document.getElementById("captchaimg").src= "<?php echo plugins_url('/captcha/captcha.php', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH); ?>"+qs;
	}
	generateCaptcha();
</script>