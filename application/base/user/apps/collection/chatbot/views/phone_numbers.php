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
                                <i class="lni-phone"></i>
                                <?php echo $this->lang->line('chatbot_phone_numbers'); ?>
                            </div>
                            <div class="col-4">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary export-phone-csv theme-background-green">
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
                                <?php echo form_open('user/app/chatbot', array('class' => 'search-phone-numbers', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <i class="icon-magnifier"></i>
                                    </div>
                                    <input type="text" class="form-control phone-key" placeholder="<?php echo $this->lang->line('chatbot_search_phone_numbers'); ?>">
                                    <div class="input-group-append">
                                        <button type="button" class="btn input-group-text cancel-phone-numbers-search">
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="phone-numbers-management">
                                    <div class="checkbox-option-select">
                                        <input id="all-phone-numbers-select" name="all-phone-numbers-select" type="checkbox">
                                        <label for="all-phone-numbers-select"></label>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary theme-background-white delete-phone-numbers">
                                        <i class="icon-trash"></i>
                                        <?php echo $this->lang->line('chatbot_delete'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <ul class="phone-numbers-list">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <nav>
                            <ul class="pagination" data-type="phone-numbers">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>