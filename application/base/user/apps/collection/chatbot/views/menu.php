<a href="<?php echo site_url('user/app/chatbot?p=new-suggestions'); ?>" class="btn btn-success chatbot-new-suggestions">
    <i class="lni-comment"></i>
    <?php echo $this->lang->line('chatbot_new_suggestions_group'); ?>
</a>
<div class="chatbot-menu-group">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="<?php echo site_url('user/app/chatbot'); ?>" class="nav-link<?php echo (!$this->input->get('p', TRUE))?' active show':''; ?>">
                <i class="lni-comment-reply"></i>
                <?php echo $this->lang->line('chatbot_suggestions'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo site_url('user/app/chatbot?p=replies'); ?>" class="nav-link<?php echo ($this->input->get('p', TRUE) === 'replies')?' active show':''; ?>">
                <i class="lni-support"></i>
                <?php echo $this->lang->line('chatbot_replies'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo site_url('user/app/chatbot?p=pages'); ?>" class="nav-link<?php echo ($this->input->get('p', TRUE) === 'pages')?' active show':''; ?>">
                <i class="lni-facebook"></i>
                <?php echo $this->lang->line('chatbot_facebook_pages'); ?>
            </a>
        </li>        
        <li class="nav-item">
            <a href="<?php echo site_url('user/app/chatbot?p=subscribers'); ?>" class="nav-link<?php echo ($this->input->get('p', TRUE) === 'subscribers')?' active show':''; ?>">
                <i class="lni-users"></i>
                <?php echo $this->lang->line('chatbot_subscribers'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo site_url('user/app/chatbot?p=history'); ?>" class="nav-link<?php echo ($this->input->get('p', TRUE) === 'history')?' active show':''; ?>">
                <i class="lni-sort-amount-asc"></i>
                <?php echo $this->lang->line('chatbot_history'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo site_url('user/app/chatbot?p=phone-numbers'); ?>" class="nav-link<?php echo ($this->input->get('p', TRUE) === 'phone-numbers')?' active show':''; ?>">
                <i class="lni-phone"></i>
                <?php echo $this->lang->line('chatbot_phone_numbers'); ?>
            </a>
        </li> 
        <li class="nav-item">
            <a href="<?php echo site_url('user/app/chatbot?p=email-addresses'); ?>" class="nav-link<?php echo ($this->input->get('p', TRUE) === 'email-addresses')?' active show':''; ?>">
                <i class="lni-envelope"></i>
                <?php echo $this->lang->line('chatbot_email_addresses'); ?>
            </a>
        </li>        
        <li class="nav-item">
            <a href="<?php echo site_url('user/app/chatbot?p=audit-logs'); ?>" class="nav-link<?php echo ($this->input->get('p', TRUE) === 'audit-logs')?' active show':''; ?>">
                <i class="lni-bar-chart"></i>
                <?php echo $this->lang->line('chatbot_audit_logs'); ?>
            </a>
        </li>
    </ul>
</div>