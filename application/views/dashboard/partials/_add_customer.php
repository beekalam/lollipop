<div class="portlet box green">
    <div class="portlet-title">

        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase">
              مشتری جدید
            </span>
        </div>
    </div>
    <div class="portlet-body form">
        <form role="form" method="POST" action="<?php echo base_url() . 'customers/add'; ?>">
            <?php if (!empty(validation_errors())): ?>
                <div class="alert alert-danger">
                    <?php echo validation_errors(); ?>
                </div>
            <?php endif; ?>

            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">نام</label>
                    <div class="col-md-9">
                        <input type="text" id="first_name" name="first_name" class="form-control input-sm"
                               placeholder="نام"
                               value="<?php echo set_value('first_name'); ?>">
                    </div>
                </div>
            </div>

            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">نام خانوادگی</label>
                    <div class="col-md-9">
                        <input type="text" id="last_name" name="last_name" class="form-control input-sm"
                               placeholder="نام خانوادگی"
                               value="<?php echo set_value('last_name') ?>">
                    </div>
                </div>
            </div>

            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">شماره موبایل</label>
                    <div class="col-md-9">
                        <input type="text" id="mobile" name="mobile" class="form-control input-sm"
                               placeholder="شماره موبایل"
                               value="<?php echo set_value('mobile'); ?>">
                    </div>
                </div>
            </div>

            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">شماره کارت</label>
                    <div class="col-md-9">
                        <input type="text" id="new_rfid" name="new_rfid" class="form-control input-sm"
                               placeholder="شماره کارت"
                               value="<?php echo set_value('new_rfid'); ?>">
                    </div>
                </div>
            </div>

            <div class="form-body">
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">تاریخ انقضا</label>
                    </div>
                    <div class="input-group col-md-9">
                        <input type="text" class="form-control form-filter input-sm"
                               name="card_expire_date" id="card_expire_date" placeholder="تاریخ انقضا"
                               readonly="readonly"
                               value="<?php echo set_value("card_expire_date"); ?>">
                        <span class="input-group-btn">
                        <button class="btn btn-sm default" type="button">
                            <i class="fa fa-calendar"></i>
                        </button>
                    </span>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $("#card_expire_date").datepicker(
                        {
                            isRTL: true,
                            changeMonth: true,
                            changeYear: true,
                            dateFormat: "yy/mm/dd"
                        });
                });
            </script>
            <div class="form-actions">
                <button type="submit" class="btn blue btn-circle">ثبت</button>
                <!-- <button type="button" class="btn default">لغو</button> -->
            </div>
        </form>
    </div>
</div>