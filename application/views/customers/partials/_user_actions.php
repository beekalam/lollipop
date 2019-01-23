<div class="portlet box red">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-user"></i>
            <span class="caption-subject bold uppercase">ورود و خروج</span>
        </div>
    </div>
    <div class="portlet-body form">
        <form role="form" method="POST"
              action="<?php echo base_url() . 'customers/manage'; ?>">
            <input type="hidden" value="<?php echo $customer->cardid; ?>" name="cardid" id="cardid"/>
            <input type="hidden" value="<?php echo $customer->id; ?>" name="id"/>
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">نام</label>
                    <div class="col-md-9">
                        <input type="text" id="first_name" name="first_name" class="form-control input-sm"
                               placeholder="نام"
                               value="<?php echo $customer->first_name; ?>" readonly="readonly">
                    </div>
                </div>
            </div>

            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">نام خانوادگی</label>
                    <div class="col-md-9">
                        <input type="text" id="last_name" name="last_name" class="form-control input-sm"
                               placeholder="نام خانوادگی"
                               value="<?php echo $customer->last_name; ?>"
                               readonly="readonly">
                    </div>
                </div>
            </div>

            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">ساعت ورود:</label>
                    <div class="col-md-9">
                        <input type="text" id="last_name" name="last_name" class="form-control input-sm persian-number"
                               placeholder="نام خانوادگی"
                               value="<?php
                               if (!is_null($customer->checkin))
                                   echo explode(' ', unix_timestamp_to_jalali($customer->checkin))[1];
                               else
                                   echo unix_timestamp_to_jalali(time());
                               ?>"
                               readonly="readonly">
                        <input type="hidden" value="<?php echo time(); ?>" name="checkin_timestamp"/>
                    </div>
                </div>
            </div>

            <?php if (!is_null($customer->checkin)): ?>
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">ساعت خروج:</label>
                        <div class="col-md-9">
                            <input type="text" id="last_name" name="last_name"
                                   class="form-control input-sm persian-number" placeholder="نام خانوادگی"
                                   value="<?php
                                   if (!is_null($customer->checkout))
                                       echo explode(' ', unix_timestamp_to_jalali($customer->checkout))[1];
                                   else
                                       echo explode(' ', unix_timestamp_to_jalali(time()))[1];
                                   ?>"
                                   readonly="readonly">
                            <input type="hidden" value="<?php echo time(); ?>" name="checkout_timestamp"/>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!is_null($customer->checkin) && !is_null($customer->checkout)): ?>
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">مدت زمان حضور</label>
                        <div class="col-md-9">
                            <input type="text" id="last_name" name="last_name"
                                   class="form-control input-sm" placeholder="نام خانوادگی"
                                   value="<?php
                                   echo formatDuration($customer->checkout - $customer->checkin);
                                   ?>"
                                   readonly="readonly">
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <?php if ($show_user_checkin): ?>
                    <input type="hidden" value="checkin" name="action"/>
                    <button type="submit" class="btn btn-circle default">ثبت ورود</button>
                <?php endif; ?>
                <?php if ($show_user_checkout): ?>
                    <input type="hidden" value="checkout" name="action"/>
                    <button type="submit" class="btn btn-circle blue">ثبت خروج</button>
                <?php endif; ?>
                <?php if (!is_null($customer->checkin) && !is_null($customer->checkout)): ?>
                    <!-- <input type="hidden" value="factor" name="action"/> -->
                    <!-- <button type="button" id="view-factor" class="btn default">نمایش فاکتور</button> -->
                    <!-- <button type="button" id="final-checkout" class="btn default pull-right">ثبت فاکتور</button> -->
                <?php endif; ?>
                <?php if ($show_last_factor): ?>
                    <button type="button"
                            id="view-factor2"
                            class="btn btn-circle default pull-right"
                            data-id="<?php echo $last_factor_id; ?>">مشاهده فاکتور
                    </button>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>