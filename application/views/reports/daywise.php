<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <small>Reports</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Day Wise Reports</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12 col-xs-12">
          <form class="form-inline" action="<?php echo base_url('reports/daywise') ?>" method="POST" value="<?='user_date'?>">
            <div class="form-group">
                <label for="date">Select Date </label>
                <input type="date" name="user_date" id="user_date" class="form-control" max="<?php echo date('Y-m-d');?>" required autocomplete="off">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
        </div>

        <br><br>

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


          <!-- ./col -->

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3><?php echo $company_currency.$total_paid_amount ?></h3>

                <b><h4>Total</h4></b>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-blue">
              <div class="inner">
                <h3><?php echo $company_currency.$malkajgiri_store_amount ?></h3>

                <b><h4>Malkajgiri</h4></b>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
            </div>
          </div>


          <!--<div class="col-lg-3 col-xs-6">
      
            <div class="small-box bg-orange">
              <div class="inner">
                <h3><?php echo $company_currency.$tarnaka_store_amount ?></h3>

                <b><h4>Tarnaka</h4></b> 
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
            </div>
          </div>-->
          
      </div>
    </section>  
  <!-- /.content-wrapper -->
  </div>

  <script type="text/javascript">
    $(document).ready(function() {
      $("#ReportMainNav").addClass('active');
      $("#daywiseReportSubMenu").addClass('active');
    });
  </script>