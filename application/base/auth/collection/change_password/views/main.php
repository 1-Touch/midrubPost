<main role="main" class="main">
    <section class="member-access-form">
        <?php echo form_open('', array('class' => 'form-change-password', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
            <div class="cl-form-column" id="page-form">
                <h1>
                    <?php
                    echo (md_the_single_content_meta('auth_change_password_details_title'))?md_the_single_content_meta('auth_change_password_details_title'):$this->lang->line('auth_change_password_page_title');
                    ?>
                </h1>
                <h2>
                    <?php
                    echo (md_the_single_content_meta('auth_change_password_details_under_title'))?md_the_single_content_meta('auth_change_password_details_under_title'):$this->lang->line('auth_change_password_page_under_title');
                    $sign_in = the_url_by_page_role('sign_in') ? the_url_by_page_role('sign_in') : site_url('auth/signin');
                    ?>                    
                    <a href="<?php echo $sign_in; ?>">
                        <?php echo $this->lang->line('auth_change_password_signin_link'); ?>
                    </a>
                </h2>
                <div class="form-label-group input-password">
                    <input class="form-control new-password" type="password" placeholder="<?php echo $this->lang->line('auth_change_password_enter_password'); ?>" autocomplete="new-password" required>
                </div>
                <div class="form-label-group input-password">
                    <input class="form-control repeat-password" type="password" placeholder="<?php echo $this->lang->line('auth_change_password_repeat_password'); ?>" autocomplete="new-password" required>
                </div>
                <button class="submit-change-password">
                    <?php echo $this->lang->line('auth_change_password_btn'); ?>
                    <input class="form-control reset-code" type="hidden" value="<?php echo $this->input->get('reset', true); ?>">
                    <input class="form-control user-id" type="hidden" value="<?php echo $this->input->get('f', true); ?>">
                </button>
                <div class="form-label-group">
            </div>
        <?php echo form_close() ?>
    </section>
</main>