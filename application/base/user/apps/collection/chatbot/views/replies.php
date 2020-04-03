<section class="chatbot-page" data-up="<?php echo (get_option('upload_limit')) ? get_option('upload_limit'):'6'; ?>">
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
                                <i class="lni-support"></i>
                                <?php echo $this->lang->line('chatbot_replies'); ?>
                            </div>
                            <div class="col-4">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary theme-background-green" data-toggle="modal" data-target="#create-new-reply">
                                        <i class="lni-line-double"></i>
                                        <?php echo $this->lang->line('chatbot_new_reply'); ?>
                                    </button>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary theme-background-green dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon-arrow-down"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#import-csv" data-toggle="modal">
                                                <i class="lni-exit-down"></i>
                                                <?php echo $this->lang->line('chatbot_import_csv'); ?>
                                            </a>
                                            <a class="dropdown-item" href="#export-csv" data-toggle="modal">
                                                <i class="lni-upload"></i>
                                                <?php echo $this->lang->line('chatbot_export_csv'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-12">
                                <?php echo form_open('user/app/chatbot', array('class' => 'search-replies', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <i class="icon-magnifier"></i>
                                    </div>
                                    <input type="text" class="form-control replies-key" placeholder="<?php echo $this->lang->line('chatbot_search_replies'); ?>">
                                    <div class="input-group-append">
                                        <button type="button" class="btn input-group-text cancel-replies-search">
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="replies-management">
                                    <div class="checkbox-option-select">
                                        <input id="all-replies-select" name="all-replies-select" type="checkbox">
                                        <label for="all-replies-select"></label>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary theme-background-white delete-replies">
                                        <i class="icon-trash"></i>
                                        <?php echo $this->lang->line('chatbot_delete'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <ul class="replies-list">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <nav>
                            <ul class="pagination" data-type="replies">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Create New Reply -->
<div class="modal fade" id="create-new-reply" tabindex="-1" role="dialog" aria-boostledby="create-new-reply" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <?php echo $this->lang->line('chatbot_create_new_reply'); ?>
                </h2>
                <button type="button" class="close" data-dismiss="modal" aria-boost="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open('user/app/chatbot', array('class' => 'chatbot-create-reply', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend><?php echo $this->lang->line('chatbot_keywords'); ?></legend>
                            <div class="form-group">
                                <textarea class="form-control reply-keywords" rows="3" placeholder="<?php echo $this->lang->line('chatbot_enter_the_keywords'); ?>" required></textarea>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend><?php echo $this->lang->line('chatbot_response'); ?></legend>
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
                                                        <textarea class="form-control reply-text-message" rows="3" placeholder="<?php echo $this->lang->line('chatbot_enter_the_keywords'); ?>"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingTwo">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#menu-suggestions" aria-expanded="false" aria-controls="menu-suggestions">
                                            <?php echo $this->lang->line('chatbot_suggestions'); ?>
                                        </button>
                                    </div>
                                    <div id="menu-suggestions" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" data-type="suggestions-group">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="dropdown dropdown-suggestions">
                                                        <button class="btn btn-secondary dropdown-toggle chatbot-select-suggestions-group btn-select" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('chatbot_select_suggestion'); ?>
                                                        </button>
                                                        <div class="dropdown-menu chatbot-suggestions-dropdown" aria-labelledby="dropdownMenuButton" x-placement="bottom-start">
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
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        <fieldset>
                            <button type="submit" class="btn btn-primary">
                                <i class="lni-save"></i>
                                <?php echo $this->lang->line('save'); ?>
                            </button>
                        </fieldset>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Import CSV -->
<div class="modal fade" id="import-csv" tabindex="-1" role="dialog" aria-boostledby="import-csv" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <?php echo $this->lang->line('chatbot_import_csv'); ?>
                </h2>
                <button type="button" class="close" data-dismiss="modal" aria-boost="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('user/app/chatbot', array('class' => 'upcsv', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend>
                                <?php echo $this->lang->line('chatbot_upload'); ?>
                            </legend>
                            <div class="form-group">
                                <p>
                                    <i class="lni-alarm"></i>
                                    <?php echo $this->lang->line('chatbot_import_csv_instructions'); ?>
                                </p>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary theme-background-blue select-csv-file">
                                    <i class="lni-exit-down"></i>
                                    <?php echo $this->lang->line('chatbot_select_csv'); ?>
                                </button>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <input type="file" name="csvfile[]" id="csvfile" accept=".csv">
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Export CSV -->
<div class="modal fade" id="export-csv" tabindex="-1" role="dialog" aria-boostledby="export-csv" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <?php echo $this->lang->line('chatbot_export_csv'); ?>
                </h2>
                <button type="button" class="close" data-dismiss="modal" aria-boost="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend>
                                <?php echo $this->lang->line('chatbot_download'); ?>
                            </legend>
                            <div class="form-group">
                                <p>
                                    <i class="lni-alarm"></i>
                                    <?php echo $this->lang->line('chatbot_export_csv_instructions'); ?>
                                </p>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary theme-background-blue download-csv-file">
                                    <i class="lni-upload"></i>
                                    <?php echo $this->lang->line('chatbot_download_csv'); ?>
                                </button>
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
        file_too_large: '<?php echo $this->lang->line('chatbot_file_too_large'); ?>',
    };
</script>