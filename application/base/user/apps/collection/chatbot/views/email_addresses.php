<section class="chatbot-page">
    <div class="row">
        <div class="col-xl-2 offset-xl-1 theme-box">
            <?php get_the_file(MIDRUB_BASE_USER_APPS_CHATBOT . 'views/menu.php'); ?>
        </div>
        <div class="col-xl-8">
            <div class="chatbot-list theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-8">
                                <i class="lni-envelope"></i>
                                <?php echo $this->lang->line('chatbot_email_addresses'); ?>
                            </div>
                            <div class="col-4">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary export-email-csv theme-background-green">
                                        <i class="lni-upload"></i>
                                        <?php echo $this->lang->line('chatbot_export_csv'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-12">
                                <?php echo form_open('user/app/chatbot', array('class' => 'search-email-addressess', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <i class="icon-magnifier"></i>
                                    </div>
                                    <input type="text" class="form-control email-key" placeholder="<?php echo $this->lang->line('chatbot_search_email_addresses'); ?>">
                                    <div class="input-group-append">
                                        <button type="button" class="btn input-group-text cancel-email-addresses-search">
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="email-addresses-management">
                                    <div class="checkbox-option-select">
                                        <input id="all-email-addresses-select" name="all-email-addresses-select" type="checkbox">
                                        <label for="all-email-addresses-select"></label>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary theme-background-white delete-email-addresses">
                                        <i class="icon-trash"></i>
                                        <?php echo $this->lang->line('chatbot_delete'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <ul class="email-addresses-list">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <nav>
                            <ul class="pagination" data-type="email-addresses">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>