"use strict";
var KTDatatablesDataSourceHtml = function() {

	var initTable1 = function() {
		var table = $('#table_order_new');

		// begin first table
		table.DataTable({
			responsive: true,
			columnDefs: [
				{
					targets: -1,
					title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {
						// console.log(data);
						// console.log("====");
						// console.log(type);
						// console.log("====");
						// console.log(full);
						// console.log("====");
						// console.log(meta);

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
	                                        <a href="order_detail.html?order_id='+pesanan_id+'" class="navi-link">\
	                                            <span class="navi-icon"><i class="la la-check-circle"></i></span>\
	                                            <span class="navi-text">Proses</span>\
	                                        </a>\
	                                    </li>\
	                                    <li class="navi-item">\
	                                        <a href="javascript:void(0)" onClick="tolakPesanan('+pesanan_id+'); return false;" class="navi-link">\
	                                            <span class="navi-icon font-weight-bold"><i class="la la-close"></i></span>\
	                                            <span class="navi-text text-danger">Tolak</span>\
	                                        </a>\
	                                    </li>\
	                                </ul>\
							  	</div>\
							</div>\
						';
					},
				},
				{
					targets: -2,
					title: 'Status Pengajuan',
					orderable: true,
					render: function(data,type,full,meta) {
						var status = {
							'1': {'title': 'Menunggu Konfirmasi', 'class': ' label-light-warning'},
							'0': {'title': 'Ditolak', 'class': ' label-light-danger'},
							'2': {'title': 'Disetujui', 'class': ' label-light-primary'},
						};
						
						if (typeof status[data] === 'undefined') {
							data = 2;
						}

						return '<span class="label label-inline font-weight-bold font-size-xs' + status[data].class + ' btn-pill btn-sm">' + status[data].title + '</span>';
					}
				}
			],
		});

	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
		},

	};

}();

jQuery(document).ready(function() {
	KTDatatablesDataSourceHtml.init();
});

function tolakPesanan(no_pesanan){
	Swal.fire({
		title: "Apakah anda yakin?",
		text: "Anda akan menolak pesanan obat dari pasien ini, dan pasien harus datang ke RS",
		icon: "warning",
		showCancelButton: true,
		confirmButtonText: "Iya, tolak pesanan",
		cancelButtonText: "Tidak, batalkan",
		reverseButtons: true
	}).then(function(result) {
		if (result.value) {
			Swal.fire({
				title: 'Tulis alasan anda menolak pesanan',
				input: 'text',
				inputAttributes: {
				autocapitalize: 'off'
				},
				showCancelButton: true,
				confirmButtonText: 'Tolak Pesanan',
				showLoaderOnConfirm: true,
				preConfirm: (reason) => {
					Swal.fire({
						title: "Pesanan Ditolak",
						text: "Pesanan telah ditolak dengan alasan '"+ reason +"'",
						icon: 'success'
					})
					// $.ajax({
					// 	url: 'host',
					// 	type: 'post',
					// 	dataType: 'json',
					// 	data: {
					// 		dismiss_reason: reason
					// 	}
					// }).done(function(resp){

					// }).error(function(){

					// }).fail(function(){

					// });
				//   return fetch(`//api.github.com/users/${login}`)
				// 	.then(response => {
				// 	  if (!response.ok) {
				// 		throw new Error(response.statusText)
				// 	  }
				// 	  return response.json()
				// 	})
				// 	.catch(error => {
				// 	  Swal.showValidationMessage(
				// 		`Request failed: ${error}`
				// 	  )
				// 	})
				},
				allowOutsideClick: () => !Swal.isLoading()
			}).then((result) => {
				if (result.isConfirmed) {
				Swal.fire({
					title: `${result.value.login}'s avatar`,
					imageUrl: result.value.avatar_url
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
