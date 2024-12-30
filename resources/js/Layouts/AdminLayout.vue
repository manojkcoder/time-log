<template>
    <div>
        <div class="sidebar">
            <div class="d-flex justify-content-between mb-4 logo-wrapper align-items-center">
                <div class="logo ">
                    <a :href="route('admin.dashboard')">
                        <img src="/images/logo.png">
                    </a>
                </div>
                <div class="toggle-menu hide">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" :class="isRoute('admin.dashboard') ? 'active' : ''" :href="route('admin.dashboard')"><i class="demo-icon icon-dashboard"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="isRoute('admin.users') ? 'active' : ''" :href="route('admin.users')"><i class="demo-icon icon-clients"></i> Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="isRoute('admin.clients') ? 'active' : ''" :href="route('admin.clients')"><i class="demo-icon icon-clients"></i> Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="isRoute('admin.activities') ? 'active' : ''" :href="route('admin.activities')"><i class="demo-icon icon-clients"></i> Activities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="isRoute('admin.projects') ? 'active' : ''" :href="route('admin.projects')"><i class="demo-icon icon-project"></i> Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="isRoute('admin.project-logs') ? 'active' : ''" :href="route('admin.project-logs')"><i class="demo-icon icon-project-logging"></i> Project Loggings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="(isRoute('admin.project-summary') || isRoute('admin.project-summary.view')) ? 'active' : ''" :href="route('admin.project-summary')"><i class="demo-icon icon-project-summary"></i> Project Summary</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="isRoute('admin.employee-summary') ? 'active' : ''" :href="route('admin.employee-summary')"><i class="demo-icon icon-employee-summary"></i> Employee Summary</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="isRoute('admin.change-password') ? 'active' : ''" :href="route('admin.change-password')"> <i class="demo-icon icon-password"></i> Change Password</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="content dashboard-content">
            <div class="header d-flex justify-content-between align-items-center border-bottom border-1 py-3 px-5">
                <div class="d-flex  header-wrapper w-100 align-items-center justify-content-between gap-3">
                    <p class="mb-0 header-left">Project System | {{ $page.props.auth.user.name }}</p>
                    <div class="d-flex align-items-center gap-3 header-right">
                        <p class="mb-0">Last Login: {{ $page.props.auth.user.last_login }}</p>
                        <a :href="route('logout')" class="btn btn-dark  btn-sm">Logout</a>
                    </div>
                </div>
            </div>
            <div class="inner-content p-5 bg-light">
                <slot/>
            </div>
        </div>
    </div>
