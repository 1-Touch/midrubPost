<section class="chatbot-page">
    <?php if ( $messages_limit ) { ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="reached-plan-limit">
                <div class="row">
                    <div class="col-xl-9">
                        <i class="icon-info"></i>
                        <?php echo $this->lang->line('chatbot_reached_maximum_number_bot_messages'); ?>
                    </div>
                    <div class="col-xl-3 text-right">
                        <?php if (!$this->session->userdata('member')) { ?>
                            <a href="<?php echo site_url('user/plans') ?>" class="btn">
                                <i class="icon-basket"></i>
                                <?php echo $this->lang->line('chatbot_our_plans'); ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if ( $new_phone_numbers ) { ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="reached-plan-limit">
                <div class="row">
                    <div class="col-xl-9">
                        <i class="icon-info"></i>
                        <?php echo $this->lang->line('chatbot_you_have_a_new_phone_number'); ?>
                    </div>
                    <div class="col-xl-3 text-right">
                        <a href="<?php echo site_url('user/app/chatbot?p=phone-numbers') ?>" class="btn">
                            <i class="lni-phone"></i>
                            <?php echo $this->lang->line('chatbot_more_details'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if ( $new_email_addresses ) { ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="reached-plan-limit">
                <div class="row">
                    <div class="col-xl-9">
                        <i class="icon-info"></i>
                        <?php echo $this->lang->line('chatbot_you_have_a_new_email_addresses'); ?>
                    </div>
                    <div class="col-xl-3 text-right">
                        <a href="<?php echo site_url('user/app/chatbot?p=email-addresses') ?>" class="btn">
                            <i class="lni-envelope"></i>
                            <?php echo $this->lang->line('chatbot_more_details'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="row">
        <div class="col-xl-2 offset-xl-1 theme-box">
            <?php get_the_file(MIDRUB_BASE_USER_APPS_CHATBOT . 'views/menu.php'); ?>
        </div>
        <div class="col-xl-8">
            <div class="chatbot-list theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-12">
                                <i class="lni-comment-reply"></i>
                                <?php echo $this->lang->line('chatbot_all_suggestions_group'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-12">
                                <?php echo form_open('user/app/chatbot', array('class' => 'search-suggestions', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <i class="icon-magnifier"></i>
                                    </div>
                                    <input type="text" class="form-control suggestions-key" placeholder="<?php echo $this->lang->line('chatbot_search_for_groups'); ?>">
                                    <div class="input-group-append">
                                        <button type="button" class="btn input-group-text cancel-suggestions-search">
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <ul class="suggestions-list">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <nav>
                            <ul class="pagination" data-type="suggestions-group">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>