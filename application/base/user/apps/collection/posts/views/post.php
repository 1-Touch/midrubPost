<section class="post-page">
    <div class="container-fluid">
        <div class="row clean">
            <div class="col-xl-6 offset-xl-3">
                <div class="col-xl-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading" id="accordion">
                            <h3>
                                <i class="icon-info"></i>
                                <?php echo $this->lang->line('post_content'); ?>
                            </h3>                                                                        
                        </div>
                        <div class="panel-body history-post-content">
                            <div class="row">
                                <div class="col-xl-12">
                                    <h3><?php echo $post['title']; ?></h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12 mb-3">
                                    <?php echo $post['body']; ?>
                                </div>
                            </div>
                            <?php
                            if ( $images ) {
                                
                                foreach ( $images as $img ) {
                                 
                                    echo '<div class="row">'
                                            . '<div class="col-xl-12">'
                                                . '<div class="post-history-media">'
                                                    . '<img src="' . $img['body'] . '">'
                                                . '</div>'
                                            . '</div>'
                                        . '</div>';
                                    
                                }
                                
                            }
                            if ( $videos ) {
                                
                                foreach ( $videos as $video ) {
                                 
                                    echo '<div class="row">'
                                            . '<div class="col-xl-12">'
                                                . '<div class="post-history-media">'
                                                    . '<video controls><source src="' . $video['body'] . '" type="video/mp4"></video>'
                                                . '</div>'
                                            . '</div>'
                                        . '</div>';
                                    
                                }
                                
                            }
            
                            ?>                           
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-heading history-post-status" id="accordion">
                            <h3>
                                <i class="icon-chart"></i>
                                <?php echo $this->lang->line('publish_status'); ?>
                            </h3>                                                                        
                        </div>
                        <div class="panel-body history-profiles-list">
                            <?php
                            echo '<div class="row">'
                                    . '<div class="col-xl-12">'
                                        . '<div class="history-status-actions">'
                                            . '<div class="row">'
                                                . '<div class="col-xl-6">'
                                                    . '<p>' . calculate_time($post['time'], $post['current']) . '</p>'
                                                . '</div>'
                                                . '<div class="col-xl-6 text-right">'
                                                . '</div>'
                                            . '</div>'
                                        . '</div>'
                                    . '</div>'
                                . '</div>';
                            
                            if ( $post['sent'] ) {
                                
                                echo '<ul>';
                                
                                $networks_icon = array();
                                
                                foreach ( $post['sent'] as $sent ) {

                                    if ( in_array( $sent['network_name'], $networks_icon ) ) {

                                        $network_icon = $networks_icon[$sent['network_name']];

                                    } else {

                                        $network_icon = (new MidrubBase\User\Apps\Collection\Posts\Helpers\Accounts)->get_network_icon($sent['network_name']);

                                        if ( $network_icon ) {

                                            $networks_icon[$sent['network_name']] = $network_icon;

                                        }

                                    }
                                    
                                    $status = '<i class="icon-check"></i>';

                                    if ( $sent['status'] === '0' || $sent['status'] === '2' ) {
                                        $status = '<i class="icon-close"></i>';
                                    }

                                    echo '<li>'
                                            . '<div class="row">'
                                                . '<div class="col-xl-2 col-sm-2 text-center">'
                                                    . $network_icon
                                                . '</div>'
                                                . '<div class="col-xl-8 col-sm-8 clean">'
                                                    . '<h3>' . $sent['user_name'] . '</h3>'
                                                    . '<p><i class="icon-user"></i> ' . ucwords( str_replace('_', ' ', $sent['network_name']) ) . '</p>'
                                                . '</div>'                              
                                                . '<div class="col-xl-2 col-sm-2 text-center">'
                                                    . $status
                                                . '</div>'
                                            . '</div>'
                                        . '</li>';
                                    
                                }
                                
                                echo '</ul>';
                                
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>