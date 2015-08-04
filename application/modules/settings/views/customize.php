<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <style type="text/css" media="screen">
            #editor {
                position:relative;
                height:500px;
                width:auto;
                margin:0;
                border:1px solid #e0e0e0;
            }
        </style>
        <?php
        $this->load->helper('file');
        $css = read_file('./resource/css/style.css');
        ?>
        <section class="panel panel-default">
            <header class="panel-heading font-bold"><i class="fa fa-cogs"></i> Custom CSS</header>
            <div class="panel-body">
                <?php
                if (!is_really_writable('./resource/css/style.css'))
                {
                    echo "CSS file ./resource/css/style.css not writable";
                }
                ?>
                <div id="editor"><?=$css;?></div>
                <script src="<?=base_url()?>resource/js/jquery-2.1.1.min.js"></script>
                <script src="//cdn.jsdelivr.net/ace/1.1.8/min/ace.js" type="text/javascript" charset="utf-8"></script>
                <script src="//cdn.jsdelivr.net/ace/1.1.8/min/ext-beautify.js" type="text/javascript" charset="utf-8"></script>
                <script type="text/javascript">
                    $(document).ready(function(){
                        var editor = ace.edit("editor");
                        editor.setTheme("ace/theme/monokai");
                        editor.getSession().setMode("ace/mode/css");

                        $("#saveeditor").click(function(){
                            $('#css-area').val(editor.getSession().getValue());
                            $('#css_form').submit();
                        });
                    });
                </script>
                <?php
                $attributes = array('class' => 'bs-example form-horizontal', 'id' => 'css_form');
                echo form_open_multipart('settings/customize', $attributes);
                ?>
                <textarea style="display:none;" id="css-area" name="css-area"></textarea>
                <?php echo form_close(); ?>
            </div>
            <div class="panel-footer">
                <div class="text-center">
                    <button id="saveeditor" class="btn btn-sm btn-primary"><?=lang('save_changes')?></button>
                </div>
            </div>
        </section>
    </div>
    <!-- End Form -->
</div>