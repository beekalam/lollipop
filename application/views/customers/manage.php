<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN THEME PANEL -->

        <div class="page-bar"></div>

        <div class="row" style="direction:rtl;">
            <div class="col-xs-12 col-md-6">
                <?php $this->load->view("customers/partials/_user_actions"); ?>
            </div>
            <?php if (!is_null($customer->checkin)): ?>
                <div class="col-md-6 col-sm-12">
                    <?php $this->load->view("customers/partials/_extra_services"); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($customer_history)): ?>
                <div class="col-xs-12">
                    <?php $this->load->view("customers/partials/_user_factor_history"); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- modal items -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog"
             aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">فاکتور</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?php echo base_url('customers/add_extra_service'); ?>">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">انتخاب گروه</label>
                                <select id="price-select" name="price-select" class="form-control price-select">
                                    <option value="" disabled selected>نوع هزینه</option>
                                    <?php
                                    $fmt = "<option value='%s' data-price='%s'>%s</otpion>";
                                    foreach ($prices as $v) {
                                        echo sprintf($fmt, $v->id, $v->price, $v->description);
                                    }
                                    ?>
                                </select>
                                <span id="item-select-error"></span>
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">قیمت</label>
                                <input type="text" class="form-control persian-number" id="item-price" name="item-price"
                                       readonly="readonly">
                                <span id="item-description-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">تعداد</label>
                                <input type="text" class="form-control" id="item-count" name="item-count" value="1">
                                <span id="item-price-error"></span>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-circle" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary btn-circle" id="add-user-extra">ثبت</button>
                        <input type="hidden" name="customer-id" value="<?php echo $customer->id; ?>"/>
                    </div>
                    </form>
                </div>
            </div>
        </div> <!-- end model -->

        <!-- modal discount  -->
        <div class="modal fade" id="discount-modal" tabindex="-1" role="dialog"
             aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">فاکتور</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?php echo base_url('customers/add_discount'); ?>">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">انتخاب گروه</label>
                                <select id="discount" name="discount" class="form-control price-select">
                                    <option value="" disabled selected>مقدار تخفیف</option>
                                    <?php
                                    foreach ($discounts as $v) {
                                        echo "<option value='{$v->value}'>{$v->value} %</option>";
                                    }
                                    ?>
                                </select>
                                <span id="item-select-error"></span>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-circle" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary btn-circle" id="add-user-extra">ثبت</button>
                        <input type="hidden" name="customer-id" value="<?php echo $customer->id; ?>"/>
                        <input type="hidden" name="customer-rfid" value="<?php echo $customer->cardid; ?>"/>
                        <!-- <input type="hidden" value="" id="price_id" /> -->
                    </div>
                    </form>
                </div>
            </div>
        </div> <!-- end model -->

        <!-- modal factor  -->
        <div class="modal fade" tabindex="-1" id="modal-factor" role="dialog"
             aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-factor-body">
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div> <!-- end model -->

        <script>
            $(document).ready(function () {
                $(".show-add-modal").click(function () {
                    $("#modal").modal();
                });


                $(".show-discount-modal").click(function () {
                    $("#discount-modal").modal();
                });

                $("#price-select").on('change', function (e) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>settings/get_price',
                        async: 'false',
                        cache: 'false',
                        type: 'POST',
                        data: {"id": $(this).val()},
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data["success"] == false) {
                                $("#item-price").val("0");

                            } else {
                                $("#item-price").val(data["data"][0]["price"]);
                            }
                        }
                    }); //$.ajax
                });

                $(".delete-extra-service").on('click', function () {
                    $.ajax({
                        url: '<?php echo base_url(); ?>customers/delete_extra_service',
                        async: 'false',
                        cache: 'false',
                        type: 'POST',
                        data: {"id": $(this).attr("data-id")},
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data["success"] == false) {

                            } else {
                                window.location = "<?php echo base_url(); ?>/customers/manage?id=<?php echo $customer->cardid; ?>"
                            }
                        }
                    }); //$.ajax
                });

                // $("#view-factor").on('click',function()
                // {
                //         $.ajax({
                //             url: '<?php echo base_url(); ?>customers/factor?id=<?php echo $customer->id; ?>',
                //             async: 'false',
                //             cache: 'false',
                //             type: 'GET',
                //             success: function(response) {
                //               $("#modal-factor-body").html(response);      
                //               $("#modal-factor").modal();
                //             }
                //         }); //$.ajax     
                // });

                $("#view-factor2").on('click', function (e) {
                    var factor_id = $(this).attr("data-id");
                    $.ajax({
                        url: '<?php echo base_url(); ?>customers/factor2?id=' + factor_id,
                        async: 'false',
                        cache: 'false',
                        type: 'GET',
                        success: function (response) {
                            $("#modal-factor-body").html(response);
                            $("#modal-factor").modal();
                        }
                    }); //$.ajax
                });

                // $("#final-checkout").on('click',function()
                // {

                //        $.ajax({
                //             url: '<?php echo base_url(); ?>customers/final_checkout?id=<?php echo $customer->id; ?>',
                //             async: 'false',
                //             cache: 'false',
                //             type: 'POST',
                //             data:{ "id":<?php echo $customer->id; ?>},
                //             success: function(response) {
                //               data = JSON.parse(response);
                //               if(data["success"] ==false){
                //                 alert(data["msg"]);
                //               }else{
                //                 window.location="<?php echo base_url('dashboard') ?>";
                //               }
                //             }
                //         }); //$.ajax    
                // })

            });
        </script>

    </div>
</div>

<!-- /End content -->