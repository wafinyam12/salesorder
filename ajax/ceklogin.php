<?php 
	session_start();
	include "../koneksi.php";

	$username = @mysqli_real_escape_string($conn, $_GET['username']);
	$password = @mysqli_real_escape_string($conn, $_GET['password']);

	$query = "SELECT * FROM tbl_pegawai WHERE username = '$username' AND password = '$password'";
	$sql = mysqli_query($conn, $query) or die ($conn->error);
	$data = mysqli_fetch_array($sql);
	if(mysqli_num_rows($sql) > 0) {
		$_SESSION['username_peg'] = $data['username'];
		$_SESSION['id_peg'] = $data['id_peg'];
		$_SESSION['session_id'] = $data['session_id'];
		$_SESSION['nama_peg'] = $data['nama_peg'];
		$_SESSION['posisi_peg'] = $data['pos_peg'];
		$_SESSION['session_user'] = $data['session_user'];
		$_SESSION['session_pass'] = $data['session_pass'];
		$_SESSION['dept'] = $data['dept'];
		$_SESSION['cabang'] = $data['cabang'];
		$_SESSION['session_tipe'] = $data['session_tipe'];
		echo "berhasil";
	} else {
		echo "gagal";
	}

 ?>