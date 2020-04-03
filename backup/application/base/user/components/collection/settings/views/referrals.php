<div class="row">
    <div class="col-xl-12">
        <div class="input-group">
            <h4><?php echo $this->lang->line('share_referrals_link'); ?></h4>
            <p>
                <?php echo $this->lang->line('you_will_earn_will earn_a_commission'); ?>
            </p>
            <input type="text" class="form-control" value="<?php echo base_url() . '?ref=' . str_replace('=', '', base64_encode($this->user_id)); ?>">
            <div class="input-group-append">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(base_url() . '?ref=' . str_replace('=', '', base64_encode($this->user_id))); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" class="btn btn-default" title="Share on Facebook">
                    <i class="icon-social-facebook"></i>
                </a>
                <a href="https://twitter.com/share?url=<?php echo urlencode(base_url() . '?ref=' . str_replace('=', '', base64_encode($this->user_id))); ?>&via=TWITTER_HANDLE" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" class="btn btn-default btn-twitter" target="_blank" title="Share on Twitter">
                    <i class="icon-social-twitter"></i>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(base_url() . '?ref=' . str_replace('=', '', base64_encode($this->user_id))); ?>&source=LinkedIn
" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" class="btn btn-default btn-linkedin" target="_blank" title="Share on Twitter">
                    <i class="fab fa-linkedin"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row clean">
    <div class="col-xl-4 referral-single-stats">
        <div class="col-xl-12">
            <h3><?php echo ($stats[0]->signups) ? $stats[0]->signups : '0'; ?></h3>
            <p><?php echo $this->lang->line('total_signups'); ?></p>
        </div>
    </div>
    <div class="col-xl-4 referral-single-stats">
        <div class="col-xl-12">
            <h3><?php echo ($stats[0]->total_paid) ? $stats[0]->total_paid . ' ' . $stats[0]->currency_code : '0'; ?></h3>
            <p><?php echo $this->lang->line('total_paid'); ?></p>
        </div>
    </div>
    <div class="col-xl-4 referral-single-stats">
        <div class="col-xl-12">
            <h3><?php echo ($stats[0]->total_unpaid) ? $stats[0]->total_unpaid . ' ' . $stats[0]->currency_code : '0'; ?></h3>
            <p><?php echo $this->lang->line('total_unpaid'); ?></p>
        </div>
    </div>
</div>