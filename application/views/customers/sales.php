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
                             <i class="icon-bubbles"></i> لیست فروش
                          </div>
                          <div class="actions">
                            
                            <a href="<?php echo base_url('customers/sales_search?duration=thismonth'); ?>"
                               class="btn btn-default btn-sm btn-circle">
                                <!-- <i class="fa fa-plus"></i> -->
                                گزارش این ماه
                            </a>

                            <a href="<?php echo base_url('customers/sales_search?duration=lastmonth'); ?>"
                               class="btn btn-default btn-sm btn-circle">
                                <!-- <i class="fa fa-plus"></i> -->
                                گزارش ماه گذشته
                            </a>

                            <a href="<?php echo base_url('customers/sales_csv_export'); ?>"
                               class="btn btn-default btn-sm btn-circle">
                                <i class="fa fa-file-excel-o"></i>خروجی اکسل
                            </a>

                          </div>

                        </div>
                        <div class="portlet-body">
                          
                          <table border="0" class="table table-responsive table-bordered table-striped" 
                                id="customers-table">
                            <thead>
                                <tr>
                                    <td>شماره فاکتور</td>
                                    <td>مدت حضور</td>
                                    <th>تاریخ</th>
                                    <th>نام</th>
                                    <th>عملیات</th>
                                </tr>
                                <tr role="filter">
                                  <td></td>
                                  <td></td>
                                  <td>
                                      <div class="row">
                                          <div class="col-xs-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-filter input-sm" readonly="" name="from_date" id="from_date" placeholder="از تاریخ"
                                                 value="<?php @eisset($from_date); ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                          </div>
                                          <div class="col-xs-6">
                                            <div class="input-group" >
                                                <input type="text" class="form-control form-filter input-sm" readonly="" name="to_date" id="to_date" placeholder="تا تاریخ"
                                                 value="<?php @eisset($to_date); ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                          </div>
                                        </div>
                                  </td>
                                  <td></td>
                                  <td>
                                    <div class="margin-bottom-5">
                                      <button class="btn btn-sm green btn-outline filter-submit btn-circle margin-bottom">
                                          <i class="fa fa-search"></i> جستجو</button>
                                    </div>
                                  </td>
                                </tr>
                            </thead>
                            <tbody class="persian-number">
                               
                            </tbody>
                          </table>
                          <div class="alert alert-warning" id="summary" style='display:none;'>
                          </div>

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
                                  
                                  $(document).on('click','.filter-submit',function()
                                  {
                                      var from_date = $("#from_date").val();
                                      var to_date = $("#to_date").val();
                                      var str = "<?php echo base_url('customers/sales_search'); ?>?";
                                      if(from_date && to_date){
                                        str += "fd=" + from_date;
                                        str +="&";
                                        str += "td=" + to_date;
                                        window.location=str;
                                      }
                                  });

                                  $("#customers-table")
                                  .on('xhr.dt',function(e,settings, json, xhr){
                                    // console.log(json);
                                    if(json["show_summary"]){
                                      if(!json["sum"]) json["sum"]=0;
                                      var line = "مجموع: ";
                                       line +=  "<span class='persian-number'>" + number_format(json["sum"]) + " ریال" + "</span>";
                                      $("#summary").html(line);
                                      $("#summary").show();
                                    }
                                  })
                                  .DataTable({
                                      "bScrollInfinite": true,
                                      "ordering":false,
                                      "searching":true,
                                      // "bScrollCollapse": true,
                                     // "sScrollY": "200px",
                                      // "pageLength": 10,
                                     scrollY: 200,
                                     scroller: {
                                            loadingIndicator: true
                                    },
                                    serverSide: true,
                                    ajax:{
                                        "url":"<?php echo base_url() ?>/customers/sales_list",
                                        "type":"POST"
                                    },
    
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
                                    "columnDefs":[
                                        {
                                              "targets": 0,
                                              "data":"id",
                                              "render":function(data,type,row,meta){
                                                   return data;
                                              }
                                        },
                                        {
                                          "targets":1,
                                          "data":"checkin",
                                          "render":function(data, type, row, meta){
                                            return format_duration(row['checkout'] - row['checkin']);
                                          }
                                        },
                                        {
                                          "targets":2,
                                          "data":"created_at_persian",
                                          "render":function(data, type, row, meta){
                                              return "<div class='ltr pull-left'>" + data + "</div>";
                                          }
                                        },
                                        {
                                          "targets":3,
                                          "data":"first_name",
                                          "render":function(data, type, row, meta){
                                              return row["first_name"] + " " + row["last_name"];
                                          }
                                        },       
                                        {
                                          "targets":4,
                                          "data":"first_name",
                                          "render":function(data, type, row, meta){
                                              // return row["first_name"] + " " + row["last_name"];
                                              var ret =  "<button class='btn btn-sm btn-success btn-circle show-factor' ";
                                                  ret += " data-id='" + row['id'] + "' ";
                                                  ret += ">فاکتور </button>";
                                              return ret;
                                          }
                                        }                                                      
                                    ],

                                     "columns": [
                                        { "data": "id"},
                                        { "data": "checkin" },
                                        { "data": "created_at_persian" },
                                        { "data": "first_name"},
                                        // { "data":"last_name"}
                                        // { "data": "mobile"},
                                        // { "data": "id"}
                                      ]
                                  });    

                                    $(document).on('click',".show-factor",function()
                                    { 
                                          var id=$(this).attr('data-id');
                                            $.ajax({
                                                url: '<?php echo base_url('customers/factor2?id='); ?>' + id,
                                                async: 'false',
                                                cache: 'false',
                                                type: 'GET',
                                                success: function(response) {
                                                  $("#modal-factor-body").html(response);      
                                                  $("#modal-factor").modal();
                                                }
                                            }); //$.ajax     
                                    }); 


                                  $(document).on('keypress','.dataTables_filter .form-control',
                                    function(e)
                                    {
                                      $("#summary").hide();
                                    });

                                   $("#from_date").datepicker(
                                    {
                                      isRTL: true, 
                                      changeMonth: true,
                                      changeYear: true,
                                      dateFormat: "yy/mm/dd"
                                    });               

                                    $("#to_date").datepicker(
                                    {
                                      isRTL: true, 
                                      changeMonth: true,
                                      changeYear: true,
                                      dateFormat: "yy/mm/dd"
                                    });        
                              });
                          </script>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
