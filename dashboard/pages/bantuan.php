<?php include 'header.php'; ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header">Bantuan</h2>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-6">
                      <div class="panel panel-info">
                        <div class="panel-heading">
                          Format SMS Untuk Pelaporan Data
                        </div>
                        <div class="panel-body">
                          <p>Ketik:</p>
                          <div class="well">
                            <p>LAPOR&lt;spasi&gt;JENIS SENTRA#NAMA SENTRA#KODE POS#NAMA KOMODITAS#JENIS KOMODITAS#KUANTITAS#SATUAN#HARGA</p>
                          </div>
                          <p>Contoh:</p>
                          <div class="well">
                            <p>LAPOR Pasar#Among tani#51273#BERAS#pandan wangi#1#KG#13450</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="panel panel-info">
                        <div class="panel-heading">
                          Format SMS Untuk Mengetahui Harga
                        </div>
                        <div class="panel-body">
                          <p>Ketik:</p>
                          <div class="well">
                            CARI&lt;spasi&gt;JENIS SENTRA#NAMA KOMODITAS#JENIS KOMODITAS#KODE POS</p>
                          </div>
                          <p>Contoh:</p>
                          <div class="well">
                            <p>CARI pasar#beras#beras merah#80361</p>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

<?php include 'footer.php'; ?>