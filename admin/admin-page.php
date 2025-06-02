<?php
// Page d'administration Lexikus : sélection, aperçu CSS, etc.

add_action('admin_menu', function() {
    // Ajout du JS d'administration Lexikus
    add_action('admin_enqueue_scripts', function($hook) {
        if ($hook === 'settings_page_lexikus') {
            wp_enqueue_script('lexikus-admin', plugins_url('admin/assets/lexikus-admin.js', dirname(__FILE__)), ['jquery'], null, true);
            wp_localize_script('lexikus-admin', 'lexikusAjax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('lexikus_ajax_nonce')
            ]);
        }
    });
    // Ajout correct de la page d'options
    add_options_page(
        'Lexikus',
        'Lexikus',
        'manage_options',
        'lexikus',
        'lexikus_admin_page_render'
    );
});

// récupérer le nombre d'occurrence d'une définition dans l'ensemble des posts correspondants à la sélection du type de contenu lié
function lexikus_get_definition_occurrences(WP_Post $definition, $posts) {
    $count = 0;
    if ($posts) {
        foreach ($posts as $post) {
            if (stripos($post->post_content, $definition->post_title) !== false && $definition->ID != $post->ID) {
                $count++;
            }
        }
    }

    return $count;
}
    

// Handler AJAX pour récupérer dynamiquement les définitions (termes d'une taxonomie)
add_action('wp_ajax_lexikus_get_definitions', function() {
    check_ajax_referer('lexikus_ajax_nonce', 'nonce');
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
    $taxonomy = isset($_POST['taxonomy']) ? sanitize_text_field($_POST['taxonomy']) : '';
    $term_id = isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;
    $linkable_types = get_option('lexikus_linkable_types', []);

    $linkableArgs = [
        'post_type' => $linkable_types,
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];
    $linkableQuery = new WP_Query($linkableArgs);
    $linkablePosts = $linkableQuery->posts;
    wp_reset_postdata();

    if (!$post_type) {
        wp_send_json_error(['message' => 'Type de contenu manquant']);
    }

    $args = [
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];

    // Si un terme est sélectionné, filtre sur ce terme
    if ($term_id) {
        $args['tax_query'] = [
            [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $term_id,
            ]
        ];
    }

    $query = new WP_Query($args);
    $list = '<table>';
    $list .= '<thead><tr><th>Titre</th><th>Nombre d\'occurrences</th></tr></thead><tbody>';
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $list .= '<tr><td>' . esc_html(get_the_title()) . '</td><td>' . lexikus_get_definition_occurrences(get_post(), $linkablePosts) . '</td></tr>';
        }
        $list .= '</tbody></table>';
    } else {
        $list = '<em>Aucune définition trouvée.</em>';
    }
    wp_reset_postdata();
    wp_send_json_success([
        'html' => $list,
        'post_type' => $post_type,
        'taxonomy' => $taxonomy,
        'term_id' => $term_id,
    ]);
});
add_action('wp_ajax_lexikus_get_taxonomies', function() {
    check_ajax_referer('lexikus_ajax_nonce', 'nonce');
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
    $taxonomies = get_object_taxonomies($post_type, 'objects');
    $selected_term = get_option('lexikus_term', '');
    $options = '<option value="">Sélectionnez un terme</option>';
    /** @var WP_Taxonomy $tax */
    foreach ($taxonomies as $tax) {
        $terms = get_terms(['taxonomy' => $tax->name, 'hide_empty' => false]);
        if (!empty($terms) && !is_wp_error($terms)) {
            $options .= '<optgroup data-taxonomy="' . esc_attr($tax->name) . '" label="' . esc_attr($tax->labels->singular_name) . '">';
            foreach ($terms as $term) {
                $selected = ($term->term_id == $selected_term) ? 'selected' : '';
                $options .= '<option value="' . esc_attr($term->term_id) . '" ' . $selected . '>' . esc_html($term->name) . '</option>';
            }
            $options .= '</optgroup>';
        }
    }
    wp_send_json_success(['options' => $options]);
});


