<section class="chatbot-page">
    <div class="row">
        <div class="col-xl-2 offset-xl-1 theme-box">
            <?php get_the_file(MIDRUB_BASE_USER_APPS_CHATBOT . 'views/menu.php'); ?>
        </div>
        <div class="col-xl-8">
            <div class="chatbot-user-list theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-12">
                                <i class="lni-users"></i>
                                <?php echo $this->lang->line('chatbot_subscribers'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-12">
                                <?php echo form_open('user/app/chatbot', array('class' => 'search-subscribers', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <i class="icon-magnifier"></i>
                                    </div>
                                    <input type="text" class="form-control subscribers-key" placeholder="<?php echo $this->lang->line('chatbot_search_subscribers'); ?>">
                                    <div class="input-group-append">
                                        <button type="button" class="btn input-group-text cancel-subscribers-search">
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <ul class="subscribers-list">
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <nav>
                            <ul class="pagination" data-type="subscribers">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>