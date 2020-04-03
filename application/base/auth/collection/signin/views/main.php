<main role="main" class="main">
    <section class="member-access-form">
        <?php echo form_open('', array('class' => 'form-signin', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
        <div class="cl-form-column" id="page-form">
            <h1>
                <?php
                echo (md_the_single_content_meta('auth_signin_details_title')) ? md_the_single_content_meta('auth_signin_details_title') : $this->lang->line('auth_signin_sign_in_title');
                ?>
            </h1>
            <h2>
                <?php
                echo (md_the_single_content_meta('auth_signin_details_under_title')) ? md_the_single_content_meta('auth_signin_details_under_title') : $this->lang->line('auth_signin_forgot_your_password');
                ?>
                <a href="<?php echo base_url('auth/reset'); ?>">
                    <?php
                    echo $this->lang->line('auth_signin_reset_it');
                    ?>
                </a>
            </h2>
            <div class="form-label-group">
                <input class="form-control email" type="text" placeholder="<?php echo $this->lang->line('auth_signin_email_or_username'); ?>" required>
            </div>
            <div class="form-label-group input-password">
                <input class="form-control password" type="password" placeholder="<?php echo $this->lang->line('auth_signin_password'); ?>" autocomplete="new-password" required>
            </div>
            <div class="form-label-group remember-me-input">
                <label class="remember-me-label" title="<?php echo $this->lang->line('auth_signin_remember'); ?>">
                    <input type="checkbox" class="remember-me" checked>
                    <span>&#10003;</span>
                </label>
                <?php echo $this->lang->line('auth_signin_remember_me'); ?>
            </div>
            <button class="submit-signin">
                <?php echo $this->lang->line('auth_signin_continue'); ?>
            </button>
            <div class="form-label-group">
                <?php
                if ( md_auth_social_access_options() ) {

                    // Set the sign in page
                    $sign_in = the_url_by_page_role('sign_in') ? the_url_by_page_role('sign_in') : site_url('auth/signin');

                    // List available sign in options
                    foreach (md_auth_social_access_options() as $option) {

                        // Set the sign in page
                        $sign_in = the_url_by_page_role('sign_in') ? the_url_by_page_role('sign_in') : site_url('auth/signin');

                        echo '<div class="row">'
                            . '<div class="col-12">'
                            . '<a href="' . $sign_in . '/' . strtolower($option->name) . '" class="sign-in-btn sign-in-' . strtolower($option->name) . '-btn" style="background-color: ' . $option->color . ';">'
                            . $option->icon
                            . $this->lang->line('auth_signin_continue_with') . ' ' . ucwords(str_replace(array('_', '-'), ' ', $option->name))
                            . '</a>'
                            . '</div>'
                            . '</div>';
                    }
                }
                ?>
            </div>
            <div class="form-label-group">
                <?php
                // Verify if signup is disabled
                if (get_option('enable_registration')) {

                    $url = the_url_by_page_role('sign_up') ? the_url_by_page_role('sign_up') : site_url('auth/signup');

                    echo '<p class="text-center">'
                        . '<a href="' . $url . '">'
                        . $this->lang->line('auth_signin_or_sign_up')
                        . '</a>'
                        . '</p>';
                }
                ?>
            </div>
        </div>
        <?php echo form_close() ?>
    </section>
    <?php
    if (md_the_component_variable('auth_error')) {

        echo '<div class="notification show">'
            . md_the_component_variable('auth_error')
            . '</div>';
    }
    ?>
</main>