function lexikus_admin_page_render() {
    ?>
    <div class="wrap">
        <h1>Lexikus – Gestion du lexique</h1>
        <div id="lexikus-admin">
            <section>
    <h2>Sélection du type de contenu et de la taxonomie</h2>
    <form method="post" action="" id="lexikus-form">
        <?php
        // Sécurité du formulaire
        wp_nonce_field('lexikus_content_taxonomy', 'lexikus_nonce');

        // Gestion de la sauvegarde
        if (isset($_POST['lexikus_post_type'], $_POST['lexikus_term']) && isset($_POST['lexikus_nonce']) && isset($_POST['lexikus_taxonomy'])) {
            update_option('lexikus_post_type', sanitize_text_field($_POST['lexikus_post_type']));
            update_option('lexikus_taxonomy', sanitize_text_field($_POST['lexikus_taxonomy']));
            update_option('lexikus_term', intval($_POST['lexikus_term']));
            echo '<div class="updated"><p>Configuration enregistrée.</p></div>';
        }

        // Récupération des post types publics
        $post_types = get_post_types(['public' => true], 'objects');
        $selected_post_type = get_option('lexikus_post_type', 'post');
        $selected_taxonomy = get_option('lexikus_taxonomy', '');
        $selected_term = get_option('lexikus_term', '');
        ?>
        <label for="lexikus_post_type">Type de contenu :</label>
        <select name="lexikus_post_type" id="lexikus_post_type">
            <option value="">Sélectionnez un type de contenu</option>
            <?php foreach ($post_types as $pt) : ?>
                <option value="<?php echo esc_attr($pt->name); ?>" <?php selected($selected_post_type, $pt->name); ?>><?php echo esc_html($pt->labels->singular_name); ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <input type="hidden" id="lexikus_taxonomy" name="lexikus_taxonomy" value="<?php echo esc_attr($selected_taxonomy); ?>">
        <?php
        // Récupération des taxonomies associées au post type sélectionné
        $taxonomies = get_object_taxonomies($selected_post_type, 'objects');
        ?>
        <label for="lexikus_term">Terme de taxonomie :</label>
        <select name="lexikus_term" id="lexikus_term">
            <option value="">Sélectionnez un terme</option>
            <?php
            foreach ($taxonomies as $tax) {
                $terms = get_terms(['taxonomy' => $tax->name, 'hide_empty' => false]);
                if (!empty($terms) && !is_wp_error($terms)) {
                    echo '<optgroup data-taxonomy="' . esc_attr($tax->name) . '" label="' . esc_attr($tax->labels->singular_name) . '">';
                    foreach ($terms as $term) {
                        $selected = ($term->term_id == $selected_term) ? 'selected' : '';
                        echo '<option value="' . esc_attr($term->term_id) . '" ' . $selected . '>' . esc_html($term->name) . '</option>';
                    }
                    echo '</optgroup>';
                }
            }
            ?>
        </select>
        <br><br>
        <input type="submit" class="button button-primary" value="Enregistrer" />
    </form>
</section>
            <section>
                <h2>Listing dynamique des définitions</h2>
                <div id="lexikus-definitions-list">Chargement…</div>
            </section>
            <section>
    <h2>Sélection des types de contenus à lier</h2>
    <form method="post" action="" id="lexikus-linkable-types-form">
        <?php
        wp_nonce_field('lexikus_linkable_types', 'lexikus_linkable_types_nonce');
        // Gestion de la sauvegarde
        if (isset($_POST['lexikus_linkable_types']) && isset($_POST['lexikus_linkable_types_nonce']) && wp_verify_nonce($_POST['lexikus_linkable_types_nonce'], 'lexikus_linkable_types')) {
            $types = array_map('sanitize_text_field', (array)$_POST['lexikus_linkable_types']);
            update_option('lexikus_linkable_types', $types);
            echo '<div class="updated"><p>Types de contenus à lier enregistrés.</p></div>';
        }
        $post_types = get_post_types(['public' => true], 'objects');
        $selected_types = get_option('lexikus_linkable_types', []);
        ?>
        <fieldset>
            <legend>Types de contenus à enrichir :</legend>
            <?php foreach ($post_types as $pt) : ?>
                <label style="display:block; margin-bottom:4px;">
                    <input type="checkbox" name="lexikus_linkable_types[]" value="<?php echo esc_attr($pt->name); ?>" <?php checked(in_array($pt->name, $selected_types)); ?> />
                    <?php echo esc_html($pt->labels->singular_name); ?>
                </label>
            <?php endforeach; ?>
        </fieldset>
        <br>
        <input type="submit" class="button button-primary" value="Enregistrer" />
    </form>
</section>
            <section>
    <h2>Section d’édition CSS avec aperçu</h2>
    <form method="post" action="" id="lexikus-css-form">
        <?php
        wp_nonce_field('lexikus_css', 'lexikus_css_nonce');
        // Gestion de la sauvegarde
        if (isset($_POST['lexikus_link_css']) && isset($_POST['lexikus_css_nonce']) && wp_verify_nonce($_POST['lexikus_css_nonce'], 'lexikus_css')) {
            update_option('lexikus_link_css', wp_strip_all_tags($_POST['lexikus_link_css']));
            echo '<div class="updated"><p>CSS enregistré.</p></div>';
        }
        $css = get_option('lexikus_link_css', 'a.lexikus-link { text-decoration: underline dotted; color: #0073aa; cursor: pointer; }');
        ?>
        <label for="lexikus_link_css">CSS appliqué aux liens lexicaux :</label><br>
        <textarea id="lexikus_link_css" name="lexikus_link_css" rows="5" cols="60" style="font-family:monospace; width:100%;"><?php echo esc_textarea($css); ?></textarea>
        <br><br>
        <input type="submit" class="button button-primary" value="Enregistrer le CSS" />
    </form>
    <h3>Aperçu du rendu :</h3>
    <div id="lexikus-css-preview-container" style="padding:10px; border:1px solid #ccc; display:inline-block; background:#f9f9f9;">
        <a href="#" class="lexikus-link" id="lexikus-css-preview-link">Exemple de lien lexical</a>
    </div>
    <style id="lexikus-css-preview-style">
        a.lexikus-link {
            <?php echo $css; ?>
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var textarea = document.getElementById('lexikus_link_css');
        var styleTag = document.getElementById('lexikus-css-preview-style');
        textarea.addEventListener('input', function() {
            styleTag.textContent = 'a.lexikus-link { ' + textarea.value + ' }';
        });
    });
    </script>
</section>
        </div>
    </div>
    <?php
}
