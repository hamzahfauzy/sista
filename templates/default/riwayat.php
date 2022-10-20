<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=asset('assets/img/main-logo.png')?>" sizes="32x32" />
    <title>Data Riwayat Indeks Keluarga Sehat | <?=app('name')?></title>
    <script src="<?=asset('assets/js/plugin/webfont/webfont.min.js')?>"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['<?=asset('assets/css/fonts.min.css')?>']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="<?=asset('assets/css/bootstrap.min.css')?>">
	<link rel="stylesheet" href="<?=asset('assets/css/atlantis.min.css')?>">

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="<?=asset('assets/css/demo.css')?>">
</head>
<body>
    <div class="container">
        <center>
            <img src="<?=asset('assets/img/main-logo.png')?>" alt="" width="100px">
            <h2>DATA RIWAYAT INDEKS KELUARGA SEHAT (<?=$penduduk->nama?>)</h2>
            <a href="<?=routeTo('')?>">Kembali ke halaman awal</a>
            <p></p>
        </center>

        <table class="table table-bordered table-striped">
            <tr>
                <th>ID</th>
                <th>Tanggal Survey</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($data as $d): ?>
            <tr>
                <td><?=$d->no_kk?></td>
                <td><?=$d->tanggal?></td>
                <td style="background-color:<?=$d->kategori->warna?>"><?=$d->kategori->nama?></td>
                <td><a href="<?=routeTo('default/download',['id'=>$d->no_kk])?>" class="btn btn-success"><i class="fas fa-download fa-fw"></i> Download</a></td>
            </tr>
            <?php endforeach ?>
        </table>
    </div>
</body>
</html>