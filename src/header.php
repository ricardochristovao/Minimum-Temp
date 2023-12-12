<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo is_front_page() || is_home() ? bloginfo('name') : wp_title('', false) . ' - ' . get_bloginfo('name'); ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?php if(is_front_page() || is_home()) { bloginfo('name'); } else { wp_title(''); echo ' - '; bloginfo('name'); } ?>">
    <meta property="og:description" content="<?php bloginfo('description'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>