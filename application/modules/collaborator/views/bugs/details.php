<div style="font-size:10pt; font-family: Open Sans;">
 

  <div style="padding:35px;">

    <?php
                if (!empty($bug_details)) {
        foreach ($bug_details as $key => $bug) { ?>

    <div style="padding:35px 0 50px;text-align:center">
      <span style="border-bottom:1px solid #eee;font-size:13pt;"><?=lang('bug_details')?></span>
    </div>
    <div style="width: 70%;float: left;">
      <div style="width: 100%;padding: 11px 0;">
        <div style="color:#999;width:35%;float:left;"><?=lang('project_name')?></div>
        <div style="width:65%;border-bottom:1px solid #eee;float:right;foat:right;">
      <a href="<?=base_url()?>collaborator/projects/details/<?=$bug->project?>">
      <?=$this->user_profile->get_project_details($bug->project,'project_title')?></a>
      </div>
        <div style="clear:both;"></div>
        </div><div style="width: 100%;padding: 10px 0;">
        <div style="color:#999;width:35%;float:left;"><?=lang('reference_no')?></div>
        <div style="width:65%;border-bottom:1px solid #eee;float:right;foat:right;min-height:22px"><?=$bug->issue_ref?></div>
        <div style="clear:both;"></div>
      </div>
    </div>
<?php
if ($bug->bug_status == 'Unconfirmed') {
  $colorcode = '#FB6B5B';
}elseif ($bug->bug_status == 'Confirmed') {
  $colorcode = '#4CC0C1';
}elseif ($bug->bug_status == 'Resolved') {
  $colorcode = '#65BD77';
}elseif ($bug->bug_status == 'In Progress') {
  $colorcode = '#2E3E4E';
}else{
  $colorcode = '#78ae54';
}
?>
    <div style="text-align:center;color:white;float:right;background:<?=$colorcode?>;width: 25%;
      padding: 20px 5px;">
      <span> <?=strtoupper(lang('bug_status'))?></span><br>
      <span style="font-size:16pt;"><?=$bug->bug_status?></span>
    </div><div style="clear:both;"></div>

    <div style="padding-top:10px">
      <div style="width:75%;border-bottom:1px solid #eee;float:right">
      <strong id="zb-pdf-customer-detail"><a href="#">
      <?=ucfirst($this->user_profile->get_profile_details($bug->reporter,'fullname')? $this->user_profile->get_profile_details($bug->reporter,'fullname'):$this->user_profile->get_user_details($bug->reporter,'username'))?></a></strong></div>
      <div style="color:#999;width:25%"><?=lang('reporter')?></div> 
    </div>
    <div style="padding-top:25px">
        <div style="width:75%;border-bottom:1px solid #eee;float:right">
        <?=ucfirst($this->user_profile->get_profile_details($bug->assigned_to,'fullname')? $this->user_profile->get_profile_details($bug->assigned_to,'fullname'):$this->user_profile->get_user_details($bug->assigned_to,'username'))?></div>
        <div style="color:#999;width:25%"><?=lang('assigned_to')?></div>
    </div>
    <div style="padding-top:25px">
        <div style="width:75%;border-bottom:1px solid #eee;float:right"><?=$bug->priority?></div>
        <div style="color:#999;width:25%"><?=lang('priority')?></div>
    </div>
    <div style="padding-top:25px">
        <div style="width:75%;border-bottom:1px solid #eee;float:right"><?=$bug->bug_description?></div>
        <div style="color:#999;width:25%"><?=lang('bug_description')?></div>
    </div>
    
<?php } } ?>
    
  </div>
</div>