 <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        
        <li id="dashboardMainMenu">
          <a href="<?php echo base_url('dashboard') ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        <?php if($user_permission): ?>
          <?php if(in_array('createUser', $user_permission) || in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)): ?>
            <li class="treeview" id="userMainNav">
              <a href="#">
                <i class="fa fa-user"></i>
                <span>Users</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if(in_array('createUser', $user_permission)): ?>
                <li id="createUserSubNav"><a href="<?php echo base_url('users/create') ?>"><i class="fa fa-circle-o"></i> Add User</a></li>
                <?php endif; ?>

                <?php if(in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)): ?>
                <li id="manageUserSubNav"><a href="<?php echo base_url('users') ?>"><i class="fa fa-circle-o"></i> Manage Users</a></li>
              <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>

          <?php if(in_array('createGroup', $user_permission) || in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)): ?>
            <li class="treeview" id="groupMainNav">
              <a href="#">
                <i class="fa fa-users"></i>
                <span>Groups</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if(in_array('createGroup', $user_permission)): ?>
                  <li id="createGroupSubMenu"><a href="<?php echo base_url('groups/create') ?>"><i class="fa fa-circle-o"></i> Add Group</a></li>
                <?php endif; ?>
                <?php if(in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)): ?>
                <li id="manageGroupSubMenu"><a href="<?php echo base_url('groups') ?>"><i class="fa fa-circle-o"></i> Manage Groups</a></li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>
        

        

        <!-- <li class="header">Settings</li> -->
        <?php if(in_array('createStore', $user_permission) || in_array('updateStore', $user_permission) || in_array('viewStore', $user_permission) || in_array('deleteStore', $user_permission)): ?>
          <li id="storesMainNav"><a href="<?php echo base_url('stores/') ?>"><i class="fa fa-home"></i> <span>Stores / Colonies</span></a></li>
        <?php endif; ?>

        <?php if(in_array('createCategory', $user_permission) || in_array('updateCategory', $user_permission) || in_array('viewCategory', $user_permission) || in_array('deleteCategory', $user_permission)): ?>
          <li id="categoryMainNav"><a href="<?php echo base_url('category/') ?>"><i class="fa fa-calendar"></i> <span>Category</span></a></li>
        <?php endif; ?>

        <?php if(in_array('createProduct', $user_permission) || in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
            <li class="treeview" id="productMainNav">
              <a href="#">
                <i class="fa fa-cutlery"></i>
                <span>Products</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if(in_array('createProduct', $user_permission)): ?>
                  <li id="createProductSubMenu"><a href="<?php echo base_url('products/create') ?>"><i class="fa fa-circle-o"></i> Add product</a></li>
                <?php endif; ?>
                <?php if(in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
                <li id="manageProductSubMenu"><a href="<?php echo base_url('products') ?>"><i class="fa fa-circle-o"></i> Manage Products</a></li>
                <?php endif; ?>
              </ul>
            </li>
        <?php endif; ?>

        <?php if(in_array('createSubscription', $user_permission) || in_array('updateSubscription', $user_permission) || in_array('viewSubscription', $user_permission) || in_array('deleteSubscription', $user_permission)): ?>
            <li class="treeview" id="SubscribeMainNav">
              <a href="#">
                <i class="fa fa-bell"></i>
                <span>Subscribe</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if(in_array('createSubscription', $user_permission)): ?>
                  <li id="newSubscriptionSubMenu"><a href="<?php echo base_url('subscribe/new') ?>"><i class="fa fa-circle-o"></i> New Subscription</a></li>
                <?php endif; ?>
                <?php if(in_array('updateSubscription', $user_permission) || in_array('viewSubscription', $user_permission) || in_array('deleteSubscription', $user_permission)): ?>
                <li id="manageSubscriptionSubMenu"><a href="<?php echo base_url('subscribe') ?>"><i class="fa fa-circle-o"></i> Manage Subscriptions</a></li>
                <?php endif; ?>
              </ul>
            </li>
        <?php endif; ?>
        

        <?php if(in_array('createOrder', $user_permission) || in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
            <li class="treeview" id="OrderMainNav">
              <a href="#">
                <i class="fa fa-truck"></i>
                <span>Deliveries</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if(in_array('createOrder', $user_permission)): ?>
                  <li id="createOrderSubMenu"><a href="<?php echo base_url('orders/create') ?>"><i class="fa fa-circle-o"></i> To Stores/Colonies</a></li>
                <?php endif; ?>
                <?php if(in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
                <li id="manageOrderSubMenu"><a href="<?php echo base_url('orders') ?>"><i class="fa fa-circle-o"></i> Manage Deliveries</a></li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>

<<<<<<< HEAD
=======
          <?php if(in_array('viewPayments', $user_permission)): ?>
            <li class="treeview" id="PaymentsMainNav">
              <a href="#">
                <i class="fa fa-credit-card"></i>
                <span>Payments</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if(in_array('createPayments', $user_permission)): ?>
                  <li id="createPaymentsSubMenu"><a href="<?php echo base_url('payments/main') ?>"><i class="fa fa-circle-o"></i> New Payment</a></li>
                <?php endif; ?>
                <?php if(in_array('viewPayments', $user_permission)): ?>
                  <li id="managepaymentsSubMenu"><a href="<?php echo base_url('payments') ?>"><i class="fa fa-circle-o"></i>Manage Payments</a></li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>

          <?php if(in_array('viewReport', $user_permission)): ?>
            <li class="treeview" id="ReportMainNav">
              <a href="#">
                <i class="fa fa-signal"></i>
                <span>Reports</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if(in_array('viewReport', $user_permission)): ?>
                  <li id="productReportSubMenu"><a href="<?php echo base_url('reports') ?>"><i class="fa fa-circle-o"></i>Total Revenue</a></li>
                <?php endif; ?>
                <?php if(in_array('viewReport', $user_permission)): ?>
                  <li id="productReportSubMenu"><a href="<?php echo base_url('reports/todayitemwise') ?>"><i class="fa fa-circle-o"></i>Today's Delivered Items</a></li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>
>>>>>>> dev

          <?php if(in_array('updateCompany', $user_permission)): ?>
            <li id="companyMainNav"><a href="<?php echo base_url('company/') ?>"><i class="fa fa-files-o"></i> <span>Company Info</span></a></li>
          <?php endif; ?>

          <?php if(in_array('viewProfile', $user_permission)): ?>
            <li id="profileMainNav"><a href="<?php echo base_url('users/profile/') ?>"><i class="fa fa-user-md"></i> <span>Profile</span></a></li>
          <?php endif; ?>
          <?php if(in_array('updateSetting', $user_permission)): ?>
            <li id="settingMainNav"><a href="<?php echo base_url('users/setting/') ?>"><i class="fa fa-wrench"></i> <span>Setting</span></a></li>
          <?php endif; ?>

        <?php endif; ?>
        

        <li><a href="<?php echo base_url('auth/logout') ?>"><i class="glyphicon glyphicon-log-out"></i> <span>Logout</span></a></li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>