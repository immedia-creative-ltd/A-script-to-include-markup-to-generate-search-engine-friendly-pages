<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Immedia
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<meta name="apple-mobile-web-app-capable" content="yes">

<!-- Latest compiled and minified CSS -->

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- mailchimp css -->

<link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">


<!-- Latest compiled and minified JavaScript -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<!-- Additional Fonts -->

<link href="https://fonts.googleapis.com/css?family=Raleway:300,300i,400,500,700&display=swap" rel="stylesheet"> 

<!-- hamburger -->

<script src="<?php echo (get_theme_root_uri()); ?>/immedia-theme/bigSlide.js"></script>

<script>

$(document).ready(function() {

$('.menu-link').bigSlide();

});

</script>

<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />

<meta property="og:title" content="<?php bloginfo('name'); ?>" />

<meta property="og:description" content="<?php bloginfo('description'); ?>" />

<meta property="og:url" content="<?php bloginfo('url'); ?>" />

<meta property="og:type" content="website" />

<?php wp_head(); ?>

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<?php $favicon = esc_attr( get_option( 'favicon' ) ); ?>
<?php if($favicon != ''){ ?>
<link rel="icon" href="<?php print $favicon; ?>" type="image/x-icon" />
<link rel="shortcut icon" href="<?php print $favicon; ?>" type="image/x-icon" />
<?php } ?>


<?php $customCSS = esc_attr( get_option( 'css_options' ) ); ?>
<?php $decodeCSS = html_entity_decode($customCSS); ?>
<?php if($customCSS != ''){ ?>
<style>
<?php print $decodeCSS; ?>
</style>
<?php } ?>

<?php $googleAnalytics = esc_attr( get_option( 'google_analytics' ) ); ?>
<?php $decodeAnalytics = html_entity_decode($googleAnalytics); ?>
<?php if($googleAnalytics != ''){ ?>
<script>
<?php print $decodeAnalytics; ?>
</script>
<?php } ?>

<?php $googleMaps = esc_attr( get_option( 'google_maps' ) ); ?>
<?php $decodeMaps = html_entity_decode($googleMaps); ?>
<?php if($googleMaps != ''){ ?>
<script async defer
      src="https://maps.googleapis.com/maps/api/js?key=<?php print $decodeMaps; ?>&callback=initMap">
    </script>
<?php } ?>

<?php 
	//Note later use of function sanitize($s) is defined in functions.php

	//Start of schema
	$schema = '';
	$schema .= '<script type="application/ld+json">';
    $schema .= '{';
		$schema .= '"@context": "http://schema.org",';
		$schema .= '"@type": "MedicalCondition",';
		
		//MedicalCause
		if( have_rows('medical_causes') ){ 
		$schema .= '"cause": [';
		//Find total of rows in repeater
		$count = 0;
		$fields = get_field_object('medical_causes');
		$total = (count($fields['value']));
		//Start loop
		while( have_rows('medical_causes') ): the_row(); 
			//Add 1 to $count
			$count++;
			
			// vars
			$cause = get_sub_field('cause');
			$cause = sanitize($cause);

			$schema .= '{';
			$schema .= '"@type": "MedicalCause",';
			$schema .= '"name": "' . $cause . '"';
			
			//If this is the last row do not return comma
			if ( $total != $count ){
				$schema .= '},';
			} else {
				$schema .= '}';	
			}
		 endwhile; 
		$schema .= '],';
		}
		
		//Expected Prognosis
		if( get_field('expected_prognosis') ){
			$field = get_field('expected_prognosis');
			$field = sanitize($field);
			$schema .= '"expectedPrognosis": "'. $field .'",';
		}		
		
		//Natural Progression
		if( get_field('natural_progression') ){
			$field = get_field('natural_progression');
			$field = sanitize($field);
			$schema .= '"naturalProgression": "'. $field .'",';
		}		
		
		//Possible Complication
		if( get_field('possible_complication') ){
			$field = get_field('possible_complication');
			$field = sanitize($field);
			$schema .= '"possibleComplication": "'. $field .'",';
		}
		
		//Possible Treatment
		if( have_rows('possible_treatments') ){ 
		$schema .= '"possibleTreatment": [';
		//Find total of rows in repeater
		$count = 0;
		$fields = get_field_object('possible_treatments');
		$total = (count($fields['value']));
		//Start loop
		while( have_rows('possible_treatments') ): the_row(); 
			//Add 1 to $count
			$count++;
		
			// vars
			$name = get_sub_field('name');
			$name = sanitize($name);
			
			$drug = get_sub_field('drug');
			$type = sanitize($drug);	

			$schema .= '{';
			$schema .= '"@type": "MedicalTherapy",';
			if( get_sub_field('name') ){
				$schema .= '"name": "' . $name . '"';
			}
			if( get_sub_field('drug') ){
			$schema .= ', "drug": "' . $drug . '"';
			}
			
			//If this is the last row do not return comma
			if ( $total != $count ){
				$schema .= '},';
			} else {
				$schema .= '}';	
			}
		 endwhile; 
		$schema .= '],';
		}
		
		//Signs or symptoms
		if( have_rows('signs_or_symptoms') ){ 
		$schema .= '"signOrSymptom": [';
		//Find total of rows in repeater
		$count = 0;
		$fields = get_field_object('signs_or_symptoms');
		$total = (count($fields['value']));
		//Start loop
		while( have_rows('signs_or_symptoms') ): the_row(); 
			//Add 1 to $count
			$count++;
			
			// vars
			$sign_or_symptom = get_sub_field('sign_or_symptom');
			$sign_or_symptom = sanitize($sign_or_symptom);

			$schema .= '{';
			$schema .= '"@type": "MedicalSymptom",';
			$schema .= '"name": "' . $sign_or_symptom . '"';
			
			//If this is the last row do not return comma
			if ( $total != $count ){
				$schema .= '},';
			} else {
				$schema .= '}';	
			}
		 endwhile; 
		$schema .= '],';
		}
		
		//Name of Condition
		$schema .= '"name": "'. get_the_title() .'"';
		
     $schema .= '}';
     $schema .= '</script>';
	 
	echo $schema;
