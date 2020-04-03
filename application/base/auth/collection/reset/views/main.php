<main role="main" class="main">
    <section class="member-access-form">
        <?php echo form_open('', array('class' => 'form-reset', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
            <div class="cl-form-column" id="page-form">
                <h1>
                    <?php
                    echo (md_the_single_content_meta('auth_reset_details_title'))?md_the_single_content_meta('auth_reset_details_title'):$this->lang->line('auth_reset_page_title');
                    ?>
                </h1>
                <h2>
                    <?php
                    echo (md_the_single_content_meta('auth_reset_details_under_title'))?md_the_single_content_meta('auth_reset_details_under_title'):$this->lang->line('auth_reset_page_under_title');
                    $sign_in = the_url_by_page_role('sign_in') ? the_url_by_page_role('sign_in') : site_url('auth/signin');
                    ?>                    
                    <a href="<?php echo $sign_in; ?>">
                        <?php echo $this->lang->line('auth_reset_signin_link'); ?>
                    </a>
                </h2>
                <div class="form-label-group">
                    <input class="form-control email" type="email" placeholder="<?php echo $this->lang->line('auth_reset_email'); ?>" required>
                </div>
                <button class="submit-reset">
                    <?php echo $this->lang->line('auth_reset_btn'); ?>
                </button>
                <div class="form-label-group">
            </div>
        <?php echo form_close() ?>
    </section>
</main>