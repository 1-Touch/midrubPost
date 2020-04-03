<section class="rss-page" data-id="<?php echo $rss_id; ?>" data-mobile-installed="<?php echo (get_user_option('mobile_installed'))?'1':'0'; ?>">  
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo site_url('user/app/posts') ?>"><?php echo $this->lang->line( 'posts' ); ?></a></li>
            <li class="breadcrumb-item active"><a href="javascript:window.location.href=window.location.href"><?php echo $this->lang->line( 'rss_feed' ); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?></li>
        </ol>
    </nav>
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" data-toggle="tab" href="#nav-last-posts" role="tab" aria-controls="nav-last-posts" aria-selected="true">
                <?php echo $this->lang->line('last_posts'); ?>
            </a>
            <a class="nav-item nav-link" data-toggle="tab" href="#nav-accounts" role="tab" aria-controls="nav-accounts" aria-selected="false">
                <?php echo $this->lang->line('accounts'); ?>
            </a>
            <a class="nav-item nav-link" data-toggle="tab" href="#nav-history" role="tab" aria-controls="nav-history" aria-selected="false">
                <?php echo $this->lang->line('history'); ?>
            </a>
            <a class="nav-item nav-link" data-toggle="tab" href="#nav-settings" role="tab" aria-controls="nav-settings" aria-selected="false">
                <?php echo $this->lang->line('settings'); ?>
            </a>
            <?php  
            if ( get_option('app_posts_enable_faq') ) {
                ?>
                <a class="nav-item nav-link" data-toggle="tab" href="#nav-faq" role="tab" aria-controls="nav-faq" aria-selected="false">
                    <?php echo $this->lang->line('faq'); ?>
                </a>
                <?php  
            }
            ?>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-last-posts" role="tabpanel" aria-labelledby="nav-last-posts">
            <?php
            if ( posts_plan_limit($this->user_id) ) {
                ?>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="reached-plan-limit">
                            <div class="row">
                                <div class="col-xl-9">
                                    <i class="icon-info"></i>
                                    <?php echo $this->lang->line( 'reached_maximum_number_posts' ); ?>
                                </div>
                                <div class="col-xl-3 text-right">
                                    <?php 
                                    if ( !$this->session->userdata( 'member' ) ) {
                                    ?>
                                    <a href="<?php echo site_url('user/plans') ?>" class="btn"><i class="icon-basket"></i> <?php echo $this->lang->line( 'our_plans' ); ?></a>
                                    <?php 
                                    }
                                    ?> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php echo form_open('user/app/posts', array('class' => 'send-post', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                <div class="row">
                    <div class="col-xl-8">
                        <div class="col-xl-12 clean rss-feeds-rss-content">
                            <?php
                            
                            // Verify if the RSS Feed is available
                            if ( !empty($rss_content['title']) ) {
                                
                                $count = count($rss_content['title']);
                                
                                for ( $i = 0; $i < $count; $i++ ) {
                                    
                                    $img = '';
                                    
                                    if ( isset($rss_content['show'][$i]) ) {
                                    
                                        $img = '<p><img src="' . $rss_content['show'][$i] . '" class="rss-post-image"></p>';
                                        
                                    }
                                    
                                    $btn = '';
                                    
                                    if ( posts_verify_post_published( $rss_id, posts_clean_url_for_rss_posts($rss_content['url'][$i]) ) ) {
                                        $btn = '<button type="button" class="rss-feeds-rss-content-single-share-button"><i class="icon-share-alt"></i></button>';
                                    }
                                    
                                    $description = '';
                                    
                                    if ( isset($rss_content['description'][$i]) ) {
                                        $description = '<p>' . str_replace( "\n", '</p><p>', $rss_content['description'][$i] ) . '</p>';
                                    }
                                    
                                    echo '<div class="col-xl-12 rss-feeds-rss-content-single">'
                                            . '<h3 class="rss-feeds-post-title">'
                                                . $rss_content['title'][$i]
                                                . $btn
                                            . '</h3>'
                                            . '<div class="rss-feeds-post-content">'
                                                . $description
                                            . '</div>'
                                            . $img
                                            . '<p><a href="' . $rss_content['url'][$i] . '" class="rss-feeds-post-url" target="_blank">' . $rss_content['url'][$i] . '</a></p>'
                                        . '</div>';
                                    
                                }
                                
                            } else {
                             
                                echo '<div class="col-xl-12 rss-feeds-rss-content-single">'
                                        . '<h6>'
                                            . $this->lang->line('rss_not_available')
                                        . '</h6>'
                                    . '</div>';                                
                                
                            }
                            
                            ?>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="col-xl-12 theme-box post-composer">
                            <div class="row">
                                <div class="col-xl-12 post-body">
                                    <div class="row composer-title"> 
                                        <div class="col-xl-12">
                                            <input type="text" placeholder="<?php echo $this->lang->line('enter_post_title'); ?>">
                                        </div>
                                    </div>
                                    <div class="row post-composer-editor"> 
                                        <div class="col-xl-12 composer">
                                            <textarea class="new-post" placeholder="<?php echo $this->lang->line('share_what_new'); ?>"></textarea>
                                        </div>
                                    </div>
                                    <div class="row post-composer-buttons">
                                        <div class="col-xl-12">
                                            <?php
                                            if ( get_user_option('settings_character_count') ) {
                                                echo '<div class="numchar">0</div>';
                                            }
                                            ?>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>                            
                            <div class="row clean">
                                <div class="col-xl-12 post-footer">
                                    <div class="row">
                                        <div class="col-xl-12 post-preview-title">
                                            <div class="row">
                                                <div class="col-xl-8"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12 post-preview-body">
                                            <div class="row">
                                                <div class="col-xl-11"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-11"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-11"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-7"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12 post-preview-medias">
                                            <div>
                                                <img src="">
                                            </div>            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12 post-preview-url">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 theme-box selected-accounts">
                            <div class="panel panel-primary">
                                <div class="panel-heading" id="accordion">
                                    <h3>
                                        <?php if ( get_user_option('settings_display_groups') ): echo '<i class="icon-folder"></i> ' . $this->lang->line('selected_groups'); else: echo '<i class="icon-people"></i> ' . $this->lang->line('selected_accounts'); endif; ?>
                                    </h3>                                                                        
                                </div>
                                    <?php
                                    if ( get_user_option('settings_display_groups') ) {
                                    echo '<div class="panel-body rss-selected-group">';
                                    } else {
                                    echo '<div class="panel-body rss-selected-accounts">';    
                                    }
                                    ?>
                                    <ul></ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 theme-box buttons-control">
                            <input type="text" class="datetime">
                            <div class="btn-group dropup">
                                <button type="submit" class="btn btn-success"><i class="icon-share-alt"></i> <?php echo $this->lang->line('share_now'); ?></button>
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#" class="open-midrub-planner"><?php echo $this->lang->line('schedule'); ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
        <div class="tab-pane fade" id="nav-accounts" role="tabpanel" aria-labelledby="nav-accounts">
            <div class="row">
                <div class="col-xl-6">
                    <div class="col-xl-12 rss-destination theme-box">
                        <div class="row">
                            <div class="col-xl-12 input-group rss-accounts-search">
                                <div class="input-group-prepend">
                                    <i class="icon-magnifier"></i>
                                </div>
                                <?php if ( get_user_option('settings_display_groups') ): ?>
                                <input type="text" class="form-control rss-search-for-groups" placeholder="<?php echo $this->lang->line('search_for_groups'); ?>">
                                <?php else: ?>
                                <input type="text" class="form-control rss-search-for-accounts" placeholder="<?php echo $this->lang->line('search_for_accounts'); ?>">
                                <?php endif; ?>
                                <button type="button" class="rss-cancel-search-for-accounts">
                                    <i class="icon-close"></i>
                                </button>
                                <?php if ( get_user_option('settings_social_pagination') ): ?>
                                <button type="button" class="back-button btn-disabled">
                                    <span class="fc-icon fc-icon-left-single-arrow"></span>
                                </button>
                                <button type="button" class="next-button<?php if ( $total < 11 ): echo ' btn-disabled'; else: echo '" data-page="2'; endif; ?>">
                                    <span class="fc-icon fc-icon-right-single-arrow"></span>
                                </button>
                                <?php endif; ?>
                                <button type="button" class="rss-manage-members" data-toggle="modal" data-target="#accounts-manager-popup">
                                    <i class="icon-user-follow"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                        <?php
                        if ( get_user_option('settings_display_groups') ) {

                            echo '<div class="col-xl-12 rss-feed-groups-list">'
                                    . '<ul>';

                            if ( $groups_list ) {

                                foreach ( $groups_list as $group ) {
                                    ?>
                                    <li>
                                        <a href="#" data-id="<?php echo $group->list_id; ?>">
                                            <?php echo '<i class="icon-folder-alt"></i>'; ?>
                                            <?php echo $group->name; ?>
                                            <i class="icon-check"></i>
                                        </a>
                                    </li>
                                    <?php
                                }

                            } else {

                                if ( get_user_option('settings_display_groups') ) {
                                    echo '<li class="no-groups-found">' . $this->lang->line('no_groups_found') . '</li>';
                                } else {
                                    echo '<li class="no-groups-found">' . $this->lang->line('no_accounts_found') . '</li>';
                                }

                            }

                                echo '</ul>'
                                . '</div>';

                        } else {

                            echo '<div class="col-xl-12 rss-feed-accounts-list">'
                                    . '<ul>';

                            if ( $accounts_list ) {

                                foreach ( $accounts_list as $account ) {
                                    ?>
                                    <li>
                                        <a href="#" data-id="<?php echo $account['network_id']; ?>" data-net="<?php echo $account['net_id']; ?>" data-network="<?php echo $account['network_name']; ?>" data-category="<?php echo isset($account['network_info']['types']['categories'])?'true':'value'; ?>">
                                            <?php echo str_replace(' class', ' style="color: ' . $account['network_info']['color'] . '" class', $account['network_info']['icon'] ); ?>
                                            <?php echo $account['user_name']; ?>
                                            <span>
                                                <i class="icon-user"></i> <?php echo ucwords(str_replace('_', ' ', $account['network_name'])); ?>
                                            </span>
                                            <i class="icon-check"></i>
                                        </a>
                                    </li>
                                    <?php
                                }

                            } else {

                                echo '<li class="no-accounts-found">' . $this->lang->line('no_accounts_found') . '</li>';

                            }

                                echo '</ul>'
                                . '</div>';

                        }
                        ?>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="col-xl-12 selected-accounts theme-box">
                        <div class="panel panel-primary">
                            <div class="panel-heading" id="accordion">
                                <h3>
                                    <?php if ( get_user_option('settings_display_groups') ): echo '<i class="icon-folder"></i> ' . $this->lang->line('selected_groups'); else: echo '<i class="icon-people"></i> ' . $this->lang->line('selected_accounts'); endif; ?>
                                </h3>                                                                        
                            </div>
                                <?php
                                if ( get_user_option('settings_display_groups') ) {
                                echo '<div class="panel-body rss-selected-group">';
                                } else {
                                echo '<div class="panel-body rss-selected-accounts">';    
                                }
                                ?>
                                <ul></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-history" role="tabpanel" aria-labelledby="nav-history">
            <div class="row">
                <div class="col-xl-12">
                    <div class="col-xl-12 clean">
                        <div class="row">
                            <div class="col-xl-3">
                                <div class="row">
                                    <div class="col-xl-10 col-sm-10 col-9 input-group history-posts-search">
                                        <div class="input-group-prepend">
                                            <i class="icon-magnifier"></i>
                                        </div>
                                        <input type="text" class="form-control history-search-for-posts" placeholder="<?php echo $this->lang->line('search_for_posts'); ?>">
                                        <button type="button" class="history-cancel-search-for-posts">
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                    <div class="col-xl-2 col-sm-2 col-3">
                                        <button type="button" class="history-generate-reports" data-toggle="modal" data-target="#history-generate-reports">
                                            <i class="icon-pie-chart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 input-group history-posts-results">                                    
                                    </div>
                                </div>
                                <nav>
                                    <ul class="pagination" data-type="history-posts">
                                    </ul>
                                </nav>
                            </div>
                            <div class="col-xl-5">
                                <div class="panel panel-primary theme-box">
                                    <div class="panel-heading" id="accordion">
                                        <h3>
                                            <i class="icon-info"></i>
                                            <?php echo $this->lang->line('post_content'); ?>
                                        </h3>                                                                        
                                    </div>
                                    <div class="panel-body history-post-content">
                                        <p class="no-post-selected"><?php echo $this->lang->line('no_post_selected'); ?></p>
                                    </div>
                                </div>
                            </div>    
                            <div class="col-xl-4">
                                <div class="panel panel-primary theme-box">
                                    <div class="panel-heading history-post-status" id="accordion">
                                        <h3>
                                            <i class="icon-chart"></i>
                                            <?php echo $this->lang->line('publish_status'); ?>
                                        </h3>                                                                        
                                    </div>
                                    <div class="panel-body history-profiles-list">
                                        <p class="no-post-selected"><?php echo $this->lang->line('no_post_selected'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-settings" role="tabpanel" aria-labelledby="nav-settings">
            <div class="row">
                <div class="col-xl-12">
                    <ul>
                        <li class="theme-box">
                            <div class="row">
                                <div class="col-xl-10 col-sm-9 col-xs-9"><?php echo $this->lang->line('enabled'); ?></div>
                                <div class="col-xl-2 col-sm-3 col-xs-3 text-right">
                                    <div class="checkbox-option pull-right">
                                        <input id="enabled" name="enabled"<?php if ($enabled == 1) echo ' checked="checked"'; ?> class="set_rss_opt" type="checkbox">
                                        <label for="enabled"></label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="theme-box">
                            <div class="row">
                                <div class="col-xl-10 col-sm-9 col-xs-9"><?php echo $this->lang->line('publish_description'); ?></div>
                                <div class="col-xl-2 col-sm-3 col-xs-3 text-right">
                                    <div class="checkbox-option pull-right">
                                        <input id="publish_description" name="publish_description"<?php if ($publish_description == 1) echo ' checked="checked"'; ?> class="set_rss_opt" type="checkbox">
                                        <label for="publish_description"></label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="theme-box">
                            <div class="row">
                                <div class="col-xl-10 col-sm-9 col-xs-9"><?php echo $this->lang->line('publish_manually'); ?></div>
                                <div class="col-xl-2 col-sm-3 col-xs-3 text-right">
                                    <div class="checkbox-option pull-right">
                                        <input id="pub" name="pub"<?php if ($publish_way == 1) echo ' checked="checked"'; ?> class="set_rss_opt" type="checkbox">
                                        <label for="pub"></label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="theme-box">
                            <div class="row">
                                <div class="col-xl-10 col-sm-9 col-xs-9"><?php echo $this->lang->line('publish_images'); ?></div>
                                <div class="col-xl-2 col-sm-3 col-xs-3 text-right">
                                    <div class="checkbox-option pull-right">
                                        <input id="type" name="type" class="set_rss_opt" type="checkbox"<?php if ($type == 1) echo ' checked="checked"'; ?>>
                                        <label for="type"></label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="theme-box">
                            <div class="row">
                                <div class="col-xl-10 col-sm-9 col-xs-9"><?php echo $this->lang->line('posts_remove_url'); ?></div>
                                <div class="col-xl-2 col-sm-3 col-xs-3 text-right">
                                    <div class="checkbox-option pull-right">
                                        <input id="remove_url" name="remove_url" class="set_rss_opt" type="checkbox"<?php if ($remove_url == 1) echo ' checked="checked"'; ?>>
                                        <label for="remove_url"></label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="theme-box">
                            <div class="row">
                                <div class="col-xl-10 col-sm-9 col-xs-9"><?php echo $this->lang->line('posts_keep_html'); ?></div>
                                <div class="col-xl-2 col-sm-3 col-xs-3 text-right">
                                    <div class="checkbox-option pull-right">
                                        <input id="keep_html" name="keep_html" class="set_rss_opt" type="checkbox"<?php if ($keep_html == 1) echo ' checked="checked"'; ?>>
                                        <label for="keep_html"></label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="theme-box">
                            <div class="row">
                                <div class="col-xl-10 col-sm-9 col-xs-9"><div class="image-head-title"><?php echo $this->lang->line('referral_code'); ?></div></div>
                                <div class="col-xl-2 col-sm-3 col-xs-3 text-right">
                                    <div class="checkbox-option pull-right">
                                        <input type="text" class="optionvalue rss-settings-input" id="refferal" value="<?php echo $refferal ?>" placeholder="ref=abc">
                                    </div>
                                </div>                                        
                            </div>
                        </li>
                        <li class="theme-box">
                            <div class="row">
                                <div class="col-xl-10 col-sm-9 col-xs-9"><div class="image-head-title"><?php echo $this->lang->line('how_often_published'); ?></div></div>
                                <div class="col-xl-2 col-sm-3 col-xs-3 text-right">
                                    <div class="checkbox-option pull-right">
                                        <input type="text" class="optionvalue rss-settings-input" id="period" value="<?php echo $period ?>" placeholder="<?php echo $this->lang->line('only_number_minutes'); ?>">
                                    </div>
                                </div>                                        
                            </div>
                        </li>
                        <li class="theme-box">
                            <div class="row">
                                <div class="col-xl-10 col-sm-9 col-xs-9"><div class="image-head-title"><?php echo $this->lang->line('publish_posts_with_these_words'); ?></div></div>
                                <div class="col-xl-2 col-sm-3 col-xs-3 text-right">
                                    <div class="checkbox-option pull-right">
                                        <input type="text" class="optionvalue rss-settings-input" id="include" value="<?php echo $include ?>" placeholder="<?php echo $this->lang->line('separate_by_comma'); ?>">
                                    </div>
                                </div>                                        
                            </div>
                        </li>
                        <li class="theme-box">
                            <div class="row">
                                <div class="col-xl-10 col-sm-9 col-xs-9"><div class="image-head-title"><?php echo $this->lang->line('dont_publish_posts_with_these_words'); ?></div></div>
                                <div class="col-xl-2 col-sm-3 col-xs-3 text-right">
                                    <div class="checkbox-option pull-right">
                                        <input type="text" class="optionvalue rss-settings-input" id="exclude" value="<?php echo $exclude ?>" placeholder="<?php echo $this->lang->line('separate_by_comma'); ?>">
                                    </div>
                                </div>                                        
                            </div>
                        </li>
                        <li class="theme-box">
                            <div class="row">
                                <div class="col-xl-12 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-danger pull-left delete-rss"><?php echo $this->lang->line('delete_rss'); ?></button>
                                    <p class="pull-left confirm"><?php echo $this->lang->line('are_you_sure'); ?> <a href="#" class="delete-feeds yes"><?php echo $this->lang->line('yes'); ?></a><a href="#" class="no"><?php echo $this->lang->line('no'); ?></a></p>
                                </div>
                            </div> 
                        </li>
                    </ul>
                    <div class="form-group">
                        <div class="form-group alert-msg"></div>
                    </div> 
                </div>
            </div>
        </div>
        <?php  
        if ( get_option('app_posts_enable_faq') ) {
        ?>
        <div class="tab-pane fade" id="nav-faq" role="tabpanel" aria-labelledby="nav-faq">
            <div class="row">
                <div class="col-xl-3">
                    <div class="panel panel-default theme-box">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <?php echo $this->lang->line('categories'); ?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="about-nav" data-toggle="tab" href="#about-tab" role="tab" aria-controls="about-tab" aria-selected="true"><?php echo $this->lang->line('about'); ?> <i class="fas fa-angle-right"></i></a>
                                    <a class="nav-item nav-link" id="last-posts-nav" data-toggle="tab" href="#last-posts-tab" role="tab" aria-controls="last-posts-tab" aria-selected="false"><?php echo $this->lang->line('last_posts'); ?> <i class="fas fa-angle-right"></i></a>
                                    <a class="nav-item nav-link" id="accounts-nav" data-toggle="tab" href="#acconts-tab" role="tab" aria-controls="accounts-tab" aria-selected="false"><?php echo $this->lang->line('accounts'); ?> <i class="fas fa-angle-right"></i></a>
                                    <a class="nav-item nav-link" id="history-nav" data-toggle="tab" href="#history-tab" role="tab" aria-controls="history-tab" aria-selected="false"><?php echo $this->lang->line('history'); ?> <i class="fas fa-angle-right"></i></a>
                                    <a class="nav-item nav-link" id="settings-nav" data-toggle="tab" href="#settings-tab" role="tab" aria-controls="settings-tab" aria-selected="false"><?php echo $this->lang->line('settings'); ?> <i class="fas fa-angle-right"></i></a>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>                
                <div class="col-xl-9 theme-box">
                    <div class="tab-content" id="nav-tabContent">
                        <?php echo $this->lang->line('rss_faq'); ?>                        
                    </div>                   
                </div>
            </div>
        </div>
        <?php  
        }
        ?>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="accounts-manager-popup" tabindex="-1" role="dialog" aria-labelledby="accounts-manager-popup" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="nav-accounts-manager-tab" data-toggle="tab" href="#nav-accounts-manager" role="tab" aria-controls="nav-accounts-manager" aria-selected="true">
                           <?php echo $this->lang->line('accounts'); ?> 
                        </a>
                        <a class="nav-item nav-link" id="nav-groups-manager-tab" data-toggle="tab" href="#nav-groups-manager" role="tab" aria-controls="nav-groups-manager" aria-selected="false">
                            <?php echo $this->lang->line('groups'); ?> 
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="nav-accounts-manager" role="tabpanel" aria-labelledby="nav-accounts-manager">
                    </div>
                    <div class="tab-pane fade" id="nav-groups-manager" role="tabpanel" aria-labelledby="nav-groups-manager">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="history-generate-reports" tabindex="-1" role="dialog" aria-labelledby="history-generate-reports" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="nav-publish-reports-tab" data-toggle="tab" href="#nav-publish-reports" role="tab" aria-controls="nav-publish-reports" aria-selected="true">
                            <i class="icon-pie-chart"></i>
                            <?php echo $this->lang->line('publish_report'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="nav-publish-reports" role="tabpanel" aria-labelledby="nav-publish-reports">
                        <?php echo form_open('user/app/posts', array('class' => 'posts-generate-report', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                            <div class="row">
                                <div class="col-3">
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary btn-md order-reports-by-time dropdown-toggle" data-time="3" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-calendar-alt"></i>
                                            <?php echo $this->lang->line('last_30_days'); ?>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <div class="card">
                                                <div class="card-body">
                                                    <ul class="list-group history-reports-by-time">
                                                        <li class="list-group-item">
                                                            <a href="#" data-time="1">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo $this->lang->line('today'); ?>
                                                            </a>
                                                        </li>                                                        
                                                        <li class="list-group-item">
                                                            <a href="#" data-time="2">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo $this->lang->line('last_7_days'); ?>
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <a href="#" data-time="3">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo $this->lang->line('last_30_days'); ?>
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <a href="#" data-time="4">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo $this->lang->line('last_90_days'); ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="col-6">
                                   &nbsp;
                                </div>
                                <div class="col-3">
                                    <button type="submit" class="btn btn-default btn-show-reports">
                                        <i class="icon-refresh"></i>
                                        <?php echo $this->lang->line('show_report'); ?>
                                    </button>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                       <table class="table">
                                          <thead>
                                             <tr>
                                                <th scope="col"><?php echo $this->lang->line('date'); ?></th>
                                                <th scope="col"><?php echo $this->lang->line('published_posts'); ?></th>
                                                <th scope="col"><?php echo $this->lang->line('accounts'); ?></th>
                                                <th scope="col"><?php echo $this->lang->line('errors'); ?></th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                              <tr>
                                                  <td colspan="4">
                                                      <p>
                                                          <?php echo $this->lang->line('no_posts_found'); ?>
                                                      </p>
                                                  </td>
                                              </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="midrub-planner">
    <div class="row">
        <div class="col-xl-12">
            <table class="midrub-calendar iso">
                <thead>
                    <tr>
                        <th class="text-center"><a href="#" class="go-back"><i class="icon-arrow-left"></i></a></th>
                        <th colspan="5" class="text-center year-month"></th>
                        <th class="text-center"><a href="#" class="go-next"><i class="icon-arrow-right"></i></a></th>
                    </tr>
                </thead>
                <tbody class="calendar-dates">
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 text-center">
            <?php
            $scheduler_time = '<select class="midrub-calendar-time-hour">';
            $scheduler_time .= "\n";
                $scheduler_time .= '<option value="01">01</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="02">02</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="03">03</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="04">04</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="05">05</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="06">06</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="07">07</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="08" selected>08</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="09">09</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="10">10</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="11">11</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="12">12</option>';
                $scheduler_time .= "\n";
            if ( get_user_option('24_hour_format') ) {
                $scheduler_time .= '<option value="13">13</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="14">14</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="15">15</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="16">16</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="17">17</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="18">18</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="19">19</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="20">20</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="21">21</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="22">22</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="23">23</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="00">00</option>';
                $scheduler_time .= "\n";
            }
            $scheduler_time .= '</select>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<select class = "midrub-calendar-time-minutes">';
            $scheduler_time .= "\n";
                $scheduler_time .= '<option value="00">00</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="10">10</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="20">20</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="30">30</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="40">40</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="50">50</option>';
                $scheduler_time .= "\n";
            $scheduler_time .= '</select>';
            $scheduler_time .= "\n";

            if ( !get_user_option('24_hour_format') ) {
                $scheduler_time .= '<select class = "midrub-calendar-time-period">';
                $scheduler_time .= "\n";
                    $scheduler_time .= '<option value="AM">AM</option>';
                    $scheduler_time .= "\n";
                    $scheduler_time .= '<option value="PM">PM</option>';
                    $scheduler_time .= "\n";
                $scheduler_time .= '</select>';
                $scheduler_time .= "\n";
            }

            echo $scheduler_time;

            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 text-center">
            <button type="btn" class="btn rss-schedule-post"><?php echo $this->lang->line('schedule'); ?></button>
        </div>
    </div>
</div>

<!-- Translations !-->
<script language="javascript">
    var words = {
        please_install_the_mobile_client: '<?php echo $this->lang->line('please_install_the_mobile_client'); ?>',
    };
</script>