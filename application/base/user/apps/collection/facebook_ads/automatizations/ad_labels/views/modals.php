<!-- Create New Ad Label -->
<div class="modal fade" id="fb-labels-create-ad-label" tabindex="-1" role="dialog" aria-labelledby="fb-labels-create-ad-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <?php echo form_open('user/app/facebook-ads', array('class' => 'fb-labels-create-new-ad-label', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                <div class="modal-header">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-tab-stream-react">
                                <?php echo $this->lang->line('fb_labels_create_new_ad_label'); ?>
                            </a>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </nav>
                </div>
                <div class="modal-body">
                    <div class="row-input">           
                        <div class="panel-group wrap" id="bs-collapse">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#" href="#fb-labels-select-campaign" class="opened-box">
                                            <span>
                                                1
                                            </span>
                                            <?php echo $this->lang->line('select_ad_campaign'); ?>
                                            <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>
                                        </a>
                                    </h4>
                                </div>
                                <div id="fb-labels-select-campaign" class="panel-collapse collapse in collapse show">
                                    <div class="panel-body">
                                        <ul>
                                            <li>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h3>
                                                            <?php echo $this->lang->line('ad_campaign'); ?>
                                                        </h3>
                                                        <p>
                                                            <?php echo $this->lang->line('ad_campaign_description'); ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="dropdown">
                                                           <button class="btn btn-secondary dropdown-toggle fb-labels-selected-ad-campaign btn-select" type="button" id="dropdownMenuButton36" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                               <?php echo $this->lang->line('ad_campaigns'); ?>
                                                           </button>
                                                           <div class="dropdown-menu fb-labels-select-ad-campaign" aria-labelledby="dropdownMenuButton36" x-placement="bottom-start">
                                                              <div class="card">
                                                                 <div class="card-head"><input type="text" class="ad-label-filter-fb-campaigns" placeholder="<?php echo $this->lang->line('search_for_campaigns'); ?>"></div>
                                                                 <div class="card-body">
                                                                    <ul class="list-group ad-label-filter-fb-campaigns-list">
                                                                    </ul>
                                                                 </div>
                                                              </div>
                                                           </div>
                                                        </div>
                                                    </div>    
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#" href="#fb-labels-select-ad-set">
                                            <span>
                                                2
                                            </span>
                                            <?php echo $this->lang->line('select_ad_set'); ?>
                                            <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>
                                        </a>
                                    </h4>
                                </div>
                                <div id="fb-labels-select-ad-set" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul>
                                            <li>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h3>
                                                            <?php echo $this->lang->line('ad_set'); ?>
                                                        </h3>
                                                        <p>
                                                            <?php echo $this->lang->line('ad_set_description'); ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="dropdown">
                                                           <button class="btn btn-secondary dropdown-toggle fb-labels-selected-ad-set btn-select" type="button" id="dropdownMenuButton37" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                               <?php echo $this->lang->line('ad_sets'); ?>
                                                           </button>
                                                           <div class="dropdown-menu fb-labels-select-ad-campaign" aria-labelledby="dropdownMenuButton37" x-placement="bottom-start">
                                                              <div class="card">
                                                                 <div class="card-head">
                                                                     <input type="text" class="fb-labels-filter-fb-adsets" placeholder="<?php echo $this->lang->line('search_for_adsets'); ?>">
                                                                 </div>
                                                                 <div class="card-body">
                                                                    <ul class="list-group fb-labels-filter-fb-adsets-list">
                                                                    </ul>
                                                                 </div>
                                                              </div>
                                                           </div>
                                                        </div>
                                                    </div>    
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#" href="#fb-labels-add-preferences">
                                            <span>
                                                3
                                            </span>
                                            <?php echo $this->lang->line('fb_labels_preferences'); ?>
                                            <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>
                                        </a>
                                    </h4>
                                </div>
                                <div id="fb-labels-add-preferences" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul>
                                            <li>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h3>
                                                            <?php echo $this->lang->line('fb_labels_ad_label_name'); ?> <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>                                                     
                                                        </h3>
                                                        <p>
                                                            <?php echo $this->lang->line('fb_labels_enter_label_name'); ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control fb-labels-ad-label-name" placeholder="<?php echo $this->lang->line('fb_labels_enter_ad_label_name'); ?>">
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h3>
                                                            <?php echo $this->lang->line('fb_labels_spending_limits'); ?>
                                                        </h3>
                                                        <p>
                                                            <?php echo $this->lang->line('fb_labels_spending_limits_description'); ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control fb-labels-ad-spending-limit" placeholder="<?php echo $this->lang->line('fb_labels_enter_spending_limits'); ?>">
                                                    </div>    
                                                </div>
                                            </li>
                                            <li class="ad-identity ad-identity-facebook-pages">
                                            </li>
                                            <li class="ad-identity ad-identity-instagram-accounts">
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alerts-display-reports">
                    </div>
                    <div class="row-input">
                        <div class="row clean">
                            <div class="col-6">                          
                            </div>
                            <div class="col-6">
                                <button type="submit" class="fb-labels-save-ad-label">
                                    <i class="far fa-save"></i> <?php echo $this->lang->line('fb_labels_save_label'); ?>
                                </button>
                            </div> 
                        </div> 
                    </div>  
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Reports Modal -->
<div class="modal fade" id="fb-labels-generate-reports" tabindex="-1" role="dialog" aria-labelledby="fb-labels-generate-reports" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="ad-labels-generate-report-tab" data-toggle="tab" href="#ad-labels-generate-report" role="tab" aria-controls="ad-labels-generate-report" aria-selected="true">
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
                    <div class="tab-pane fade active show" id="ad-labels-generate-report" role="tabpanel" aria-labelledby="ad-labels-generate-report">
                        <?php echo form_open('user/app/facebook-ads', array('class' => 'ad-labels-generate-report', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                            <div class="row">
                                <div class="col-3">
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary btn-md ad-labels-order-reports-by-time dropdown-toggle" data-time="3" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-calendar-alt"></i>
                                            <?php echo $this->lang->line('fb_labels_last_30_days'); ?>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <div class="card">
                                                <div class="card-body">
                                                    <ul class="list-group ad-labels-history-reports-by-time">
                                                        <li class="list-group-item">
                                                            <a href="#" data-time="1">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo $this->lang->line('fb_labels_today'); ?>
                                                            </a>
                                                        </li>                                                        
                                                        <li class="list-group-item">
                                                            <a href="#" data-time="2">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo $this->lang->line('fb_labels_last_7_days'); ?>
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <a href="#" data-time="3">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo $this->lang->line('fb_labels_last_30_days'); ?>
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <a href="#" data-time="4">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo $this->lang->line('fb_labels_last_90_days'); ?>
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
                                        <?php echo $this->lang->line('fb_labels_show_report'); ?>
                                    </button>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                       <table class="table">
                                          <thead>
                                             <tr>
                                                <th scope="col"><?php echo $this->lang->line('fb_labels_date'); ?></th>
                                                <th scope="col"><?php echo $this->lang->line('fb_labels_name'); ?></th>
                                                <th scope="col"><?php echo $this->lang->line('fb_labels_created_ads'); ?></th>
                                                <th scope="col"><?php echo $this->lang->line('fb_labels_errors_ads'); ?></th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                              <tr>
                                                  <td colspan="4">
                                                      <p>
                                                          <?php echo $this->lang->line('fb_labels_no_reports_found'); ?>
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