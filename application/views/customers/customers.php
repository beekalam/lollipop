<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
                
            <div class="page-bar"></div>
            <div class="row">
                  <div class="col-md-12">
                    <div class="portlet box blue-hoki">
                        <div class="portlet-title">
                         <div class="caption">
                          <i class="fa fa-cogs"></i>  لیست مشتری ها
                        </div>
                        <div class="actions">
                            <a href="<?php echo base_url('customers/csv_export'); ?>"
                               class="btn btn-default btn-sm">
                                <i class="fa fa-file-excel-o"></i>خروجی اکسل
                            </a>

                        </div>

                        </div>
                        <div class="portlet-body">
                            <!-- <div class="portlet-toolbar">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="btn-group">
                                            <button  class="btn sbold green">
                                              <?php //echo anchor('customers/add','تعریف کاربر'); ?>
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>    
                                </div>
                            </div> -->
                          
                          <table border="0" class="table table-responsive table-bordered table-striped" 
                                id="customers-table">
                            <thead>
                                <tr>
                                    <!-- <td>ID</td> -->
                                    <th>نام</th>
                                    <th>نام خانوادگی</th>
                                    <td>موبایل</td>
                                    <td>شماره کارت</td>
                                    <td>وضعیت کارت</td>
                                    <td>عملیات</td>
                                    <!-- <th>عملیات</th> -->
                                </tr>
                            </thead>
                            <tbody class="persian-number">
                               
                            </tbody>
                          </table>
                          <script>
                              $(document).ready(function()
                              {
                                  $("#customers-table").DataTable({
                                    ordering:false,
                                     scrollY: 300,
                                     scroller: {
                                            loadingIndicator: true
                                    },
                                    serverSide: true,
                                    ajax:{
                                        "url":"<?php echo base_url() ?>/customers/customers_list",
                                        "type":"POST"
                                    },
                                    "columnDefs":[
                                      {
                                            "targets": 4,
                                            "data":"id",
                                            "render":function(data,type,row,meta){
                                                var ret = "";
                                                if(data){
                                                  if(Date.parse(data)  < Date.now())
                                                  {
                                                    ret  = "<span class='label label-sm label-warning' ";
                                                    ret += "style='font-family:BYekan' >";
                                                    ret += "منقضی";
                                                    ret += "</span>";
                                                  }else{
                                                    ret = "<span class='label label-sm label-info' ";
                                                    ret += "style='font-family:BYekan' >";
                                                    ret += "دارای اعتبار";
                                                    ret += "</span>";
                                                  }
                                                }

                                                return ret;
                                            }
                                      },
                                      {
                                            "targets": 5,
                                            "data":"id",
                                            "render":function(data,type,row,meta){
                                                var ret= "<a class='btn btn-sm btn-outline grey-salsa' href='";
                                                ret += "<?php echo base_url(); ?>/customers/manage?id=" + row['cardid'];
                                                ret += "'>";
                                                ret += "پنل ";
                                                ret += '<i class="fa fa-search"></i>';
                                                ret +=  "</a>";

                                                if(row['card_canceled'] == 'no'){
                                                  ret += "<button class='btn btn-sm btn-success card-cancelled' ";
                                                  ret +=  "data-id='" + row['id'] + "' >";
                                                  ret +=  "باطل کردن کارت"; 
                                                  ret += '<i class="fa fa-search"></i>';
                                                  ret +=  "</button>";
                                                }

                                                return ret;
                                            }
                                      }
                                    ],
                                    "language": {
                                        "sEmptyTable":     "هیچ داده ای در جدول وجود ندارد",
                                        "sInfo":           "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
                                        "sInfoEmpty":      "نمایش 0 تا 0 از 0 رکورد",
                                        "sInfoFiltered":   "(فیلتر شده از _MAX_ رکورد)",
                                        "sInfoPostFix":    "",
                                        "sInfoThousands":  ",",
                                        "sLengthMenu":     "نمایش _MENU_ رکورد",
                                        "sLoadingRecords": "در حال بارگزاری...",
                                        "sProcessing":     "در حال پردازش...",
                                        "sSearch":         "جستجو:",
                                        "sZeroRecords":    "رکوردی با این مشخصات پیدا نشد",
                                        "oPaginate": {
                                          "sFirst":    "ابتدا",
                                          "sLast":     "انتها",
                                          "sNext":     "بعدی",
                                          "sPrevious": "قبلی"
                                        },
                                        "oAria": {
                                          "sSortAscending":  ": فعال سازی نمایش به صورت صعودی",
                                          "sSortDescending": ": فعال سازی نمایش به صورت نزولی"
                                        }
                                    },
                                     "columns": [
                                        // {"data":"id"},
                                        { "data": "first_name" },
                                        { "data": "last_name" },
                                        { "data": "mobile"},
                                        { "data" : "cardid"},
                                        { "data" : "card_expire_date"},
                                        { "data": "id"},
                                      ]
                                  });    
                              });
                          </script>
                          <script>
                            $(document).ready(function()
                            {
                                  $(document).on('click','.card-cancelled',function()
                                  {
                                     var self = $(this);
                                     var id = self.attr("data-id");
                                     $.ajax({
                                      url: 'customers/cancel_card',
                                      async: 'false',
                                      cache: 'false',
                                      type: 'POST',
                                      data: {'id': id},
                                      success: function (response) {
                                          var data = JSON.parse(response);
                                          if (data["success"] == false) {
                                              var msg = data["error"] || "خطا در باطل کردن کارت";
                                              swal("", msg , "warning")
                                          } else {
                                              swal("", "عملیات با موفقیت انجام شد.", "success")
                                              self.remove();
                                          }
                                      }                                   

                                    }); 
                                 });
                            });
                          </script>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
