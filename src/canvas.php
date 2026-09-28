<?php
/**
 * Template Name: Elementor Canvas (Minimum Temp)
 * Template sem header/footer — apenas o conteúdo do Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
<style>body{margin:0;padding:0;-webkit-font-smoothing:antialiased}.elementor-section-wrap{display:block}</style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php the_content(); ?>
<?php endwhile; ?>

<?php wp_footer(); ?>
</body>
</html>