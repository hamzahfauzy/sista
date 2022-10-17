<!doctype html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title><?=app('name')?></title>
      <link rel="icon" href="<?=asset('assets/img/main-logo.png')?>" sizes="32x32" />
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,700i,800,800i,900,900i" rel="stylesheet">
      <link href="<?=asset('assets/css/style.css')?>" rel="stylesheet">
   </head>
   <body>
        <div class="preloader" id="preloader">
	        <div id="loader"></div>
	    </div>
        <div class="content-wrap">
            <div class="logo-box">
                <img src="<?=asset('assets/img/main-logo.png')?>">
            </div>
            <div class="cta-box">
                <h1>Indeks Keluarga <span class="highlight">Sehat</span></h1>
                <p>Masukkan NIK anda untuk mengetahui riwayat indeks keluarga sehat</p>
            </div>
            <form action="" class="newsletter" name="nik">
                <input type="text" class="form-field" name="nik" placeholder="Masukkan NIK Anda" value="<?=$nik?>">
                <button type="submit" class="btn-main">Cari</button>
            </form>
            <?php if($nik && $data): ?>
            <div class="result success">
                <p>Data riwayat indeks keluarga sehat ditemukan. Klik <a href="<?=routeTo('default/riwayat',['nik'=>$nik])?>">disini</a> untuk melihat</p>
            </div>
            <?php endif ?>
            <?php if($nik && !$data): ?>
            <div class="result fail">
                <p>Data riwayat indeks keluarga sehat tidak ditemukan.</p>
            </div>
            <?php endif ?>
            <p style="color: white; margin-top: 20px;">Surveyor <a href="<?=routeTo('auth/login')?>" style="font-weight: bold; color: #31ce36;">Login Disini</a> </p> 
        </div>
        <script src="<?=asset('assets/js/main.js')?>"></script>
   </body>
</html>