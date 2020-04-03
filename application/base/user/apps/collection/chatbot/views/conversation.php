<section class="chatbot-page">
    <div class="row">
        <div class="col-xl-2 offset-xl-1 theme-box">
            <?php get_the_file(MIDRUB_BASE_USER_APPS_CHATBOT . 'views/menu.php'); ?>
        </div>
        <div class="col-xl-3">
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
                                <?php echo $this->lang->line('chatbot_conversation'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="accordion" id="accordion">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#menu-text-reply" aria-expanded="true" aria-controls="menu-text-reply">
                                        <?php echo $this->lang->line('chatbot_message_reply'); ?>
                                        <span>
                                            <i class="far fa-calendar-check"></i>
                                            <?php echo calculate_time($created, time()); ?>
                                        </span>
                                    </button>
                                </div>
                                <div id="menu-text-reply" class="collapse show" aria-labelledby="menu-text-reply" data-parent="#accordion" data-type="text-reply">
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <textarea class="form-control text-message" rows="3"><?php echo htmlspecialchars($question); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ( $type < 2 ) { ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <textarea class="form-control reply-text-message" rows="3"><?php echo htmlspecialchars($response); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <i class="lni-line-spacing"></i>
                                                    <?php echo ($group_name)?$group_name:$this->lang->line('chatbot_group_deleted'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ( $error ) { ?>
            <div class="messages d-block theme-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-12">
                                <i class="lni-alarm"></i>
                                <?php echo $this->lang->line('chatbot_error'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                </div>
            </div>
            <?php } ?>                
        </div>
    </div>
</section>