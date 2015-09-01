(function($) {

    var $plugin,
        $wpTable,
        $actions,
        $mappedCheckboxes,
        checked = 0;

    $(initialize);
    function initialize() {
        $wpTable = $('.wp-list-table');

        var $dwlContainer = $('.dwl-plugin-container');

        if($dwlContainer.length == 0) {
            $wpTable.after('<div class="dwl-plugin-container"></div>');
            $dwlContainer = $('.dwl-plugin-container');
        }

        var template = $('#dwl-' + plugin.prefix).html();
        $dwlContainer.append(template);

        $plugin = $('.dwl-' + plugin.prefix);

        initializeActions();
        initializeTable();
    }

    function initializeActions() {
        $actions = $plugin.find('button');

        $actions.on('click', function() {
             var $button = $(this);

            $plugin.find('input[name="action"]').val($button.data('action'));

            // fetch selected posts
            var posts = [];

            $mappedCheckboxes.each(function() {
                var $checkbox = $(this);

                if($checkbox.is(':checked')) {
                    posts.push($checkbox.val());
                }
            });

            // fetch selected taxonomies
            var taxonomies = [];

            $plugin.find('input[name="tax_input['+ plugin.taxonomy +'][]"]').each(function() {
                var $checkbox = $(this);

                if($checkbox.is(':checked')) {
                    taxonomies.push($checkbox.val());
                }
            });

            $plugin.find('input[name="posts"]').val(posts);
            $plugin.find('input[name="taxonomies"]').val(taxonomies);

            $plugin.submit();
        });
    }

    function initializeTable() {
        $mappedCheckboxes = $wpTable.find('input[name="' + plugin.map + '[]"]');

        $wpTable.find('.check-column input[type="checkbox"]').on('change', function() {
            var $checkbox = $(this);

            if($checkbox.parent().hasClass('manage-column')) {
                // check all checkbox
                if($checkbox.is(':checked')) {
                    // select all that map
                    checked = $mappedCheckboxes.length;
                } else {
                    // reset
                    checked = 0;
                }
            } else {
                checked = 0;
                $mappedCheckboxes.each(function() {
                    if($(this).is(':checked')) checked++;
                });
            }

            if(checked == 0) {
                hideAction();
            } else {
                showAction();
            }
        });
    }

    function showAction() {
        $wpTable.addClass('dwl-visible');
        $plugin.addClass('dwl-visible');
    }

    function hideAction() {
        $wpTable.removeClass('dwl-visible');
        $plugin.removeClass('dwl-visible');
    }

})(jQuery);