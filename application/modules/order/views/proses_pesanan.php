<!--begin::Row-->
<div class="row mt-0 mt-lg-8">
	<div class="col-xl-12">
		<div class="card card-custom">
			<div class="card-header flex-wrap border-0 pt-6 pb-0">
				<div class="card-title">
					<h3 class="card-label">Pesanan #<?php echo $order_detail[0]['order_no'];?>
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
													<i class="flaticon-file-2"></i> No.
													Pesanan #20201001-01
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
															#20201001-01
														</li>
														<li>
															<span
																class="font-weight-bold">Tanggal
																Pesanan</span>: 9 Januari
															2020
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
															200102134146D</li>
														<li>
															<span
																class="font-weight-bold">Obat
																yang diresepkan</span>:
															<ul>
																<li>Cefixime 100mg - 10
																	tablet - 2x1</li>
																<li>Megabal Caps /100s - 10
																	tablet - 2x1</li>
																<li>Methyl Prednisolon tab
																	4mg - 10 tablet - 2x1
																</li>
															</ul>
														</li>
													</ul>
													<a href="#resep-form-group"
														class="btn btn-outline-warning btn-sm font-weight-bold mt-8 btn-fetch-resep" data-category="pesanan" data-id="1" data-kode="200102134146D">Gunakan
														Resep Ini untuk meresepkan obat</a>
												</div>
											</div>
										</div>
										
									</div>
								<?php } ?>

								<button type="button" class="btn btn-block bg-dark text-white mt-5">Lihat Semua Riwayat Pesanan</button>
								</div>
							</div>

							<form action="<?php echo base_url();?>order/submit_receipt" method="post" name="form1">
								<input type="hidden" name="order_id" value="<?php echo $order_detail[0]['id']?>">
								<div id="kt_repeater_1">
									<div class="form-group row" id="kt_repeater_1">
										<label class="col-md-2 col-form-label">Resep:</label>
										<div data-repeater-list="" class="col-md-10" id="resep-form-group">
											<div data-repeater-item class="form-group row align-items-center">
												<div class="col-md-3">
													<label>Nama Obat:</label>
													<select name="obat[]" class="form-control select2">
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
													<input type="number" class="form-control" name="qty[]"/>
													<div class="d-md-none mb-2"></div>
												</div>
												<div class="col-6 col-md-2">
													<label>Satuan:</label>
													<select name="unit[]" class="form-control">
														<option value="">Pilih Satuan</option>
														<option value="kapsul">kapsul</option>
														<option value="tablet">tablet</option>
														<option value="strip">strip</option>
													</select>
													<div class="d-md-none mb-2"></div>
												</div>
												<div class="col-6 col-md-2">
													<label>Dosis:</label>
													<input type="number" class="form-control" name="dosis[]"/>
													<div class="d-md-none mb-2"></div>
												</div>
												<div class="col-6 col-md-2">
													<label>Frekuensi:</label>
													<input type="number" class="form-control" name="frekuensi[]"/>
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
										<textarea class="form-control" name="description_receupt" id="" rows="5" placeholder="Keterangan tambahan mengenai resep"></textarea>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-md-12 text-right">
										<a href="<?php echo base_url();?>" class="btn btn-default mr-3">Kembali</a>
										<?php if ( $order_detail[0]['status'] == "1") { ?>
										<button type="submit" class="btn btn-primary">Proses & Simpan Resep</button>
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