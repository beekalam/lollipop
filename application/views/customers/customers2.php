<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
                
            <div class="page-bar"></div>
            <div class="row">
                  <div class="col-md-12">
                    <div class="portlet box blue-hoki">
                        <div class="portlet-title">لیست کاربران</div>
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
                                    <td>mobile</td>
                                    <td>actions</td>
                                    <!-- <td>مدت</td> -->
                                    <!-- <td>هزینه</td> -->
                                    <!-- <th>عملیات</th> -->
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                          </table>
                          <script>
                              $(document).ready(function()
                              {
                                  $("#customers-table").DataTable({
                                     scrollY: 300,
                                     scroller: {
                                            loadingIndicator: true
                                    },
                                    serverSide: true,
                                    ajax:{
                                        "url":"<?php echo base_url() ?>/customers/get_customer",
                                        "type":"POST"
                                    },
                                    "columnDefs":[{
                                          "targets": 3,
                                          "data":"id",
                                          "render":function(data,type,row,meta){
                                              var ret= "<a class='btn btn-sm btn-outline grey-salsa' href='";
                                              ret += "<?php echo base_url(); ?>/customers/manage?id=" + row['cardid'];
                                              ret += "'>";
                                              ret += "پنل ";
                                              ret += '<i class="fa fa-search"></i>';
                                              ret +=  "</a>";
                                              return ret;
                                          }
                                    }],
                                     "columns": [
                                        // {"data":"id"},
                                        { "data": "first_name" },
                                        { "data": "last_name" },
                                        { "data": "mobile"},
                                        { "data": "id"}
                                      ]
                                  });    
                              });
                          </script>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
