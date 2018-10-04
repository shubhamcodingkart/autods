<?php
/**
 * The Template for displaying a full width page.
 *
 * Template Name: Registration
 *
 * @package dokan
 * @package dokan - 2013 1.0
 */
get_header();
?>
<script>

var html='<li class="wpuf-el password" data-label="Password"><div class="wpuf-label"><label for="password">Password <span class="required">&lowast;</span></label></div><div class="wpuf-fields"><input id="pass1" type="password" class="password  wpuf_password_168" data-required="yes" data-type="password" data-repeat="true" name="pass1" placeholder="" value="" size="40" ></div></li><li class="wpuf-el password-repeat" data-label="Confirm Password"><div class="wpuf-label"><label for="pass2">Confirm Password <span class="required">&lowast;</span></label></div><div class="wpuf-fields"><input id="pass2" type="password" class="password  wpuf_password_168" data-required="yes" data-type="confirm_password" name="pass2" value="" placeholder="" size="40"></div></li>';

console.log(html);
jQuery(document).ready(function(){
	check(241);
	jQuery('ul.tabs li').click(function(){
		var tab_id = jQuery(this).attr('data-tab');

		jQuery('ul.tabs li').removeClass('current');
		jQuery('.tab-content').removeClass('current');

		jQuery(this).addClass('current');
		jQuery("#"+tab_id).addClass('current');
	});

jQuery('div#pass-strength-result').addClass('pass-strength-result_custom');

});

function check(form_id)
{ 
  if(form_id==259)
  {
   jQuery('#tab-1 .wpuf-el.password').remove();
   jQuery('#tab-1 .wpuf-el.password-repeat ').remove();
   jQuery('#tab-2 .wpuf-el.password').remove();
   jQuery('#tab-2 .wpuf-el.password-repeat ').remove();
     
   jQuery('#tab-2 .wpuf-el.user_email').after(html);
   
   // strength indicator code
   jQuery('#tab-1 .pass-strength-result_custom').removeAttr('id');
   jQuery('#tab-1 .pass-strength-result_custom').removeClass('short bad good strong');
   jQuery('#tab-1 .pass-strength-result_custom').text('Strength indicator');
   jQuery('#tab-2 .pass-strength-result_custom').attr('id', 'pass-strength-result');
  }
  
  if(form_id==241)
  {
   
   jQuery('#tab-1 .wpuf-el.password').remove();
   jQuery('#tab-1 .wpuf-el.password-repeat ').remove();
   jQuery('#tab-2 .wpuf-el.password').remove();
   jQuery('#tab-2 .wpuf-el.password-repeat ').remove();
     
   jQuery('#tab-1 .wpuf-el.user_email').after(html);
   
    // strength indicator code 
   jQuery('#tab-2 .pass-strength-result_custom').removeAttr('id');
   jQuery('#tab-2 .pass-strength-result_custom').removeClass('short bad good strong');
   jQuery('#tab-2 .pass-strength-result_custom').text('Strength indicator');
   jQuery('#tab-1 .pass-strength-result_custom').attr('id', 'pass-strength-result');
  
  }

}
jQuery( ".wpuf-form-add" ).unbind('submit');
jQuery( ".password" ).unbind('keyup');
//jQuery(document).on("submit", ".wpuf-form-add",WP_User_Frontend.formSubmit);
jQuery(document).on('keyup', '.password', WP_User_Frontend.check_pass_strength );
</script>
<style>
	ul.tabs {
    margin: 0px;
    padding: 0px;
    text-align: center;
    list-style: none;
    font-size: 24px;
    }
	ul.tabs li {
	background: none;
	color: #222;
	display: inline-block;
	padding: 15px 108px;
	cursor: pointer;
	}

		ul.tabs li.current{
			background: #ededed;
			color: #222;
		}

		.tab-content{
			display: none;
			background: #ededed;
			padding: 15px;
		}

		.tab-content.current{
			display: inherit;
		}
		li.wpuf-el.location {
			display: none;
		}
</style>
<div id="primary" class="content-area col-md-12">
  <div id="content" class="site-content" role="main">
    <div class="container">
      <ul class="tabs">
        <li class="tab-link current" data-tab="tab-1"  onclick="check(241)">Vendor</li>
        <li class="tab-link" data-tab="tab-2" onclick="check(259)">Reseller</li>
      </ul>
      <div id="tab-1" class="tab-content current"> <?php echo do_shortcode( '[wpuf_profile type="registration" id="241"]' ); ?> </div>
      <div id="tab-2" class="tab-content"> <?php echo do_shortcode( '[wpuf_profile type="registration" id="259"]' ); ?> </div>
    </div>
    <!-- container -->
  </div>
  <!-- #content .site-content -->
</div>
<!-- #primary .content-area -->
<?php get_footer(); ?>

