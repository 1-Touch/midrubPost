<section class="pixabay-page" data-pixabay="<?php echo get_option('app_post_pixabay_api_key'); ?>">
    <div class="container-fluid">
        <?php
        echo form_open('#', array('class' => 'search-pixabay-photos'));
        ?>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="icon-magnifier"></i>
                </span>
            </div>
            <input type="text" class="form-control search-input" placeholder="<?php echo $this->lang->line('search_photos_by_keywords'); ?>" required>
            <div class="input-group-append">
                <button type="submit"><?php echo $this->lang->line('search_on_pixabay'); ?></button>
            </div>
        </div>
        <?php
        echo form_close();
        ?>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <p class="no-results-found">
                <?php echo $this->lang->line('no_results_found'); ?>
            </p>
        </div>
    </div>
</section>