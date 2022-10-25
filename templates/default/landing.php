<!doctype html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title><?=app('name')?></title>
      <link rel="icon" href="<?=asset('assets/img/main-logo.png')?>" sizes="32x32" />
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,700i,800,800i,900,900i" rel="stylesheet">
      <link href="<?=asset('assets/css/style.css')?>" rel="stylesheet">
      <style>
        a {
            text-decoration:none;
        }
      </style>
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
            </div>
            <?php if($success_msg): ?>
            <div class="result success">
                <p><?=$success_msg?></p>
            </div>
            <?php endif ?>
            <form action="<?=routeTo('auth/login')?>" class="newsletter" name="nik" method="post">
                <label for="">NIK</label>
                <input type="tel" class="form-field" name="username" placeholder="Masukkan NIK Anda">
                <label for="">Kata Sandi</label>
                <input type="password" class="form-field" name="password" placeholder="Masukkan Password Anda">
                <button type="submit" class="btn-main">Masuk</button>
            </form>
            <?php if($nik && !$data): ?>
            <div class="result fail">
                <p>Data riwayat indeks keluarga sehat tidak ditemukan.</p>
            </div>
            <?php endif ?>
            <p style="color: white; margin-top: 20px;"><a href="<?=routeTo('auth/register')?>" style="font-weight: bold; color: #31ce36;">Daftar</a> Sebagai Penduduk atau <a href="<?=routeTo('auth/login')?>" style="font-weight: bold; color: #31ce36;">Login</a> Sebagai Admin/Surveyor</p> 
            <p style="color: white; margin-top: 10px;"><a href="<?=routeTo('auth/forget')?>" style="font-weight: bold; color: #31ce36;">Lupa Password ?</a></p> 
            <!-- <p style="color: white; margin-top: 20px;"><a href="<?=routeTo('default/rekapitulasi')?>" style="font-weight: bold; color: #31ce36;">Rekapitulasi Indeks Keluarga Sehat</a></p>  -->
        </div>
        <script src="<?=asset('assets/js/main.js')?>"></script>
   </body>
</html>