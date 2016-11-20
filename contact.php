<!DOCTYPE html>
<html lang="en">
  <head>

  <title>IaREIA = Iowa Landlord Association + Two Rivers REIA | Mobile</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" /><!-- Hosted on CDN -->
  <link rel="stylesheet" href="css/ttc-3.css" /><!-- Hosted Locally - Use to Customize CSS -->
  <!-- Links to jQuery Core (Hosted on MediaTemple CDN) -->
  <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="http://code.jquery.com/jquery-migrate-1.1.1.min.js"></script>
  <!-- Link to jQuery Mobile (Hosted on MediaTemple CDN) -->
  <script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>
  <!-- Link to FitVid.js file which makes YouTube video responsive (Hosted locally) -->
  <script src="js/jquery.fitvids.js"></script>

  <!-- Script required for FitVids.js -->
  <script>
  $(document).ready(function(){
    // Target the div container that contains the video
    $(".video").fitVids();
  });
  </script>

 </head>

 <body class="custom">
 <div data-role="page" id="home">
	
	<!-- Header Content (Homepage) -->
   <div data-role="header" class="border-bottom">
	 <h1 class="logoimg"><a href="index.html" rel="external"><img src="images/logo.png" alt=" IaREIA = Iowa Landlord Association + Two Rivers REIA" /></a></h1>
</div><!-- END data-role="header" -->

		<!-- Main Content Area -->
		<div data-role="content" class="text-page">
          <h2>Contact Us</h2>
          <div>
            <p>Have an urgent need to get in touch?</p>
            
              <p>Send email to: <br /><a href="mailto:Info.IaREIA@gmail.com" target="_blank">Info.IaREIA@gmail.com</a><br />or
                Send a text to 515-255-0675 (from a cell phone), <br />or Call the office at 515-255-0675, <br />or
                Send a letter to: </p>
            <h3>ILA – IaREIA – TRREIA<br>
              PO Box 13246<br>
              Des Moines, IA 50310-0246</h3>
            <p>Or, complete this form.  We will get back to you just as soon as humanly possible.</p>
          </div>
          
          <?php

// if the from is loaded from WordPress form loader plugin, 
// the phpfmg_display_form() will be called by the loader 
if( !defined('FormmailMakerFormLoader') ){
    # This block must be placed at the very top of page.
    # --------------------------------------------------
	require_once( dirname(__FILE__).'/form.lib.php' );
    phpfmg_display_form();
    # --------------------------------------------------
};


function phpfmg_form( $sErr = false ){
		$style=" class='form_text' ";

?>

          
          <div id='frmFormMailContainer'>

<form name="frmFormMail" id="frmFormMail" target="submitToFrame" action='admin.php' method='post' enctype='multipart/form-data' onsubmit='return fmgHandler.onSubmit(this);'>

<input type='hidden' name='formmail_submit' value='Y'>
<input type='hidden' name='mod' value='ajax'>
<input type='hidden' name='func' value='submit'>
            
            

	<label class='form_field'>Full Name:</label>
	<input type="text" name="field_0"  id="field_0" value="" class='text_box'>

	<label class='form_field'>Email:</label> 
	<input type="text" name="field_1"  id="field_1" value="" class='text_box'>

	<label class='form_field'>Comments:</label> 
	<textarea name="field_2" id="field_2" rows=4 cols=25 class='text_area'></textarea>


            <div class='col_label'>&nbsp;</div>
            <div class='form_submit_block col_field'>
	
                <input type='submit' value='SEND YOUR MESSAGE' data-role="button" data-theme="a">

				<div id='err_required' class="form_error" style='display:none;'>
				    <label class='form_error_title'>Please check the required fields</label>
				</div>
				
                <span id='phpfmg_processing' style='display:none;'>
                    <img id='phpfmg_processing_gif' src='admin.php?mod=image&amp;func=processing' border=0 alt='Processing...'> <label id='phpfmg_processing_dots'></label>
                </span>
            </div>
            
</form>

<iframe name="submitToFrame" id="submitToFrame" src="javascript:false" style="position:absolute;top:-10000px;left:-10000px;" /></iframe>

</div> 
<!-- end of form container -->


<!-- [Your confirmation message goes here] -->
<div id='thank_you_msg' style='display:none;'>
  <h1 style="color:#F00">Your Message Has Been Sent. Thank You! </h1>
</div>

</div><!-- END data-role="content" -->
		
		<!-- Footer Content -->
		<div data-role="footer" class="border-top" id="home-footer">
		<h4 style="font-size:10px">&copy; 2013  IaREIA = Iowa Landlord Association + Two Rivers REIA</h4>
		</div><!-- END data-role="footer" -->
	
	</div><!-- END data-role="page" -->
  
  <?php
			
    phpfmg_javascript($sErr);

} 
# end of form




function phpfmg_form_css(){
    $formOnly = isset($GLOBALS['formOnly']) && true === $GLOBALS['formOnly'];
?>

<?php
}
# end of css
 
# By: formmail-maker.com
?>
 </body>
</html>