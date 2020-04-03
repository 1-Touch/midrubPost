<!-- Create New Ad Boost -->
<div class="modal fade" id="fb-boosts-create-ad-boost" tabindex="-1" role="dialog" aria-boostledby="fb-boosts-create-ad-boost" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <?php echo form_open('user/app/facebook-ads', array('class' => 'fb-boosts-create-new-ad-boost', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                <div class="modal-header">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-tab-stream-react">
                                <?php echo $this->lang->line('fb_boosts_create_new_ad_boost'); ?>
                            </a>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-boost="Close">
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
                                        <a data-toggle="collapse" data-parent="#" href="#fb-boosts-select-campaign" class="opened-box">
                                            <span>
                                                1
                                            </span>
                                            <?php echo $this->lang->line('select_ad_campaign'); ?>
                                            <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>
                                        </a>
                                    </h4>
                                </div>
                                <div id="fb-boosts-select-campaign" class="panel-collapse collapse in collapse show">
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
                                                           <button class="btn btn-secondary dropdown-toggle fb-boosts-selected-ad-campaign btn-select" type="button" id="dropdownMenuButton35" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                               <?php echo $this->lang->line('ad_campaigns'); ?>
                                                           </button>
                                                           <div class="dropdown-menu fb-boosts-select-ad-campaign" aria-boostledby="dropdownMenuButton35" x-placement="bottom-start">
                                                              <div class="card">
                                                                 <div class="card-head"><input type="text" class="ad-boost-filter-fb-campaigns" placeholder="<?php echo $this->lang->line('search_for_campaigns'); ?>"></div>
                                                                 <div class="card-body">
                                                                    <ul class="list-group ad-boost-filter-fb-campaigns-list">
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
                                        <a data-toggle="collapse" data-parent="#" href="#fb-boosts-select-ad-set">
                                            <span>
                                                2
                                            </span>
                                            <?php echo $this->lang->line('select_ad_set'); ?>
                                            <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>
                                        </a>
                                    </h4>
                                </div>
                                <div id="fb-boosts-select-ad-set" class="panel-collapse collapse">
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
                                                           <button class="btn btn-secondary dropdown-toggle fb-boosts-selected-ad-set btn-select" type="button" id="dropdownMenuButton34" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                               <?php echo $this->lang->line('ad_sets'); ?>
                                                           </button>
                                                           <div class="dropdown-menu fb-boosts-select-ad-campaign" aria-boostledby="dropdownMenuButton34" x-placement="bottom-start">
                                                              <div class="card">
                                                                 <div class="card-head">
                                                                     <input type="text" class="fb-boosts-filter-fb-adsets" placeholder="<?php echo $this->lang->line('search_for_adsets'); ?>">
                                                                 </div>
                                                                 <div class="card-body">
                                                                    <ul class="list-group fb-boosts-filter-fb-adsets-list">
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
                                        <a data-toggle="collapse" data-parent="#" href="#fb-boosts-add-preferences">
                                            <span>
                                                3
                                            </span>
                                            <?php echo $this->lang->line('fb_boosts_preferences'); ?>
                                            <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>
                                        </a>
                                    </h4>
                                </div>
                                <div id="fb-boosts-add-preferences" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul>
                                            <li>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h3>
                                                            <?php echo $this->lang->line('fb_boosts_ad_boost_name'); ?> <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>                                                    
                                                        </h3>
                                                        <p>
                                                            <?php echo $this->lang->line('fb_boosts_enter_boost_name'); ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control fb-boosts-ad-boost-name" placeholder="<?php echo $this->lang->line('fb_boosts_enter_ad_boost_name'); ?>">
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h3>
                                                            <?php echo $this->lang->line('fb_boosts_spending_limits'); ?>
                                                        </h3>
                                                        <p>
                                                            <?php echo $this->lang->line('fb_boosts_spending_limits_description'); ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control fb-boosts-ad-spending-limit" placeholder="<?php echo $this->lang->line('fb_boosts_enter_spending_limits'); ?>">
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
                                <button type="submit" class="fb-boosts-save-ad-boost">
                                    <i class="far fa-save"></i> <?php echo $this->lang->line('fb_boosts_save_boost'); ?>
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
<div class="modal fade" id="fb-boosts-generate-reports" tabindex="-1" role="dialog" aria-boostledby="fb-boosts-generate-reports" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="ad-boosts-generate-report-tab" data-toggle="tab" href="#ad-boosts-generate-report" role="tab" aria-controls="ad-boosts-generate-report" aria-selected="true">
                            <i class="icon-pie-chart"></i>
                            <?php echo $this->lang->line('publish_report'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-boost="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="ad-boosts-generate-report" role="tabpanel" aria-boostledby="ad-boosts-generate-report">
                        <?php echo form_open('user/app/facebook-ads', array('class' => 'ad-boosts-generate-report', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                            <div class="row">
                                <div class="col-3">
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary btn-md ad-boosts-order-reports-by-time dropdown-toggle" data-time="3" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-calendar-alt"></i>
                                            <?php echo $this->lang->line('fb_boosts_last_30_days'); ?>
                                        </a>
                                        <div class="dropdown-menu" aria-boostledby="dropdownMenuLink">
                                            <div class="card">
                                                <div class="card-body">
                                                    <ul class="list-group ad-boosts-history-reports-by-time">
                                                        <li class="list-group-item">
                                                            <a href="#" data-time="1">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo $this->lang->line('fb_boosts_today'); ?>
                                                            </a>
                                                        </li>                                                        
                                                        <li class="list-group-item">
                                                            <a href="#" data-time="2">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo $this->lang->line('fb_boosts_last_7_days'); ?>
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <a href="#" data-time="3">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo $this->lang->line('fb_boosts_last_30_days'); ?>
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <a href="#" data-time="4">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo $this->lang->line('fb_boosts_last_90_days'); ?>
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
                                        <?php echo $this->lang->line('fb_boosts_show_report'); ?>
                                    </button>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                       <table class="table">
                                          <thead>
                                             <tr>
                                                <th scope="col"><?php echo $this->lang->line('fb_boosts_date'); ?></th>
                                                <th scope="col"><?php echo $this->lang->line('fb_boosts_name'); ?></th>
                                                <th scope="col"><?php echo $this->lang->line('fb_boosts_created_ads'); ?></th>
                                                <th scope="col"><?php echo $this->lang->line('fb_boosts_errors_ads'); ?></th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                              <tr>
                                                  <td colspan="4">
                                                      <p>
                                                          <?php echo $this->lang->line('fb_boosts_no_reports_found'); ?>
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