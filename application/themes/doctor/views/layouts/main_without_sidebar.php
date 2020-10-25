<!DOCTYPE html>
<html lang="en">

<!--begin::Head-->

<head>
    <base href="">
    <meta charset="utf-8" />
    <title>Koncibumi Web Apps - <?php echo $template['title'];?></title>
    <meta name="description" content="Updates and statistics" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="https://keenthemes.com/metronic" />

    <!--begin::Fonts-->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/_fonts.css');?>" />

    <!--end::Fonts-->

    <!--end::Page Vendors Styles-->

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="<?php echo base_url('assets/plugins/global/plugins.bundle.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/plugins/custom/prismjs/prismjs.bundle.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/style.bundle.css');?>" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles-->

    <!--begin::Layout Themes(used by all pages)-->

    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="<?php echo base_url('assets/media/logos/favicon.ico');?>" />
</head>

<!--end::Head-->

<!--begin::Body-->

<body id="kt_body"
    class="page-loading-enabled page-loading header-fixed header-mobile-fixed page-loading">

    <!--begin::Page loader-->
    <?php echo $template['partials']['page-loader'];?>
    <!--end::Page Loader-->

    <!--begin::Main-->

    <!--begin::Header Mobile-->
    <?php echo $template['partials']['header-mobile'];?>
    <!--end::Header Mobile-->

    <div class="d-flex flex-column flex-root">

        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">

            <!--begin::Aside-->
            <?php echo $template['partials']['aside'];?>
            <!--end::Aside-->

            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

                <!--begin::Header-->
                <?php echo $template['partials']['header'];?>
                <!--end::Header-->

                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

                    <!--begin::Subheader-->
                    <?php echo $template['partials']['subheader-v1'];?>
                    <!--end::Subheader-->

                    <!--begin::Entry-->
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container-fluid mr-0 ml-0">
                            <?php echo $template['body'];?>
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Entry-->

                </div>

                <!--end::Content-->

                <<?php echo $template['partials']['footer'];?>
            </div>

            <!--end::Wrapper-->

        </div>

        <!--end::Page-->
    </div>

    <!--end::Main-->
    
    <?php echo $template['partials']['quick-user'];?>
    
    <?php echo $template['partials']['quick-panel'];?>

    <?php echo $template['partials']['scrolltop'];?>

    <script>
        var HOST_URL = "<?php echo site_url();?>";
    </script>

    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1200
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#8950FC",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#F3F6F9",
                        "dark": "#212121"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1E9FF",
                        "secondary": "#ECF0F3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#212121",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#ECF0F3",
                    "gray-300": "#E5EAEE",
                    "gray-400": "#D6D6E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#80808F",
                    "gray-700": "#464E5F",
                    "gray-800": "#1B283F",
                    "gray-900": "#212121"
                }
            },
            "font-family": "Poppins"
        };
    </script>

    <!--end::Global Config-->

    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="<?php echo base_url('assets/plugins/global/plugins.bundle.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/custom/prismjs/prismjs.bundle.js');?>"></script>
    <script src="<?php echo base_url('assets/js/scripts.bundle.js');?>"></script>

    <!--end::Global Theme Bundle-->

    <!--begin::Page Vendors(used by this page)-->
    <script src="<?php echo base_url('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js');?>"></script>

    <!--end::Page Vendors-->

    <!--begin::Page Scripts(used by this page)-->
    <script src="<?php echo base_url('assets/js/pages/widgets.js');?>"></script>
    <!--begin::Page Scripts(used by this page)-->
    <script src="<?php echo base_url('assets/js/pages/features/charts/apexcharts.js');?>"></script>
    <script src="<?php echo base_url('assets/js/pages/crud/forms/widgets/bootstrap-daterangepicker.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/custom/datatables/datatables.bundle.js');?>"></script>
    <!--end::Page Scripts-->
    <script src="<?php echo base_url('assets/js/custom.js');?>"></script>
    <!--end::Page Scripts-->
</body>

<!--end::Body-->

</html>