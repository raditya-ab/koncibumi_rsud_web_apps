<div class="card card-custom card-shadowless gutter-b">
    <!--begin::Body-->
    <div class="card-body p-2">
        <!--begin::User-->
        <div class="text-center">
            <div class="symbol symbol-60 symbol-circle symbol-xl-90 mb-5">
                <div class="symbol-label"
                    style="background-image:url('<?php echo base_url('assets/media/users/300_21.jpg');?>'); min-width: 150px; min-height: 150px;">
                </div>
            </div>
            <h4 class="font-weight-bolder mt-2 mb-5">Ahmad Fulan</h4>
        </div>
        <!--end::User-->
    </div>
    <!--end:: Body-->
</div>
<div class="card card-custom card-shadowless gutter-b bg-light-primary">
    <!--begin::Body-->
    <div class="card-body p-5">
        <!--begin::User-->
        <div class="text-left mb-5">
            <div class="mb-2"><span class="font-weight-bold">No. Rekam Medis</span><br>000000-12
            </div>
            <div class="mb-2"><span class="font-weight-bold">No. BPJS</span><br>0000000000983</div>
        </div>
        <div class="text-left mb-5">
            <div class="mb-2"><span class="font-weight-bold">Tanggal Lahir</span><br>9 Januari 1980
            </div>
            <div class="mb-2"><span class="font-weight-bold">Umur</span><br>40 tahun</div>
            <div class="mb-2"><span class="font-weight-bold">Jenis Kelamin</span><br>Laki-laki</div>
            <div class="mb-2"><span class="font-weight-bold">Golongan Darah</span><br>O rh.+</div>

            <!-- <span
									class="label label-light-warning label-inline font-weight-bold label-lg">Active
								</span> -->
        </div>
        <!--end::User-->
    </div>
    <!--end:: Body-->
</div>
<div class="card card-custom card-shadowless gutter-b bg-light-success">
    <div class="card-header border-0 p-5 justify-content-center">
        <h3 class="card-title align-items-center flex-column">
            <span class="card-label font-weight-bolder text-dark mr-0 mb-1">Riwayat Kunjungan
                Terakhir</span>
        </h3>
    </div>
    <div class="card-body p-5">
        <div class="row">
            <div class="col-md-12">
                <div class="text-left mb-5">
                    <div class="mb-2">
                        <span class="font-weight-boldest">Tanggal Kunjungan</span>
                        <br>
                        <span class="text-navy">9 Januari 2020</span>
                    </div>
                    <div class="mb-2">
                        <span class="font-weight-boldest">Dokter</span>
                        <br>
                        <span class="text-navy">dr.Fari A.D. Sp.THT-KL, M.Kes</span>
                    </div>
                    <div class="mb-2">
                        <span class="font-weight-boldest">Poli</span>
                        <br>
                        <span class="text-navy">Poli THT</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-navy font-weight-boldest">Systol/Diastol</span>
                        <br>
                        <span class="text-navy">110/80</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-navy font-weight-boldest">Diagnosa</span>
                        <br>
                        <span class="text-navy">H66.3 - OMSK (OTITIS MEDIA SUPURATIF KRONIS)</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-navy font-weight-boldest">Resep Terakhir</span>
                        <br>
                        <a href="#" data-toggle="modal" data-target="#modal_list_obat" class="text-navy">Lihat Resep</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card card-custom gutter-b card-shadowless bg-secondary">
    <div class="card-header border-0 p-5 justify-content-center">
        <h3 class="card-title align-items-center flex-column">
            <span class="card-label font-weight-bolder text-dark mr-0 mb-1">Statistik Permohonan
                <br>Obat</span>
            <span class="text-muted font-weight-bold font-size-sm">Ada 5 permohonan dalam minggu
                ini</span>
        </h3>
    </div>
    <div class="card-body">
        <!--begin::Chart-->
        <div id="user_statistics_chart" class="d-flex justify-content-center"></div>
        <!--end::Chart-->
    </div>
</div>