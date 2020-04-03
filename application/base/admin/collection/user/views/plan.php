<div class="row">
   <div class="col-lg-12">
      <div class="col-xl-1 col-lg-2 col-md-2">
         <ul class="nav nav-tabs plan-categories">
            <?php

            // Get plans options
            $plans_options = md_the_plans_options();

            // Verify if plans options exists
            if ($plans_options) {

               // List options
               for ( $p = 0; $p < count($plans_options); $p++) {

                  $active = '';

                  // Verify if is the first
                  if ( $p < 1 ) {
                     $active = ' active';
                  }

                  // Verify if the app is enabled
                  if ( !get_option('app_' . $plans_options[$p]['slug'] . '_enable') && ($plans_options[$p]['slug'] !== 'general') && ($plans_options[$p]['slug'] !== 'networks') ) {
                     continue;
                  }

                  echo '<li class="nav-item' . $active . '">'
                     . '<a class="nav-link" id="' . $plans_options[$p]['slug'] . '-tab" data-toggle="tab" href="#nav-' . $plans_options[$p]['slug'] . '" role="tab" aria-controls="nav-' . $plans_options[$p]['slug'] . '" aria-selected="false">'
                        . $plans_options[$p]['icon'] . '<br>'
                        . $plans_options[$p]['name']
                     . '</a>'
                  . '</li>';

               }

            }
            ?>
         </ul>
      </div>
      <div class="col-xl-11 col-lg-10 col-md-10">
         <?php echo form_open('', array('class' => 'update-plan', 'data-csrf' => $this->security->get_csrf_token_name(), 'data-plan-id' => $this->input->get('plan_id', true))) ?>
         <div class="row">
            <div class="col-lg-12">
               <div class="tab-content" id="myTabContent">
                  <?php
                  md_get_plans_options($this->input->get('plan_id', true));
                  ?>
               </div>
            </div>
         </div>
         <?php echo form_close() ?>
      </div>
   </div>
</div>

<div class="settings-save-changes">
   <div class="col-xs-6">
      <p><?php echo $this->lang->line('user_settings_you_have_unsaved_changes'); ?></p>
   </div>
   <div class="col-xs-6 text-right">
      <button type="button" class="btn btn-default">
         <i class="far fa-save"></i>
         <?php echo $this->lang->line('user_settings_save_changes'); ?>
      </button>
   </div>
</div>