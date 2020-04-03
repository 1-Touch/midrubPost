<?php
if ( $reached_the_maximum_api_limit ) {
    ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="input-group no-account-selected">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="icon-bell"></i>
                    </span>
                </div>
                <div class="form-control">
                    <h3><?php echo $reached_the_maximum_api_limit; ?></h3>
                </div>
            </div> 
        </div>
    </div>                                      
    <?php
} elseif ( $selected_account ) {
?>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="row" colspan="3">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#fb-boosts-create-ad-boost">
                        <i class="icon-rocket"></i>
                        <?php echo $this->lang->line('fb_boosts_new_ad_boost'); ?>
                    </button>
                    <button type="button" class="btn btn-dark ads-delete-ad-boosts">
                        <i class="icon-trash"></i>
                        <?php echo $this->lang->line('delete'); ?>
                    </button>
                </th>
                <th scope="row" colspan="2">
                    <button type="button" class="btn btn-dark pull-right btn-ads-reports" data-toggle="modal" data-target="#fb-boosts-generate-reports">
                        <i class="icon-pie-chart"></i>
                        <?php echo $this->lang->line('reports'); ?>
                    </button>
                </th>
            </tr>                                                    
            <tr>
                <th scope="row">
                    <div class="checkbox-option-select">
                        <input id="ad-boosts-select-all" name="ad-boosts-select-all" type="checkbox">
                        <label for="ad-boosts-select-all"></label>
                    </div>
                </th>
                <th scope="col">
                    <?php echo $this->lang->line('fb_boosts_name'); ?>
                </th>
                <th scope="col">
                    <?php echo $this->lang->line('fb_boosts_category'); ?>
                </th>
                <th scope="col">
                    <?php echo $this->lang->line('fb_boosts_created_ads'); ?>
                </th>
                <th scope="col">
                    <?php echo $this->lang->line('fb_boosts_errors_ads'); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ( $this->boosts ) {
                
                foreach ( $this->boosts as $boost ) {
                    
                echo '<tr>'
                        . '<th scope="row">'
                            . '<div class="checkbox-option-select">'
                                . '<input id="ad-boosts-' . $boost->boost_id . '" name="ad-boosts-' . $boost->boost_id . '" type="checkbox" data-id="' . $boost->boost_id . '">'
                                . '<label for="ad-boosts-' . $boost->boost_id . '"></label>'
                            . '</div>'
                        . '</th>'
                        . '<td>'
                            . $boost->boost_name
                        . '</td>'
                        . '<td>'
                            . 'Post Engagement'
                        . '</td>'
                        . '<td>'
                            . $boost->success
                        . '</td>'
                        . '<td>'
                            . $boost->errors
                        . '</td>' 
                    . '</tr>';
                    
                }
                
            } else {
                
                echo '<tr>'
                        . '<td colspan="5" class="p-3">'
                            . $this->lang->line('fb_boosts_no_ad_boosts_found')
                        . '</td>' 
                    . '</tr>';
                
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">
                    <button type="button" class="btn btn-dark btn-previous btn-ad-boosts-pagination btn-disabled">
                        <i class="far fa-arrow-alt-circle-left"></i>
                        <?php echo $this->lang->line('previous'); ?>
                    </button>
                    <button type="button" class="btn btn-dark btn-next btn-ad-boosts-pagination<?php if ($this->total_boosts < 11): echo ' btn-disabled'; endif; ?>" data-page="2">
                        <?php echo $this->lang->line('next'); ?>
                        <i class="far fa-arrow-alt-circle-right"></i>
                    </button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<?php
} else {
?>
<div class="row">
    <div class="col-xl-12">
        <div class="input-group no-account-selected">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="icon-user-unfollow"></i>
                </span>
            </div>
            <div class="form-control">
                <h3><?php echo $this->lang->line('no_account_selected'); ?></h3>
                <p><?php echo $this->lang->line('please_select_ad_account'); ?></p>  
            </div>
        </div>
    </div>
</div>
<?php
}
?> 

<!-- Translations !-->
<script language="javascript">
    var ad_boost_words = {
        fb_boosts_new_ad_boost: '<?php echo $this->lang->line('fb_boosts_new_ad_boost'); ?>',
        delete_this: '<?php echo $this->lang->line('delete'); ?>',
        reports: '<?php echo $this->lang->line('reports'); ?>',
        fb_boosts_name: '<?php echo $this->lang->line('fb_boosts_name'); ?>',
        fb_boosts_category: '<?php echo $this->lang->line('fb_boosts_category'); ?>',
        fb_boosts_created_ads: '<?php echo $this->lang->line('fb_boosts_created_ads'); ?>',
        fb_boosts_active_ads: '<?php echo $this->lang->line('fb_boosts_active_ads'); ?>',
        previous: '<?php echo $this->lang->line('previous'); ?>',
        next: '<?php echo $this->lang->line('next'); ?>',
    };
</script>