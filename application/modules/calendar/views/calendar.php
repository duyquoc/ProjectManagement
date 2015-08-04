<section id="content">
  <section class="hbox stretch">
 
    <!-- .aside -->
    <aside>
      <section class="vbox">
        <header class="header bg-white b-b b-light">
          <a href="<?=base_url()?>calendar/settings" data-toggle="ajaxModal" class="btn btn-sm btn-primary pull-right"><i class="fa fa-cog"></i> <?=lang('calendar_settings')?></a>
          <p><?=lang('calendar')?></p>
        </header>
        <section class="scrollable wrapper">
            <?php 
            if ($role == '1') { ?>
                <div class="calendar" id="calendar">
                </div>
            <?php } ?>
        </section>

    </section>
  </aside>
  <!-- /.aside -->

</section>
<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" data-target="#nav,html"></a>
</section>
