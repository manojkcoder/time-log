<script setup>
    import UserLayout from "@/Layouts/UserLayout.vue";
    import {Head} from "@inertiajs/vue3";
</script>
<template>
    <Head title="Employee Summary"/>
    <UserLayout>
        <section class="py-5 px-3 time-log-section">
            <div class="container">
                <div class="inner-content inner-content-users bg-white p-4">
                    <div class="d-flex gap-3 header-bar align-items-center justify-content-between mb-4">
                        <h1 class="mb-0">Employee Summary</h1>
                        <div class="bar-form-wrapper gap-3 d-flex justify-content-between align-items-center">
                            <div class="field d-flex gap-2 align-items-center">
                                <label>Select Employee</label>
                                <select id="Employee_option" name="Employee_id" class="form-control">
                                    <option value="Employee_One">Employee One</option>
                                    <option value="Employee_Two">Employee Two</option>
                                    <option value="Employee_Three">Employee Three</option>
                                </select>
                            </div>
                            <div class="field d-flex gap-2 align-items-center">
                                <label>Select dates *</label>
                                <input type="date" class="form-control" name="select_date_from">
                                <input type="date" class="form-control" name="select_date_to">
                            </div>
                            <button class="btn btn-dark">Filter</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Total Spent time</th>
                                    <th class="action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-title="Project Name">Project 1</td>
                                    <td data-title="Total Spent time">8:45 hours</td>
                                    <td data-title="Action">
                                        <a class="btn btn-dark small-btn" title="view"><i class="fa fa-eye"></i> View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-title="Project Name">Project 2</td>
                                    <td data-title="Total Spent time">7:05 hours</td>
                                    <td data-title="Action">
                                        <a class="btn btn-dark small-btn" title="view"><i class="fa fa-eye"></i> View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-title="Project Name">Project 3</td>
                                    <td data-title="Total Spent time">9:45 hours</td>
                                    <td data-title="Action">
                                        <a class="btn btn-dark small-btn" title="view"><i class="fa fa-eye"></i> View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-title="Project Name">Project 4</td>
                                    <td data-title="Total Spent time">9:45 hours</td>
                                    <td data-title="Action">
                                        <a class="btn btn-dark small-btn" title="view"><i class="fa fa-eye"></i> View</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="co-sm-12 project-info mt-3 d-flex justify-content-between align-items-center gap-2 bg-light px-4 py-2 rounded">
                        <div class="info-row">showing <strong>4</strong> of <strong>4</strong> Records</div>
                        <div class="info-row">Total hours on current page: <strong>22:40</strong></div>
                        <div class="info-row">Total hours: <strong>22:40</strong></div>
                        <nav>
                            <ul class="pagination mb-0">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">«</span>
                                    </a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">»</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
    </UserLayout>
</template>