jQuery.fn.exists = function () {
    return this.length > 0;
}

function tolakPesanan(idPesanan) {
    Swal.fire({
        title: "Apakah anda yakin?",
        text: "Anda akan menolak pesanan obat dari pasien ini, dan pasien harus datang ke RS",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Iya, tolak pesanan",
        cancelButtonText: "Tidak, batalkan",
        reverseButtons: true
    }).then(function (result) {
        if (result.value) {
            Swal.fire({
                title: 'Tulis alasan anda menolak pesanan',
                html: "<input type='text' class='form-control mb-7' name='alasan' id='alasan_penolakan'>",
                showCancelButton: true,
                confirmButtonText: 'Tolak Pesanan',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const reason = Swal.getPopup().querySelector('#alasan_penolakan').value;

                    if (reason.trim().length == 0) {
                        Swal.showValidationMessage('Alasan penolakan harus diisi')
                    }

                    return {
                        reason: reason
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    return fetch(HOST_URL + 'order/reject', {
                            method: 'post',
                            body: JSON.stringify({
                                dismiss_reason: result.value.reason,
                                id_pesanan: idPesanan
                            }),
                            headers: {
                                'Content-type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.status) {
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        })
                        .then(data => {
                            Swal.fire({
                                title: "Pesanan Ditolak",
                                text: "Pesanan telah ditolak dengan alasan '" + result.value.reason + "'",
                                icon: 'success'
                            });
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                'Request failed: ${error}'
                            )
                        })
                }
            })
        } else if (result.dismiss === "cancel") {
            Swal.fire(
                "Cancelled",
                "Your imaginary file is safe :)",
                "error"
            )
        }
    });
};

