<script src="<?php echo base_url('assets/js/mansouri.js') . "?cc=" . uniqid(); ?>"></script>
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->

        <div class="page-bar"></div>

        <div class="row">

            <div class="col-xs-12 col-md-6">
                <div class="portlet box green-meadow" style="box-shadow: inset 1px 2px;">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-login"></i>
                            <span class="caption-subject bold uppercase">
                              ورود مشتری 
                              </span>
                        </div>
                    </div>
                    <div class="portlet-body" style="height:380px;">
                        <div class="row"
                             style="text-align: center; font-size:100px;color:#67809F;">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="row" style="padding-top:50px;">

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="input-group">
                                            <span class="input-group-addon input-circle-left">
                                                    <i class="fa fa-credit-card"></i>
                                              </span>
                                        <input type="text" value="" id="rfid"
                                               name="rfid" autofocus="autofocus"
                                               class="form-control input-circle-right"
                                               style="height: 50px;"
                                               placeholder="ورود با استفاده از کارت"/>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <?php if (check_perm('add_customer')): ?>
                <div class="col-xs-12 col-md-6">
                    <?php $this->view('dashboard/partials/_add_customer'); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($active_customers)): ?>
                <div class="col-xs-12 col-md-6" id="active-customers">
                    <?php $this->view('dashboard/partials/_present_customers'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->userdata("isadmin") && isset($user_summary)): ?>
                <div class="col-xs-12 col-md-6" id="user-summary">
                    <?php $this->view('dashboard/partials/_user_summary'); ?>
                </div>
            <?php endif; ?>

        </div>
        <script>
            $(document).ready(function () {
//                console.log("in document ready");
//                set_height('active-customers', 'user-summary');
//                var left   = $("#active-customers").height();
//                var right  = $("#user-summary").height();
//                var height = Math.max(left, right);
//                $("#active-customers").height(height);
//                $("#user-summary").height(height);
//                console.log("height max:" + height);

                $("#usersearch").click(function () {
                    if ($("#rfid").val() != "") {
                        window.location = "<?php echo base_url(); ?>/customers/manage?id=" + $("#rfid").val();
                    }

                    // swal({
                    //    title: "An input!",
                    //    text: "Write something interesting:",
                    //    type: "input",
                    //    showCancelButton: true,
                    //    closeOnConfirm: false,
                    //    inputPlaceholder: "Write something"
                    //  }, function (inputValue) {
                    //    if (inputValue === false) return false;
                    //    if (inputValue === "") {
                    //      swal.showInputError("You need to write something!");
                    //      return false
                    //    }
                    //     window.location = "<?php echo base_url(); ?>/customers/manage?id="+inputValue;
                    //  });
                });

            });
            var update_interval_second = 5;
            setInterval(function () {

                if ($("#rfid").val() != "") {
                    window.location = "<?php echo base_url(); ?>/customers/manage?id=" + $("#rfid").val();
                }

                $(".checkin").each(function () {
                    var checkin = parseInt($(this).attr("data-checkin")) + update_interval_second;
                    $(this).attr("data-checkin", checkin);
                    $(this).html(format_duration(checkin));
                });
            }, update_interval_second * 1000);

        </script>
    </div>
</div>