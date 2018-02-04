<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
                
            <div class="page-bar"></div>

            <div class="row">

                  <div class="col-xs-12 col-md-6">
                    <div class="portlet box green-meadow">
                        <div class="portlet-title">
                            <i class="fa fa-exchange"></i>
                            ورود کابر 
                        </div>
                        <div class="portlet-body" style="height:293px;">
                            <div class="portlet-toolbar">
                                <div class="row">
                                    <div class="col-md-6">
                                      <input type="text" value="" id="rfid" name="rfid" autofocus="autofocus" />
                                  <!--       <div class="btn-group">
                                            <button  class="btn sbold green" id="usersearch">
                                              search
                                            </button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-xs-12 col-md-6">
                    <div class="portlet box green">
                        <div class="portlet-title">
                              <i class="fa fa-exchange"></i>
                              کاربر جدید
                            <div class="caption font-red-sunglo">
                                <i class="icon-settings font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase"></span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form role="form" method="POST" action="<?php echo base_url() . 'customers/add'; ?>">
                                <?php if(!empty(validation_errors())): ?>
                                    <div class="alert alert-danger">
                                      <?php echo validation_errors(); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">نام</label>
                                        <div class="col-md-9">
                                          <input type="text" id="first_name" name="first_name" class="form-control input-sm" placeholder="نام"
                                           value="<?php echo set_value('first_name'); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">نام خانوادگی</label>
                                        <div class="col-md-9">
                                          <input type="text" id="last_name" name="last_name" class="form-control input-sm" placeholder="نام خانوادگی"
                                           value="<?php echo set_value('last_name') ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">شماره موبایل</label>
                                        <div class="col-md-9">
                                          <input type="text" id="mobile" name="mobile" class="form-control input-sm" placeholder="شماره موبایل" 
                                            value="<?php echo set_value('mobile'); ?>" >
                                        </div>
                                    </div>
                                </div>

                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">rfid</label>
                                        <div class="col-md-9">
                                          <input type="text" id="new_rfid" name="new_rfid" class="form-control input-sm" placeholder="rfid"
                                           value="<?php echo set_value('new_rfid'); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn blue">ثبت</button>
                                    <!-- <button type="button" class="btn default">لغو</button> -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


<?php if(isset($active_customers)): ?>    
                <div class="col-xs-12 col-md-6">
                    <div class="portlet box blue-hoki">
                        <div class="portlet-title">
                          <i class="fa fa-exchange"></i>
                          تعداد حاضران(<?php echo isset($active_customers_count) ? $active_customers_count:0; ?>)
                        </div>
                        <div class="portlet-body">
                            <div class="portlet-toolbar">
                                <div class="row">
                                    <div class="col-xs-12">
                                         <table class="table table-striped table-bordered" id="last-customers">
                                           <thead>
                                             <tr>
                                              <th>نام</th>
                                              <th>ساعت ورود</th>
                                              <th>مدت حضور</th>
                                              <th>actions</th>
                                             </tr>
                                           </thead>
                                           <tbody>
<?php 
foreach($active_customers as $v){
      $span = "<span class='checkin' data-checkin='" . intVal(time()- $v->checkin) . "'>";
      $span .= formatDuration(time() - $v->checkin) . "</span>";
      $user_panel =  "<a class='btn btn-sm btn-success' href='" . base_url() . "/customers/manage?id=" . $v->cardid ."'>panel</a>";
    echo "<tr>";
      echo "<td>{$v->first_name} {$v->last_name}</td>";
      echo "<td>". unix_timestamp_to_jalali($v->checkin) . "</td>";
      echo "<td>" . $span . "</td>";
      echo "<td>" . $user_panel . "</td>";
    echo "</tr>";
 }
?>
                                           </tbody>
                                         </table>
                                         <script>
                                           $(document).ready(function()
                                           {
                                             $("#last-customers").dataTable();
                                           });
                                         </script>
                                    </div>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<?php endif; ?>

            </div>
            <script>
                $(document).ready(function()
                {
                    $("#usersearch").click(function()
                    {
                      if($("#rfid").val()!="")
                      {
                        window.location="<?php echo base_url(); ?>/customers/manage?id="+$("#rfid").val();
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
                  setInterval(function(){
                      
                      if($("#rfid").val()!="")
                      {
                        window.location="<?php echo base_url(); ?>/customers/manage?id="+$("#rfid").val();
                      }
                      $(".checkin").each(function()
                      {
                        var checkin = parseInt($(this).attr("data-checkin")) + 1;
                        $(this).attr("data-checkin",checkin);
                        $(this).html(format_duration(checkin));
                      });
                    },1000);
            </script>
    </div>
</div>