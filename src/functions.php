<?php
// Configurações básicas do tema
function minimun_temp_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('customize-selective-refresh-widgets');
    
    // Registra o local do menu
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'minimun-temp'),
    ));
}

add_action('after_setup_theme', 'minimun_temp_setup');

// Verifica se o Elementor está instalado e ativo
function minimun_temp_check_elementor() {
    // Verifica se a função do Elementor não existe
    if ( ! function_exists( 'elementor_load_plugin_textdomain' ) ) {
        add_action('admin_notices', 'minimun_temp_elementor_missing_notice');
    }
}

// Exibe um aviso no painel administrativo
function minimun_temp_elementor_missing_notice() {
    $install_url = esc_url( network_admin_url( 'plugin-install.php?s=elementor&tab=search&type=term' ) );
    echo '<div class="notice notice-warning is-dismissible">';
    echo '<p>' . esc_html__( 'Minimun Temp requer o plugin Elementor para funcionar corretamente.', 'minimun_temp' ) . '</p>';
    echo '<p><a href="' . $install_url . '" class="button button-primary">' . esc_html__( 'Instalar o Plugin Elementor', 'minimun_temp' ) . '</a></p>';
    echo '</div>';
}

// Hook para verificar o Elementor após a inicialização do tema
add_action('after_setup_theme', 'minimun_temp_check_elementor');

// Adiciona o favicon ao cabeçalho do site
function minimun_temp_add_favicon() {
    $custom_favicon = get_site_icon_url();
    if ($custom_favicon) {
        echo '<link rel="shortcut icon" href="' . esc_url($custom_favicon) . '" />';
    } else {
        $diretorio_tema = get_stylesheet_directory_uri();
        echo '<link rel="shortcut icon" href="' . esc_url($diretorio_tema) . '/favi.ico" />';
    }
}

add_action('wp_head', 'minimun_temp_add_favicon');

// Remove o CSS de blocos do WordPress
function minimun_temp_remove_block_css() {
    wp_dequeue_style( 'wp-block-library' );
}

add_action('wp_enqueue_scripts', 'minimun_temp_remove_block_css', 100);

// Carrega scripts e estilos (opcional)
function minimun_temp_scripts() {
    // Enfileira estilos e scripts aqui se necessário
}

add_action('wp_enqueue_scripts', 'minimun_temp_scripts');

// Carregamento condicional para otimização
function minimun_temp_conditional_scripts() {
    if ( is_page() || is_single() ) {
        // Carrega scripts e estilos específicos para páginas e posts
    }
}

add_action('wp_enqueue_scripts', 'minimun_temp_conditional_scripts');

function theme_enqueue_styles() {
    wp_enqueue_style('main-styles', get_stylesheet_uri());
}

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

function disable_wp_emojicons() {
    // Remove todos os hooks relacionados aos emojis
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');

    // Remove o DNS prefetch dos emojis
    add_filter('emoji_svg_url', '__return_false');
}

add_action('init', 'disable_wp_emojicons');

function remove_specific_inline_styles() {
    // Verifica se o estilo está na fila
    if (wp_style_is('classic-theme-styles-inline-css', 'enqueued')) {
        // Remove o estilo
        wp_dequeue_style('classic-theme-styles-inline-css');
    }
}

add_action('wp_enqueue_scripts', 'remove_specific_inline_styles', 100);
