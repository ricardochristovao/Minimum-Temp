<?php
/**
 * functions.php — Tema Minimun Temp
 * Otimizado e compatível com o editor/preview do Elementor.
 */

if ( ! defined('ABSPATH') ) { exit; }

// -----------------------------------------------------------------------------
// Configurações básicas do tema
// -----------------------------------------------------------------------------
function minimun_temp_setup() {
    load_theme_textdomain('minimun_temp', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array('search-form','comment-form','comment-list','gallery','caption','style','script'));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');

    // Tamanhos de imagem personalizados
    add_image_size('minimun-thumb', 300, 200, true);
    add_image_size('minimun-medium', 600, 400, true);
}
add_action('after_setup_theme', 'minimun_temp_setup');

// -----------------------------------------------------------------------------
// Limites de upload/memória (útil em hosts restritos)
// -----------------------------------------------------------------------------
function minimun_temp_increase_limits() {
    @ini_set('upload_max_filesize', '64M');
    @ini_set('post_max_size', '64M');
    @ini_set('max_execution_time', '300');
    @ini_set('max_input_vars', '3000');
    @ini_set('memory_limit', '256M');
}
add_action('init', 'minimun_temp_increase_limits');

function minimun_temp_upload_size_limit($size) {
    return 64 * 1024 * 1024; // 64MB
}
add_filter('upload_size_limit', 'minimun_temp_upload_size_limit');

// -----------------------------------------------------------------------------
// Verificação do Elementor (aviso no admin se ausente)
// -----------------------------------------------------------------------------
function minimun_temp_check_elementor() {
    if ( ! defined('ELEMENTOR_VERSION') ) {
        add_action('admin_notices', 'minimun_temp_elementor_missing_notice');
    }
}
function minimun_temp_elementor_missing_notice() {
    $install_url = esc_url( network_admin_url('plugin-install.php?s=elementor&tab=search&type=term') );
    echo '<div class="notice notice-warning is-dismissible">';
    echo '<p>' . esc_html__('Minimun Temp requer o plugin Elementor para funcionar corretamente.', 'minimun_temp') . '</p>';
    echo '<p><a href="' . $install_url . '" class="button button-primary">' . esc_html__('Instalar o Plugin Elementor', 'minimun_temp') . '</a></p>';
    echo '</div>';
}
add_action('admin_init', 'minimun_temp_check_elementor');

// -----------------------------------------------------------------------------
// Favicon (usa o Site Icon se houver, senão favi.ico do tema)
// -----------------------------------------------------------------------------
function minimun_temp_add_favicon() {
    $custom_favicon = get_site_icon_url();
    if ( $custom_favicon ) {
        echo '<link rel="shortcut icon" href="' . esc_url($custom_favicon) . '" />';
    } else {
        $diretorio_tema = get_stylesheet_directory_uri();
        echo '<link rel="shortcut icon" href="' . esc_url($diretorio_tema . '/favi.ico') . '" />';
    }
}
add_action('wp_head', 'minimun_temp_add_favicon');

// -----------------------------------------------------------------------------
// Limpezas/otimizações sem quebrar o preview do Elementor
// -----------------------------------------------------------------------------
function minimun_temp_cleanup() {

    // Durante o preview do Elementor, não faça limpezas para evitar quebra do editor
    if ( isset($_GET['elementor-preview']) ) {
        return;
    }

    // Remove CSS de blocos (se não usar Gutenberg no front)
    if ( wp_style_is('wp-block-library', 'enqueued') ) {
        wp_dequeue_style('wp-block-library');
    }
    if ( wp_style_is('wp-block-library-theme', 'enqueued') ) {
        wp_dequeue_style('wp-block-library-theme');
    }
    if ( wp_style_is('wc-blocks-style', 'enqueued') ) {
        wp_dequeue_style('wc-blocks-style');
    }
    if ( wp_style_is('classic-theme-styles', 'enqueued') ) {
        wp_dequeue_style('classic-theme-styles');
    }

    // NÃO desregistrar jQuery Migrate para não quebrar plugins/Elementor
    // if ( ! is_admin() ) { wp_deregister_script('jquery-migrate'); }

    // Remove meta tags e links desnecessários
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'feed_links', 2);
}
add_action('wp_enqueue_scripts', 'minimun_temp_cleanup', 100);

// -----------------------------------------------------------------------------
// Desabilita emojis
// -----------------------------------------------------------------------------
function minimun_temp_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'minimun_temp_disable_emojis_tinymce');
    add_filter('wp_resource_hints', 'minimun_temp_disable_emojis_dns_prefetch', 10, 2);
}
function minimun_temp_disable_emojis_tinymce($plugins) {
    if ( is_array($plugins) ) {
        return array_diff($plugins, array('wpemoji'));
    }
    return array();
}
function minimun_temp_disable_emojis_dns_prefetch($urls, $relation_type) {
    if ( 'dns-prefetch' === $relation_type ) {
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/');
        $urls = array_diff($urls, array($emoji_svg_url));
    }
    return $urls;
}
add_action('init', 'minimun_temp_disable_emojis');

