<!--begin::Row-->
<div class="row mt-0 mt-lg-8">
	<div class="col-xl-12">
		<div class="card card-custom">
			<div class="card-header flex-wrap border-0 pt-6 pb-0">
				<div class="card-title">
					<h3 class="card-label">Pesanan #<?php echo $order_detail[0]['order_no'];?> - <?php echo  $status[$order_detail[0]['status']];?>
						<span class="d-block text-muted pt-2 font-size-sm"></span></h3>

				</div>
			</div>
			<div class="card-body">
				<div class="my-3">

					<div class="row">
						<div class="col-md-12">
							<div class="form-group row">
								<label class="col-md-2 col-form-label">Riwayat Kunjungan ke
									RS</label>
								<div class="col-md-10">
									<?php
										foreach ($latest_visit as $key => $value) {
									?>
									<div class="accordion accordion-solid accordion-toggle-plus"
										id="accordionKunjungan">

										<div class="card">
											<div class="card-header" id="heading_kunjungan-1">
												<div class="card-title collapsed"
													data-toggle="collapse"
													data-target="#kunjungan-1">
													<i class="flaticon-file-2"></i> No.
													Kunjungan #<?php echo $value['order_no']?>
												</div>
											</div>
											<div id="kunjungan-1" class="collapse"
												data-parent="#accordionKunjungan">
												<div class="card-body">
													<ul>
														<li>
															<span
																class="font-weight-bold">No.
																Kunjungan</span>:
															<?php echo $value['order_no']?>
														</li>
														<li>
															<span
																class="font-weight-bold">Tanggal
																Kunjungan <?php echo date("d-M-Y",strtotime($value['tanggal_kunjungan']));?></span>: 
														</li>

														<li>
															<span
																class="font-weight-bold">Dokter
																yang menangani</span>:
															<?php echo $value['name'];?>
														</li>
														<li>
															<span
																class="font-weight-bold">Poli</span>:
															<?php echo $value['poli']; ?>
														</li>
														<li>
															<span
																class="font-weight-bold">Systol/Diastol</span>:
															110/80
														</li>
														<li>
															<span
																class="font-weight-bold">Diagnosa</span>:
															<ul>
																<li><span
																		class="font-weight-bold">ICD
																		Code</span>: <?php echo $value['icd_code']; ?>
																</li>
																<li><span
																		class="font-weight-bold">Description</span>:
																	<?php echo $value['icd_description']; ?></li>
															</ul>
														</li>
														
													</ul>
												</div>
											</div>
										</div>
									</div>
									<?php } ?>
									<button type="button" class="btn btn-block bg-dark text-white mt-5">Lihat Semua Riwayat Kunjungan</button>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 col-form-label">Riwayat Pemesanan
									Obat</label>

								<div class="col-md-10">
								<?php
									foreach ($latest_receipt as $key => $value) {
								?>
									<div class="accordion accordion-solid accordion-toggle-plus"
										id="accordionPesanan">
										<div class="card">
											<div class="card-header" id="heading_pesanan-1">
												<div class="card-title collapsed"
													data-toggle="collapse"
													data-target="#pesanan-1">
													<i class="flaticon-file-2"></i> <?php echo $value['order_no'];?>
												</div>
											</div>
											<div id="pesanan-1" class="collapse"
												data-parent="#accordionPesanan">
												<div class="card-body">
													<ul>
														<li>
															<span
																class="font-weight-bold">No.
																Pesanan</span>:
															<?php echo $value['order_no'];?>
														</li>
														<li>
															<span
																class="font-weight-bold">Tanggal
																Pesanan</span>: <?php echo date("d M Y ",strtotime($value['created_at']));?>
														</li>

														<li>
															<span
																class="font-weight-bold">Dokter
																yang menangani</span>:
															dr.Fari A.D. Sp.THT-KL, M.Kes
														</li>
														<li>
															<span
																class="font-weight-bold">Poli</span>:
															Poli THT
														</li>
														<li><span
																class="font-weight-bold">No.
																Resep</span>:
															<?php echo $value['receipt_no'];?></li>
														<li>
															<span
																class="font-weight-bold">Obat
																yang diresepkan</span>:
															<ul>
																<?php
																	if ( count($value['receipt_detail']) > 0 ){
																		foreach ($value['receipt_detail'] as $key_detail => $value_detail) {
																?>
																<li><?php echo $value_detail['name']; ?> - <?php echo $value_detail['order_qty']; ?> <?php echo $value_detail['unit']; ?> - <?php echo $value_detail['dosis']; ?>x<?php echo $value_detail['frekuensi']; ?></li>
																<?php 
																	}
																}
																?>
															</ul>
														</li>
													</ul>
													<?php if ( $order_detail[0]['status'] == "1") { ?>
													<a href="#resep-form-group"
														class="btn btn-outline-warning btn-sm font-weight-bold mt-8 btn-fetch-resep" data-category="pesanan" data-id="1" data-kode="200102134146D">Gunakan
														Resep Ini untuk meresepkan obat</a>
													<?php } ?>
												</div>
											</div>
										</div>
										
									</div>
								<?php } ?>

								<button type="button" class="btn btn-block bg-dark text-white mt-5">Lihat Semua Riwayat Pesanan</button>
								</div>
							</div>
							<form id="submit-receipt">
								<input type="hidden" name="order_id" id="order_id" value="<?php echo $order_detail[0]['id']?>">
								<div id="kt_repeater_1">
									<div class="form-group row" id="kt_repeater_1">
										<label class="col-md-2 col-form-label">Resep:</label>
										<div data-repeater-list="" class="col-md-10" id="resep-form-group">
											<div data-repeater-item class="form-group row align-items-center">
												<div class="col-md-3">
													<label>Nama Obat:</label>
													<select class="form-control select2 obat" name="obat">
														<option value="">Pilih Obat</option>
											            <?php 
											            	if(count($obat) > 0 ){ 
											            		foreach ( $obat as $key => $value) { 
								            			?>
											            		<option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
											            <?php } } ?>
													</select>
													<div class="d-md-none mb-2"></div>
												</div>
												<div class="col-6 col-md-2">
													<label>Qty:</label>
													<input type="number" class="form-control qty" name="qty" />
													<div class="d-md-none mb-2"></div>
												</div>
												<div class="col-6 col-md-2">
													<label>Satuan:</label>
													<select class="form-control unit" name="unit">
														<option value="">Pilih Satuan</option>
														<option value="kapsul">kapsul</option>
														<option value="tablet">tablet</option>
														<option value="strip">strip</option>
													</select>
													<div class="d-md-none mb-2"></div>
												</div>
												<div class="col-6 col-md-2">
													<label>Dosis:</label>
													<input type="number" class="form-control dosis" name="dosis" />
													<div class="d-md-none mb-2"></div>
												</div>
												<div class="col-6 col-md-2">
													<label>Frekuensi:</label>
													<input type="number" class="form-control freq" name="freq" />
													<div class="d-md-none mb-2"></div>
												</div>
												<div class="col-md-1">
													<a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger">
														<i class="la la-trash-o"></i> Hapus
													</a>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-2 col-form-label text-right"></label>
										<div class="col-lg-4">
											<a href="javascript:;" data-repeater-create="" id="btn-add-resep" class="btn btn-sm font-weight-bolder btn-light-primary">
												<i class="la la-plus"></i>Tambah Obat
											</a>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-md-2 col-md-label">Catatan Resep</div>
									<div class="col-md-10">
										<textarea class="form-control" name="desc" id="desc" rows="5" placeholder="Keterangan tambahan mengenai resep"></textarea>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-md-12 text-right">
										<a href="<?php echo base_url();?>" class="btn btn-default mr-3">Kembali</a>
										<?php if ( $order_detail[0]['status'] == "1") { ?>
										<button type="button" class="btn btn-primary" onClick="saveReceipt();">Proses & Simpan Resep</button>
										<?php } ?>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end::Row-->