<main role="main" class="main">
    <section class="member-access-form">
        <?php echo form_open('', array('class' => 'form-signup', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
        <div class="cl-form-column" id="page-form">
            <h1>
                <?php
                echo (md_the_single_content_meta('auth_signup_details_title')) ? md_the_single_content_meta('auth_signup_details_title') : $this->lang->line('auth_signup_page_title');
                ?>
            </h1>
            <h2>
                <?php
                echo (md_the_single_content_meta('auth_signup_details_under_title')) ? md_the_single_content_meta('auth_signup_details_under_title') : $this->lang->line('auth_signup_page_under_title');
                $sign_in = the_url_by_page_role('sign_in') ? the_url_by_page_role('sign_in') : site_url('auth/signin');
                ?>
                <a href="<?php echo $sign_in; ?>">
                    <?php echo $this->lang->line('auth_signup_signin_link'); ?>
                </a>
            </h2>
            <div class="form-label-group">
                <input class="form-control first-name" type="text" placeholder="<?php echo $this->lang->line('auth_signup_first_name'); ?>" required>
            </div>
            <div class="form-label-group">
                <input class="form-control last-name" type="text" placeholder="<?php echo $this->lang->line('auth_signup_last_name'); ?>" required>
            </div>
            <div class="form-label-group">
                <input class="form-control username" type="text" placeholder="<?php echo $this->lang->line('auth_signup_user_name'); ?>" required>
            </div>
            <div class="form-label-group">
                <input class="form-control email" type="email" placeholder="<?php echo $this->lang->line('auth_signup_email'); ?>" required>
            </div>
            <div class="form-label-group">
                <input class="form-control password" type="password" name="password" placeholder="<?php echo $this->lang->line('auth_signup_password'); ?>" autocomplete="new-password" required>
            </div>
            <div class="form-label-group">
                <div class='full-input'>
                    <select class="selected-plan">
                        <?php

                        // Get visible plans
                        $plans = (new MidrubBase\Classes\Plans\Read)->get_plans(array('visible' => 0));

                        // Verify if plans exists
                        if ($plans) {

                            foreach ($plans as $plan) {

                                $selected = '';

                                if ( $this->input->get('plan', TRUE) == $plan['plan_id'] ) {
                                    $selected = ' selected';
                                }

                                echo '<option value="' . $plan['plan_id'] . '"' . $selected . '>' . $plan['plan_name'] . '</option>';

                            }

                        }

                        ?>
                    </select>
                </div>
            </div>
            <button class="submit-signup">
                <?php echo $this->lang->line('auth_signup_get_started'); ?>
            </button>
            <div class="form-label-group">
                <?php
                if (md_auth_social_access_options()) {

                    // Set the sign up page
                    $sign_up = the_url_by_page_role('sign_up')?the_url_by_page_role('sign_up'):site_url('auth/signup');

                    // List available signup options
                    foreach (md_auth_social_access_options() as $option) {

                        //  Display social option
                        echo '<div class="row">'
                            . '<div class="col-12">'
                                . '<a href="' . $sign_up . '/' . strtolower($option->name) . '" class="sign-up-btn sign-up-' . strtolower($option->name) . '-btn" style="background-color: ' . $option->color . ';">'
                                    . $option->icon
                                    . $this->lang->line('auth_signup_continue_with') . ' ' . ucwords(str_replace(array('_', '-'), ' ', $option->name))
                                . '</a>'
                            . '</div>'
                        . '</div>';

                    }
                }
                ?>
            </div>
            <p>
                <?php

                echo (md_the_single_content_meta('auth_signup_details_accept_terms')) ? md_the_single_content_meta('auth_signup_details_accept_terms') : $this->lang->line('auth_signup_page_approve_terms');
                
                if ( the_url_by_page_role('terms_and_conditions') ) {
                    echo ' <a href="' . the_url_by_page_role('terms_and_conditions') . '">' . $this->lang->line('auth_signup_terms_of_service') . '</a>';
                }

                if ( the_url_by_page_role('privacy_policy') ) {
                    echo ' ' . $this->lang->line('auth_signup_and') . ' <a href="' . the_url_by_page_role('privacy_policy') . '">' . $this->lang->line('auth_signup_privacy_policy') . '</a>';
                }                
                
                ?>
            </p>
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