</template>
<style>
    .sidebar{position:fixed;top:0;left:0;width:250px;height:100vh;background:#d6e9f3;box-shadow:0px 0px 2px rgba(0,0,0,0.1);}
    .content{padding-left:250px;}
    .header{z-index:99;position:relative;color:#333;}
    .logo-wrapper{padding:20px;}
    .logo img{max-width:110px;}
    .table-responsive table thead tr:first-child th{padding:14px 15px !important;text-transform:uppercase;color:#39c;background:#3399cc2b;height:50px !important;font-size:16px;line-height:1;}
    .table-responsive table thead tr:first-child th:first-child{border-radius:10px 0px 0px 0px;}
    .table-responsive table thead tr:first-child th:last-child{border-radius:0px 10px 0px 0px;text-align:center;}
    .table-responsive table th:last-child{width:120px;}
    .table-responsive table tbody tr td{padding:14px 15px !important;font-size:16px;vertical-align:middle;}
    .btn.btn-dark{background:#2196f3;border-color:#2196f3;font-size:17px;color:#fff;}
    .btn.btn-dark:hover{background:#111;border-color:#111;}
    .btn:not(.small-btn){border-radius:24px;font-weight:600;padding:6px 20px;}
    .switch{position:relative;display:inline-block;width:60px;height:28px;}
    .switch input{opacity:0;width:0;height:0;}
    .slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;-webkit-transition:.4s;transition:.4s;}
    .slider::before{position:absolute;content:"";height:24px;width:24px;left:7px;bottom:2.5px;background-color:white;-webkit-transition:.4s;transition:.4s;}
    input:checked+.slider{background-color:#2196F3;}
    input:focus+.slider{box-shadow:0 0 1px #2196F3;}
    input:checked+.slider:before{-webkit-transform:translateX(26px);-ms-transform:translateX(26px);transform:translateX(26px);}
    .slider.round{border-radius:34px;}
    .slider.round:before{border-radius:50%;}
    .nav-link{color:#222;padding:12px 20px;}
    .nav-item{border-bottom:1px solid #9999993d;}
    .nav-link.active,
    .nav-link:hover{color:#2196f3;}
    .bg-white.p-4.rounded-30px{border-radius:20px;box-shadow:0px 0px 83px rgba(0,0,0,0.01);}
    .btn.btn-danger.small-btn{background:#dc354538 !important;border:0px;color:#dc3545;}
    .btn.btn-danger.small-btn:hover{background:#dc3545 !important;color:#fff;}
    .btn.btn-dark.small-btn{background:#2196f34a;color:#2196f3;border:0px;}
    .btn.btn-dark.small-btn:hover{background:#2196f3;color:#fff;}
    .btn.small-btn{font-size:15px;padding:6px 10px;}
    .toggle-menu.hide{display:none;}
    .form-wrapper select,
    .form-wrapper input{background:#fff;border:1px solid #9999;border-radius:10px;padding:14px 10px !important;display:block;font-size:16px;min-height:48px;}
    .content.form-content{padding:0px;justify-content:center;}
    .field label{width:max-content;min-width:110px;margin:0px;font-weight:600;}
    .form-wrapper{margin:auto;}
    .form-wrapper:not(.small-form-wrapper){max-width:780px;}
    .small-form-wrapper{max-width:500px;}
    .password-row{max-width:440px;margin:auto;}
    .btn-dark[type="submit"]{padding:9px 24px 12px !important;width:max-content;line-height:normal;min-width:150px;}
    .password-row input:not([type="submit"]){min-height:45px;box-shadow:unset !important;}
    select,
    input,
    textarea{box-shadow:unset !important;}
    .table thead tr th{border:0px;}
    .bar-form-wrapper{background:#f1f1f1;padding:8px 10px;border-radius:10px;flex-wrap:wrap;}
    .bar-form-wrapper input{width:auto;}
    .bar-form-wrapper .field label{font-size:14px;text-align:right;}
    .popup-wrapper{position:fixed;z-index:99;background:rgba(0,0,0,0.4);left:0;top:0;width:100vw;height:100vh;justify-content:center;align-items:center;display:none;}
    .popup-wrapper.active{display:flex;}
    .popup-wrapper .inner-content{position:relative;}
    .popup-wrapper .close{position:absolute;line-height:0;font-size:34px;color:#2196f3 !important;right:5px;top:5px;cursor:pointer;z-index:9;opacity:1;}

    @media(max-width:1600px){
        .h1,
        h1{font-size:2.1rem !important;}
    }
    @media(max-width:1500px){
        .header-bar{flex-wrap:wrap;}
    }
    @media(min-width:1300px){
        .h1,
        h1{font-size:1.8rem !important;}
        .project-info.bg-light{background:#d6e9f3 !important;}
    }
    @media(min-width:768px){
        td[data-title="Action"]{text-align:center;}
    }
    @media(max-width:1299px){
        .project-info.bg-light{background:transparent !important;flex-wrap:wrap;padding:0px !important;justify-content:center !important;}
        .bar-form-wrapper .field label{min-width:90px;font-size:13px;}
        .bar-form-wrapper .field input,
        .bar-form-wrapper .field select{font-size:13px;padding:5px;}
        .btn.btn-dark{padding:5px 15px 6px;font-size:14px;line-height:normal;outline:0px !important;}
        .inner-content{padding:25px !important;}
        .header{padding-left:25px !important;padding-right:25px !important;}
        .project-info .info-row{background:#d6e9f3;font-size:13px;flex:1;text-align:center;padding:8px;border-radius:7px;min-width:32%;}
        .project-info .pagination{margin-top:20px;}
    }
    @media(max-width:1024px){
        .logging-form-bar{flex-wrap:wrap;flex-direction:column;gap:20px;align-items:flex-start !important;}
        .bar-form-wrapper.logging-form-bar input{width:100%;}
        .logging-form-bar > .align-items-center:first-child{align-items:flex-end !important;}
        .logging-form-bar > .align-items-center:first-child .gap-3{gap:4px;}
        .h1,
        h1{font-size:1.4rem !important;}
        .bar-form-wrapper .field label{text-align:left;}
        .bar-form-wrapper .field{width:100%;}
        .logo img{max-width:90px;}
        .header-bar{flex-wrap:wrap;}
        .h1,
        h1{font-size:2rem !important;}
        .bar-form-wrapper .field{flex-wrap:wrap;}
        .bar-form-wrapper input{width:calc(100% / 2 - 4px);}
        .bar-form-wrapper label{width:100%;}
        .sidebar{width:210px;}
        .project-info .info-row{padding:8px 12px;flex:unset;width:calc(100% / 2 - 4px);text-align:left;}
        .project-info.bg-light{justify-content:flex-start !important;}
        .project-info nav{width:100%;}
        .content{padding-left:210px;}
        .inner-content{padding:50px 20px !important;}
        .table thead tr th{padding:10px;font-size:12px;}
        .table tbody tr td{padding:10px 5px;font-size:14px;}
        .header{padding:10px 20px !important;}
        .project-info{flex-wrap:wrap;padding:0px !important;}
    }
    @media(max-width:767px){
        .table{display:block !important;}
        .logo img{max-width:60px;}
        .table thead{display:none !important;}
        .table tbody{width:100%;display:block;}
        .table tbody tr{display:flex;flex-direction:column;border:1px solid #9999991c;padding:10px;border-radius:20px;margin-bottom:10px;}
        .table tbody tr td::before{content:attr(data-title);color:#000;margin-right:10px;font-weight:700;min-width:60px;display:inline-block;}
        .switch{width:50px;height:24px;}
        .slider::before{height:19px;width:19px;left:2px;bottom:3px;}
        .btn.small-btn{font-size:12px !important;padding:4px 8px;}
        .logo-wrapper{margin-bottom:0px !important;}
        .sidebar-wrapper{max-width:220px;margin-left:auto;position:fixed;right:0;background:#d6e9f3;min-height:calc(100vh - 80px);transform:translateX(100%);transition:0.3s all;}
        .sidebar.active .sidebar-wrapper{transform:translateX(0);}
        .table tbody tr td{padding:8px 5px;font-size:15px;border:0px;}
        .logo-wrapper{padding:10px 20px;}
        h1{font-size:30px;margin:0px;}
        .btn:not(.small-btn){padding:4px 16px 6px;}
        .btn.btn-dark{background:#2196f3;border-color:#2196f3;font-size:16px;}
        .sidebar{z-index:999;box-shadow:unset !important;position:relative;width:100%;height:unset;}
        .toggle-menu.hide{display:flex;background:#39c;width:40px;height:40px;justify-content:center;align-items:center;color:#fff;cursor:pointer;}
        .content{padding-left:0;}
        .header-wrapper{flex-wrap:wrap-reverse;}
        .header-right{width:100%;justify-content:space-between;}
        .bottom-bar{flex-wrap:wrap;gap:10px;justify-content:center !important;margin-bottom:10px;}
    }
    @media(max-width:480px){
        .btn.btn-dark{font-size:14px;}
        .project-info .info-row{width:100%;}
        .inner-content>.p-4{padding:10px !important;}
    }
</style>