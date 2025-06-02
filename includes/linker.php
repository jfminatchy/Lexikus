<?php
// Logique de liaison automatique des termes du lexique dans les contenus sélectionnés

add_filter('the_content', 'lexikus_auto_link_terms', 20);

function lexikus_auto_link_terms($content) {
    if (is_admin() || !in_the_loop() || !is_main_query()) return $content;

    // Récupération des options du plugin
    $post_types = get_option('lexikus_linkable_types', []);
    $taxonomy   = get_option('lexikus_taxonomy', '');
    $term_id    = get_option('lexikus_term', '');
    $css        = get_option('lexikus_link_css', '');
    $definition_post_type = get_option('lexikus_post_type', '');

    global $post;
    if (!$post || !in_array($post->post_type, (array)$post_types)) return $content;

    // Exclure l'article de définition lui-même
    $current_id = $post->ID;

    // Récupérer tous les termes du lexique (posts de définitions)
    $terms = lexikus_get_definitions($taxonomy, $definition_post_type, $term_id);
    if (empty($terms)) return $content;

    // Pour chaque terme, remplacer la première occurrence (hors balises h1-h6)
    foreach ($terms as $term_post) {
        if ($term_post->ID == $current_id) continue;
        $term = $term_post->post_title;
        $term_link = get_permalink($term_post->ID);
        $term_excerpt = $term_post->post_excerpt;
        $term_title = esc_attr($term);
        $style = $css ? 'style="' . esc_attr($css) . '"' : '';
        $tooltip = 'title="' . $term_title;
        $tooltip .= $term_excerpt ? ' : ' . $term_excerpt : '';
        $tooltip .= '"';
        $pattern = '/(<h[1-6][^>]*>.*?<\/h[1-6]>)/is';
        $segments = preg_split($pattern, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($segments as $i => $seg) {
            if (!preg_match($pattern, $seg)) {
                $segments[$i] = lexikus_link_first_occurrence($seg, $term, $term_link, $style, $tooltip);
            }
        }
        $content = implode('', $segments);
    }
    return $content;
}

function lexikus_get_definition_ids($taxonomy, $post_type, $term_id) {
    $args = [
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
    ];
    if ($term_id && $taxonomy) {
        $args['tax_query'] = [
            [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $term_id,
            ]
        ];
    }
    return get_posts($args);
}

function lexikus_get_definitions($taxonomy, $post_type, $term_id) {
    $args = [
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];
    if ($term_id && $taxonomy) {
        $args['tax_query'] = [
            [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $term_id,
            ]
        ];
    }
    $query = new WP_Query($args);
    return $query->posts;
}

function lexikus_link_first_occurrence($content, $term, $link, $style, $tooltip) {
    $pattern = '/(\b' . preg_quote($term, '/') . '\b)/iu';
    $count = 0;
    $content = preg_replace_callback($pattern, function($matches) use ($link, $style, $tooltip, &$count) {
        if ($count++ === 0) {
            // $matches[0] contient le texte trouvé avec la casse d'origine
            return '<a href="' . esc_url($link) . '" class="lexikus-link" ' . $style . ' ' . $tooltip . '>' . $matches[0] . '</a>';
        }
        return $matches[0];
    }, $content, 1);
    return $content;
}