?>

</head>





<body <?php body_class(); ?>>
	
<?PHP
/*display the admin bar to staff*/
show_admin_bar( true );
?>	
	
<div id="page" class="site">
	<div class="container">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'immedia' ); ?></a>
	</div>
	<header id="masthead" class="site-header" role="banner">
	
		<?php $topBar = esc_attr( get_option( 'top_bar' ) );?>
		<?php if($topBar == 'Yes'){	?>
		<?php $containTopbar = esc_attr( get_option( 'contain_topbar' ) ); ?>
		<div class="top-bar-cont">
			<?php if($containTopbar == 'Yes' || $containTopbar == ''){?><div class="container"><?php } ?>	
			<div class="row">	
				<div class="col-md-12">
					<?php dynamic_sidebar( 'top-bar' ); ?>
				</div>			
			</div>
			<?php if($containTopbar == 'Yes' || $containTopbar == ''){?></div><?php } ?>
		</div>
		<?php } ?>
	
		<div class="top-content">
			<?php $containHeader = esc_attr( get_option( 'contain_header' ) ); ?>
			<?php if($containHeader == 'Yes' || $containHeader == ''){?><div class="container"><?php } ?>
			<div class="row">
			
		 		<div class="logo col-md-3 col-sm-4 col-xs-6 head-left">
				<?php $logo = esc_attr( get_option( 'logo' ) ); ?>
				<?php $logoWidth = esc_attr( get_option( 'logo_width' ) ); ?>
				<?php $logoAltText = esc_attr( get_option( 'logo_alt_text' ) );?>
					<a href="/index.php">
						<img src="<?php print $logo; ?>" <?php if($logoWidth != ''){ ?> style="width:100%; max-width:<?php print $logoWidth; ?>px;" <?php }?> <?php if($logoAltText != ''){ ?> alt="<?php print $logoAltText; ?>" <?php }?>/>
					</a>
					
				<?php $logoText = esc_attr( get_option( 'logo_text' ) ); ?>
				<?php if($logoText != ''){ ?>
					<div class="logo-text-band"><?php print $logoText; ?></div>
				<?php } ?>
				</div>
				
				<div class="col-md-9 col-xs-6 col-sm-8 head-right">
					<?php dynamic_sidebar( 'header-content' ); ?>
				</div>
				
			</div>
			<?php if($containHeader == 'Yes' || $containHeader == ''){?></div><?php } ?>
		</div>
		
<div class="nav-content">		
        <!-- Brand and toggle get grouped for better mobile display -->
    <?php $containNavigation = esc_attr( get_option( 'contain_navigation' ) ); ?>
	<?php if($containNavigation == 'Yes' || $containNavigation == ''){?><div class="container"><?php } ?>		
		
<nav class="navbar navbar-default " role="navigation">
	

	<div class="row">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button"  class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div><!--end navbar-header-->
        <div class="collapse navbar-collapse menu-primary" id="bs-example-navbar-collapse-1">
            <?php
            wp_nav_menu( array(
                'menu'              => '',
                'theme_location'    => 'primary',
                'depth'             => 2,
                'container'         => '',
                'container_class'   => 'collapse navbar-collapse',
                'container_id'      => 'bs-example-navbar-collapse-1',
                'menu_class'        => 'nav navbar-nav',
                'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                'walker'            => new wp_bootstrap_navwalker())
            );
            ?>
        </div><!--end navbar-colapse-->
	</div>

</nav>
    <?php if($containNavigation == 'Yes' || $containNavigation == ''){?></div><?php } ?><!--end container-->
</div>

	</header><!-- #masthead -->

	<div id="content" class="site-content">

		<?php 	
		if ( is_page_template('page-image.php') || ( is_single() && 'conditions' == get_post_type() ) || ( is_single() && 'patient-information' == get_post_type() ) ) {
			
				if ( has_post_thumbnail() ) {
				  $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
				} else {
				  $featured_img_url = '/wp-content/uploads/2019/09/feature-image-1920x827.jpg'; 
				}
				
				?>
				<div class="header-img" style="background-image:url(<?php echo  $featured_img_url ?>)"></div>
				<?php
				
		}

		if ( !is_page_template('page-image.php') && !is_single() && !is_page_template('page-custom.php') ){ ?>

		<div class="grey-swatch header-swatch">
			<div class="vc_empty_space header-spacer"><span class="vc_empty_space_inner"></span></div>
		</div>		
		
		<?php
		}		
		
		if (  is_single() && 'post' == get_post_type() ){ ?>

		<div class="grey-swatch header-swatch">
			<div class="vc_empty_space header-spacer"><span class="vc_empty_space_inner"></span></div>
		</div>		
		
		<?php
		}
		
		if( is_single() && 'staff' == get_post_type()){ ?>
		
		<div class="grey-swatch">
			<div class="vc_empty_space staff-spacer"><span class="vc_empty_space_inner"></span></div>
		</div>		
		
		<?php }
		?>
		
	<?php $containBody = esc_attr( get_option( 'contain_body' ) ); ?>
	<?php if($containBody == 'Yes' || $containBody == ''){
			 if ( ('conditions' != get_post_type()) && ('patient-information' != get_post_type()) && ('staff' != get_post_type()) ){	
				?><div class="container"><?php
			 }	
			 if ( !is_single() && 'staff' == get_post_type() ){	
				?><div class="container"><?php
			 }	
		} ?>