<script>
$(document).ready(function() {
    $('.sidebar .nav-link').click(function() {
        if ($(this).parent().find('.nav-treeview').length > 0) {
            return;
        }
        if($('body').hasClass('sidebar-open')) {
            $('#sidebar-overlay').trigger('click')
        }
    })
});
</script>
<template>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link text-center">
        <span class="brand-text font-weight-light text-bold font-weight-bolder">XM - Exercise</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <router-link to="/admin/home" class="nav-link">
                        <i class="fa fa-home nav-icon"></i>
                        <p>Home</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/admin/stock/list" class="nav-link">
                        <i class="fa fa-file-alt nav-icon"></i>
                        <p>Available Stock</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/admin/stock/transaction" class="nav-link">
                        <i class="fa fa-wallet nav-icon"></i>
                        <p>Transactions</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/admin/stock/in" class="nav-link">
                        <i class="fa fa-sign-in-alt nav-icon"></i>
                        <p>Stock-In</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/admin/stock/out" class="nav-link">
                        <i class="fas fa-truck  nav-icon"></i>
                        <p>Stock-Out</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/admin/customer/list" class="nav-link">
                        <i class="fas fa-users  nav-icon"></i>
                        <p>Customers</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/admin/payment/list" class="nav-link">
                        &nbsp;&nbsp;<i class="fas fa-rupee-sign"></i> &nbsp;&nbsp;
                        <p>Payments</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fa fa-list nav-icon"></i>
                        <p>
                            Reports
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/admin/stock/report/summary" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stock Summary</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/admin/stock/report/transaction-detail" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Transaction detail</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/admin/stock/report/product-detail" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Product Detail</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
    </aside>
</template>
<style scoped>
ul.nav.nav-treeview .nav-item {
    padding-left: 15px;
}
</style>
