

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Subscription</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Subscribe</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif($this->session->flashdata('error')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>


        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Subscribe</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('orders/create') ?>" method="post" class="form-horizontal">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Date: <?php date_default_timezone_set("Asia/Kolkata"); echo date('d-m-Y') ?></label>
                </div>
                <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Time: <?php date_default_timezone_set("Asia/Kolkata"); echo date('h:i a') ?></label>
                </div>

                <div class="col-md-4 col-xs-12 pull pull-left">
                  <div class="form-group">
                    <h4><label for="store_name" class="col-sm-5 control-label" style="text-align:center;">Select Store / Colony Name:</label></h4>
                    <div class="col-sm-7">
                       <select class="form-control" id="store_name" name="store_name" onchange="getUsersData()" readonly="true" disabled style="width:100%;" required>
                            <option value="">Please select Store / Colony</option>
                            <?php foreach ($stores as $k => $v): ?>
                              <option value="<?php echo $v['id'] ?>" <?php if($subscribe_data['subscribe']['store_id'] == $v['id']) { echo "selected='selected'"; } ?>><?php echo $v['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 pull pull-left">
                  <div class="form-group">
                    <h4><label for="user_name" class="col-sm-5 control-label" style="text-align:center;">Select User:</label></h4>
                    <div class="col-sm-7">
                       <select class="form-control" id="user_name" name="user_name" style="width:100%;" readonly="true" disabled required>
                            <option>-- Select User --</option>
                            <?php foreach ($users as $k => $v): ?>
                            <option value="<?php echo $v['id'] ?>" <?php if($subscribe_data['subscribe']['user_id'] == $v['id']) { echo "selected='selected'"; } ?>><?php echo $v['username'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                  </div>
                </div><br><br><br>
                
                <br /> <br/>
                <table class="table table-bordered" id="product_info_table">
                  <thead>
                    <tr valign="middle">
                      <th style="width:35;text-align:center">Product Name</th>
                      <th style="width:15%;text-align:center">Quantity</th>
                      <th style="width:20%;text-align:center">Amount</th>
                      <th style="width:20%;text-align:center">Total Amount</th>
                      <th style="width:10%;text-align:center"><button type="button" id="add_row" style="background-color:#4CAF50" class="btn btn-default"><i class="fa fa-plus" style="color:white"></i></button></th>
                    </tr>
                  </thead>

                   <tbody>
                    <?php if(isset($subscribe_data['subscribe_item'])): ?>
                      <?php $x = 1; ?>
                      <?php foreach ($subscribe_data['subscribe_item'] as $key => $val): ?>
                     <tr id="row_<?php echo $x; ?>">
                       <td>
                        <select class="form-control" data-row-id="row_<?php echo $x; ?>" id="product_<?php echo $x; ?>" id="product_1" name="product[]" style="width:100%;" onchange="getProductData(1)" required>
                            <option value="">Select Product</option>
                                <?php foreach ($products as $k => $v): ?>
                              <option value="<?php echo $v['id'] ?>" <?php if($val['product_id'] == $v['id']) { echo "selected='selected'"; } ?>><?php echo $v['name'] ?></option>
                              <?php endforeach ?>
                          </select>
                          </select>
                        </td>
                        <td><input type="text" name="qty[]" id="qty_<?php echo $x; ?>" class="form-control" required onkeyup="getTotal(<?php echo $x; ?>)" value="<?php echo $val['qty'] ?>" autocomplete="off"></td>
                        <td>
                          <input type="text" name="rate[]" id="rate_<?php echo $x; ?>" class="form-control" readonly="true" value="<?php echo $val['amount']/$val['qty'] ?>" autocomplete="off">
                          <input type="hidden" name="rate_value[]" id="rate_value_<?php echo $x; ?>" value="<?php echo $val['amount']/$val['qty']?>" class="form-control" autocomplete="off">
                        </td>
                        <td>
                          <input type="text" name="amount[]" id="amount_<?php echo $x; ?>" class="form-control" readonly="true" value="<?php echo $val['amount'] ?>" autocomplete="off">
                          <input type="hidden" name="amount_value[]" id="amount_value_<?php echo $x; ?>" class="form-control" autocomplete="off" value="<?php echo $val['amount'] ?>">
                        </td>
                        <td style="width:10%;text-align:center"><button type="button" class="btn btn-default" style="background-color:#f44336;color:white" onclick="removeRow('1')"><i class="fa fa-close"></i></button></td>
                     </tr>
                     <?php $x++; ?>
                    <?php endforeach; ?>
                   <?php endif; ?>
                  </tbody>
                </table>

                <br /> <br/>

                <div class="col-md-6 col-xs-12 pull pull-right">

                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label">Net Amount</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="gross_amount" name="gross_amount" readonly="true" value="<?php echo $subscribe_data['subscribe']['net_amount'] ?>" autocomplete="off">
                      <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" value="<?php echo $subscribe_data['subscribe']['net_amount'] ?>" autocomplete="off">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="active_status" class="col-sm-5 control-label">Status</label>
                    <div class="col-sm-7">
                      <select type="text" class="form-control" id="active" name="active">
                        <option value="1" <?php if($subscribe_data['subscribe']['active'] == 1) { echo 'selected="selected"'; } ?>>Active</option>
                        <option value="2" <?php if($subscribe_data['subscribe']['active'] == 2) { echo 'selected="selected"'; } ?>>In Active</option>
                      </select>
                    </div>
                  </div>

                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Update Subscription</button>
                <a href="<?php echo base_url('subscribe/') ?>" class="btn btn-warning">Back</a>
              </div>
            </form>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- col-md-12 -->
    </div>
    <!-- /.row -->
    

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";

  $(document).ready(function() {
    //$(".select_group").select2();
    // $("#description").wysihtml5();

    $("#SubscribeMainNav").addClass('active');
    $("#manageSubscriptionSubMenu").addClass('active');
    
    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
        'onclick="alert(\'Call your custom code here.\')">' +
        '<i class="glyphicon glyphicon-tag"></i>' +
        '</button>'; 
  

    // Add new row in the table 
    $("#add_row").unbind('click').bind('click', function() {
      var table = $("#product_info_table");
      var count_table_tbody_tr = $("#product_info_table tbody tr").length;
      var row_id = count_table_tbody_tr + 1;

      $.ajax({
          url: base_url + '/products/getTableProductRow/',
          type: 'post',
          dataType: 'json',
          success:function(response) {
            
              // console.log(reponse.x);
               var html = '<tr id="row_'+row_id+'">'+
                   '<td>'+ 
                    '<select class="form-control" data-row-id="'+row_id+'" id="product_'+row_id+'" name="product[]" style="width:100%;" onchange="getProductData('+row_id+')">'+
                        '<option value="">Select Product</option>';
                        $.each(response, function(index, value) {
                          html += '<option value="'+value.id+'">'+value.name+'</option>';             
                        });
                        
                      html += '</select>'+
                    '</td>'+ 
                    '<td><input type="text" name="qty[]" id="qty_'+row_id+'" class="form-control" onkeyup="getTotal('+row_id+')"></td>'+
                    '<td><input type="text" name="rate[]" id="rate_'+row_id+'" class="form-control" readonly="true"><input type="hidden" name="rate_value[]" id="rate_value_'+row_id+'" class="form-control"></td>'+
                    '<td><input type="text" name="amount[]" id="amount_'+row_id+'" class="form-control" readonly="true"><input type="hidden" name="amount_value[]" id="amount_value_'+row_id+'" class="form-control"></td>'+
                    '<td style="width:10%;text-align:center"><button type="button" class="btn btn-default" onclick="removeRow(\''+row_id+'\')" style="background-color:#f44336;color:white"><i class="fa fa-close"></i></button></td>'+
                    '</tr>';

                if(count_table_tbody_tr >= 1) {
                $("#product_info_table tbody tr:last").after(html);  
              }
              else {
                $("#product_info_table tbody").html(html);
              }

              //$(".product").select2();

          }
        });

      return false;
    });

  }); // /document

  function getTotal(row = null) {
    if(row) {
      var total = Number($("#rate_value_"+row).val()) * Number($("#qty_"+row).val());
      total = total.toFixed(2);
      $("#amount_"+row).val(total);
      $("#amount_value_"+row).val(total);
      
      subAmount();

    } else {
      alert('no row !! please refresh the page');
    }
  }

  // get the product information from the server
  function getProductData(row_id)
  {
    var product_id = $("#product_"+row_id).val();    
    if(product_id == "") {
      $("#rate_"+row_id).val("");
      $("#rate_value_"+row_id).val("");

      $("#qty_"+row_id).val("");           

      $("#amount_"+row_id).val("");
      $("#amount_value_"+row_id).val("");

    } else {
      $.ajax({
        url: base_url + 'orders/getProductValueById',
        type: 'post',
        data: {product_id : product_id},
        dataType: 'json',
        success:function(response) {
          // setting the rate value into the rate input field
          
          $("#rate_"+row_id).val(response.price);
          $("#rate_value_"+row_id).val(response.price);

          $("#qty_"+row_id).val(1);
          $("#qty_value_"+row_id).val(1);

          var total = Number(response.price) * 1;
          total = total.toFixed(2);
          $("#amount_"+row_id).val(total);
          $("#amount_value_"+row_id).val(total);
          
          subAmount();
        } // /success
      }); // /ajax function to fetch the product data 
    }
  }

  function getUsersData()
  {
    var store_id = $("#store_name").val();
    if(store_id == "") {
      $("#user_name").val("");
    }
    else{
      
        $.ajax({
            url: base_url + 'users/getUsersData',
            type: "post",
            data: {store_id : store_id},
            dataType: "json",
            success:function(data) {
                $('#user_name').empty();
                $('#user_name').append('<option> -- Select User -- </option>');
                $.each(data, function(key, value) {
                    $('#user_name').append('<option value="'+ value.id +'">'+ value.username +'</option>');
                });
            }
        });
    }
  }

  // calculate the total amount of the order
  function subAmount() {
    
    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;
    for(x = 0; x < tableProductLength; x++) {
      var tr = $("#product_info_table tbody tr")[x];
      var count = $(tr).attr('id');
      count = count.substring(4);

      totalSubAmount = Number(totalSubAmount) + Number($("#amount_"+count).val());
    }

    totalSubAmount = totalSubAmount.toFixed(2);

    // sub total
    $("#gross_amount").val(totalSubAmount);
    $("#gross_amount_value").val(totalSubAmount);

    // total amount
    var totalAmount = Number(totalSubAmount);
    totalAmount = totalAmount.toFixed(2);
    $("#net_amount").val(totalAmount);
    $("#totalAmountValue").val(totalAmount);

  } // /sub total amount

  function removeRow(tr_id)
  {
    $("#product_info_table tbody tr#row_"+tr_id).remove();
    subAmount();
  }
</script>