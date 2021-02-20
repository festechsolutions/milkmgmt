

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Deliveries</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Delivery</li>
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
            <h3 class="box-title">Deliver to Colony</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('orders/create') ?>" method="post" class="form-horizontal">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Date-Time: <?php date_default_timezone_set("Asia/Kolkata"); echo date('h:i A, d-M-Y') ?></label>
                </div>

                <div class="col-md-4 col-xs-12 pull pull-right">
                  <div class="form-group">
                    <h4><label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Select Date :</label></h4>
                    <div class="col-sm-7">
                       <input type="date" name="date" id="date" class="form-control" max="<?php echo date('d-m-Y'); ?>" required autocomplete="off" placeholder="Select Date">
                    </div>
                  </div>
                </div>

                <div class="col-md-4 col-xs-12 pull pull-left">
                  <div class="form-group">
                    <h4><label for="store_name" class="col-sm-5 control-label" style="text-align:center;">Select Store / Colony Name:</label></h4>
                    <div class="col-sm-7">
                       <select class="form-control" id="store_name" name="store_name" onchange="getUsersData()" style="width:100%;" required>
                            <option value="">Please select Store / Colony</option>
                            <?php foreach ($store as $k => $v): ?>
                              <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                  </div>
                </div><br>
                
                <br /> <br/>
                <table class="table table-bordered" id="product_info_table">
                  <thead>
                    <tr valign="middle">
                      <th style="width:30%;text-align:center">Name</th>
                      <th style="width:10%;text-align:center">Subscribed</th>
                      <th colspan='2' style="width:40%;text-align:center">Extra</th>
                    </tr>
                  </thead>

                   <tbody>
                     <tr>
                      <td style="width:20%;text-align:center">Swakhil M</td>
                      <td style="width:10%;text-align:center"><input type="checkbox"></td>
                      <td style="width:30%;text-align:center">
                        <select class="form-control" id="product_name" name="product_name" onchange="getProductsData()" style="width:100%;" required>
                            <option value="">Product</option>
                            <?php foreach ($products as $k => $v): ?>
                              <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                        <td style="width:20%;text-align:center">
                          <select type="text" class="form-control" id="qty" name="qty">
                            <option value="">Select Quantity</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                          </select>
                        </td>
                      </td>
                    </tr>
                   </tbody>
                </table>

                <br /> <br/>

                <div class="col-md-6 col-xs-12 pull pull-right">

                  <div class="form-group">
                    <label for="net_amount" class="col-sm-5 control-label">Net Amount</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="net_amount" name="net_amount" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="net_amount_value" name="net_amount_value" autocomplete="off">
                    </div>
                  </div>

                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary" onclick="this.disabled=true;this.value='Submitting...';this.form.submit();">Create Order</button>
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

  $('#submit').click(function () {
    var mysave = $('#amount').html();
    $('#net_amount').val(mysave);
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
  
  // Get today's date
  $(function(){
    //date_default_timezone_set("Asia/Kolkata");
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
    $('#date').attr('max', maxDate);
});

</script>