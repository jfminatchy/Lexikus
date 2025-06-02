jQuery(document).ready(function($) {
    // Rafraîchit les taxonomies selon le post type
    $('#lexikus_post_type').on('change', function() {
        var postType = $(this).val();
        $.post(lexikusAjax.ajax_url, {
            action: 'lexikus_get_taxonomies',
            post_type: postType,
            nonce: lexikusAjax.nonce
        }, function(response) {
            if (response.success && response.data.options) {
                $('#lexikus_term').html(response.data.options);
                $('#lexikus_term').trigger('change'); // Déclenche le chargement des définitions
            }
        });
    });

    // Rafraîchit dynamiquement la liste des définitions selon la taxonomie/terme sélectionné
    function refreshDefinitionsList() {
        var postType = $('#lexikus_post_type').val();
        var taxonomy = $('#lexikus_term option:selected').closest('optgroup').data('taxonomy') || '';
        var termId = $('#lexikus_term option:selected').val() || '';

        $('#lexikus_taxonomy').val(taxonomy);

        $('#lexikus-definitions-list').html('Chargement…');
        $.post(lexikusAjax.ajax_url, {
            action: 'lexikus_get_definitions',
            post_type: postType,
            taxonomy: taxonomy,
            term_id: termId,
            nonce: lexikusAjax.nonce
        }, function(response) {
            if (response.success && response.data.html) {
                $('#lexikus-definitions-list').html(response.data.html);
            } else {
                $('#lexikus-definitions-list').html('<em>Erreur lors du chargement des définitions.</em>');
            }
        });
    }

    // Rafraîchit le listing quand on change de post type ou de taxonomie
    $('#lexikus_post_type').on('change', function() {
        // La taxonomie va être rechargée, donc le déclencheur sera la suite
        // Mais on recharge aussi le listing pour le cas où il n'y a pas de taxonomie
        setTimeout(refreshDefinitionsList, 200);
    });
    $(document).on('change', '#lexikus_term', function() {
        refreshDefinitionsList();
    });

    // Chargement initial
    refreshDefinitionsList();
});
