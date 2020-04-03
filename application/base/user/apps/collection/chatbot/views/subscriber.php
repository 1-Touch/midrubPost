<section class="chatbot-page" data-csrf-name="<?php echo $this->security->get_csrf_token_name(); ?>" data-csrf-hash="<?php echo $this->security->get_csrf_hash(); ?>" data-subscriber="<?php echo $subscriber_id; ?>">
    <div class="row">
        <div class="col-xl-2 offset-xl-1 theme-box">
            <?php get_the_file(MIDRUB_BASE_USER_APPS_CHATBOT . 'views/menu.php'); ?>
        </div>
        <div class="col-xl-3 mb-4">
            <div class="user-profile theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-12">
                                <i class="lni-user"></i>
                                <?php echo htmlspecialchars($name); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body p-3 text-center">
                        <img src="<?php echo htmlspecialchars($image); ?>" class="mb-4">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="messages d-block theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-12">
                                <i class="lni-comment-alt"></i>
                                <?php echo $this->lang->line('chatbot_conversations'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body messages-list">
                        
                    </div>
                    <div class="panel-footer">
                        <nav>
                            <ul class="pagination" data-type="messages">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="categories-list d-block theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-12">
                                <i class="far fa-bookmark"></i>
                                <?php echo $this->lang->line('chatbot_categories'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body clean">
                        <div class="col-12 all-categories-list">
                        </div>
                    </div>
                </div>
            </div>
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