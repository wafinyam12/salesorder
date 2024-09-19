<?php 
  session_start();
  if(@$_SESSION['posisi_peg']) {
    echo "<script>window.location='./';</script>";
  } else {
 ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="asset/bootstrap_4/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="asset/private_style/style.css">
    <link rel="stylesheet" href="asset/sweetalert/dist/sweetalert2.min.css">

    <title>Aplikasi Sales Order | Login</title>
  </head>
  <body>
  	<div class="container">
  		<div class="row">
  			<div class="welcome col-lg-8">
  				SELAMAT DATANG <br> APLIKASI SAP MOBILE <br> UTOMODECK GROUP
  			</div>
	    	<div class="form-login col-lg-4">
	    		<form>
	    		  <h6>SISTEM SALES ORDER</h6>
  				  <div class="form-group">
  				    <label for="username">USERNAME</label>
  				    <input type="email" class="form-control" id="username" placeholder="username" autofocus="">
  				  </div>
  				  <div class="form-group">
  				    <label for="password">PASSWORD</label>
  				    <input type="password" class="form-control" id="password" placeholder="password">
  				  </div>
            
            <div class="form-group tombol-login">
              <a href="javascript:void(0)">        
                <div class="btn btn-sm btn-info" id="tombol_login">LOG IN</div>
              </a>
            </div>
  				</form>
	    	</div>
  		</div>	
  	</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="asset/Jquery/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="asset/bootstrap_4/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="asset/sweetalert/dist/sweetalert2.min.js"></script>

    <script>
      $("#tombol_login").click(function() {
        var username = $("#username").val();
        var password = $("#password").val();

        $.ajax({
          type: "GET",
          url: "ajax/ceklogin.php",
          data: "username="+username+"&password="+password,
          success: function(hasil) {
            if(hasil=="berhasil") {
              window.location='./';
            } else {
              document.getElementById("username").focus();
              Swal.fire({
                type: 'error',
                title: 'Gagal',
                text: 'Periksa kembali username dan password anda',
                showConfirmButton: true
                // timer: 1500
              })
            }
          }
        });
      });
    </script>
  </body>
</html>
<?php } ?>