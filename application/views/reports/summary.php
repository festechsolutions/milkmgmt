<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Reports
      <small>Daywise Summary</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Reports</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <br>    
        <div id="messages"></div>

        <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif($this->session->flashdata('errors')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('errors'); ?>
          </div>
        <?php endif; ?>



        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Daywise Summary</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Bill no</th>
                <th>Saree</th>
                <th>Blouse</th>
                <th>Pant</th>
                <th>Shirt</th>
                <th>Others</th>
                <th>Total Amount</th>                
              </tr>
              </thead>
              <tfoot>
                <tr>
                 <th>Total</th>
                 <th> <?php echo $saree_qty ?> </th>
                 <th> <?php echo $blouse_qty ?> </th>
                 <th> <?php echo $pant_qty ?> </th>
                 <th> <?php echo $shirt_qty ?> </th>
                 <th> <?php echo $others_qty ?> </th>
                 <th> <?php echo $total_amount.'.00' ?> </th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
 

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script type="text/javascript">
var manageTable;
var base_url = "<?php echo base_url(); ?>";

$(document).ready(function() {

  $("#ReportMainNav").addClass('active');
  $("#summaryReportSubMenu").addClass('active');

  // initialize the datatable 
  manageTable = $('#manageTable').DataTable({
    'ajax': base_url + 'reports/fetchSummary',
    'order': [],
     dom: 'Bfrtip',
     buttons: [{
          extend: 'print',
          text: '<i class="fa fa-print"></i> Print',
          title: $('h1').text(),
          exportOptions: {
           columns: ':not(.no-print)'
          },
           title: 'New Fashions Dry Cleaners',
           messageTop: 'Day-wise Report'
    }]
    } );

});

</script>