<!--begin::Row-->
<div class="row mt-0 mt-lg-8">
								<div class="col-xl-4">
									<!--[html-partial:begin:{"id":"demo1/dist/inc/view/partials/content/widgets/mixed/widget-6","page":"index"}]/-->
									<!--begin::Mixed Widget 4-->
									<div class="card card-custom card-stretch gutter-b">
										<div class="card-body d-flex p-0">
											<div class="flex-grow-1 bg-info p-12 pb-20 card-rounded flex-grow-1 bgi-no-repeat"
												style="background-position: right bottom; background-size: 55% auto; background-image: url(<?php echo base_url('assets/media/svg/humans/custom-6.svg');?>)">
												<h3 class="text-inverse-info pb-5 font-weight-bolder">Halo, dr. Fulan
												</h3>
												<p class="text-inverse-info pb-5 font-size-h6">Anda mempunyai</p>
												<p class="text-inverse-info pb-5 display-1 font-weight-bolder"><?php echo count($new_orders);?></p>
												<p class="text-inverse-info pb-5 font-size-h6">permohonan obat baru.</p>
												
												<a href="<?php echo site_url('order/new');?>"
													class="btn btn-success font-weight-bold py-2 px-6 mt-20">Buka<i
														class="fas fa-arrow-right ml-3"
														style="color: white; font-size: 9px;"></i></a>
											</div>
										</div>
									</div>
									<!--end::Mixed Widget 4-->
									<!--[html-partial:end:{"id":"demo1/dist/inc/view/partials/content/widgets/mixed/widget-6","page":"index"}]/-->
								</div>
								<div class="col-xl-8">
									<!--[html-partial:begin:{"id":"demo1/dist/inc/view/partials/content/widgets/advance-tables/widget-1","page":"index"}]/-->
									<!--begin::Advance Table Widget 1-->
									<div class="card card-custom card-stretch card-shadowless gutter-b">
										<!--begin::Header-->
										<div class="card-header border-0 py-5">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label font-weight-bolder text-dark">Ringkasan Permohonan
													Obat Terbaru</span>
											</h3>
											<div class="card-toolbar">
												<a href="<?php echo site_url('order/new');?>" class="btn btn-success font-weight-bolder font-size-sm">
													Lihat Semua
													<i class="fa fa-arrow-right ml-3" aria-hidden="true"></i>
												</a>
											</div>
										</div>
										<!--end::Header-->
										<!--begin::Body-->
										<div class="card-body py-0">
											<!--begin::Table-->
											<div class="table-responsive">
												<table class="table table-head-custom table-vertical-center table-hover"
													id="kt_advance_table_widget_1">
													<thead>
														<tr class="text-left">
															<th class="pl-0" style="width: 20px">No.</th>
															<th class="pr-0" style="min-width: 150px;">Nama Pasien</th>
															<th style="min-width: 50px"></th>
															<th style="min-width: 150px">Tanggal Pengajuan</th>
															<th style="min-width: 150px">Diagnosa Terakhir</th>
															<th style="min-width: 110px">Batas Waktu Respons</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach($new_orders as $i => $order):?>
															<tr>
																<td class="pl-3"><?php echo $i+1;?></td>
																<td>
																	<div class="symbol symbol-50 symbol-light mt-1">
																		<span class="symbol-label">
																			<img src="assets/media/svg/avatars/001-boy.svg"
																				class="h-75 align-self-end" alt="" />
																		</span>
																	</div>
																</td>
																<td class="pl-0">
																	<a href="#" style="margin-left: -80px;">
																		<span
																			class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
																			<?php echo $order['nama_pasien'];?>
																		</span>
																		<span
																			class="text-muted font-weight-bold text-muted d-block font-size-sm"
																			style="margin-left: -80px;">MR: <?php echo $order['no_medrec'];?></span>
																		<span
																			class="text-muted font-weight-bold text-muted d-block font-size-sm"
																			style="margin-left: -80px;"><?php echo $order['gender'];?></span>
																	</a>
																</td>
																<td>
																	<span
																		class="text-muted font-weight-bold font-size-sm"><?php echo date('j F Y',strtotime($order['created_at']));?></span><br>
																	<span
																		class="text-muted font-weight-bold font-size-sm"><?php echo date('H:i',strtotime($order['created_at']));?></span>
																</td>
																<td>
																	<a href="https://icd.who.int/browse10/2019/en#/H66.3" target="_blank"
																		class="text-primary font-weight-bold font-size-sm">
																		H66.3 - OMSK (OTITIS MEDIA SUPURATIF KRONIS)
																	</a>
																</td>
																<td>
																	<span
																		class="label label-inline label-light-primary font-weight-bold"
																		style="font-size: 12px;">05:00:00</span>
																</td>
															</tr>
														<?php endforeach;;?>
														
														<tr>
															<td class="pl-3">
																2
															</td>
															<td class="pr-0">
																<div class="symbol symbol-50 symbol-light mt-1">
																	<span class="symbol-label">
																		<img src="assets/media/svg/avatars/018-girl-9.svg"
																			class="h-75 align-self-end" alt="" />
																	</span>
																</div>
															</td>
															<td class="pl-0">
																<a href="#"
																	class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg"
																	style="margin-left: -80px;">
																	Yahyani Fulanah
																</a>
																<span
																		class="text-muted font-weight-bold text-muted d-block font-size-sm"
																		style="margin-left: -80px;">02020203</span>
																<span
																	class="text-muted font-weight-bold text-muted d-block font-size-sm"
																	style="margin-left: -80px;">Perempuan</span>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">27
																	Agustus 2020</span>
																<span
																	class="text-muted font-weight-bold font-size-sm">12:00</span>
															</td>
															<td>
																<a href="https://icd.who.int/browse10/2019/en#/H66.3" target="_blank"
																	class="text-primary font-weight-bold font-size-sm">
																	H66.3 - OMSK (OTITIS MEDIA SUPURATIF KRONIS)
																</a>
															</td>
															<td>
																<span
																	class="label label-inline label-light-danger font-weight-bold"
																	style="font-size: 12px;">00:10:00</span>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<!--end::Table-->
										</div>
										<!--end::Body-->
									</div>
									<!--end::Advance Table Widget 1-->
									<!--[html-partial:end:{"id":"demo1/dist/inc/view/partials/content/widgets/advance-tables/widget-1","page":"index"}]/-->
								</div>
							</div>
							<!--end::Row-->
							<!--begin::Row-->
							<div class="row">
								<div class="col-xl-12">
									<!--[html-partial:begin:{"id":"demo1/dist/inc/view/partials/content/widgets/base-tables/widget-5","page":"index"}]/-->
									<!--begin::Base Table Widget 5-->
									<div class="card card-custom card-stretch card-shadowless gutter-b">
										<!--begin::Header-->
										<div class="card-header border-0 py-5">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label font-weight-bolder text-dark">Ringkasan Riwayat Permohonan
													Obat</span>
											</h3>
											<div class="card-toolbar">
												<a href="order_history.html" class="btn btn-success font-weight-bolder font-size-sm">
													Lihat Semua
													<i class="fa fa-arrow-right ml-3" aria-hidden="true"></i>
												</a>
											</div>
										</div>
										<!--end::Header-->
										<!--begin::Body-->
										<div class="card-body py-0">
											<!--begin::Table-->
											<div class="table-responsive">
												<table class="table table-head-custom table-vertical-center table-hover"
													id="kt_advance_table_widget_1">
													<thead>
														<tr class="text-left">
															<th class="pl-0" style="width: 20px">No.</th>
															<th class="pr-0" style="min-width: 150px;">Nama Pasien</th>
															<th style="min-width: 50px"></th>
															<th style="min-width: 50px">Tanggal Pengajuan</th>
															<th style="min-width: 150px">Tanggal Konfirmasi</th>
															<th style="min-width: 150px">Diagnosa Terakhir</th>
															<th style="min-width: 150px">Obat Diresepkan</th>
															<th style="min-width: 110px">Status</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td class="pl-3">1</td>
															<td>
																<div class="symbol symbol-50 symbol-light mt-1">
																	<span class="symbol-label">
																		<img src="assets/media/svg/avatars/001-boy.svg"
																			class="h-75 align-self-end" alt="" />
																	</span>
																</div>
															</td>
															<td class="pl-0">
																<a href="#" style="margin-left: -80px;">
																	<span
																		class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
																		Yahya Fulan
																	</span>
																	<span
																		class="text-muted font-weight-bold text-muted d-block font-size-sm"
																		style="margin-left: -80px;">02020202</span>
																	<span
																		class="text-muted font-weight-bold text-muted d-block font-size-sm"
																		style="margin-left: -80px;">Laki-laki</span>
																</a>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">27
																	Agustus 2020</span>
																<span
																	class="text-muted font-weight-bold font-size-sm">12:00</span>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">27
																	Agustus 2020</span>
																<span
																	class="text-muted font-weight-bold font-size-sm">15:00</span>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">H66.3 - OMSK (OTITIS MEDIA SUPURATIF KRONIS)</span>
															</td>
															<td>
																<a href="#">
																	<button type="button"
																		class="btn label label-inline label-default font-weight-bold"
																		data-toggle="modal" data-target="#modal_setuju"
																		style="font-size: 12px;">Lihat List
																		Obat</button>
																</a>
															</td>
															<td>
																<span
																	class="label label-inline label-light-primary font-weight-bold"
																	style="font-size: 12px;">Disetujui</span>
															</td>
														</tr>
														<tr>
															<td class="pl-3">
																2
															</td>
															<td class="pr-0">
																<div class="symbol symbol-50 symbol-light mt-1">
																	<span class="symbol-label">
																		<img src="assets/media/svg/avatars/018-girl-9.svg"
																			class="h-75 align-self-end" alt="" />
																	</span>
																</div>
															</td>
															<td class="pl-0">
																<a href="#"
																	class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg"
																	style="margin-left: -80px;">
																	Yahyani Fulanah
																</a>
																<span
																	class="text-muted font-weight-bold text-muted d-block font-size-sm"
																	style="margin-left: -80px;">Perempuan</span>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">27
																	Agustus 2020</span>
																<span
																	class="text-muted font-weight-bold font-size-sm">12:00</span>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">27
																	Agustus 2020</span>
																<span
																	class="text-muted font-weight-bold font-size-sm">15:00</span>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">H66.3 - OMSK (OTITIS MEDIA SUPURATIF KRONIS)</span>
															</td>
															<td>
																<a href="#">
																	<button type="button"
																		class="btn label label-inline label-default font-weight-bold"
																		data-toggle="modal" data-target="#modal_tolak"
																		style="font-size: 12px;">Lihat List
																		Obat</button>
																</a>
															</td>
															<td>
																<span
																	class="label label-inline label-light-danger font-weight-bold"
																	style="font-size: 12px;">Ditolak</span>
															</td>
														</tr>
														<tr>
															<td class="pl-3">
																3
															</td>
															<td class="pr-0">
																<div class="symbol symbol-50 symbol-lightv mt-1">
																	<span class="symbol-label">
																		<img src="assets/media/svg/avatars/016-boy-7.svg"
																			class="h-75 align-self-end" alt="" />
																	</span>
																</div>
															</td>
															<td class="pl-0">
																<a href="#"
																	class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg"
																	style="margin-left: -80px;">Putranto</a>
																<span
																	class="text-muted font-weight-bold text-muted d-block font-size-sm"
																	style="margin-left: -80px;">Laki-laki</span>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">27
																	Agustus 2020</span>
																<span
																	class="text-muted font-weight-bold font-size-sm">12:00</span>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">27
																	Agustus 2020</span>
																<span
																	class="text-muted font-weight-bold font-size-sm">15:00</span>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">H66.3 - OMSK (OTITIS MEDIA SUPURATIF KRONIS)</span>
															</td>
															<td>
																<a href="#">
																	<span
																		class="label label-inline label-default font-weight-bold"
																		style="font-size: 12px;">Lihat List Obat</span>
																</a>
															</td>
															<td>
																<span
																	class="label label-inline label-light-danger font-weight-bold"
																	style="font-size: 12px;">Ditolak</span>
															</td>
														</tr>
														<tr>
															<td class="pl-3">
																4
															</td>
															<td class="pr-0">
																<div class="symbol symbol-50 symbol-light mt-1">
																	<span class="symbol-label">
																		<img src="assets/media/svg/avatars/014-girl-7.svg"
																			class="h-75 align-self-end" alt="" />
																	</span>
																</div>
															</td>
															<td class="pl-0">
																<a href="#"
																	class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg"
																	style="margin-left: -80px;">Putriwati</a>
																<span
																	class="text-muted font-weight-bold text-muted d-block font-size-sm"
																	style="margin-left: -80px;">Perempuan</span>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">27
																	Agustus 2020</span>
																<span
																	class="text-muted font-weight-bold font-size-sm">12:00</span>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">27
																	Agustus 2020</span>
																<span
																	class="text-muted font-weight-bold font-size-sm">15:00</span>
															</td>
															<td>
																<span
																	class="text-muted font-weight-bold font-size-sm">H66.3 - OMSK (OTITIS MEDIA SUPURATIF KRONIS)</span>
															</td>
															<td>
																<a href="#">
																	<span
																		class="label label-inline label-default font-weight-bold"
																		style="font-size: 12px;">Lihat List Obat</span>
																</a>
															</td>
															<td>
																<span
																	class="label label-inline label-light-primary font-weight-bold"
																	style="font-size: 12px;">Disetujui</span>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<!--end::Table-->
										</div>
										<!--end::Body-->
									</div>

									<!--end::Base Table Widget 5-->
									<!--[html-partial:end:{"id":"demo1/dist/inc/view/partials/content/widgets/base-tables/widget-5","page":"index"}]/-->
								</div>
							</div>