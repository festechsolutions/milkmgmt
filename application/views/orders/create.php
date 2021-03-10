
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
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
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <?php echo $this->session->flashdata('success'); ?>
        </div>
        <?php elseif($this->session->flashdata('error')): ?>
        <div class="alert alert-error alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
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
                <label for="gross_amount" class="col-sm-12 control-label">Date-Time:
                  <?php date_default_timezone_set("Asia/Kolkata"); echo date('h:i A, d-M-Y') ?>
                </label>
              </div>

              <!--<div class="col-md-4 col-xs-12 pull pull-right">
                  <div class="form-group">
                    <h4><label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Select Date :</label></h4>
                    <div class="col-sm-7">
                       <input type="date" name="date" id="date" class="form-control" max="<?php echo date('d-m-Y'); ?>" required autocomplete="off" placeholder="Select Date">
                    </div>
                  </div>
                </div>-->

              <div class="col-md-4 col-xs-12 pull pull-left">
                <div class="form-group">
                  <h4><label for="store_name" class="col-sm-5 control-label" style="text-align:center;">Select Store /
                      Colony Name:</label></h4>
                  <div class="col-sm-7">
                    <select class="form-control" id="store_name" name="store_name" onchange="getSubscribedUsersData()"
                      style="width:100%;" required>
                      <option value="">Please select Store / Colony</option>
                      <?php foreach ($store as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>">
                        <?php echo $v['name'] ?>
                      </option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
              </div><br>

              <br /> <br />
              <div id='newOne'>
                <div class="prdiv">
                  <div></div>
                  <div>
                    <h5> Name</h5>
                  </div>
                  <div>
                    <h5>Subscribed</h5>
                  </div>
                </div>
                <div id="tab1"></div>
                </table>
              </div>
              <br/> <br/>
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

  $(document).ready(function () {
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
    $("#add_row").unbind('click').bind('click', function () {
      //if (iCnt < 5) { 
      //iCnt = iCnt + 1;
      var table = $("#product_info_table");
      var count_table_tbody_tr = $("#product_info_table tbody tr").length;
      var row_id = count_table_tbody_tr + 1;

      return false;
    });

  }); // /document

  // get the product information from the server
  function getProductData(row_id) {
    var product_id = $("#product_" + row_id).val();
  }

  $(document).ready(function () {
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

  function expand(e) { //to expand and vice versa
    console.log(e);
    if (document.getElementById(`datadiv${e}`).style.display != 'flex') {
      document.getElementById(`datadiv${e}`).style.display = 'flex'
      document.getElementById(`updown${e}`).setAttribute('class', 'fa fa-angle-up')
    } else {
      document.getElementById(`datadiv${e}`).style.display = 'none'
      document.getElementById(`updown${e}`).setAttribute('class', 'fa fa-angle-down')
    }

  }

  function remove(e) { // to remove unwanted childs
    if (document.getElementById(`datadiv${e}`).childNodes.length > 1) {
      document.getElementById(`datadiv${e}`).removeChild(document.getElementById(`datadiv${e}`).lastChild)
    }
  }

  function func1(e) { // to add childs
    var insideDiv = document.createElement('div');
    insideDiv.className = 'same2'
    var inschldDiv1 = document.createElement('div');

    try {
      if (document.getElementById(`datadiv${e}`).childNodes.length < 1) {
        var button = document.createElement('div');
        var icon = document.createElement('i')
        icon.setAttribute('onclick', `addMore(${e})`)
        icon.setAttribute('class', 'fa fa-plus')
        button.appendChild(icon)
        inschldDiv1.appendChild(button)
      } else {
        var button = document.createElement('div');
        var icon2 = document.createElement('i')
        icon2.setAttribute('onclick', `remove(${e})`)
        icon2.setAttribute('class', 'fa fa-minus')
        button.appendChild(icon2)
        inschldDiv1.appendChild(button)
      }
    }
    catch {
      var button = document.createElement('div');
      var icon = document.createElement('i')
      icon.setAttribute('onclick', `addMore(${e})`)
      icon.setAttribute('class', 'fa fa-plus')
      button.appendChild(icon)
      inschldDiv1.appendChild(button)
    }

    insideDiv.appendChild(inschldDiv1)

    var inschldDiv2 = document.createElement('div');
    var slct = document.createElement('select')
    slct.setAttribute('type', 'text')
    slct.setAttribute('class', 'form-control')
    slct.setAttribute('id', 'product_name')
    slct.setAttribute('name', 'product_name[]')
    slct.setAttribute('onchange', 'getProductsData()')
    for (var gg = -1; gg < productsData.length; gg++) {
      var optn = document.createElement('option')
      if (gg == -1) {
        optn.setAttribute('value', '')
        optn.innerHTML = 'Select Product';
      } else {
        optn.setAttribute('value', productsData[gg].id)
        optn.innerHTML = productsData[gg].name;
      }
      slct.appendChild(optn)
    }
    inschldDiv2.appendChild(slct)
    insideDiv.appendChild(inschldDiv2)

    var inschldDiv3 = document.createElement('div');
    var slct = document.createElement('select')
    slct.setAttribute('type', 'text')
    slct.setAttribute('class', 'form-control')
    slct.setAttribute('id', 'qty')
    slct.setAttribute('name', 'qty[]')
    for (var gg = 0; gg < 6; gg++) {
      var optn = document.createElement('option')
      if (gg == 0) {
        optn.setAttribute('value', '')
        optn.innerHTML = 'Select Quantity';
      } else {
        optn.setAttribute('value', gg)
        optn.innerHTML = gg;
      }
      slct.appendChild(optn)
    }
    inschldDiv3.appendChild(slct)
    insideDiv.appendChild(inschldDiv3)
    return insideDiv
  }
  function addMore(e) {
    console.log(e);

    document.getElementById(`datadiv${e}`).appendChild(func1(e))
  }


  var productsData;
  $.ajax({
    url: base_url + 'products/getTableProductRow',
    type: "get",
    dataType: "json",
    success: function (data) {
      console.log(data);
      productsData = data;
    }
  })

  function getSubscribedUsersData() {
    var store_id = $("#store_name").val();

    var table = $("#users_info_table");
    var count_table_tbody_tr = $("#users_info_table tbody tr").length;
    var extra = '';
    var row_id = 0;
    var html = '';

    $.ajax({
      url: base_url + 'users/getSubscribedUsersData',
      type: "post",
      data: { store_id: store_id },
      dataType: "json",
      success: function (data) {
        console.log(data);
        $('#tab1').empty();
        $.each(data, function (key, value) {
          console.log(row_id);
          row_id++;

          console.log(key, value);

          var trdiv = document.createElement('div')
          trdiv.id = `TabPardiv${value.id}`
          trdiv.className = 'trPardiv'

          var tr = document.createElement('div')
          tr.id = `Tabdiv${value.id}`
          tr.className = 'same2'

          var td1 = document.createElement('div')
          td1.setAttribute('onclick', `expand(${value.id})`)
          var icon = document.createElement('i')
          icon.setAttribute('class', 'fa fa-angle-down')
          icon.id = `updown${value.id}`
          td1.appendChild(icon)

          var td2 = document.createElement('div')
          td2.innerHTML = value.firstname+' '+value.lastname;

          var td3 = document.createElement('div')
          var chkbx = document.createElement('input')
          chkbx.setAttribute('name', 'subscribed[]')
          chkbx.setAttribute('id', value.id)
          chkbx.setAttribute('type', 'checkbox')
          td3.appendChild(chkbx)

          tr.appendChild(td1)
          tr.appendChild(td2)
          tr.appendChild(td3)
          trdiv.appendChild(tr)

          var datadiv = document.createElement('div')
          datadiv.id = `datadiv${value.id}`

          datadiv.appendChild(func1(value.id))
          trdiv.appendChild(datadiv)
          document.getElementById('tab1').appendChild(trdiv)

        });
        if (count_table_tbody_tr >= 0) {
          $("#users_info_table tbody tr:last").after(html);
        }
      }
    });
  }

  function removeRow(tr_id) {
    $("#product_info_table tbody tr#row_" + tr_id).remove();
    calculateSum();
  }

  // Get today's date
  $(function () {
    //date_default_timezone_set("Asia/Kolkata");
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if (month < 10)
      month = '0' + month.toString();
    if (day < 10)
      day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;
    //alert(maxDate);
    $('#date').attr('max', maxDate);
  });

</script>
