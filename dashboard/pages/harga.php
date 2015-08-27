<?php include 'header.php'; ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="page-header">Harga Beras</h2>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-danger errorGet" id="errorGet" role="alert">Mengambil data gagal. Coba cek koneksi Anda.</div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Data Table Harga Beras
                        </div>
                        <!-- /.panel-heading -->
                        <div class="container" id="sandbox-container">
                            <form role="form">
                                <h5><label>Masukkan tanggal:</label></h5>
                                <div class="input-daterange input-group" id="datepicker">
                                    <input class="input-sm form-control" name="periodeDari" id="periodeDari" type="text" value="23/08/2015">
                                    <!-- <span class="input-group-addon">sampai</span>
                                    <input class="input-sm form-control" name="end" type="text"> -->
                                </div>
                            </form>
                        </div>
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTableHarga">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Daerah</th>
                                            <th>Harga Rata-Rata Sentra Produksi</th>
                                            <th>Harga Rata-Rata Sentra Pasar</th>
                                            <th>Unit Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
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