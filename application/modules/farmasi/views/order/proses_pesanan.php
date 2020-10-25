<style>
	.datepicker tbody tr > td.day{
		font-weight: 600;
		background: rgba(178,212,99,0.5) !important;
	}
	.datepicker table tr td.disabled, .datepicker table tr td.disabled:hover,
	.datepicker table tr td.disabled.old, .datepicker table tr td.disabled.old:hover,
	.datepicker table tr td.disabled.new, .datepicker table tr td.disabled.new:hover{
		font-weight: 400;
		color: #DDD;
		background: #F7FFF1 !important;
	}
	.datepicker tbody tr > td.day.selected, .datepicker tbody tr > td.day.selected:hover, .datepicker tbody tr > td.day.active, .datepicker tbody tr > td.day.active:hover {
		background: #009347 !important;
	}
</style>
<!--begin::Row-->
<div class="row mt-0 mt-lg-8">
								<div class="col-xl-12">
									<div class="card card-custom">
										<div class="card-header flex-wrap border-0 pt-6 pb-0">
											<div class="card-title">
												<h3 class="card-label">Pesanan #20201014-0001
													<span class="d-block text-muted pt-2 font-size-sm"></span></h3>
											</div>
										</div>
										<div class="card-body">
											<div class="my-3">
												<div class="row">
													<div class="col-md-12">
														<div class="form-group row">
															<label class="col-md-2 col-form-label">Resep Dokter</label>
															<div class="col-md-10">
																<ul class="list-unstyled">
																	<li>
																		<span
																			class="font-weight-bold">Cefixime - 10 tablet - 2x1</span>
																	</li>
																	<li>
																		<span
																			class="font-weight-bold">Cefixime - 10 tablet - 2x1</span>
																	</li>
																</ul>
															</div>
														</div>
														<div class="form-group row">
															<div class="col-md-2 col-md-label">Catatan Dokter</div>
															<div class="col-md-10">
															<span class="font-weight-bold">"ITER 2x"</span>
															</div>
														</div>
														<div class="form-group row">
															<label class="col-md-2 col-form-label">Tanggal Kirim/Ambil</label>
															<div class="col-md-10">
																<div class id="datepicker-inline"></div>
															</div>
														</div>
														
														<div class="form-group row">
															<div class="col-md-12 text-right">
																<a href="order_list.html" class="btn btn-default mr-3">Kembali</a>
																<button type="button" class="btn btn-primary">Proses & Simpan Resep</button>

															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--end::Row-->