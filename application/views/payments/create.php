<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Payments</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Payments</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-md-12 col-xs-12">
        <form class="form-inline" action="<?php echo base_url('payments/fetch') ?>" method="POST">
            <div class="form-group">
              <label for="store_name">Select Store / Colony Name</label>
              <select class="form-control" id="store_name" name="store_name" onchange="getUsersData()" style="width:100%;" required>
                  <option value="">Please select Store / Colony</option>
                  <?php foreach ($stores as $k => $v): ?>
                    <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                  <?php endforeach ?>
              </select>
            </div>
            <div class="form-group">
              <label for="user_name">Select User</label>
              <select class="form-control" id="user_name" name="user_name" style="width:100%;" required>
                <option value="">Select User</option>
              </select>
            </div>
            <div class="form-group">
              <label for="month">Select Month</label>
              <select class="form-control" id="month" name="month" style="width:100%;" required>
                <option value="">Select Month</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
              </select>
            </div>
            <div class="form-group">
              <label for="year">Select Year</label>
              <select class="form-control" id="year" name="year" style="width:100%;" required>
                <option value="2021">2021</option>
              </select>  
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
      </div>
        
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

        <br />

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Products Delivered for the Month - </h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="datatables" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Product Name</th>
                  <th>Quantity</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th colspan="3">Total Amount</th>
                  <th>
                    <?php foreach ($results as $k => $v): ?>
                    <tr>
                      <td><?php 
                      
                        //echo $v['product_name'];
                        echo $v;
                      
                      ?></td>
                    </tr>
                  <?php endforeach ?>
                  </th>
                </tr>
              </tbody>
            </table>
          </div>
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
var manageTable;
var base_url = "<?php echo base_url(); ?>";

$(document).ready(function() {

  $("#PaymentsMainNav").addClass('active');
  $("#createPaymentsSubMenu").addClass('active');

});

function getUsersData()
  {
    var store_id = $("#store_name").val();
    if(store_id == "") {
      $('#user_name').empty();
      $('#user_name').append('<option>Select User</option>');
    }
    else{
      
        $.ajax({
            url: base_url + 'users/getUsersData',
            type: "post",
            data: {store_id : store_id},
            dataType: "json",
            success:function(data) {
                $('#user_name').empty();
                $('#user_name').append('<option>Select User</option>');
                $.each(data, function(key, value) {
                    $('#user_name').append('<option value="'+ value.id +'">'+ value.firstname +' '+ value.lastname+'</option>');
                });
            }
        });
    }
  }

</script>