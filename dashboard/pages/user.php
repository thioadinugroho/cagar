<?php include 'header.php'; ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header">Profil Anda</h2>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 toppad" >
                              <div class="panel panel-info">
                                <div class="panel-heading">
                                  <h3 class="panel-title">Profil</h3>
                                </div>
                                <div class="panel-body">
                                  <div class="row">
                                    <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="../img/avatar-300x300.png" class="img-circle img-responsive"> </div>
                                    <div class=" col-md-9 col-lg-9 "> 
                                      <table class="table table-user-information">
                                        <tbody>
                                          <tr>
                                            <td>Name:</td>
                                            <td>Alpha Orion</td>
                                          </tr>
                                          <tr>
                                            <td>Tanggal register:</td>
                                            <td>06/23/2013</td>
                                          </tr>
                                          <tr>
                                            <td>Email:</td>
                                            <td><a href="mailto:info@support.com">info@support.com</a></td>
                                          </tr>
                                            <td>Telepon:</td>
                                            <td id="userHp">08123456789</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                                 <div class="panel-footer">
                                    <a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
                                    <span class="pull-right">
                                        <a href="edit.html" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                                        <a data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
                                    </span>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6 toppad" >
                              <div class="panel panel-info">
                                <div class="panel-heading">
                                  <h3 class="panel-title">Point</h3>
                                </div>
                                <div class="panel-body">
                                  <div class="row">
                                    <div class="col-lg-6">                           
                                        <p style="font-size:47px;"><span class="avg" id="userPoint">6.2</span></p> 
                                    </div>
                                    <div class="col-lg-6">
                                    <br>         <br>               
                                        <a href="toko.php" class="btn btn-primary btn-plan-select"><i class="icon-white icon-ok"></i> Tukar Point Anda</a>
                                    </div>
                                  </div>
                                </div>
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