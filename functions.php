<?php
/**
 * functions.php — Minimum Temp v3.0
 * Tema ultra-leve para Landing Pages com Elementor.
 * Foco: performance máxima, zero peso no front, não interferir nas LPs.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// Setup básico
add_action( 'after_setup_theme', function() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'responsive-embeds' );
} );

// Limpeza do <head>
add_action( 'wp_enqueue_scripts', function() {
    if ( isset( $_GET['elementor-preview'] ) || isset( $_GET['elementor_library'] ) ) { return; }
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-blocks-style' );
    wp_dequeue_style( 'classic-theme-styles' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
}, 100 );

// Desabilita emojis
add_action( 'init', function() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', function( $p ) {
        return is_array( $p ) ? array_diff( $p, array( 'wpemoji' ) ) : array();
    } );
    add_filter( 'wp_resource_hints', function( $urls, $rel ) {
        if ( 'dns-prefetch' === $rel ) {
            $urls = array_diff( $urls, array( apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/' ) ) );
        }
        return $urls;
    }, 10, 2 );
} );

// Heartbeat: desativa no front, respeita WP Rocket
add_action( 'init', function() {
    if ( is_admin() || isset( $_GET['elementor-preview'] ) || isset( $_GET['elementor_library'] ) ) { return; }
    if ( defined( 'WP_ROCKET_VERSION' ) ) { return; }
    wp_deregister_script( 'heartbeat' );
}, 20 );

// Strip query strings (protege CDNs externas)
add_filter( 'script_loader_src', 'minimum_temp_strip_ver', 15 );
add_filter( 'style_loader_src', 'minimum_temp_strip_ver', 15 );
function minimum_temp_strip_ver( $src ) {
    if ( is_user_logged_in() || isset( $_GET['elementor-preview'] ) || isset( $_GET['elementor_library'] ) ) { return $src; }
    $hh = wp_parse_url( home_url(), PHP_URL_HOST );
    $sh = wp_parse_url( $src, PHP_URL_HOST );
    if ( $sh && $sh !== $hh ) { return $src; }
    return remove_query_arg( 'ver', $src );
}

// Segurança e banco leve
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'wp_revisions_to_keep', function() { return 3; }, 10, 2 );
add_filter( 'jpeg_quality', function() { return 85; } );
add_filter( 'wp_editor_set_quality', function() { return 85; } );

// Aviso admin se Elementor ausente
add_action( 'admin_init', function() {
    if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
        add_action( 'admin_notices', function() {
            $u = esc_url( network_admin_url( 'plugin-install.php?s=elementor&tab=search&type=term' ) );
            echo '<div class="notice notice-warning is-dismissible"><p><strong>Minimum Temp:</strong> Elementor é necessário.</p><p><a href="' . $u . '" class="button button-primary">Instalar Elementor</a></p></div>';
        } );
    }
} );

// Template Elementor Canvas
add_filter( 'template_include', function( $t ) {
    if ( isset( $_GET['elementor_library'] ) || get_post_meta( get_the_ID(), '_wp_page_template', true ) === 'elementor_canvas' ) {
        $c = get_template_directory() . '/canvas.php';
        if ( file_exists( $c ) ) { return $c; }
    }
    return $t;
} );
add_filter( 'theme_page_templates', function( $t ) {
    $t['elementor_canvas'] = 'Elementor Canvas (Minimum Temp)';
    return $t;
} );

// Compatibilidade WP Rocket
if ( defined( 'WP_ROCKET_VERSION' ) ) {
    remove_filter( 'script_loader_src', 'minimum_temp_strip_ver', 15 );
    remove_filter( 'style_loader_src', 'minimum_temp_strip_ver', 15 );
}

// === MELHORIAS v3.0 ===

// 1. Preconnect para CDN Elementor
add_filter( 'wp_resource_hints', function( $urls, $rel ) {
    if ( ! defined( 'ELEMENTOR_VERSION' ) ) { return $urls; }
    if ( 'preconnect' === $rel ) { $urls[] = 'https://assets.elementor.com'; }
    if ( 'dns-prefetch' === $rel ) { $urls[] = '//assets.elementor.com'; }
    return $urls;
}, 10, 2 );

// 2. Lazy load + fetchpriority primeira imagem
add_filter( 'wp_img_tag_add_loading_attr', function( $v, $img, $ctx ) {
    static $first = true;
    if ( is_admin() || isset( $_GET['elementor-preview'] ) ) { return $v; }
    if ( $first ) { $first = false; return false; }
    return 'lazy';
}, 10, 3 );
add_filter( 'wp_img_tag_attributes', function( $a, $img, $ctx ) {
    static $fp = true;
    if ( $fp && ! is_admin() && ! isset( $_GET['elementor-preview'] ) ) {
        $fp = false;
        $a['fetchpriority'] = 'high';
    }
    return $a;
}, 10, 3 );

// 3. jQuery Migrate condicional
add_action( 'wp_enqueue_scripts', function() {
    if ( is_admin() || isset( $_GET['elementor-preview'] ) || isset( $_GET['elementor_library'] ) ) { return; }
    global $wp_scripts;
    if ( ! isset( $wp_scripts->registered['jquery-migrate'] ) ) { return; }
    foreach ( $wp_scripts->queue as $h ) {
        if ( isset( $wp_scripts->registered[ $h ] ) && in_array( 'jquery-migrate', $wp_scripts->registered[ $h ]->deps, true ) ) { return; }
    }
    wp_dequeue_script( 'jquery-migrate' );
}, 101 );

// 4. Header HTTP Link preload CSS Elementor
add_action( 'send_headers', function() {
    if ( is_admin() || is_user_logged_in() || isset( $_GET['elementor-preview'] ) ) { return; }
    $ext = strtolower( pathinfo( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), PATHINFO_EXTENSION ) );
    if ( $ext ) { return; }
    if ( defined( 'ELEMENTOR_VERSION' ) ) {
        header( 'Link: <' . plugins_url( 'elementor/assets/css/frontend.min.css' ) . '>; rel=preload; as=style', false );
    }
} );

// 5. Modo LP Pura
if ( defined( 'MINIMUM_TEMP_LP_MODE' ) && MINIMUM_TEMP_LP_MODE ) {
    add_action( 'wp', function() { if ( is_feed() ) { wp_die( 'Feeds off.', '', array( 'response' => 404 ) ); } } );
    add_filter( 'comments_open', '__return_false' );
    add_filter( 'pings_open', '__return_false' );
    add_filter( 'rest_endpoints', function( $e ) {
        $ok = array( '/wp/v2/pages', '/wp/v2/posts', '/elementor/v1' );
        foreach ( $e as $r => $h ) {
            $keep = false;
            foreach ( $ok as $p ) { if ( strpos( $r, $p ) === 0 ) { $keep = true; break; } }
            if ( ! $keep ) { unset( $e[ $r ] ); }
        }
        return $e;
    } );
}

// 6. CSS crítico inline
add_action( 'wp_head', function() {
    if ( is_admin() || isset( $_GET['elementor-preview'] ) ) { return; }
    echo '<style>body{margin:0;padding:0;-webkit-font-smoothing:antialiased}.elementor-section-wrap{display:block}.elementor-widget-wrap{position:relative}img{max-width:100%;height:auto}</style>';
}, 1 );

// 7. Cloudflare Early Hints
add_action( 'send_headers', function() {
    if ( ! isset( $_SERVER['HTTP_CF_RAY'] ) || is_admin() || is_user_logged_in() || isset( $_GET['elementor-preview'] ) ) { return; }
    $ext = strtolower( pathinfo( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), PATHINFO_EXTENSION ) );
    if ( $ext ) { return; }
    header( 'CF-Early-Hints: on', false );
} );

// 8. Health Check admin
add_action( 'admin_menu', function() {
    add_dashboard_page( 'MT Performance', 'MT Performance', 'manage_options', 'minimum-temp-health', 'minimum_temp_health_page' );
} );
function minimum_temp_health_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    echo '<div class="wrap"><h1>Minimum Temp — Health Check</h1>';
    echo '<table class="widefat striped" style="max-width:800px;margin-top:20px"><tbody>';
    echo '<tr><td><strong>Versão</strong></td><td>3.0.0</td></tr>';
    echo '<tr><td><strong>Elementor</strong></td><td>' . ( defined( 'ELEMENTOR_VERSION' ) ? 'Sim (' . ELEMENTOR_VERSION . ')' : 'Não' ) . '</td></tr>';
    echo '<tr><td><strong>WP Rocket</strong></td><td>' . ( defined( 'WP_ROCKET_VERSION' ) ? 'Sim (' . WP_ROCKET_VERSION . ')' : 'Não' ) . '</td></tr>';
    echo '<tr><td><strong>LP Mode</strong></td><td>' . ( ( defined( 'MINIMUM_TEMP_LP_MODE' ) && MINIMUM_TEMP_LP_MODE ) ? 'On' : 'Off' ) . '</td></tr>';
    echo '<tr><td><strong>Cloudflare</strong></td><td>' . ( isset( $_SERVER['HTTP_CF_RAY'] ) ? 'Detectado' : 'Não' ) . '</td></tr>';
    global $wp_scripts;
    $jqm = isset( $wp_scripts->registered['jquery-migrate'] ) ? 'Registrado' : 'Não registrado';
    echo '<tr><td><strong>jQuery Migrate</strong></td><td>' . $jqm . '</td></tr>';
    echo '</tbody></table></div>';
}
