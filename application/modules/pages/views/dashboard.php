<!--begin::Row-->
<div class="row mt-0 mt-lg-8">
								<div class="col-xl-4">
									<!--[html-partial:begin:{"id":"demo1/dist/inc/view/partials/content/widgets/mixed/widget-6","page":"index"}]/-->
									<!--begin::Mixed Widget 4-->
									<?php
										if ( count($user_detail) > 0 ){
									?>
									<div class="card card-custom card-stretch gutter-b">
										<div class="card-body d-flex p-0">
											<div class="flex-grow-1 bg-info p-12 pb-20 card-rounded flex-grow-1 bgi-no-repeat"
												style="background-position: right bottom; background-size: 55% auto; background-image: url(<?php echo base_url('assets/media/svg/humans/custom-6.svg');?>)">
												<h3 class="text-inverse-info pb-5 font-weight-bolder">Halo, <?php echo $user_detail[0]['first_name'].' '.$user_detail[0]['last_name'];?>
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
									<?php } ?>
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
												<a href="<?php echo site_url('order/lists/new');?>" class="btn btn-success font-weight-bolder font-size-sm">
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

																		<?php 
																			if ( isset($order['icd_code']) && isset($order['icd_description'])){
																				echo $order['icd_code'];?> - <?php echo $order['icd_description'];
																			}
																		?>
																	</a>
																</td>
																<td>
																	<span
																		class="label label-inline label-light-primary font-weight-bold"
																		style="font-size: 12px;">
																			<?php 
																				$expired = strtotime($order['created_at'])+ (8*3600);
																				$left = time() - $expired;
																				echo date("d/M/Y", strtotime("+7days",strtotime($order['created_at'])));
																			?>
																		</span>
																</td>
															</tr>
														<?php endforeach;?>
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
												<a href="<?php echo site_url('order/list/');?>" class="btn btn-success font-weight-bolder font-size-sm">
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
														<?php foreach($list_orders as $i => $order){ ?>
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
																		<?php echo $order['nama_pasien'];?>
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
																	class="text-muted font-weight-bold font-size-sm"><?php echo date('j F Y',strtotime($order['created_at']));?></span>
																<span
																	class="text-muted font-weight-bold font-size-sm"><?php echo date('H:i',strtotime($order['created_at']));?></span>
															</td>
															<td>
																<?php
																	if ( $order['doctor_approve_time'] != ""){
																?>
																<span
																	class="text-muted font-weight-bold font-size-sm"><?php echo date('j F Y',strtotime($order['doctor_approve_time']));?></span>
																<span
																	class="text-muted font-weight-bold font-size-sm"><?php echo date('H:i',strtotime($order['doctor_approve_time']));?></span>
																<?php } ?>
															</td>
															<td>
																<?php 
																	if ( isset($order['icd_code']) && isset($order['icd_description'])){
																		echo $order['icd_code'];?> - <?php echo $order['icd_description'];
																	}
																?>
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
																	style="font-size: 12px;"><?php echo $config_status[$order['status']]?></span>
															</td>
														</tr>
														<?php } ?>
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

							<div class="modal fade" id="modal_setuju" tabindex="-1" role="dialog" aria-labelledby="modal_setuju"
						aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Pesanan #039483</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<i aria-hidden="true" class="ki ki-close"></i>
									</button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-4 col-md-3 ">Nama Pasien</div>
										<div class="col-6 col-md-9">: Yahya Fulan</div>
									</div>
									<div class="row">
										<div class="col-4 col-md-3 ">No. BPJS / No. Rekam Medis</div>
										<div class="col-6 col-md-9">: 00010101012 / 001157-07</div>
									</div>
									<div class="row">
										<div class="col-4 col-md-3 ">Tanggal Pengajuan</div>
										<div class="col-6 col-md-9">: 06 Oktober 2020</div>
									</div>
									<!-- <div class="row">
                    <div class="col-4 col-md-3 ">Jadwal Pengambilan</div>
                    <div class="col-6 col-md-9">: 10 Oktober 2020 pukul 10.00</div>
                </div> -->
									<div class="row">
										<div class="col-4 col-md-3 ">Status</div>
										<div class="col-6 col-md-9">: <label
												class="label label-inline label-primary">Disetujui</label></div>
									</div>
									<div class="table-responsive mt-5">
										<table class="table table-striped table-hover" style="width: 100%">
											<thead class="thead-inverse">
												<tr>
													<th>No</th>
													<th>Nama Obat</th>
													<th colspan="2">Qty</th>
													<th>Aturan Minum</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td>Amoxycillin 500mg</td>
													<td>3</td>
													<td>strip</td>
													<td>2 x 1 - sebelum makan</td>
												</tr>
												<tr>
													<td>1</td>
													<td>Simvastatin 5mg</td>
													<td>1</td>
													<td>strip</td>
													<td>2 x 1 - sebelum makan</td>
												</tr>
												<tr>
													<td>1</td>
													<td>Glimepiride 2,5mg</td>
													<td>3</td>
													<td>strip</td>
													<td>2 x 1 - sebelum makan</td>
												</tr>
											</tbody>
										</table>
									</div>

								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-light-primary font-weight-bold"
										data-dismiss="modal">Tutup</button>
								</div>
							</div>
						</div>
					</div>

					<div class="modal fade" id="modal_tolak" tabindex="-1" role="dialog" aria-labelledby="modal_tolak"
						aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Pesanan #039483</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<i aria-hidden="true" class="ki ki-close"></i>
									</button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-4 col-md-3 ">Nama Pasien</div>
										<div class="col-6 col-md-9">: Yahya Fulan</div>
									</div>
									<div class="row">
										<div class="col-4 col-md-3 ">No. BPJS / No. Rekam Medis</div>
										<div class="col-6 col-md-9">: 00010101012 / 001157-07</div>
									</div>
									<div class="row">
										<div class="col-4 col-md-3 ">Tanggal Pengajuan</div>
										<div class="col-6 col-md-9">: 06 Oktober 2020</div>
									</div>
									<!-- <div class="row">
                    <div class="col-4 col-md-3 ">Jadwal Pengambilan</div>
                    <div class="col-6 col-md-9">: 10 Oktober 2020 pukul 10.00</div>
                </div> -->
									<div class="row">
										<div class="col-4 col-md-3 ">Status</div>
										<div class="col-6 col-md-9">: <label
												class="label label-inline label-danger">Ditolak</label></div>
									</div>
									<div class="row">
										<div class="col-4 col-md-3 ">Alasan Penolakan</div>
										<div class="col-6 col-md-9">: <b>"Kontrol ke RS terlebih dahulu"</b></div>
									</div>
									<div class="table-responsive mt-5">
										<table class="table table-striped table-hover" style="width: 100%">
											<thead class="thead-inverse">
												<tr>
													<th>No</th>
													<th>Nama Obat</th>
													<th colspan="2">Qty</th>
													<th>Aturan Minum</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td>Amoxycillin 500mg</td>
													<td>3</td>
													<td>strip</td>
													<td>2 x 1 - sebelum makan</td>
												</tr>
												<tr>
													<td>1</td>
													<td>Simvastatin 5mg</td>
													<td>1</td>
													<td>strip</td>
													<td>2 x 1 - sebelum makan</td>
												</tr>
												<tr>
													<td>1</td>
													<td>Glimepiride 2,5mg</td>
													<td>3</td>
													<td>strip</td>
													<td>2 x 1 - sebelum makan</td>
												</tr>
											</tbody>
										</table>
									</div>

								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-light-primary font-weight-bold"
										data-dismiss="modal">Tutup</button>
								</div>
							</div>
						</div>
					</div>