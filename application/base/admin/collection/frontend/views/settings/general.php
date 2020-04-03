<div class="frontend-settings">
    <div class="row">
        <div class="col-lg-2 col-lg-offset-1">
            <div class="row">
                <div class="col-lg-12">
                    <?php md_include_component_file(MIDRUB_BASE_ADMIN_FRONTEND . 'views/settings/menu.php'); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-12 settings-area">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="icon-login"></i>
                            <?php echo $this->lang->line('frontend_settings_member_access'); ?>
                        </div>
                        <div class="panel-body">
                            <ul class="settings-list-options">
                                <li>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-xs-12">
                                            <h4>
                                                <?php echo $this->lang->line('frontend_settings_home_page'); ?>
                                            </h4>
                                            <p>
                                                <?php echo $this->lang->line('frontend_settings_home_page_description'); ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 text-right">
                                            <?php
                                            md_get_option_dropdown(
                                                'settings_home_page',
                                                array(
                                                    'search' => true,
                                                    'words' => array(
                                                        'select_btn' => $this->lang->line('frontend_settings_select_page'),
                                                        'search_text' => $this->lang->line('frontend_settings_search_page'),
                                                    )
                                                )
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-xs-12">
                                            <h4>
                                                <?php echo $this->lang->line('frontend_settings_sign_in_page'); ?>
                                            </h4>
                                            <p>
                                                <?php echo $this->lang->line('frontend_settings_sign_in_page_description'); ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 text-right">
                                            <?php
                                            md_get_option_dropdown(
                                                'settings_auth_sign_in_page',
                                                array(
                                                    'search' => true,
                                                    'words' => array(
                                                        'select_btn' => $this->lang->line('frontend_settings_select_page'),
                                                        'search_text' => $this->lang->line('frontend_settings_search_page'),
                                                    )
                                                )
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-xs-12">
                                            <h4>
                                                <?php echo $this->lang->line('frontend_settings_sign_up_page'); ?>
                                            </h4>
                                            <p>
                                                <?php echo $this->lang->line('frontend_settings_sign_up_page_description'); ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 text-right">
                                            <?php
                                            md_get_option_dropdown(
                                                'settings_auth_sign_up_page',
                                                array(
                                                    'search' => true,
                                                    'words' => array(
                                                        'select_btn' => $this->lang->line('frontend_settings_select_page'),
                                                        'search_text' => $this->lang->line('frontend_settings_search_page'),
                                                    )
                                                )
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-xs-12">
                                            <h4>
                                                <?php echo $this->lang->line('frontend_settings_reset_password_page'); ?>
                                            </h4>
                                            <p>
                                                <?php echo $this->lang->line('frontend_settings_reset_password_page_description'); ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 text-right">
                                            <?php
                                            md_get_option_dropdown(
                                                'settings_auth_reset_password_page',
                                                array(
                                                    'search' => true,
                                                    'words' => array(
                                                        'select_btn' => $this->lang->line('frontend_settings_select_page'),
                                                        'search_text' => $this->lang->line('frontend_settings_search_page'),
                                                    )
                                                )
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-xs-12">
                                            <h4>
                                                <?php echo $this->lang->line('frontend_settings_change_password_page'); ?>
                                            </h4>
                                            <p>
                                                <?php echo $this->lang->line('frontend_settings_change_password_page_description'); ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 text-right">
                                            <?php
                                            md_get_option_dropdown(
                                                'settings_auth_change_password_page',
                                                array(
                                                    'search' => true,
                                                    'words' => array(
                                                        'select_btn' => $this->lang->line('frontend_settings_select_page'),
                                                        'search_text' => $this->lang->line('frontend_settings_search_page'),
                                                    )
                                                )
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-xs-12">
                                            <h4>
                                                <?php echo $this->lang->line('frontend_privacy_policy'); ?>
                                            </h4>
                                            <p>
                                                <?php echo $this->lang->line('frontend_privacy_policy_description'); ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 text-right">
                                            <?php
                                            md_get_option_dropdown(
                                                'settings_auth_privacy_policy_page',
                                                array(
                                                    'search' => true,
                                                    'words' => array(
                                                        'select_btn' => $this->lang->line('frontend_settings_select_page'),
                                                        'search_text' => $this->lang->line('frontend_settings_search_page'),
                                                    )
                                                )
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-xs-12">
                                            <h4>
                                                <?php echo $this->lang->line('frontend_cookies'); ?>
                                            </h4>
                                            <p>
                                                <?php echo $this->lang->line('frontend_cookies_description'); ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 text-right">
                                            <?php
                                            md_get_option_dropdown(
                                                'settings_auth_cookies_page',
                                                array(
                                                    'search' => true,
                                                    'words' => array(
                                                        'select_btn' => $this->lang->line('frontend_settings_select_page'),
                                                        'search_text' => $this->lang->line('frontend_settings_search_page'),
                                                    )
                                                )
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-xs-12">
                                            <h4>
                                                <?php echo $this->lang->line('frontend_terms_and_conditions'); ?>
                                            </h4>
                                            <p>
                                                <?php echo $this->lang->line('frontend_terms_and_conditions_description'); ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 text-right">
                                            <?php
                                            md_get_option_dropdown(
                                                'settings_auth_terms_and_conditions_page',
                                                array(
                                                    'search' => true,
                                                    'words' => array(
                                                        'select_btn' => $this->lang->line('frontend_settings_select_page'),
                                                        'search_text' => $this->lang->line('frontend_settings_search_page'),
                                                    )
                                                )
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </li>                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo form_open('admin/frontend', array('class' => 'save-settings', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
<?php echo form_close() ?>

<div class="settings-save-changes">
    <div class="col-xs-6">
        <p><?php echo $this->lang->line('frontend_settings_you_have_unsaved_changes'); ?></p>
    </div>
     <div class="col-xs-6 text-right">
        <button type="button" class="btn btn-default">
            <i class="far fa-save"></i>
            <?php echo $this->lang->line('frontend_settings_save_changes'); ?>
        </button>
    </div>   
</div>