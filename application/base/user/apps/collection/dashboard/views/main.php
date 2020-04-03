<section class="section dashboard-page">
    <div class="container-fluid">
        <?php if ( $expired ) { ?>
        <div class="row">
            <div class="col-xl-12">
                <div class="reached-plan-limit">
                    <div class="row">
                        <div class="col-xl-9">
                            <i class="icon-info"></i>
                            <?php echo $this->lang->line( 'your_subscription_has_expired' ); ?>
                        </div>
                        <div class="col-xl-3 text-right">
                            <a href="<?php echo site_url('user/plans') ?>" class="btn"><i class="icon-basket"></i> <?php echo $this->lang->line( 'our_plans' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } else if ( $expires_soon ) { ?>
        <div class="row">
            <div class="col-xl-12">
                <div class="reached-plan-limit">
                    <div class="row">
                        <div class="col-xl-9">
                            <i class="icon-info"></i>
                            <?php echo $this->lang->line( 'your_subscription_expires' ); ?>
                        </div>
                        <div class="col-xl-3 text-right">
                            <a href="<?php echo site_url('user/plans') ?>" class="btn"><i class="icon-basket"></i> <?php echo $this->lang->line( 'our_plans' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <?php
            if ( $widgets ) {
                
                foreach ( $widgets as $key => $value ) {
                    
                    if ( $key > 0 ) {
                        echo '</div><div class="row">';
                    }
                    
                    echo $value;
                    
                }
                
            }
            ?>
        </div>
    </div>
</section>
