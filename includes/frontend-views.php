<script type="text/template" id="dwl-<?php echo $this->prefix ?>">
    <form class="dwl-<?php echo $this->prefix ?> dwl-floating-box" name="dwl-<?php echo $this->prefix ?>" method="POST"
          action="<?php echo admin_url('admin.php') ?>">
        <input type="hidden" name="action">

        <input type="hidden" name="posts">
        <input type="hidden" name="taxonomies">

        <div class="wp-filter">
            <h3><?php echo $this->label ?></h3>
        </div>

        <div class="dwl-checklist">
            <?php

            $args = array(
                'descendants_and_self' => 0,
                'selected_cats' => false,
                'popular_cats' => false,
                'walker' => null,
                'taxonomy' => $this->taxonomy,
                'checked_ontop' => true
            );

            wp_terms_checklist(0, $args);

            ?>
        </div>

        <div class="dwl-actions">
            <?php foreach ($this->actions as $action => $label) : ?>

                <button type="button" class="button" data-action="<?php echo $this->prefix . '-' . $action ?>"><?php echo $label ?></button>

            <?php endforeach ?>
        </div>
    </form>
</script>