$(document).ready(function () {
    // order_list & order_new
    if ($('#table_order_new').exists()) {
        var table = $('#table_order_new');

        // begin first table
        table.DataTable({
            responsive: true,
            ajax: {
                url: HOST_URL + 'order/list_detail_order/new',
                type: 'POST',
                data: {
                    pagination: {
                        perpage: 50,
                    },
                    status: 1
                },
                dataSrc: "results"
            },
            columns: [{
                    data: 'no'
                },
                {
                    data: 'no_pesanan'
                },
                {
                    data: 'tanggal_pesanan'
                },
                {
                    data: 'patient_data'
                },
                {
                    data: 'nama_pasien'
                },
                {
                    data: 'diagnose'
                },
                {
                    data: 'total_order_after_last_visit'
                },
                {
                    data: 'status'
                },
                {
                    data: 'id_pesanan',
                    responsivePriority: -1
                },
            ],
            columnDefs: [{
                targets: 3,
                orderable: true,
                render: function (data, type, full, meta) {
                    return '<span class="font-weight-bold">No.BPJS: </span>' + data.no_bpjs + '<br><span class="font-weight-bold">No.Rekam Medis: </span>' + data.no_medrek
                }
            }, {
                targets: 5,
                orderable: true,
                render: function (data, type, full, meta) {
                    return '<a href="https://icd.who.int/browse10/2019/en#/' + data.icd_code + '" target="_blank" class="text-primary font-weight-bold font-size-sm">' + data.icd_code + ' - ' + data.icd_description + '</a>';
                }
            }, {
                targets: -2,
                title: 'Status Pengajuan',
                orderable: true,
                render: function (data, type, full, meta) {
                    var status = {
                        '1': {
                            'class': ' btn-light-warning'
                        },
                        '0': {
                            'class': ' btn-light-danger'
                        },
                        '2': {
                            'class': ' btn-light-primary'
                        },
                    };

                    if (typeof status[data.code] === 'undefined') {
                        data = 2;
                    }

                    return '<span class="btn btn-sm btn-pill font-weight-bold font-size-sm' + status[data.code].class + '">' + data.label + '</span>';
                }
            }, {
                targets: -1,
                title: 'Actions',
                orderable: false,
                render: function (data, type, full, meta) {

                    var pesanan_id = data;

                    return '\
                        <div class="dropdown dropdown-inline">\
                            <a href="javascript:;" class="btn btn-sm btn-info" data-toggle="dropdown">\
                                <i class="flaticon-more-1 icon-sm"></i> Action\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
                                <ul class="navi flex-column navi-hover py-2">\
                                    <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
                                        Choose an action:\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="'+ HOST_URL + 'order/proses/?order_id=' + pesanan_id + '" class="navi-link">\
                                            <span class="navi-icon"><i class="la la-check-circle"></i></span>\
                                            <span class="navi-text">Proses</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="javascript:void(0)" onClick="tolakPesanan(' + pesanan_id + '); return false;" class="navi-link">\
                                            <span class="navi-icon font-weight-bold"><i class="la la-close"></i></span>\
                                            <span class="navi-text text-danger">Tolak</span>\
                                        </a>\
                                    </li>\
                                </ul>\
                            </div>\
                        </div>\
                    ';
                },
            }],
        });
    }
    if ($('#table_order_').exists()) {
        var table = $('#table_order');

        // begin first table
        table.DataTable({
            responsive: true,
            ajax: {
                url: HOST_URL + 'order/list_detail_order/',
                type: 'POST',
                data: {
                    pagination: {
                        perpage: 50,
                    },
                },
                dataSrc: "results"
            },
            columns: [{
                    data: 'no'
                },
                {
                    data: 'no_pesanan'
                },
                {
                    data: 'tanggal_pesanan'
                },
                {
                    data: 'patient_data'
                },
                {
                    data: 'nama_pasien'
                },
                {
                    data: 'diagnose'
                },
                {
                    data: 'total_order_after_last_visit'
                },
                {
                    data: 'status'
                },
                {
                    data: 'id_pesanan',
                    responsivePriority: -1
                },
            ],
            columnDefs: [{
                targets: 3,
                orderable: true,
                render: function (data, type, full, meta) {
                    return '<span class="font-weight-bold">No.BPJS: </span>' + data.no_bpjs + '<br><span class="font-weight-bold">No.Rekam Medis: </span>' + data.no_medrek
                }
            }, {
                targets: 5,
                orderable: true,
                render: function (data, type, full, meta) {
                    return '<a href="https://icd.who.int/browse10/2019/en#/' + data.icd_code + '" target="_blank" class="text-primary font-weight-bold font-size-sm">' + data.icd_code + ' - ' + data.icd_description + '</a>';
                }
            }, {
                targets: -2,
                title: 'Status Pengajuan',
                orderable: true,
                render: function (data, type, full, meta) {
                    var status = {
                        '1': {
                            'class': ' btn-light-warning'
                        },
                        '0': {
                            'class': ' btn-light-danger'
                        },
                        '2': {
                            'class': ' btn-light-primary'
                        },
                    };

                    if (typeof status[data.code] === 'undefined') {
                        data = 2;
                    }

                    return '<span class="btn btn-sm btn-pill font-weight-bold font-size-sm' + status[data.code].class + '">' + data.label + '</span>';
                }
            }/*, {
                targets: -1,
                title: 'Actions',
                orderable: false,
                render: function (data, type, full, meta) {

                    var pesanan_id = data;

                    return '\
                        <div class="dropdown dropdown-inline">\
                            <a href="javascript:;" class="btn btn-sm btn-info" data-toggle="dropdown">\
                                <i class="flaticon-more-1 icon-sm"></i> Action\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
                                <ul class="navi flex-column navi-hover py-2">\
                                    <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
                                        Choose an action:\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="order_detail.html?orders_id=' + pesanan_id + '" class="navi-link">\
                                            <span class="navi-icon"><i class="la la-check-circle"></i></span>\
                                            <span class="navi-text">Proses</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="javascript:void(0)" onClick="tolakPesanan(' + pesanan_id + '); return false;" class="navi-link">\
                                            <span class="navi-icon font-weight-bold"><i class="la la-close"></i></span>\
                                            <span class="navi-text text-danger">Tolak</span>\
                                        </a>\
                                    </li>\
                                </ul>\
                            </div>\
                        </div>\
                    ';
                },
            }*/],
        });
    }

    if ($("#user_statistics_chart").exists()) {
        // Shared Colors Definition
        const primary = '#6993FF';
        const success = '#1BC5BD';
        const info = '#8950FC';
        const warning = '#FFA800';
        const danger = '#F64E60';

        const apexChart = "#user_statistics_chart";
        var options = {
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                height: 50,
                width: 200
            },
            noData: {
                text: 'Loading...'
            },
            labels: ['Diterima', 'Ditolak', 'Belum Dikonfirmasi'],
            series: [],
            dataLabels: {
                enabled: true,
                textAnchor: 'middle',
                formatter: function (value, {
                    seriesIndex,
                    dataPointIndex,
                    w
                }) {
                    return w.config.series[seriesIndex]
                }
            },
            chart: {
                width: 200,
                height: 275,
                type: 'donut',
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                        labels: {
                            show: true,
                            name: {
                                show: false,
                            },
                            value: {
                                show: true,
                                formatter: function (val) {
                                    return val
                                }
                            }
                        }
                    }
                }

            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            colors: [success, danger, '#AAA']
        };

        var chart;

        $.getJSON(HOST_URL + 'assets/api/pesanan/fetch_list_pesanan.json', function (response) {
            var result = response.results;
            var data = [],
                disetujui = 0,
                ditolak = 0,
                belumDikonfirmasi = 0;

            $.each(result, function (index, item) {
                console.log(item);
                if (item.status.code == 1) belumDikonfirmasi++;
                else if (item.status.code > 1) disetujui++;
                else if (item.status.code == 0) ditolak++;

                data = [disetujui, ditolak, belumDikonfirmasi ];

                console.log(data);
            })
            options.series = data;
            chart = new ApexCharts(document.querySelector(apexChart), options);
            chart.render();
        });
    }

    if ($(".select2").exists()) {
        $(".select2").select2({
            placeholder: "Pilih Obat"
        });

    }

    if ($('#kt_repeater_1').exists()) {
        $('#kt_repeater_1').repeater({
            initEmpty: false,
            isFirstItemUndeletable: false,

            show: function () {
                $(this).slideDown(400, function () {
                    $('.select2-container').remove();
                    $('.select2').select2({
                        placeholder: "Pilih Obat",
                        allowClear: true
                    });
                    $('.select2-container').css('width', '100%');
                });

            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    }

    if ($('.btn-fetch-resep').exists()) {
        $('.btn-fetch-resep').on('click', function (e) {
            e.preventDefault();

            var scrollTo = $(this).attr('href');
            var type = $(this).data('category');
            var kode = $(this).data('kode');

            fetch(HOST_URL + 'assets/api/resep/fetch_resep.json')
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    var resep = $('<ul/>', {
                        "class": "list-unstyled my-3"
                    });
                    $.each(data.results.detail, function (i, item) {
                        var obat = $('<li/>').html('<span class="font-size-sm">' + item.nama_obat + ' - ' + item.qty + ' ' + item.unit + ' - ' + item.frekuensi_minum + ' x ' + item.dosis + '</span>');
                        resep.append(obat);
                    });
                    Swal.fire({
                            title: "Anda yakin?",
                            html: "Gunakan resep <span class='font-weight-bold'>#" + kode + "</span> untuk pengajuan ini? <br>" + resep.prop('outerHTML') + '<br><span class="font-size-sm font-weight-bold text-primary"><u>Anda masih bisa mengubah resep ini pada form resep dibawah</u></span>',
                            icon: "question",
                            showCancelButton: true
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                fetch(HOST_URL + 'order/api/generate_resep_form')
                                    .then((response) => response.text())
                                    .then((data) => {
                                        $("#resep-form-group").html(data);

                                        $('.select2-container').remove();
                                        $('.select2').select2({
                                            placeholder: "Pilih Obat",
                                            allowClear: true
                                        });
                                        $('.select2-container').css('width', '100%');

                                        $('html, body').animate({
                                                scrollTop: $($(this).attr('href')).offset().top,
                                            },
                                            500,
                                            'linear'
                                        )
                                    })
                            }
                        });
                })
                .catch((error) => {
                    console.log(error)
                });


        })
    }

    if($("#datepicker-inline").exists()){
        var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }
        $('#datepicker-inline').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            templates: arrows,
            daysOfWeekDisabled: [0,2,4,6]
        });
    }
})