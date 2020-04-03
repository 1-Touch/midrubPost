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
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#fb-labels-create-ad-label">
                        <i class="icon-rocket"></i>
                        <?php echo $this->lang->line('fb_labels_new_ad_label'); ?>
                    </button>
                    <button type="button" class="btn btn-dark ads-delete-ad-labels">
                        <i class="icon-trash"></i>
                        <?php echo $this->lang->line('delete'); ?>
                    </button>
                </th>
                <th scope="row" colspan="2">
                    <button type="button" class="btn btn-dark pull-right btn-ads-reports" data-toggle="modal" data-target="#fb-labels-generate-reports">
                        <i class="icon-pie-chart"></i>
                        <?php echo $this->lang->line('reports'); ?>
                    </button>
                </th>
            </tr>                                                    
            <tr>
                <th scope="row">
                    <div class="checkbox-option-select">
                        <input id="ad-labels-select-all" name="ad-labels-select-all" type="checkbox">
                        <label for="ad-labels-select-all"></label>
                    </div>
                </th>
                <th scope="col">
                    <?php echo $this->lang->line('fb_labels_name'); ?>
                </th>
                <th scope="col">
                    <?php echo $this->lang->line('fb_labels_category'); ?>
                </th>
                <th scope="col">
                    <?php echo $this->lang->line('fb_labels_created_ads'); ?>
                </th>
                <th scope="col">
                    <?php echo $this->lang->line('fb_labels_errors_ads'); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ( $this->labels ) {
                
                foreach ( $this->labels as $label ) {
                    
                echo '<tr>'
                        . '<th scope="row">'
                            . '<div class="checkbox-option-select">'
                                . '<input id="ad-labels-' . $label->label_id . '" name="ad-labels-' . $label->label_id . '" type="checkbox" data-id="' . $label->label_id . '">'
                                . '<label for="ad-labels-' . $label->label_id . '"></label>'
                            . '</div>'
                        . '</th>'
                        . '<td>'
                            . $label->label_name
                        . '</td>'
                        . '<td>'
                            . 'Link Clicks'
                        . '</td>'
                        . '<td>'
                            . $label->success
                        . '</td>'
                        . '<td>'
                            . $label->errors
                        . '</td>' 
                    . '</tr>';
                    
                }
                
            } else {
                
                echo '<tr>'
                        . '<td colspan="5" class="p-3">'
                            . $this->lang->line('fb_labels_no_ad_labels_found')
                        . '</td>' 
                    . '</tr>';
                
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">
                    <button type="button" class="btn btn-dark btn-previous btn-ad-labels-pagination btn-disabled">
                        <i class="far fa-arrow-alt-circle-left"></i>
                        <?php echo $this->lang->line('previous'); ?>
                    </button>
                    <button type="button" class="btn btn-dark btn-next btn-ad-labels-pagination<?php if ($this->total_labels < 11): echo ' btn-disabled'; endif; ?>" data-page="2">
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
    var ad_label_words = {
        fb_labels_new_ad_label: '<?php echo $this->lang->line('fb_labels_new_ad_label'); ?>',
        delete_this: '<?php echo $this->lang->line('delete'); ?>',
        reports: '<?php echo $this->lang->line('reports'); ?>',
        fb_labels_name: '<?php echo $this->lang->line('fb_labels_name'); ?>',
        fb_labels_category: '<?php echo $this->lang->line('fb_labels_category'); ?>',
        fb_labels_created_ads: '<?php echo $this->lang->line('fb_labels_created_ads'); ?>',
        fb_labels_active_ads: '<?php echo $this->lang->line('fb_labels_active_ads'); ?>',
        previous: '<?php echo $this->lang->line('previous'); ?>',
        next: '<?php echo $this->lang->line('next'); ?>',
    };
</script>