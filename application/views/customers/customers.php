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
                                    <!-- <td>ورود</td> -->
                                    <!-- <td>خروج</td> -->
                                    <!-- <td>مدت</td> -->
                                    <!-- <td>هزینه</td> -->
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($query->result() as $row) : ?>
                                    <tr>
                                        <td><?php echo $row->first_name; ?></td>
                                        <td><?php echo $row->last_name; ?></td>
                                        <!-- <td><?php echo unix_timestamp_to_jalali($row->checkin); ?> </td> -->
                                        <?php if(!is_null($row->checkout)): ?>
                                            <!-- <td><?php echo unix_timestamp_to_jalali($row->checkout); ?> </td> -->
                                        <?php else: ?>
                                            <!-- <td>----</td> -->
                                        <?php endif; ?>
                                        <!-- <td><?php echo $row->diff; ?></td> -->
                                        <!-- <td><?php echo $row->to_pay; ?></td> -->
                                        <td>
                                            <?php 
                                                echo anchor('customers/manage?id='.$row->cardid,'panel',
                                                              array('class'=>'btn btn-sm btn-success')); 
                                            ?>
                                            <?php //echo anchor('customers/delete?id='.$row->id, 'Delete',                                                                array('class'=>'btn btn-success')) ; ?>
                                            <?php //echo anchor('customers/checkin?id='.$row->id, 'checkin',                                                                array('class'=>'btn btn-success')) ; ?>
                                            <?php if(is_null($row->checkout)): ?>   
                                                <?php //echo anchor('customers/checkout?id='.$row->id, 'checkout',                                                                array('class'=>'btn btn-success')) ; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach ; ?>
                            </tbody>
                          </table>
                          <script>
                              $(document).ready(function()
                              {
                                  $("#customers-table").DataTable();    
                              });
                          </script>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
