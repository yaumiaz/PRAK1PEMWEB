<?php
session_start();

$login = false;

if (!empty($_GET['logout'])) {
	unset($_SESSION['login']);
}

if (isset($_POST['user']) && isset($_POST['password'])) {
	if ($_POST['user'] == 'ya' && $_POST['password'] == 'umi') {
		setcookie('user', $_POST['user'], 0);

		$_SESSION['login'] = true;
	}
}

if (!empty($_SESSION['login'])) {
	$login = true;
}

$target_dir = "uploads/";
$daftar     = array();

if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = null;
}

if (!$login) {
	$page = 'login';
}

switch ($page) {
	case 'upload':
		if (isset($_FILES['fileUpload'])) {
			$target_file = $target_dir . basename($_FILES["fileUpload"]["name"]);
			$uploadOk    = 1;
			if (file_exists($target_file)) {
				echo "Sorry, file already exists.";
				$uploadOk = 0;
			}
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
					echo "The file " . basename($_FILES["fileUpload"]["name"]) . " has been uploaded.";
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
			}
		}
		break;

	case 'create':
		if (isset($_POST['direktori'])) {
			mkdir($target_dir . $_POST['direktori']);
		}
		break;
	default:
		if (isset($_GET['delete'])) {
			$file = $_GET['delete'];
			if (is_dir($target_dir . $file)) {
				rmdir($target_dir . $file);
			} else {
				unlink($target_dir . $file);
			}
		}
		$daftar = scandir($target_dir);
		$daftar = array_diff($daftar, array(
			'.',
			'..'
		));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Manajemen File</title>

	<!-- Bootstrap core CSS -->
	<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="css/scrolling-nav.css" rel="stylesheet">

</head>

<body id="page-top">

	<!-- Navigation -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
		<div class="container">
			<a class="navbar-brand js-scroll-trigger" href="index.php">File Manager</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
		</div>
	</nav>

	<section id="main">
		<div class="container">
			<?php if ($login) { ?>
				<div class="row">
					<div class="col-lg-12 mx-auto">
						<a href="index.php?page=upload" class="btn btn-primary">Upload</a>
						<a href="index.php?page=create" class="btn btn-primary">Create Directory</a>
						<a href="index.php?logout=ya" class="btn btn-danger">Keluar</a><br><br>
						<h4>Manajemen File</h4>
						<?php

						switch ($page) {
							case 'upload':
								?>
							<form action="" method="post" enctype="multipart/form-data">
								<input type="file" name="fileUpload" class="form-control-file" id="fileToUpload"><br>
								<input type="submit" class="btn btn-danger" value="Upload" name="submit">
							</form>
							<?php
							break;

						case 'create':
							?>
							<form action="" method="post">
								<div class="form-group">
									<label for="namaFolder">Nama Folder</label>
									<input type="text" name="direktori" class="form-control" placeholder="Masukkan nama direktori"><br>
									<input type="submit" class="btn btn-danger" value="Submit" name="submit">
								</div>
							</form>
							<?php
							break;
						default:
							?>



							<table class="table table-hover">
								<thead>
									<tr>
										<th scope="col">No</th>
										<th scope="col">Nama</th>
										<th scope="col">Type</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$counter = 0;
									foreach ($daftar as $file) {
										?>
										<tr>
											<th scope="row"><?php
															echo ++$counter;
															?></th>
											<td><?php
												echo $file;
												?></td>
											<td><?php
												echo is_dir($target_dir . $file) ? 'folder' : 'file';
												?></td>
											<td><a href="?delete=<?php
																	echo $file;
																	?>">Delete</a></td>
										</tr>
									<?php

								}
								?>


								</tbody>
							</table>
						<?php
				}
				?>

					</div>
				</div>
			<?php } else { ?>
				<h3>Masuk</h3>
				<form action="" method="post">
					<label for="user">Username</label>
					<input type="text" class="form-control" name="user" id="user" value="<?php if (!empty($_COOKIE['user'])) {
																								echo $_COOKIE['user'];
																							} ?>"> <br />
					<label for="password">Password</label>
					<input type="password" class="form-control" name="password" id="password"> <br />
					<input type="submit" class="btn btn-primary" value="Kirim">
				</form>
			<?php } ?>
		</div>
	</section>

	<!-- Bootstrap core JavaScript -->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Plugin JavaScript -->
	<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

	<!-- Custom JavaScript for this theme -->
	<script src="js/scrolling-nav.js"></script>

</body>

</html>