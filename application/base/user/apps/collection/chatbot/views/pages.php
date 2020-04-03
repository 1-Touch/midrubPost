<section class="chatbot-page">
    <div class="row">
        <div class="col-xl-2 offset-xl-1 theme-box">
            <?php get_the_file(MIDRUB_BASE_USER_APPS_CHATBOT . 'views/menu.php'); ?>
        </div>
        <div class="col-xl-3 mb-4">
            <div class="chatbot-list theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-12">
                                <i class="lni-facebook"></i>
                                <?php echo $this->lang->line('chatbot_facebook_pages'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-12">
                                <?php echo form_open('user/app/chatbot', array('class' => 'search-pages', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <i class="icon-magnifier"></i>
                                    </div>
                                    <input type="text" class="form-control search-for-pages" placeholder="<?php echo $this->lang->line('chatbot_search_facebook_pages'); ?>">
                                    <div class="input-group-append">
                                        <button type="button" class="btn input-group-text cancel-pages-search">
                                            <i class="icon-close"></i>
                                        </button>
                                        <button type="button" class="pages-manager" data-toggle="modal" data-target="#accounts-manager-popup">
                                            <i class="icon-user-follow"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <ul class="list-pages">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <nav>
                            <ul class="pagination" data-type="pages">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <?php echo form_open('user/app/chatbot', array('class' => 'save-page-configuration', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
            <div class="error-connect-facebook-page theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-12">
                                <i class="lni-cross-circle"></i>
                                <?php echo $this->lang->line('chatbot_error_occurred'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <p>
                        </p>
                    </div>
                </div>
            </div>        
            <div class="connect-facebook-page theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-12">
                                <i class="lni-trowel"></i>
                                <?php echo $this->lang->line('chatbot_connect_facebook_page'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <p>
                            <i class="lni-alarm"></i>
                            <?php echo $this->lang->line('chatbot_connect_facebook_page_instructions'); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="connect-to-bot theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-12">
                                <i class="lni-check-box"></i>
                                <?php echo $this->lang->line('chatbot_connect_to_bot'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <p>
                            <i class="lni-alarm"></i>
                            <?php echo $this->lang->line('chatbot_connect_to_bot_instructions'); ?>
                        </p>
                        <button class="btn btn-link connect-to-bot-btn theme-background-green text-white" type="button">
                            <?php echo $this->lang->line('chatbot_connect_to_bot_btn'); ?>
                        </button>
                        <button class="btn btn-link disconnect-from-bot-btn theme-background-red text-white" type="button">
                            <?php echo $this->lang->line('chatbot_disconnect_from_bot_btn'); ?>
                        </button>
                    </div>
                </div>
            </div>            
            <div class="greeting theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-6">
                                <i class="lni-home"></i>
                                <?php echo $this->lang->line('chatbot_setting_greeting'); ?>
                            </div>
                            <div class="col-6 text-right">
                                <?php echo $this->lang->line('chatbot_enable'); ?>:
                                <div class="checkbox-option pull-right">
                                    <input id="enable-greeting" name="enable-greeting" class="enable-greeting" type="checkbox">
                                    <label for="enable-greeting"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <p>
                            <i class="lni-alarm"></i>
                            <?php echo $this->lang->line('chatbot_text_reply_or_suggestions'); ?>
                        </p>
                        <div class="accordion" id="accordion">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#menu-text-reply" aria-expanded="true" aria-controls="menu-text-reply">
                                        <?php echo $this->lang->line('chatbot_text_reply'); ?>
                                    </button>
                                </div>

                                <div id="menu-text-reply" class="collapse show" aria-labelledby="menu-text-reply" data-parent="#accordion" data-type="text-reply">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <textarea class="form-control greeting-text-message" rows="3" placeholder="<?php echo $this->lang->line('chatbot_enter_the_keywords'); ?>"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="default-response theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-6">
                                <i class="lni-comment"></i>
                                <?php echo $this->lang->line('chatbot_default_response'); ?>
                            </div>
                            <div class="col-6 text-right">
                                <?php echo $this->lang->line('chatbot_enable'); ?>:
                                <div class="checkbox-option pull-right">
                                    <input id="enable-default-text-message" name="enable-default-text-message" class="enable-default-text-message" type="checkbox">
                                    <label for="enable-default-text-message"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <p>
                            <i class="lni-alarm"></i>
                            <?php echo $this->lang->line('chatbot_if_no_response'); ?>
                        </p>
                        <div class="accordion" id="accordion">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" aria-expanded="true" aria-controls="default-text-reply">
                                        <?php echo $this->lang->line('chatbot_text_reply'); ?>
                                    </button>
                                </div>

                                <div id="default-text-reply" class="collapse show" aria-labelledby="default-text-reply" data-parent="#accordion" data-type="text-reply">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <textarea class="form-control default-text-message" rows="3" placeholder="<?php echo $this->lang->line('chatbot_enter_default_response'); ?>"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-6">
                                <i class="lni-home"></i>
                                <?php echo $this->lang->line('chatbot_menu'); ?>
                            </div>
                            <div class="col-6 text-right">
                                <?php echo $this->lang->line('chatbot_enable'); ?>:
                                <div class="checkbox-option pull-right">
                                    <input id="enable-menu" name="enable-menu" class="enable-menu" type="checkbox">
                                    <label for="enable-menu"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <p>
                            <i class="lni-alarm"></i>
                            <?php echo $this->lang->line('chatbot_select_suggestions_group'); ?>
                        </p>
                        <div class="accordion" id="accordion">
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#menu-suggestions" aria-expanded="false" aria-controls="menu-suggestions">
                                        <?php echo $this->lang->line('chatbot_suggestions'); ?>
                                    </button>
                                </div>
                                <div id="menu-suggestions" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion" data-type="suggestions-group">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="dropdown dropdown-suggestions">
                                                    <button class="btn btn-secondary dropdown-toggle chatbot-select-menu btn-select" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <?php echo $this->lang->line('chatbot_select_suggestion'); ?>
                                                    </button>
                                                    <div class="dropdown-menu chatbot-menu-list" aria-labelledby="dropdownMenuButton" x-placement="bottom-start">
                                                        <div class="card">
                                                            <div class="card-head">
                                                                <input type="text" class="chatbot-search-for-suggestions" placeholder="<?php echo $this->lang->line('chatbot_search_suggestions'); ?>">
                                                            </div>
                                                            <div class="card-body">
                                                                <ul class="list-group chatbot-suggestions-list">
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="categories-list theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-12">
                                <i class="lni-comment"></i>
                                <?php echo $this->lang->line('chatbot_categories'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-12">
                                <p>
                                    <i class="lni-alarm"></i>
                                    <?php echo $this->lang->line('chatbot_facebook_categories'); ?>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 all-categories-list">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</section>

<!-- Save Button -->
<div class="settings-save-changes row">
    <div class="col-8">
        <p>
            <i class="icon-bell"></i>
            <?php echo $this->lang->line('chatbot_you_have_unsaved_changes'); ?>
        </p>
    </div>
    <div class="col-4 text-right">
        <button type="button" class="btn btn-default">
            <i class="far fa-save"></i>
            <?php echo $this->lang->line('chatbot_save_changes'); ?>
        </button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="accounts-manager-popup" tabindex="-1" role="dialog" aria-labelledby="accounts-manager-popup" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <i class="lni-facebook"></i>
                    <?php echo $this->lang->line('chatbot_connect_pages'); ?>
                </h2>
                <button type="button" class="close" data-dismiss="modal" aria-boost="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <fieldset>
                            <legend>
                                <?php echo $this->lang->line('chatbot_facebook_pages'); ?>
                            </legend>
                            <div class="row">
                                <div class="col-12">
                                    <?php echo form_open('user/app/chatbot', array('class' => 'search-all-facebook-pages', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <i class="icon-magnifier"></i>
                                        </div>
                                        <input type="text" class="form-control search-for-pages" placeholder="<?php echo $this->lang->line('chatbot_search_facebook_pages'); ?>">
                                        <div class="input-group-append">
                                            <button type="button" class="btn input-group-text cancel-pages-search">
                                                <i class="icon-close"></i>
                                            </button>
                                            <button type="button" class="pages-manager connect-new-facebook-page">
                                                <i class="fab fa-facebook"></i>
                                                <?php echo $this->lang->line('chatbot_connect_pages_btn'); ?>
                                            </button>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <ul class="accounts-manager-accounts-list">
                                    </ul>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-4">
                        <fieldset>
                            <legend>
                                <?php echo $this->lang->line('chatbot_instructions'); ?>
                            </legend>
                            <div class="row">
                                <div class="col-12">
                                    <?php echo $this->lang->line('chatbot_connect_instructions'); ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Word list for JS !-->
<script language="javascript">
    var words = {
        please_enter_text_reply: '<?php echo $this->lang->line('chatbot_please_enter_text_reply'); ?>',
        please_select_suggestion_group: '<?php echo $this->lang->line('chatbot_please_select_suggestion_group'); ?>',
        please_select_at_least_reply: '<?php echo $this->lang->line('chatbot_please_select_at_least_reply'); ?>',
    };
</script>