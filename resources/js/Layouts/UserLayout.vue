<template>
    <header class="bg-light py-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <a :href="route('dashboard')" class="logo">
                    <img src="/images/logo.png" alt="Logo" class="img-fluid">
                </a>
                <nav class="navbar navbar-expand-lg navbar-light">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item ">
                                <a class="nav-link" :class="isRoute('dashboard') ? 'active' : ''" :href="route('dashboard')">Add Time</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" :class="isRoute('time-logs') ? 'active' : ''" :href="route('time-logs')">Time Log</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" :class="isRoute('project-summary') ? 'active' : ''" :href="route('project-summary')">Project Summary</a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div>
                    <a :href="route('logout')" class="btn btn-dark">Logout</a>
                </div>
            </div>
        </div>
    </header>
    <slot/>
</template>
<style>
    .bg-light{background:#d6e9f3 !important;}
    body{background:rgb(247,248,249);}
    .inner-content-users{border-radius:20px;box-shadow:0px 0px 83px rgba(0,0,0,0.01);}
    .logo img{max-width:80px;}
    .table thead tr th{padding:14px;text-transform:uppercase;color:#39c;background:#3399cc2b;}
    .table thead tr th:first-child{border-radius:10px 0px 0px 0px;}
    .table thead tr th:last-child{border-radius:0px 10px 0px 0px;}
    .table tbody tr td{padding:14px 15px;font-size:17px;vertical-align:middle;}
    .btn.btn-dark{background:#2196f3;border-color:#2196f3;font-size:17px;}
    .btn.btn-dark:hover{background:#111;border-color:#111;}
    .nav-link{color:#222;padding:12px 20px;}
    .nav-link.active,
    .nav-link:hover{color:#2196f3 !important;}
    .btn.btn-danger.small-btn{background:#dc354538 !important;border:0px;color:#dc3545;}
    .btn.btn-danger.small-btn:hover{background:#dc3545 !important;color:#fff;}
    .btn.btn-dark.small-btn{background:#2196f34a;color:#2196f3;border:0px;}
    .btn.btn-dark.small-btn:hover{background:#2196f3;color:#fff;}
    .page-link.active{background:#e9ecef;border-color:#dee2e6;}
    .btn.small-btn{font-size:15px;padding:6px 10px;}
    .btn:not(.small-btn){border-radius:24px;font-weight:600;padding:5px 30px 10px !important;}
    .bar-form-wrapper .btn.btn-dark{padding:7px 25px !important;}
    .form-wrapper select,
    .form-wrapper input{background:#fff;border:1px solid #9999;border-radius:10px;padding:10px 20px;}
    .field label{width:max-content;min-width:100px;margin:0px;font-weight:600;}
    .form-wrapper{max-width:780px;}
    .table th:last-child{width:120px;text-align:center;}
    .form-row{border-radius:10px;}
    .form-row:not(.small-form-wrapper){max-width:800px;margin:auto;}
    .small-form-wrapper{max-width:500px;margin:auto;}
    .form-group select,
    .form-group input,
    .form-group textarea{padding:11px 12px;border:0px;outline:0px !important;box-shadow:unset !important;min-height:46px;line-height:1;font-size:16px;}
    .form-group label{display:block;margin-bottom:8px;font-weight:600;font-size:17px;line-height:normal;}
    form.row{row-gap:20px;}
    .navbar-nav{gap:30px;}
    .nav-link{font-weight:600;}
    select,
    input{box-shadow:none !important;}
    .h1,
    h1{font-size:1.5rem !important;}
    .bar-form-wrapper .field label{min-width:110px;font-size:14px;text-align:right !important;}
    .bar-form-wrapper input,
    .bar-form-wrapper select{font-size:14px;}
    .bar-form-wrapper input,
    .bar-form-wrapper select{font-size:14px;}
    .bar-form-wrapper .btn{line-height:normal;font-size:14px;box-shadow:unset !important;outline:unset !important;}
    .bar-form-wrapper{background:#f1f1f1;padding:10px;border-radius:10px;}
    .table th{border-top:0px !important;}
    .popup-wrapper{position:fixed;z-index:99;background:rgba(0,0,0,0.4);left:0;top:0;width:100vw;height:100vh;justify-content:center;align-items:center;display:none;}
    .popup-wrapper.active{display:flex;}
    .popup-wrapper .inner-content{position:relative;}
    .popup-wrapper .close{position:absolute;line-height:0;font-size:34px;color:#2196f3 !important;right:5px;top:5px;cursor:pointer;z-index:9;opacity:1;}
    
    @media(min-width:768px){
        td[data-title="Action"]{text-align:right;}
    }
    @media(max-width:1299px){
        .time-log-section .container{max-width:100%;padding:0px;}
    }
    @media(max-width:991px){
        .header-bar{flex-wrap:wrap;}
        .bar-form-wrapper input,
        .bar-form-wrapper select{font-size:13px;padding:5px;}
        .container,
        .container-md,
        .container-sm{max-width:calc(100% - 30px);}
        .navbar.navbar-expand-lg.navbar-light{order:3;}
        #navbarNav{position:absolute;min-width:250px;background:#d6e9f3;min-height:calc(100vh - 92px);z-index:99;right:-27px;top:calc(100% + 23px);}
        .navbar-nav .nav-link{border-bottom:1px solid #ffff;}
        .navbar-nav{gap:0;}
        .navbar-toggler{box-shadow:unset !important;}
        .logo{flex:1;}
        .logo img{max-width:70px;}
        .inner-content{padding:50px 20px !important;}
        .table thead tr th{padding:10px;font-size:12px;}
        .table tbody tr td{padding:10px 5px;font-size:14px;}
        .header{padding:10px 20px !important;}
        .time-log-section .inner-content,
        .time-log-section .container{padding:0px !important;}
        .project-info{background:transparent !important;padding:0px !important;flex-wrap:wrap;}
        .project-info .info-row{padding:4px 6px 6px;border-radius:5px;flex:1;min-width:32.33%;background:#d6e9f3;font-size:13px;text-align:center;}
        .project-info{background:transparent !important;padding:0px !important;flex-wrap:wrap;justify-content:center !important;}
        .project-info .pagination{margin-top:20px;}
    }
    @media(max-width:767px){
        .table-header{flex-wrap:wrap;}
        .bar-form-wrapper .field{width:100%;}
        .bar-form-wrapper .field label{text-align:left;}
        .table-header .bar-form-wrapper{width:100%;padding:10px;background:#fafafa;flex-wrap:wrap;}
        .table{display:block !important;}
        #navbarNav{top:calc(100% + 18px);}
        .logo img{max-width:60px;}
        .table thead{display:none !important;}
        .table tbody{width:100%;display:block;}
        .table tbody tr{display:flex;flex-direction:column;border:1px solid #9999991c;padding:10px;border-radius:20px;margin-bottom:10px;}
        .table tbody tr td::before{content:attr(data-title);color:#000;margin-right:10px;font-weight:700;min-width:60px;display:inline-block;}
        .btn.small-btn{font-size:12px !important;padding:4px 8px;}
        .logo-wrapper{margin-bottom:0px !important;}
        .table tbody tr td{padding:8px 5px;font-size:15px;border:0px;}
        h1{font-size:30px;margin:0px;}
        .btn:not(.small-btn){padding:4px 16px 6px;}
        .btn.btn-dark{background:#2196f3;border-color:#2196f3;font-size:16px;}
        .content{padding-left:0;}
        .form-row{padding:20px !important;}
        .form-group select,
        .form-group input,
        .form-group textarea{padding:8px 9px 10px;}
        .form-group select{background-position:center right 8px;background-size:10px;}
        .bar-form-wrapper{flex-wrap:wrap;}
    }
    @media(max-width:575px){
        .btn.btn-dark{font-size:14px;}
        .inner-content>.p-4{padding:10px !important;}
        .col-sm-6.sm-mt-4{margin-top:20px;}
    }
    @media(max-width:480px){
        .bar-form-wrapper .field{flex-wrap:wrap;}
        .bar-form-wrapper .field label{width:100%;}
        .bar-form-wrapper .form-control[type="date"]{width:calc(100% / 2 - 4px);}
        .project-info{flex-wrap:wrap !important;}
        .project-info .info-row{padding:4px 15px 6px;font-size:13px;width:100%;flex:unset;text-align:left;}
    }
</style>