// -----------------------------------------------------------------------------
// Estilos do tema
// -----------------------------------------------------------------------------
function minimun_temp_enqueue_styles() {
    wp_enqueue_style('main-styles', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
}
add_action('wp_enqueue_scripts', 'minimun_temp_enqueue_styles');

// -----------------------------------------------------------------------------
// Carregamento condicional de scripts (exemplo)
// -----------------------------------------------------------------------------
function minimun_temp_conditional_scripts() {
    if ( is_page() || is_single() ) {
        // Enfileire aqui scripts específicos de páginas/posts
    }
    if ( is_front_page() ) {
        // Enfileire aqui scripts da página inicial
    }
}
add_action('wp_enqueue_scripts', 'minimun_temp_conditional_scripts');

// -----------------------------------------------------------------------------
// Otimização de imagens - qualidade de compressão
// -----------------------------------------------------------------------------
function minimun_temp_compress_images($quality) {
    return 85; // boa qualidade com tamanho reduzido
}
add_filter('jpeg_quality', 'minimun_temp_compress_images');
add_filter('wp_editor_set_quality', 'minimun_temp_compress_images');

// -----------------------------------------------------------------------------
// Remove query strings (?ver) apenas para visitantes e fora do preview
// -----------------------------------------------------------------------------
function minimun_temp_maybe_remove_query_strings($src) {
    if ( is_user_logged_in() || isset($_GET['elementor-preview']) ) {
        return $src; // não mexe durante edição/preview/logados
    }
    return remove_query_arg('ver', $src);
}
add_filter('script_loader_src', 'minimun_temp_maybe_remove_query_strings', 15, 1);
add_filter('style_loader_src', 'minimun_temp_maybe_remove_query_strings', 15, 1);

// -----------------------------------------------------------------------------
// Desabilita XML-RPC (se não usar)
// -----------------------------------------------------------------------------
add_filter('xmlrpc_enabled', '__return_false');

// -----------------------------------------------------------------------------
// Heartbeat: mantém no admin e no preview do Elementor
// -----------------------------------------------------------------------------
function minimun_temp_modify_heartbeat($settings) {
    $settings['interval'] = 60; // reduz carga
    return $settings;
}
add_filter('heartbeat_settings', 'minimun_temp_modify_heartbeat');

function minimun_temp_disable_heartbeat() {
    if ( is_admin() || isset($_GET['elementor-preview']) ) {
        return; // mantém Heartbeat onde é necessário
    }
    wp_deregister_script('heartbeat');
}
add_action('init', 'minimun_temp_disable_heartbeat', 1);

// -----------------------------------------------------------------------------
// Limita revisões de posts
// -----------------------------------------------------------------------------
function minimun_temp_limit_revisions($num, $post) {
    return 3; // mantém apenas 3 revisões
}
add_filter('wp_revisions_to_keep', 'minimun_temp_limit_revisions', 10, 2);

// -----------------------------------------------------------------------------
// Headers de cache somente para arquivos estáticos (NUNCA para HTML/REST/AJAX/preview/logados)
// -----------------------------------------------------------------------------
function minimun_temp_send_cache_headers() {
    if (
        is_admin()
        || is_user_logged_in()
        || isset($_GET['elementor-preview'])
        || ( defined('REST_REQUEST') && REST_REQUEST )
        || ( function_exists('wp_doing_ajax') && wp_doing_ajax() )
    ) {
        return;
    }

    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    if ( ! $ext ) {
        return;
    }

    $static_exts = array('css','js','jpg','jpeg','png','gif','svg','webp','avif','ico','woff','woff2','ttf','eot','otf','mp4','webm','ogg','mp3','pdf');

    if ( in_array($ext, $static_exts, true) ) {
        header('Cache-Control: public, max-age=31536000, immutable');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
    }
}
add_action('send_headers', 'minimun_temp_send_cache_headers');

// -----------------------------------------------------------------------------
// Debug de limites (apenas admin e quando ?debug_limits=1)
// -----------------------------------------------------------------------------
function minimun_temp_debug_limits() {
    if ( current_user_can('administrator') && isset($_GET['debug_limits']) ) {
        echo '<div style="background:#fff;padding:20px;margin:20px;border:1px solid #ccc;">';
        echo '<h3>Limites do Servidor</h3>';
        echo 'upload_max_filesize: ' . esc_html(ini_get('upload_max_filesize')) . '<br>';
        echo 'post_max_size: ' . esc_html(ini_get('post_max_size')) . '<br>';
        echo 'memory_limit: ' . esc_html(ini_get('memory_limit')) . '<br>';
        echo 'max_execution_time: ' . esc_html(ini_get('max_execution_time')) . '<br>';
        echo 'max_input_vars: ' . esc_html(ini_get('max_input_vars')) . '<br>';
        echo '</div>';
    }
}
add_action('wp_footer', 'minimun_temp_debug_limits');
