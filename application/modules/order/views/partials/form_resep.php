<div data-repeater-item class="form-group row align-items-center">
    <div class="col-md-3">
        <label>Nama Obat:</label>
        <select class="form-control select2 obat" name="obat">
            <option value="">Pilih Obat</option>
            <?php if(count($obat) > 0 ){ foreach ( $obat as $key => $value) { ?>
            <option value="<?php echo $value['id']?>" selected><?php echo $value['name']?></option>
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
            <option value="tablet" selected>tablet</option>
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
        <label>&nbsp;</label>
        <a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger">
            <i class="la la-trash-o"></i> Hapus
        </a>
    </div>
</div>
