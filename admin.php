<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "orozdesign@gmail.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "ec199c" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|", "|ajax|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );
    
    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

}


function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'1F4A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB1EQx0aHVqRxVgdRBoYWh2mOiCJiYLEpjoEBKDoBYoFOoJIuPtWZk0NW5mZmTUNyX0gdayNcHUIsdDA0BB087CoQxcTDcEUG6jwoyLE4j4ABFDJgxjyAVgAAAAASUVORK5CYII=',
			'B019' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QgMYAhimMEx1QBILmMIYwhDCEBCALNbK2soYwugggqJOpNFhClwM7KTQqGkrs6atigpDch9EHcNUFL2tYLEGETQ7gG5BswPolimobgG5mTHUAcXNAxV+VIRY3AcAldLMpdjtHXoAAAAASUVORK5CYII=',
			'648A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYWhlCgRhJTGQKw1RGR4epDkhiAS0MoawNAQEByGINjK6Mjo4OIkjui4xaunRV6MqsaUjuC5ki0oqkDqK3VTTUtSEwNARFjKGVtSEQRR3QLRh6IW5mRBEbqPCjIsTiPgD8McslP2g7hQAAAABJRU5ErkJggg==',
			'4C61' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpI37pjCGMoQytKKIhbA2Ojo6TEUWYwwRaXBtcAhFFmOdItLA2gDXC3bStGnTVi2dumopsvsCQOocHVDsCA0F6Q1AtXcKyA50MbBb0MTAbg4NGAzhRz2IxX0AUtLMe/VI2nEAAAAASUVORK5CYII=',
			'DEDC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAUklEQVR4nGNYhQEaGAYTpIn7QgNEQ1lDGaYGIIkFTBFpYG10CBBBFmsFijUEOrBgEUN2X9TSqWFLV0VmIbsPTR1BMRQ7sLgFm5sHKvyoCLG4DwCems1UdiL9yAAAAABJRU5ErkJggg==',
			'0BBC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDGaYGIImxBoi0sjY6BIggiYlMEWl0bQh0YEESC2gFqXN0QHZf1NKpYUtDV2Yhuw9NHUwMbB4DATuwuQWbmwcq/KgIsbgPAJv2y8aXe36mAAAAAElFTkSuQmCC',
			'9977' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA0NDkMREprC2MjQENIggiQW0ijQ6YBMDiyLcN23q0qVZS1etzEJyH6srY6DDFIZWFJtbGRodAhimIIsJtLI0OjowBDCguYW1gdEBw81oYgMVflSEWNwHAH/Hy9uy3KbAAAAAAElFTkSuQmCC',
			'88D2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGaY6IImJTGFtZW10CAhAEgtoFWl0bQh0EEFX1xDQIILkvqVRK8OWrooCQoT7oOoaHTDMC2hlwBSbwoDFLZhuZgwNGQThR0WIxX0ACwPNq37VX3EAAAAASUVORK5CYII=',
			'C150' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WEMYAlhDHVqRxURaGQNYGximOiCJBTSygsQCApDFgHzWqYwOIkjuiwKipZmZWdOQ3AdSx9AQCFOHW6wRaF5DAIodIq0MAYyODihuYQ1hDWUIZUBx80CFHxUhFvcBAJH2yh1vLKWcAAAAAElFTkSuQmCC',
			'5370' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkNYQ1hDA1qRxQIaRID8gKkOKGIMjQ4NAQEBSGKBAQytDI2ODiJI7gubtips1dKVWdOQ3dcKhFMYYepgYo0OAahiAWDTGFDsEJki0srawIDiFtYAoJtBJgyC8KMixOI+AMrqzFNsOb8lAAAAAElFTkSuQmCC',
			'C141' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7WEMYAhgaHVqRxURaGQMYWh2mIosFNLIGMEx1CEURawDqDYTrBTspCohWZmYtRXYfSB0rmh1gsdAAVLFGbG7BFGMNYQ0FioUGDILwoyLE4j4A7RbLQLGier8AAAAASUVORK5CYII=',
			'F706' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkNFQx2mMEx1QBILaGBodAhlCAhAE3N0dHQQQBVrZW0IdEB2X2jUqmlLV0WmZiG5D6guAKgOzTxGB5BeERQx1gZGoB2oYkAehluAYmhuHqjwoyLE4j4AJiLM5qvKK1UAAAAASUVORK5CYII=',
			'3DED' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7RANEQ1hDHUMdkMQCpoi0sjYwOgQgq2wVaXQFiokgi01BEQM7aWXUtJWpoSuzpiG7bwoWvdjMwyKGzS3Y3DxQ4UdFiMV9APWDyzSeAaQzAAAAAElFTkSuQmCC',
			'D610' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QgMYQximMLQiiwVMYW1lCGGY6oAs1irSCFQZEIAq1sAwhdFBBMl9UUunha2atjJrGpL7AlpFW5HUwc1zwCqGZgfILVNQ3QJyM2OoA4qbByr8qAixuA8AP+XNLyCa0AkAAAAASUVORK5CYII=',
			'2105' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nM2QsRHAIAwDReENGAiK9ErhIkxDwwYkG6RhykDnHCmTu1idTrL/jDZNxp/0CZ8QRHVK4/nqCHXB5liELsabhwJKXpdg+Y6WzralZPk4cszedPv2yZOeHDes50dbQcunKoqKPfzgfy/qge8CQVvIaymcbJsAAAAASUVORK5CYII=',
			'0F5D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB1EQ11DHUMdkMRYA0QaWIEyAUhiIlMgYiJIYgGtQLGpcDGwk6KWTg1bmpmZNQ3JfSB1DA2BGHrRxSB2oIqB3MLo6IjiFrCNoYwobh6o8KMixOI+AAQZynJyctO2AAAAAElFTkSuQmCC',
			'1DE4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDHRoCkMRYHURaWRsYGpHFRB1EGl0bGFoDUPSCxaYEILlvZda0lamhq6KikNwHUcfogKmXMTQE07wGNHUgt6CIiYZgunmgwo+KEIv7APcNy0c/o/9mAAAAAElFTkSuQmCC',
			'F1F3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QkMZAlhDA0IdkMQCGhgDWBsYHQJQxFiBYgwNIihiDGCxACT3hUatiloaumppFpL70NShiGEzD1MMwy2hQHUobh6o8KMixOI+APMgyzV2GY4KAAAAAElFTkSuQmCC',
			'6DB7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WANEQ1hDGUNDkMREpoi0sjY6NIggiQW0iDS6NgSgijUAxYDqApDcFxk1bWVq6KqVWUjuC5kCVteKbG9AK9i8KVjEAhgw3OLogMXNKGIDFX5UhFjcBwCYA83pJYfvwwAAAABJRU5ErkJggg==',
			'DCA7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgMYQxmmMIaGIIkFTGFtdAhlaBBBFmsVaXB0dMAQY20IAEKE+6KWTlu1dFXUyiwk90HVtTKg6w0NmIIu5toQEMCA5hbXhkAHdDezookNVPhREWJxHwChWs7o1/KomwAAAABJRU5ErkJggg==',
			'846D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYWhlCGUMdkMREpjBMZXR0dAhAEgsAqmJtcHQQQVHH6MrawAgTAztpadTSpUunrsyahuQ+kSkirayOqHoDWkVDXRsC0cQYWlnRxIBuaUV3CzY3D1T4URFicR8AdaTK5hTXM50AAAAASUVORK5CYII=',
			'8FFA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7WANEQ11DA1qRxUSmiDSwNjBMdUASC2gFiwUEYKhjdBBBct/SqKlhS0NXZk1Dch+aOiTzGENDMMVQ1GHTyxqAKTZQ4UdFiMV9AAlNywqgyhLMAAAAAElFTkSuQmCC',
			'20A6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7WAMYAhimMEx1QBITmcIYwhDKEBCAJBbQytrK6OjoIICsu1Wk0bUh0AHFfdOmrUxdFZmahey+ALA6FPMYHYBioYEOIshuaWBtZW1AFRNpYAxhbQhA0RsKdBtQDMXNAxV+VIRY3AcAEZ3LjtXNHusAAAAASUVORK5CYII=',
			'00E6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHaY6IImxBjCGsDYwBAQgiYlMYW1lBaoWQBILaBVpdAWZgOS+qKXTVqaGrkzNQnIfVB2KeTC9IljsECHgFmxuHqjwoyLE4j4AoTzKMcTbr0UAAAAASUVORK5CYII=',
			'1DD9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDGaY6IImxOoi0sjY6BAQgiYk6iDS6NgQ6iKDoRREDO2ll1rSVqauiosKQ3AdRFzAVU29AAxYxdDsw3RKC6eaBCj8qQizuAwCHtMr6lYJqPwAAAABJRU5ErkJggg==',
			'98DF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGUNDkMREprC2sjY6OiCrC2gVaXRtCEQTA6pDiIGdNG3qyrClqyJDs5Dcx+qKog4CsZgngEUMm1ugbkY1b4DCj4oQi/sADVzKUOjOyUsAAAAASUVORK5CYII=',
			'E371' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkNYQ1hDA1qRxQIaRID8gKmoYgyNDg0BoWhirUBRmF6wk0KjVoWtWgqESO4Dq5vC0IphXgCmmKMDuphIK2sDqhjYzQ0MoQGDIPyoCLG4DwDcIs1c7rRljwAAAABJRU5ErkJggg==',
			'3348' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7RANYQxgaHaY6IIkFTBFpZWh1CAhAVtkKUuXoIIIsNgUoGghXB3bSyqhVYSszs6ZmIbsPqI61EdM819BAVPNAdjSi2gF2C5pebG4eqPCjIsTiPgA6Bc0LIQDvjgAAAABJRU5ErkJggg==',
			'A1A3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB0YAhimMIQ6IImxBjAGMIQyOgQgiYlMAYo6OjSIIIkFtDIEsDYENAQguS9qKQRlIbkPTR0YhoYCxUIDsJqHKRaI4paAVtZQoDoUNw9U+FERYnEfAB+Qy90NaOj3AAAAAElFTkSuQmCC',
			'48C1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpI37pjCGMIQ6tKKIhbC2MjoETEUWYwwRaXRtEAhFFmOdwtrK2sAA0wt20rRpK8OWrlq1FNl9AajqwDA0FGQeqhjDFLAdaGJgt6CJgd0cGjAYwo96EIv7AJTGy+HILed7AAAAAElFTkSuQmCC',
			'2840' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYQxgaHVqRxUSmsLYytDpMdUASC2gVaQSKBAQg624Fqgt0dBBBdt+0lWErMzOzpiG7L4C1lbURrg4MGR1EGl1DA1HEWBuAdjSi2iHSALSjEdUtoaGYbh6o8KMixOI+AJ7RzJPqE0x1AAAAAElFTkSuQmCC',
			'200C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WAMYAhimMEwNQBITmcIYwhDKECCCJBbQytrK6OjowIKsu1Wk0bUh0AHFfdOmrUxdFZmF4r4AFHVgyOiAKcbagGmHSAOmW0JDMd08UOFHRYjFfQB1I8oCjcRvAQAAAABJRU5ErkJggg==',
			'E57C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDA6YGIIkFNIiAyAARDLFABxZUsRCGRkcHZPeFRk1dumrpyixk9wHNbnSYwujAgKIXKBaALiYCNI0RzQ7WVtYGBhS3hIYwhgDFUNw8UOFHRYjFfQDkVMyLaA1UpAAAAABJRU5ErkJggg==',
			'E501' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkNEQxmmMLQiiwU0iDQwhDJMRRdjdHQIRRMLYW0IgOkFOyk0aurSpauiliK7L6CBodEVoQ6PmEijo6MDmhhrK9AtKGKhIYwhQDeHBgyC8KMixOI+AItYzWDX3XE5AAAAAElFTkSuQmCC',
			'B8DC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDGaYGIIkFTGFtZW10CBBBFmsVaXRtCHRgQVcHFEN2X2jUyrClqyKzkN2Hpg7FPGxiGHaguQWbmwcq/KgIsbgPAPuizadnU8aOAAAAAElFTkSuQmCC',
			'9127' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGUNDkMREpjAGMDo6NIggiQW0sgawNgSgiQH1AsUCkNw3beqqqFUrs1ZmIbmP1RWorhUIkW0G6Z0ChEhiAiAxEERxC0MAowOjA6qbWUNZQwNRxAYq/KgIsbgPACKuyI92InJyAAAAAElFTkSuQmCC',
			'AF4F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB1EQx0aHUNDkMRYA0QaGFodHZDViUwBik1FFQtoBYoFwsXATopaOjVsZWZmaBaS+0DqWBtR9YaGAsVCAzHNa8RiBxFiAxV+VIRY3AcApz3LGwqaRcEAAAAASUVORK5CYII=',
			'2618' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WAMYQximMEx1QBITmcLayhDCEBCAJBbQKtLIGMLoIIKsuxXImwJXB3HTtGlhq6atmpqF7L4A0VYkdWAINKnRYQqqeawNmGIiDawYekNDgS4JdUBx80CFHxUhFvcBAKOKyxY+fynCAAAAAElFTkSuQmCC',
			'BAEF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgMYAlhDHUNDkMQCpjCGsDYwOiCrC2hlbcUQmyLS6IoQAzspNGraytTQlaFZSO5DUwc1TzQUUwyLOix6QwOAYqGOKGIDFX5UhFjcBwDQ4cs/H+RCUAAAAABJRU5ErkJggg==',
			'BC90' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgMYQxlCGVqRxQKmsDY6OjpMdUAWaxVpcG0ICAhAUSfSwNoQ6CCC5L7QqGmrVmZGZk1Dch9IHUMIXB3cPIYGTDFHDDsw3YLNzQMVflSEWNwHAKeBziqnf278AAAAAElFTkSuQmCC',
			'4C58' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpI37pjCGsoY6THVAFgthbXRtYAgIQBJjDBFpcG1gdBBBEmOdItLAOhWuDuykadOmrVqamTU1C8l9AVNAugJQzAsNBYkFopjHMAVkB7oYa6OjowOKXpCbGUIZUN08UOFHPYjFfQCZPsydpj1gTgAAAABJRU5ErkJggg==',
			'B5A9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QsQ2AMAwETZENwj6hoH8kTJFpTJENkhHSMCUBCSkGShD4u9NbPpmWywj9Ka/4MVqmSMlVDNEKMQE1C1aarnNW90Yjw8F2JfYp58X7qfJDpLkXJLUbCmOIZnbrnW6YYATKhdGUu1DOX/3vwdz4raceznnspRHAAAAAAElFTkSuQmCC',
			'19F4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDAxoCkMRYHVhbWRsYGpHFRB1EGl0bGFoDUPSCxaYEILlvZdbSpamhq6KikNwHtCPQFUii6mUA6mUMDUERYwGZ14CqDuwWFDHREKCb0cQGKvyoCLG4DwA6+Mqgp2Fw5AAAAABJRU5ErkJggg==',
			'AB4D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB1EQxgaHUMdkMRYA0RaGVodHQKQxESmiDQ6THV0EEESC2gFqguEi4GdFLV0atjKzMysaUjuA6ljbUTVGxoq0ugaGohuXqNDIxY7GlHdEtCK6eaBCj8qQizuAwDce80MphJB8QAAAABJRU5ErkJggg==',
			'E27F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDA0NDkMQCGlhbGRoCHRhQxEQaHTDEGBodGh1hYmAnhUatWrpq6crQLCT3AdVNYZjCiK43gCEAXYzRAQRRxViBEFUsNEQ01BVNbKDCj4oQi/sA3tnKt0rFzXEAAAAASUVORK5CYII=',
			'CD18' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WENEQximMEx1QBITaRVpZQhhCAhAEgtoFGl0DGF0EEEWaxBpdJgCVwd2UtSqaSuzpq2amoXkPjR1SGJo5jViioHdgqYX5GbGUAcUNw9U+FERYnEfAGFgzToMJcG6AAAAAElFTkSuQmCC',
			'482E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37pjCGMIQyhgYgi4WwtjI6Ojogq2MMEWl0bQhEEWOdwtrKgBADO2natJVhq1ZmhmYhuS8ApK6VEUVvaKhIo8MUVDGGKUCxAHQxoFsc0MUYQ1hDA1HdPFDhRz2IxX0AeJXJRe0xLwcAAAAASUVORK5CYII=',
			'6751' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WANEQ11DHVqRxUSmMDS6NjBMRRYLaAGLhaKINTC0sk5lgOkFOykyatW0pZlZS5HdFzKFIQCoGsWOgFZGB0wx1gZWNDGRKSINjI6o7mMNEAG5JDRgEIQfFSEW9wEAenDMW/e53yoAAAAASUVORK5CYII=',
			'A814' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB0YQximMDQEIImxBrC2MoQwNCKLiUwRaXQMYWhFFgtoBaqbwjAlAMl9UUtXhq2atioqCsl9EHWMDsh6Q0NFGh2mMIaGoJgHEkN1C9QONDHGEMZQBxSxgQo/KkIs7gMA9RjOBb1ylQoAAAAASUVORK5CYII=',
			'B854' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDHRoCkMQCprC2sjYwNKKItYo0ujYwtGKom8owJQDJfaFRK8OWZmZFRSG5D6SOoSHQAd08h4bA0BAMOwIw3MLoiOo+kJsZQhlQxAYq/KgIsbgPABqvz161wdSUAAAAAElFTkSuQmCC',
			'73E1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkNZQ1hDHVpRRFtFWlkbGKaiijE0ujYwhKKITWEAqYPphbgpalXY0tBVS5Hdx+iAog4MgXyQeShiIljEAhpEMPQGNIDdHBowCMKPihCL+wDEcMs5Ztp/igAAAABJRU5ErkJggg==',
			'1EB7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDGUNDkMRYHUQaWBsdGkSQxERBYg0BKGKMUHUBSO5bmTU1bGkokEJyH1RdKwO63oaAKVjEAjDEGh0dkMVEQ8BuRhEbqPCjIsTiPgC98clBPYHlzAAAAABJRU5ErkJggg==',
			'0B0F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB1EQximMIaGIImxBoi0MoQyOiCrE5ki0ujo6IgiFtAq0sraEAgTAzspaunUsKWrIkOzkNyHpg4m1uiKJobNDmxugboZRWygwo+KEIv7ALXqyVOZ6rrOAAAAAElFTkSuQmCC',
			'5546' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QkNEQxkaHaY6IIkFNIg0MLQ6BASgi011dBBAEgsMEAlhCHR0QHZf2LSpS1dmZqZmIbuvlaHRtdERxTywWGiggwiyHa0ijQ6NjihiIlNYgSpR3cIawBiC7uaBCj8qQizuAwBh/M0XxwuclgAAAABJRU5ErkJggg==',
			'6A66' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGaY6IImJTGEMYXR0CAhAEgtoYW1lbXB0EEAWaxBpdG1gdEB2X2TUtJWpU1emZiG5L2QKUJ2jI6p5raKhrg2BDiIoYiDzUMVEgHod0dzCGiDS6IDm5oEKPypCLO4DAIB4zNCRFKOaAAAAAElFTkSuQmCC',
			'8A36' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WAMYAhhDGaY6IImJTGEMYW10CAhAEgtoZW1laAh0EEBRJ9Lo0OjogOy+pVHTVmZNXZmaheQ+qDo080RDHYDmiaCIAdWhiYH0uqK5hTVApNERzc0DFX5UhFjcBwCvQc28r+UjBgAAAABJRU5ErkJggg==',
			'4068' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpI37pjAEMIQyTHVAFgthDGF0dAgIQBJjDGFtZW1wdBBBEmOdItLo2sAAUwd20rRp01amTl01NQvJfQEgdWjmhYaC9AaimMcwBWQHuhimW7C6eaDCj3oQi/sAVvbLp3PXkGgAAAAASUVORK5CYII=',
			'0234' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nM3QsRHAIAgFUCzcwOyDG5A7bZwGCzcwI9gwZbRDkzK5BLp/cLwD5FIMf+pXfAZNMBGYVGbJFpsx68xVl5Gp6IwK9CmspHypSZNDUlK+Plche1x2CXiPYbphcEgWC9txeTJv0S/mr/73YN/4TqmCzim7tlKxAAAAAElFTkSuQmCC',
			'AF5B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB1EQ11DHUMdkMRYA0QaWIEyAUhiIlMgYiJIYgGtQLGpcHVgJ0UtnRq2NDMzNAvJfSB1DA2BKOaFhkLEMMzDIsbo6IiiF2xeKCOKmwcq/KgIsbgPAE7yy79EsES4AAAAAElFTkSuQmCC',
			'3C97' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7RAMYQxlCGUNDkMQCprA2Ojo6NIggq2wVaXBtCEAVmyLSwAoUC0By38qoaatWZkatzEJ2H1AdQ0hAKwOaeSCb0MUcGwICGDDc4uiAxc0oYgMVflSEWNwHAGXizBwAx9UHAAAAAElFTkSuQmCC',
			'4B50' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpI37poiGsIY6tKKIhYi0sjYwTHVAEmMMEWl0bWAICEASY50CVDeV0UEEyX3Tpk0NW5qZmTUNyX0BQHUMDYEwdWAYGirS6IAmxjAFZEcAih1AsVZGRwcUt4DczBDKgOrmgQo/6kEs7gMA+6XMK1OhT/MAAAAASUVORK5CYII=',
			'ADD5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDGUMDkMRYA0RaWRsdHZDViUwRaXRtCEQRC2gFi7k6ILkvaum0lamrIqOikNwHURfQIIKkNzQUUwxqngOaGNAtDgEBKGIgNzNMdRgE4UdFiMV9AMOwzeze6X7yAAAAAElFTkSuQmCC',
			'DDC4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QgNEQxhCHRoCkMQCpoi0MjoENKKItYo0ujYItGKKMUwJQHJf1NJpK1OBVBSS+yDqgCZi6GUMDcG0A5tbUMSwuXmgwo+KEIv7AHVj0GjQe9baAAAAAElFTkSuQmCC',
			'5C6E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkMYQxlCGUMDkMQCGlgbHR0dHRhQxEQaXBtQxQIDRBpYGxhhYmAnhU2btmrp1JWhWcjuawWqQzMPLNYQiGpHK8gOVDGRKZhuYQ3AdPNAhR8VIRb3AQB96cq2O2Wp4gAAAABJRU5ErkJggg==',
			'AD45' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB1EQxgaHUMDkMRYA0RaGVodHZDViUwRaXSYiioW0AoUC3R0dUByX9TSaSszMzOjopDcB1Ln2ujQIIKkNzQUKAa0VQTdvEZHBzSxVoZGh4AAFDGQmx2mOgyC8KMixOI+AM9ezfUDeaWvAAAAAElFTkSuQmCC',
			'E750' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkNEQ11DHVqRxQIaGBpdGximOmCKBQSgirWyTmV0EEFyX2jUqmlLMzOzpiG5D6gugKEhEKYOKsbogCnGCoQBaHaINDA6OqC4JTQEqCuUAcXNAxV+VIRY3AcAgrHNH+nooHIAAAAASUVORK5CYII=',
			'7F7F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkNFQ11DA0NDkEVbRYBkoAMDIbEpQLFGR5gYxE1RU8NWLV0ZmoXkPkYHoLopjCh6WRuAYgGoYiJAyOiAKhYAFGNtICw2UOFHRYjFfQAUq8lpQV5+nAAAAABJRU5ErkJggg==',
			'625D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHUMdkMREprC2sjYwOgQgiQW0iDS6AsVEkMUaGBpdp8LFwE6KjFq1dGlmZtY0JPeFTGGYwtAQiKq3lSEAU4zRgRVNDOiWBkZHRxS3sAaIhjqEMqK4eaDCj4oQi/sACszLPuVeVxwAAAAASUVORK5CYII=',
			'63EB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7WANYQ1hDHUMdkMREpoi0sjYwOgQgiQW0MDS6AsVEkMUaGJDVgZ0UGbUqbGnoytAsJPeFTGHANK8Vi3lYxLC5BZubByr8qAixuA8Asu7LB7iJSLUAAAAASUVORK5CYII=',
			'02CD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB0YQxhCHUMdkMRYA1hbGR0CHQKQxESmiDS6Ngg6iCCJBbQyAMUYYWJgJ0UtXbV06aqVWdOQ3AdUN4UVoQ4mFoAuJjKF0YEVzQ5WsCpUtzA6iIY6oLl5oMKPihCL+wDoXcpRkRmaNwAAAABJRU5ErkJggg==',
			'4040' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpI37pjAEMDQ6tKKIhTCGMLQ6THVAEmMMYW1lmOoQEIAkxjpFpNEh0NFBBMl906ZNW5mZmZk1Dcl9AUB1ro1wdWAYGgoUCw1EEWOYArSjEdUOhilAtzSiugWrmwcq/KgHsbgPAGf0zIBKphlWAAAAAElFTkSuQmCC',
			'A4FF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7GB0YWllDA0NDkMRYAximsoJkkMREpjCEoosFtDK6IomBnRS1FAhCV4ZmIbkvoFWkFV1vaKhoqCuGeQwY6ogVG6jwoyLE4j4AGJ7JEvVnQVoAAAAASUVORK5CYII=',
			'041C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB0YWhmmMEwNQBJjDWCYyhDCECCCJCYyhSGUMYTRgQVJLKCV0ZVhCtAEJPdFLV26dNW0lVnI7gtoFWlFUgcVEw11QBMD2gFWh2wH0C0g96G4BeRmxlAHFDcPVPhREWJxHwDbjMmpttoljQAAAABJRU5ErkJggg==',
			'ED93' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QkNEQxhCGUIdkMQCGkRaGR0dHQJQxRpdQSQWsQAk94VGTVuZmRm1NAvJfSB1DiFwdQgxLOY5YophuAWbmwcq/KgIsbgPAI6qzugKeyD/AAAAAElFTkSuQmCC',
			'C4DC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WEMYWllDGaYGIImJtDJMZW10CBBBEgtoZAhlbQh0YEEWa2B0BYkhuy9q1dKlS1dFZiG7LwBoIpI6qJhoqCu6WCNDK7odQLe0orsFm5sHKvyoCLG4DwADrcwacNg4jwAAAABJRU5ErkJggg==',
			'8253' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHUIdkMREprC2sjYwOgQgiQW0ijS6guRQ1DE0uk4FyiG5b2nUqqVLM7OWZiG5D6huCkgVqnkMASAxERQxRgdWNDGgWxoYHR1R3MIaIAp0MQOKmwcq/KgIsbgPABgozOSdmBHZAAAAAElFTkSuQmCC',
			'B8EA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDHVqRxQKmsLayNjBMdUAWaxVpdG1gCAjAUMfoIILkvtColWFLQ1dmTUNyH5o6JPMYQ0MwxVDVYdELcbMjithAhR8VIRb3AQCqp8xqDMGq2QAAAABJRU5ErkJggg==',
			'B2FB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDA0MdkMQCprC2sjYwOgQgi7WKNLoCxURQ1DGAxQKQ3BcatWrp0tCVoVlI7gOqm4JpHkMAK7p5rYwOGGJAneh6QwNEQ4H2orh5oMKPihCL+wAnIMwOxLbwDAAAAABJRU5ErkJggg==',
			'93C3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WANYQxhCHUIdkMREpoi0MjoEOgQgiQW0MjS6Ngg0iKCKtbKCaCT3TZu6KmzpqlVLs5Dcx+qKog4CweYxoJgngMUObG7B5uaBCj8qQizuAwCEK8xk0Om0mgAAAABJRU5ErkJggg==',
			'8ABC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WAMYAlhDGaYGIImJTGEMYW10CBBBEgtoZW1lbQh0YEFRJ9Lo2ujogOy+pVHTVqaGrsxCdh+aOqh5oqGuQPNQxYDqsNqB6hbWAKAYmpsHKvyoCLG4DwC/4cz104QigwAAAABJRU5ErkJggg==',
			'95C1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WANEQxlCHVqRxUSmiDQwOgRMRRYLaBVpYG0QCEUTC2FtYIDpBTtp2tSpS5euWrUU2X2srgyNrgh1ENiKKSbQKgIUE0BzC2sr0C0oYqwBjCFAN4cGDILwoyLE4j4AzazL4ocIyxoAAAAASUVORK5CYII=',
			'50A8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QkMYAhimMEx1QBILaGAMYQhlCAhAEWNtZXR0dBBBEgsMEGl0bQiAqQM7KWzatJWpq6KmZiG7rxVFHUIsNBDFvIBW1lbWBlQxkSmMIaxoelkDGAKAYihuHqjwoyLE4j4A9xLM6BnFQ24AAAAASUVORK5CYII=',
			'AA7E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB0YAlhDA0MDkMRYAxhDGBoCHZDViUxhbUUXC2gVaXRodISJgZ0UtXTayqylK0OzkNwHVjeFEUVvaKhoqEMAI4Z5jg6YYq4NWMVQ3DxQ4UdFiMV9AMMiy2DMa0lpAAAAAElFTkSuQmCC',
			'041A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB0YWhmmADGSGGsAw1SGEIapDkhiIlMYQhlDGAICkMQCWhldGaYwOogguS9q6dKlq6atzJqG5L6AVpFWJHVQMdFQhymMoSGodmCoA7oFQwzkZsZQRxSxgQo/KkIs7gMAgarKAze/sPsAAAAASUVORK5CYII=',
			'6BA7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WANEQximMIaGIImJTBFpZQgF0khiAS0ijY6ODqhiDSKtrEAyAMl9kVFTw5auilqZheS+kClgda3I9ga0ijS6hgZMwRBrCAhgQHMLa0OgA7qb0cUGKvyoCLG4DwCVoc1V7T46MwAAAABJRU5ErkJggg==',
			'D0A8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgMYAhimMEx1QBILmMIYwhDKEBCALNbK2sro6OgggiIm0ujaEABTB3ZS1NJpK1NXRU3NQnIfmjqEWGggmnmsrawNaGJAt7Ci6QW5GSiG4uaBCj8qQizuAwC5z85wiRsuGwAAAABJRU5ErkJggg==',
			'FF94' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkNFQx1CGRoCkMQCGkQaGB0dGtHFWBsCWrGITQlAcl9o1NSwlZlRUVFI7gOpYwgJdEDXy9AQGBqCbi+QxOIWDDEGNDcPVPhREWJxHwDG5M8bMUapEgAAAABJRU5ErkJggg==',
			'C0E1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7WEMYAlhDHVqRxURaGUNYGximIosFNLK2AsVCUcQaRBpdGxhgesFOilo1bWVq6KqlyO5DU4dbDGIHNregiEHdHBowCMKPihCL+wBe/cuYGmAP0wAAAABJRU5ErkJggg==',
			'8F73' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WANEQ11DA0IdkMREpogAyUCHACSxgFaQWECDCLq6RoeGACT3LY2aGrZq6aqlWUjuA6ubwtCAYV4AA4p5IDFGBwYMO1iBosh6WQNAYgwobh6o8KMixOI+AL+ezTLb9WhxAAAAAElFTkSuQmCC',
			'AAE0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHVqRxVgDGENYGximOiCJiUxhbQWKBQQgiQW0ijS6Ak0QQXJf1NJpK1NDV2ZNQ3IfmjowDA0VDUUXg6jDZgeqW8BiaG4eqPCjIsTiPgA1BczJsbQxVgAAAABJRU5ErkJggg==',
			'ABA5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7GB1EQximMIYGIImxBoi0MoQyOiCrE5ki0ujo6IgiFtAq0sraEOjqgOS+qKVTw5auioyKQnIfRF1AgwiS3tBQkUbXUFQxoLpG14ZABxEMOwICAlDEREOAYlMdBkH4URFicR8AlCPNNVI6UQAAAAAASUVORK5CYII=',
			'F9DD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDGUMdkMQCGlhbWRsdHQJQxEQaXRsCHURwi4GdFBq1dGnqqsisaUjuC2hgDMTUy4DFPBYsYtjcgunmgQo/KkIs7gMAC47NtjKAiM8AAAAASUVORK5CYII=',
			'2DF2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WANEQ1hDA6Y6IImJTBFpZW1gCAhAEgtoFWl0bWB0EEHWDRYDqkd237RpK1NDV62KQnZfAFhdI7IdQJNAYq0obmkAi01BFhNpgLgFWSw0FOjmBsbQkEEQflSEWNwHACfUzA7zU5DvAAAAAElFTkSuQmCC',
			'90CC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WAMYAhhCHaYGIImJTGEMYXQICBBBEgtoZW1lbRB0YEERE2l0bWB0QHbftKnTVqauWpmF7D5WVxR1ENiKKSaAxQ5sbsHm5oEKPypCLO4DAOw8ykoL955OAAAAAElFTkSuQmCC',
			'7C64' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMZQxlCGRoCkEVbWRsdHR0aUcVEGlwbHFpRxKaINLACyQBk90VNW7V06qqoKCT3MToA1QENRNbL2gDSGxgagiQm0gCyIwDFLQENYLegiWFx8wCFHxUhFvcBAIQDzjIn6WNjAAAAAElFTkSuQmCC',
			'FF6F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVElEQVR4nGNYhQEaGAYTpIn7QkNFQx1CGUNDkMQCGkQaGB0dHRjQxFgbsIkxwsTATgqNmhq2dOrK0Cwk94HVYTUvkCgxbG5hCGVEERuo8KMixOI+AJbxytX7BEGTAAAAAElFTkSuQmCC',
			'25C6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WANEQxlCHaY6IImJTBFpYHQICAhAEgtoFWlgbRB0EEDW3SoSwgpUieK+aVOXLl21MjUL2X0BDI2uDYwo5gF1gcQcRJDd0iACFBNEEQPa2orultBQxhB0Nw9U+FERYnEfAOMNyzPoKtkDAAAAAElFTkSuQmCC',
			'B45C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QgMYWllDHaYGIIkFTGGYytrAECCCLNbKEMrawOjAgqKO0ZV1KqMDsvtCo5YuXZqZmYXsvoApIq0MDYEODCjmiYY6YIgB3QIUQ7WDoZXR0QHFLSA3M4QyoLh5oMKPihCL+wAGB8wYtRoi1AAAAABJRU5ErkJggg==',
			'88B8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAUUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGaY6IImJTGFtZW10CAhAEgtoFWl0bQh0EMGtDuykpVErw5aGrpqaheQ+Ys0jwg6cbh6o8KMixOI+ALskzXrMxfy/AAAAAElFTkSuQmCC',
			'BE65' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QgNEQxlCGUMDkMQCpog0MDo6OiCrC2gVaWBtQBObAhJjdHVAcl9o1NSwpVNXRkUhuQ+sztGhQQTDvAAsYoEOIhhucQhAdh/EzQxTHQZB+FERYnEfAFDLzH246pdsAAAAAElFTkSuQmCC'        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>