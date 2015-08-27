<?php include 'header.php'; ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="page-header">Input Data</h2>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Input Data
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-info successSubmit" id="successSubmit" role="alert">Data sudah masuk. Terima kasih.</div>
                                    <div class="alert alert-danger errorSubmit" id="errorSubmit" role="alert">Input data gagal. Coba cek koneksi Anda.</div>
                                    <div class="alert alert-danger errorInput" id="errorInput" role="alert">Input data gagal. Data tidak valid.</div>
                                </div>
                            </div>
                            <div class="row">
                                <form role="form">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Jenis Sentra</label>
                                            <select class="form-control" name="jenisSentra" id="jenisSentra">
                                                <option value="produsen">Produsen</option>
                                                <option value="pasar">Pasar</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama Sentra</label>
                                            <input class="form-control" name="namaSentra" id="namaSentra" placeholder="Nama sentra">
                                        </div>
                                        <div class="form-group">
                                            <label>Kode Pos</label>
                                            <input class="form-control" name="kodePos" id="kodePos" placeholder="Kode pos">
                                        </div>    
                                        <div class="form-group">
                                            <label>Nama Komoditi</label>
                                            <select class="form-control" name="namaKomoditi" id="namaKomoditi">
                                                <option value="beras">Beras</option>
                                            </select>
                                        </div>                               
                                    </div>
                                    <!-- /.col-lg-6 (nested) -->
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Jenis Komoditi</label>
                                            <input class="form-control" name="jenisKomoditi" id="jenisKomoditi" placeholder="Jenis komoditi">
                                        </div>
                                        <div class="form-group">
                                            <label>Kuantitas</label>
                                            <input class="form-control" name="kuantitas" id="kuantitas" placeholder="Kuantitas">
                                        </div>
                                        <div class="form-group">
                                            <label>Satuan</label>
                                            <select class="form-control" name="satuan" id="satuan">
                                                <option value="kg">Kilogram</option>
                                                <option value="gr">Gram</option>
                                            </select>
                                        </div>
                                        <label>Harga</label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="form-control" name="harga" id="harga" placeholder="Harga">
                                        </div>
                                    </div>
                                    <div class="col-ls-12 text-center">
                                        <div class="form-group">
                                            <input type="hidden" id="hp" name="hp" value="082130212186">
                                            <button type="submit" id="submitData" class="btn btn-primary">Submit</button> &nbsp; &nbsp;
                                            <button type="reset" class="btn btn-default">Reset</button>
                                        </div>
                                    </div>
                                    <!-- /.col-lg-6 (nested) -->
                                </form>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

<?php include 'footer.php'; ?>