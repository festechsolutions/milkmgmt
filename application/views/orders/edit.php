

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Orders</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Orders</li>
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
            <h3 class="box-title">Edit Order</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('orders/create') ?>" method="post" class="form-horizontal">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="date" class="col-sm-12 control-label">Date: <?php date_default_timezone_set("Asia/Kolkata"); echo date('Y-m-d') ?></label>
                </div>
                <div class="form-group">
                  <label for="time" class="col-sm-12 control-label">Time: <?php date_default_timezone_set("Asia/Kolkata"); echo date('h:i a') ?></label>
                </div>

                <div class="col-md-5 col-xs-12 pull pull-left">
                  <div class="form-group">
                    <h4><label for="gross_amount" class="col-sm-5 control-label" style="text-align:center;">Bill No &nbsp;&nbsp;&nbsp;&emsp;:</label></h4>
                       <h3><b>&nbsp;&nbsp;&nbsp;<?php echo $order_data['order']['bill_no'];?></b></h4>
                  </div>
                  <div class="form-group">
                    <h4><label for="gross_amount" class="col-sm-5 control-label" style="text-align:center;">Mobile No :</label></h4>
                    <div class="col-sm-7">
                       <h4><b><?php echo $order_data['order']['mobile_no'];?></b></h4>
                    </div>
                  </div>
                </div><br><br>  
                <div class="col-md-5 col-xs-12 pull pull-right">
                  <div class="form-group">
                    <h4><label for="gross_amount" class="col-sm-4 control-label" style="text-align:left;">Delivery Date (dd-mm-yy):</label></h4>
                    <div class="col-sm-7">
                       <h4><?php echo $order_data['order']['due_date'];?></h4>
                    </div>
                  </div>  
                </div>
                
                <br /> <br/>
                <table class="table table-bordered" id="product_info_table">
                  <thead>
                    <tr>
                      <th style="width:20%;text-align:center">Quantity</th>
                      <th style="width:30%;text-align:center">Item</th>
                      <th style="width:20%;text-align:center">Type</th>
                      <th style="width:20%;text-align:center">Amount</th>
                      <th style="width:10%;text-align:center"><button type="button" id="add_row" style="background-color:#4CAF50" class="btn btn-default"><i class="fa fa-plus" style="color:white"></i></button></th>
                    </tr>
                  </thead>

                   <tbody>

                    <?php if(isset($order_data['order_item'])): ?>
                      <?php $x = 1; ?>
                      <?php foreach ($order_data['order_item'] as $key => $val): ?>
                        <?php //print_r($v); ?>
                       <tr id="row_<?php echo $x; ?>">
                         <td><input type="text" name="qty[]" id="qty_<?php echo $x; ?>" class="form-control" placeholder="ENTER QUANTITY" required onkeyup="getTotal(<?php echo $x; ?>)" value="<?php echo $val['qty'] ?>" autocomplete="off"></td>
                         <td>
                          <select class="form-control select_group product" data-row-id="row_<?php echo $x; ?>" id="product_<?php echo $x; ?>" name="product[]" style="width:100%;" onchange="getProductData(<?php echo $x; ?>)" required>
                              <option value=""></option>
                              <?php foreach ($products as $k => $v): ?>
                                <option value="<?php echo $v['id'] ?>" <?php if($val['product_id'] == $v['id']) { echo "selected='selected'"; } ?>><?php echo $v['name'] ?></option>
                              <?php endforeach ?>
                            </select>
                          </td>
                          <td>
                            <input type="text" name="type[]" id="type_<?php echo $x; ?>" class="form-control" style='text-transform:uppercase' placeholder="ENTER TYPE" required value="<?php echo $val['type'] ?>" autocomplete="off">
                          </td>
                          <td>
                            <input type="text" name="amount[]" id="amount_<?php echo $x; ?>" class="form-control amount" placeholder="ENTER AMOUNT" required value="<?php echo $val['amount'] ?>" autocomplete="off">
                            <input type="hidden" name="amount_value[]" id="amount_value_<?php echo $x; ?>" class="form-control" value="<?php echo $val['amount'] ?>" autocomplete="off">
                          </td>
                          <td style="text-align:center"><button type="button" class="btn btn-default" style="background-color:#f44336" onclick="removeRow('<?php echo $x; ?>')"><i class="fa fa-close" style="color:white"></i></button></td>
                       </tr>
                       <?php $x++; ?>
                     <?php endforeach; ?>
                   <?php endif; ?>
                   </tbody>
                </table>

                <br /> <br/>

                <div class="col-md-6 col-xs-12 pull pull-right">

                  <div class="form-group">
                    <label for="net_amount" class="col-sm-5 control-label">Net Amount</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="net_amount" name="net_amount" disabled value="<?php echo $order_data['order']['net_amount'] ?>" autocomplete="off">
                      <input type="hidden" class="form-control" id="net_amount_value" name="net_amount_value" value="<?php echo $order_data['order']['net_amount'] ?>" autocomplete="off">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="paid_status" class="col-sm-5 control-label">Paid Status</label>
                    <div class="col-sm-7">
                      <select type="text" class="form-control" id="paid_status" name="paid_status">
                        <option value="1">Paid</option>
                        <option value="2">Unpaid</option>
                      </select>
                    </div>
                  </div>

                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <a target="__blank" href="<?php echo base_url() . 'orders/printDiv/'.$order_data['order']['id'] ?>" class="btn btn-default" >Print</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?php echo base_url('orders/') ?>" class="btn btn-warning">Back</a>
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
    //var iCnt = 0;
    $(".select_group").select2();
    // $("#description").wysihtml5();

    $("#OrderMainNav").addClass('active');
    $("#createOrderSubMenu").addClass('active');
    
    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
        'onclick="alert(\'Call your custom code here.\')">' +
        '<i class="glyphicon glyphicon-tag"></i>' +
        '</button>'; 
  
    // Add new row in the table 
    $("#add_row").unbind('click').bind('click', function() {
      //if (iCnt < 5) { 
        //iCnt = iCnt + 1;
        var table = $("#product_info_table");
        var count_table_tbody_tr = $("#product_info_table tbody tr").length;
        var row_id = count_table_tbody_tr + 1;

        $.ajax({
          url: base_url + '/orders/getTableProductRow/',
          type: 'post',
          dataType: 'json',
          success:function(response) {
            
              // console.log(reponse.x);
               var html = '<tr id="row_'+row_id+'" valign="baseline">'+
                   '<td><input type="number" name="qty[]" id="qty_'+row_id+'" class="form-control" placeholder="ENTER QUANTITY" required onkeyup="getTotal('+row_id+')"></td>'+
                   '<td>'+ 
                    '<select class="form-control select_group product" data-row-id="'+row_id+'" id="product_'+row_id+'" name="product[]" style="width:100%;" onchange="getProductData('+row_id+')">'+
                        '<option value=""></option>';
                        $.each(response, function(index, value) {
                          html += '<option value="'+value.id+'">'+value.name+'</option>';             
                        });
                        
                      html += '</select>'+
                    '</td>'+ 
                    '<td><input type="text" name="type[]" id="type_'+row_id+'" class="form-control" style="text-transform:uppercase" placeholder="ENTER TYPE" required></td>'+
                    '<td><input type="text" name="amount[]" id="amount_'+row_id+'" class="form-control amount" placeholder="ENTER AMOUNT" required><input type="hidden" name="amount_value[]" id="amount_value_'+row_id+'" class="form-control"></td>'+
                    '<td style="text-align:center"><button type="button" class="btn btn-default" style="background-color:#f44336" onclick="removeRow(\''+row_id+'\')"><i class="fa fa-close" style="color:white"></i></button></td>'+
                    '</tr>';

                if(count_table_tbody_tr >= 1) {
                $("#product_info_table tbody tr:last").after(html);  
              }
              else {
                $("#product_info_table tbody").html(html);
              }

              $(".product").select2();

          }
        });

        return false;
      //}
      //else{
        //$('#add_row').attr('disabled', 'disabled');
      //}
    });

  }); // /document

 

  // get the product information from the server
  function getProductData(row_id)
  {
    var product_id = $("#product_"+row_id).val();    
  }

  $(document).ready(function(){
    //iterate through each textboxes and add keyup
    //handler to trigger sum event
    $("tbody").on("keyup", ".amount", function () {
      calculateSum();
    });
  });

  function calculateSum() {
    var amount = 0;
    //iterate through each textboxes and add the values
    $(".amount").each(function() {
      //add only if the value is number
      if(!isNaN(this.value) && this.value.length!=0) {
        amount += parseFloat(this.value);
      }
    });
    //.toFixed() method will roundoff the final sum to 2 decimal places
    $('#net_amount').val(amount.toFixed(2));
    $('#net_amount_value').val(amount.toFixed(2));
  }

  function removeRow(tr_id)
  {
    $("#product_info_table tbody tr#row_"+tr_id).remove();
    calculateSum();
  }

  $(function(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var maxDate = year + '-' + month + '-' + day;
    //alert(maxDate);
    $('#due_date').attr('min', maxDate);
});
</script>