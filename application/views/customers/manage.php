<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN THEME PANEL -->
                
            <div class="page-bar"></div>

            <div class="row" style="direction:rtl;">
                <div class="col-xs-12 col-md-6">
                    <div class="portlet box red">
                        <div class="portlet-title">
                            <div class="caption font-red-sunglo">
                                <i class="icon-settings font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase">مشخصات کاربر</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form role="form" method="POST" 
                                              action="<?php echo base_url() . 'customers/manage'; ?>">
                                <input type="hidden" value="<?php echo $customer->cardid;  ?>" name="cardid" id="cardid" />
                                <input type="hidden" value="<?php echo $customer->id;  ?>" name="id" />
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">نام</label>
                                        <div class="col-md-9">
                                          <input type="text" id="first_name" name="first_name" class="form-control input-sm" placeholder="نام" 
                                                value="<?php echo $customer->first_name; ?>" readonly="readonly">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">نام خانوادگی</label>
                                        <div class="col-md-9">
                                          <input type="text" id="last_name" name="last_name" class="form-control input-sm" placeholder="نام خانوادگی"
                                            value="<?php echo $customer->last_name; ?>" 
                                            readonly="readonly">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">ساعت ورود:</label>
                                        <div class="col-md-9">
                                          <input type="text" id="last_name" name="last_name" class="form-control input-sm" placeholder="نام خانوادگی"
                                            value="<?php
                                                    if(!is_null($customer->checkin))
                                                        echo unix_timestamp_to_jalali($customer->checkin);
                                                    else
                                                        echo unix_timestamp_to_jalali(time());
                                              ?>"
                                              readonly="readonly">
                                            <input type="hidden" value="<?php echo time(); ?>" name="checkin_timestamp"/>
                                        </div>
                                    </div>
                                </div>

                                <?php if(!is_null($customer->checkin)): ?>
                                    <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">ساعت خروج:</label>
                                        <div class="col-md-9">
                                          <input type="text" id="last_name" name="last_name" class="form-control input-sm" placeholder="نام خانوادگی"
                                            value="<?php
                                                if(!is_null($customer->checkout))
                                                  echo unix_timestamp_to_jalali($customer->checkout); 
                                                else
                                                  echo unix_timestamp_to_jalali(time());
                                               ?>"
                                               readonly="readonly">
                                          <input type="hidden" value="<?php echo time(); ?>" name="checkout_timestamp"/>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(!is_null($customer->checkin) && !is_null($customer->checkout)): ?>
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
                                    <?php if($show_user_checkin): ?>
                                        <input type="hidden" value="checkin" name="action"/>
                                        <button type="submit" class="btn default">ثبت ورود</button>
                                    <?php endif; ?>
                                    <?php if($show_user_checkout): ?>
                                        <input type="hidden" value="checkout" name="action"/>
                                        <button type="submit" class="btn blue">ثبت خروج</button>
                                    <?php endif; ?>
                                    <?php if(!is_null($customer->checkin) && !is_null($customer->checkout)): ?>
                                        <input type="hidden" value="factor" name="action"/>
                                        <!-- <button type="button" id="view-factor" class="btn default">نمایش فاکتور</button> -->
                  <!-- <button type="button" id="final-checkout" class="btn default pull-right">ثبت فاکتور</button> -->
                                    <?php endif; ?>
                                    <?php if($show_last_factor): ?>
                                        <button type="button" 
                                                id="view-factor2" 
                                                class="btn default pull-right"
                                                data-id="<?php echo $last_factor_id; ?>">مشاهده فاکتور</button>
                                    <?php endif; ?>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- extra services -->
                <div class="col-md-6 col-sm-12">
                    <div class="portlet box red">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-cogs"></i>هزینه متفرقه</div>
                            <div class="actions">
                                <a href="javascript:;" class="btn btn-default btn-sm show-add-modal" data-action="insert">
                                    <i class="fa fa-plus"></i> اضافه </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                           <table id="prices-table" class="table table-bordered table-striped table-hovered">
                               <thead>
                                   <tr>
                                       <th>عنوان</th>
                                       <th>قیمت</th>
                                       <th>تعداد</th>
                                       <th>مجموع</th>
                                       <th>actions</th>
                                   </tr>
                               </thead>
                               <tfoot>
                                 <tr>
                                  <th colspan="4" style="text-align:left;">مجموع:</th>
                                  <th> <?php echo number_format($total); ?> تومان</th>
                                 </tr>
                                 <tr>
                                   <th colspan="4" style="text-align: left;">مجموع بااحتساب ورودی</th>
                                   <th> <?php echo number_format($total+$vorodi); ?> تومان</th>
                                 </tr>
                               </tfoot>
                               <tbody>
                                   <?php 
                                       $fmt = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";
                                       $delete_btn_fmt = "<button data-id='%s' class='btn btn-sm btn-danger delete-extra-service'>delete</button>";
                                       // $edit_btn_fmt = "<button data-id='%s' class='btn btn-sm btn-danger edit-price' data-cat-id='%s' data-description='%s' data-price='%s' data-action='update'>edit</button>";
                                       foreach ($extra_services as  $v) {
                                            $delete_btn=sprintf($delete_btn_fmt,$v->id);
                                            echo sprintf($fmt,$v->description,number_format($v->price)
                                                  ,$v->num,number_format($v->price * $v->num),$delete_btn);
                                       //      $edit_btn =sprintf($edit_btn_fmt,$v->id,$v->cat_id,$v->description,$v->price);
                                       //      echo sprintf($fmt,$v->description,$v->price,$v->cat_name,$delete_btn,$edit_btn);
                                        }
                                    ?>
                               </tbody>
                           </table>
                           <script>
                               $(document).ready(function()
                               {
                                   $("#prices-table").DataTable();    
                               });
                           </script>
                        </div>
                    </div>
                </div>

            </div>

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
                                foreach($prices as $v){
                                     echo sprintf($fmt,$v->id,$v->price,$v->description);
                                }
                            ?>
                        </select>
                        <span id="item-select-error"></span>
                      </div>

                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">قیمت</label>
                        <input type="text" class="form-control" id="item-price" name="item-price" 
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    <button type="submit" class="btn btn-primary" id="add-user-extra">ثبت</button>
                    <input type="hidden" name="customer-id" value="<?php echo $customer->id; ?>"/>
                    <!-- <input type="hidden" value="" id="price_id" /> -->
                  </div>
                    </form>
                </div> 
              </div>
            </div> <!-- end model -->


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
              $(document).ready(function()
              {
                $(".show-add-modal").click(function()
                {
                  $("#modal").modal();
                });

                $("#price-select").on('change',function(e)
                {
                        $.ajax({
                            url: '<?php echo base_url(); ?>settings/get_price',
                            async: 'false',
                            cache: 'false',
                            type: 'POST',
                            data: {"id":$(this).val()},
                            success: function(response) {
                                var data = JSON.parse(response);
                                if (data["success"] == false) {
                                    // alert(data["msg"]);
                                    // console.log(data);
                                  $("#item-price").val("0");
                                    
                                } else {
                                    $("#item-price").val(data["data"][0]["price"]);
                                }
                            }
                        }); //$.ajax                   
                });

                $(".delete-extra-service").on('click',function()
                {
                        $.ajax({
                            url: '<?php echo base_url(); ?>customers/delete_extra_service',
                            async: 'false',
                            cache: 'false',
                            type: 'POST',
                            data: {"id":$(this).attr("data-id")},
                            success: function(response) {
                                var data = JSON.parse(response);
                                if (data["success"] == false) {
                                                                        
                                } else {
                                    window.location="<?php echo base_url(); ?>/customers/manage?id=<?php echo $customer->cardid; ?>"
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

                $("#view-factor2").on('click',function (e) 
                {
                        var factor_id = $(this).attr("data-id");
                        $.ajax({
                            url: '<?php echo base_url(); ?>customers/factor2?id='+ factor_id,
                            async: 'false',
                            cache: 'false',
                            type: 'GET',
                            success: function(response) {
                              $("#modal-factor-body").html(response);
                              $("#modal-factor").modal();
                            }
                        }); //$.ajax                   
                });

                $("#final-checkout").on('click',function()
                {

                       $.ajax({
                            url: '<?php echo base_url(); ?>customers/final_checkout?id=<?php echo $customer->id; ?>',
                            async: 'false',
                            cache: 'false',
                            type: 'POST',
                            data:{ "id":<?php echo $customer->id; ?>},
                            success: function(response) {
                              data = JSON.parse(response);
                              if(data["success"] ==false){
                                alert(data["msg"]);
                              }else{
                                window.location="<?php echo base_url('dashboard') ?>";
                              }
                            }
                        }); //$.ajax    
                })
              });
            </script>
    
    </div>
</div>

<!-- /End content -->