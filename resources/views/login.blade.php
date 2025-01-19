<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>AdminLTE | Dashboard v2</title><!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="AdminLTE | Dashboard v2">
    <meta name="author" content="ColorlibHQ">  
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS.">
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"><!--end::Primary Meta Tags--><!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"><!--end::Fonts--><!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous"><!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous"><!--end::Third Party Plugin(Bootstrap Icons)--><!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('backend/css/adminlte.css')}}"><!--end::Required Plugin(AdminLTE)--><!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body class="hold-transition login-page">
    <div class="login-box">
    <div class="login-logo">
        <a href="../../index2.html"><b>Admin</b>LTE</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="../../index3.html" method="post">
            <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                <span class="fas fa-envelope"></span>
                </div>
            </div>
            </div>
            <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text">
                <span class="fas fa-lock"></span>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                    Remember Me
                </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
            </div>
        </form>

        <div class="social-auth-links text-center mb-3">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-primary">
            <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
            </a>
            <a href="#" class="btn btn-block btn-danger">
            <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
            </a>
        </div>
        <!-- /.social-auth-links -->

        <p class="mb-1">
            <a href="forgot-password.html">I forgot my password</a>
        </p>
        <p class="mb-0">
            <a href="register.html" class="text-center">Register a new membership</a>
        </p>
        </div>
        <!-- /.login-card-body -->
    </div>
    </div>
    
    <!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script> <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script> <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script> <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('backend/js/adminlte.js')}}"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script> <!--end::OverlayScrollbars Configure--> <!-- OPTIONAL SCRIPTS --> <!-- apexcharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
    <script>
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

        /* apexcharts
         * -------
         * Here we will create a few charts using apexcharts
         */

        //-----------------------
        // - MONTHLY SALES CHART -
        //-----------------------

        const sales_chart_options = {
            series: [{
                    name: "Digital Goods",
                    data: [28, 48, 40, 19, 86, 27, 90],
                },
                {
                    name: "Electronics",
                    data: [65, 59, 80, 81, 56, 55, 40],
                },
            ],
            chart: {
                height: 180,
                type: "area",
                toolbar: {
                    show: false,
                },
            },
            legend: {
                show: false,
            },
            colors: ["#0d6efd", "#20c997"],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "smooth",
            },
            xaxis: {
                type: "datetime",
                categories: [
                    "2023-01-01",
                    "2023-02-01",
                    "2023-03-01",
                    "2023-04-01",
                    "2023-05-01",
                    "2023-06-01",
                    "2023-07-01",
                ],
            },
            tooltip: {
                x: {
                    format: "MMMM yyyy",
                },
            },
        };

        const sales_chart = new ApexCharts(
            document.querySelector("#sales-chart"),
            sales_chart_options,
        );
        sales_chart.render();

        //---------------------------
        // - END MONTHLY SALES CHART -
        //---------------------------

        function createSparklineChart(selector, data) {
            const options = {
                series: [{
                    data
                }],
                chart: {
                    type: "line",
                    width: 150,
                    height: 30,
                    sparkline: {
                        enabled: true,
                    },
                },
                colors: ["var(--bs-primary)"],
                stroke: {
                    width: 2,
                },
                tooltip: {
                    fixed: {
                        enabled: false,
                    },
                    x: {
                        show: false,
                    },
                    y: {
                        title: {
                            formatter: function(seriesName) {
                                return "";
                            },
                        },
                    },
                    marker: {
                        show: false,
                    },
                },
            };

            const chart = new ApexCharts(document.querySelector(selector), options);
            chart.render();
        }

        const table_sparkline_1_data = [
            25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54,
        ];
        const table_sparkline_2_data = [
            12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 44,
        ];
        const table_sparkline_3_data = [
            15, 46, 21, 59, 33, 15, 34, 42, 56, 19, 64,
        ];
        const table_sparkline_4_data = [
            30, 56, 31, 69, 43, 35, 24, 32, 46, 29, 64,
        ];
        const table_sparkline_5_data = [
            20, 76, 51, 79, 53, 35, 54, 22, 36, 49, 64,
        ];
        const table_sparkline_6_data = [
            5, 36, 11, 69, 23, 15, 14, 42, 26, 19, 44,
        ];
        const table_sparkline_7_data = [
            12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 74,
        ];

        createSparklineChart("#table-sparkline-1", table_sparkline_1_data);
        createSparklineChart("#table-sparkline-2", table_sparkline_2_data);
        createSparklineChart("#table-sparkline-3", table_sparkline_3_data);
        createSparklineChart("#table-sparkline-4", table_sparkline_4_data);
        createSparklineChart("#table-sparkline-5", table_sparkline_5_data);
        createSparklineChart("#table-sparkline-6", table_sparkline_6_data);
        createSparklineChart("#table-sparkline-7", table_sparkline_7_data);

        //-------------
        // - PIE CHART -
        //-------------

        const pie_chart_options = {
            series: [700, 500, 400, 600, 300, 100],
            chart: {
                type: "donut",
            },
            labels: ["Chrome", "Edge", "FireFox", "Safari", "Opera", "IE"],
            dataLabels: {
                enabled: false,
            },
            colors: [
                "#0d6efd",
                "#20c997",
                "#ffc107",
                "#d63384",
                "#6f42c1",
                "#adb5bd",
            ],
        };

        const pie_chart = new ApexCharts(
            document.querySelector("#pie-chart"),
            pie_chart_options,
        );
        pie_chart.render();

        //-----------------
        // - END PIE CHART -
        //-----------------
    </script> <!--end::Script-->
</body><!--end::Body-->

</html>