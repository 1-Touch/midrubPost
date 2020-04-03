<ul class="nav nav-pills nav-stacked labels-info">
    <li <?php echo (!$this->input->get('section', true))?' class="active"':''; ?>>
        <a href="<?php echo site_url('admin/frontend') ?>?p=settings&group=frontend_page">
            <i class="icon-login"></i>
            <?php echo $this->lang->line('frontend_settings_member_access'); ?>
        </a>
    </li>
    <li <?php echo ($this->input->get('section', true) === 'header')?' class="active"':''; ?>>
        <a href="<?php echo site_url('admin/frontend') ?>?p=settings&group=frontend_page&section=header">
            <i class="fab fa-css3"></i>
            <?php echo $this->lang->line('frontend_settings_header'); ?>
        </a>
    </li>
    <li <?php echo ($this->input->get('section', true) === 'footer')?' class="active"':''; ?>>
        <a href="<?php echo site_url('admin/frontend') ?>?p=settings&group=frontend_page&section=footer">
            <i class="fab fa-js"></i>
            <?php echo $this->lang->line('frontend_settings_footer'); ?>
        </a>
    </li>
</ul>