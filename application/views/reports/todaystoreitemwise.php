

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <small>Reports Item Wise</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reports</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12 col-xs-12">
          <form class="form-inline" action="<?php echo base_url('reports/todaystoreitemwise') ?>" method="POST">
            <div class="form-group">
              <label for="date">Shop</label>
              <select class="form-control" name="select_store" id="select_store">
                <option value="">Select shop</option>
                <?php foreach ($store_data as $key => $value): ?>
                  <option value="<?php echo $value['id'] ?>" <?php if($selected_store == $value['id']) { echo 'selected="selected"'; } ?>><?php echo $value['name']; ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
        </div>

        <br /> <br />


        <div class="col-md-12 col-xs-12">

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
        <!-- col-md-12 -->
      </div>

      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Total Items - Report</h3>
        </div>
        <div class="box-body">
            <table id="datatables" class="table table-bordered table-striped">
              <thead>
              <tr>
                  <th>Item Name</th>
                  <th>Quantity</th>
              </tr>
              </thead>
              <tbody>
                <?php $sum='0';?>
                <?php foreach ($order_data as $k => $v): ?>
                 <tr>
                  <td><?php echo $v['product_name'];?></td>
                  <td><?php echo $v['qtysum']; $sum+=$v['qtysum'];?></td>
                 </tr>
                <?php endforeach ?>
                  
              </tbody>
              <tbody>
                <tr>
                 <th>Total Quantity</th>
                 <th>
                    <?php echo $sum; ?>
                 </th>
                </tr>
              </tbody>
            </table>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script type="text/javascript">
    $(document).ready(function() {
      $("#ReportMainNav").addClass('active');
    });
  </script>

