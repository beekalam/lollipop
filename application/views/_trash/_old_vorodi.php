<div class="portlet box red">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-cogs"></i>تنظیمات
        </div>
        <div class="actions">

        </div>
    </div>
    <!-- <i class="icon-settings font-dark"></i> -->
    <!-- <span class="caption-subject bold uppercase"> تنظیمات</span> -->
    <div class="portlet-body form">
        <form role="form" method="POST"
              action="<?php echo base_url() . 'settings/update'; ?>">
            <?php foreach ($settings->result() as $k => $v): ?>
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">
                            <?php echo $v->description; ?>
                        </label>
                        <div class="col-md-9">
                            <input type="text"
                                   id="<?php echo $v->name; ?>"
                                   name="<?php echo $v->name; ?>"
                                   class="form-control input-sm"
                                   placeholder="نام"
                                   value="<?php echo $v->value; ?>">
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
            <div class="form-actions">
                <button type="submit" class="btn blue">ثبت</button>
                <!-- <button type="button" class="btn default">لغو</button> -->
            </div>
        </form>

    </div>
</div>