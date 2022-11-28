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
    <link rel="stylesheet" href="<?=asset('assets/css/gallery-grid.css')?>">
</head>
<body>
    <div class="container">
        <center>
            <img src="<?=asset('assets/img/main-logo.png')?>" alt="" width="100px">
            <h2>DATA RIWAYAT INDEKS KELUARGA SEHAT (<?=$penduduk->nama?>)</h2>
            <a href="<?=routeTo('')?>">Kembali ke halaman awal</a> | <a href="<?=routeTo('default/riwayat',['nik'=>$penduduk->NIK,'page'=>'timeline'])?>">Timeline</a> | <a href="<?=routeTo('default/riwayat',['nik'=>$penduduk->NIK,'page'=>'form-survey'])?>">Form Survey Mandiri</a> | <a href="<?=routeTo('auth/logout')?>">Logout</a>
            <p></p>
        </center>

        <?php if(isset($_GET['page'])) : ?>
        <div class="row">
            <div class="col-12">
                <?php require $_GET['page'].'.php'; ?>
            </div>
        </div>
        <?php else: ?>
        <table class="table table-bordered table-striped">
            <tr>
                <th>ID</th>
                <th>Tanggal Survey</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php if(empty($data)): ?>
            <tr>
                <td colspan="4"><i>Tidak ada data</i></td>
            </tr>
            <?php endif ?>
            <?php foreach($data as $d): ?>
            <tr>
                <td><?=$d->no_kk?></td>
                <td><?=$d->tanggal?></td>
                <?php if($d->status == 'publish'): ?>
                <td style="background-color:<?=$d->kategori->warna?>"><?=$d->kategori->nama?></td>
                <?php else: ?>
                <td><?=$d->status?></td>
                <?php endif ?>
                <td><a href="<?=routeTo('default/download',['nik'=>auth()->user->username,'id'=>$d->no_kk])?>" class="btn btn-success"><i class="fas fa-download fa-fw"></i> Download</a></td>
            </tr>
            <?php endforeach ?>
        </table>

        <div class="row">
            <div class="col-12">
                <div class="filter">
                    <form action="">
                        <input type="hidden" name="nik" value="<?=$nik?>">
                        <div class="d-flex">
                            <?php 
                            $t = isset($_GET['tahun']) ? $_GET['tahun'] : ''; 
                            $k = isset($_GET['kecamatan_id']) ? $_GET['kecamatan_id'] : ''; 
                            $kel = isset($_GET['kelurahan_id']) ? $_GET['kelurahan_id'] : ''; 
                            $ling = isset($_GET['lingkungan_id']) ? $_GET['lingkungan_id'] : ''; 
                            ?>
                            <select name="tahun" id="" class="form-control">
                                <option value="">- Pilih Tahun -</option>
                                <?php for($i=2030;$i>=2010;$i--): ?>
                                <option <?=$t==$i ? 'selected=""' : '' ?>><?=$i?></option>
                                <?php endfor ?>
                            </select>
                            &nbsp;
                            <select name="kecamatan_id" id="kecamatan_id" class="form-control" onchange="handleKecamatan(this)" required>
                                <option value="">- Pilih Kecamatan -</option>
                                <option value="*" <?=$k == '*' ? 'selected=""' : '' ?>>Semua Kecamatan</option>
                                <?php foreach($kecamatan as $kec): ?>
                                <option value="<?=$kec->id?>" <?=$k == $kec->id ? 'selected=""' : '' ?>><?=$kec->nama?></option>
                                <?php endforeach ?>
                            </select>
                            &nbsp;
                            <select name="kelurahan_id" id="kelurahan_id" class="form-control" onchange="handleKelurahan(this)" required>
                                <option value="">- Pilih Desa / Kelurahan -</option>
                                <option value="*">Semua Desa / Kelurahan</option>
                            </select>
                            &nbsp;
                            <select name="lingkungan_id" id="lingkungan_id" class="form-control" required>
                                <option value="">- Pilih Dusun / Lingkungan -</option>
                                <option value="*">Semua Dusun / Lingkungan</option>
                            </select>
                            &nbsp;
                            <button class="btn btn-success" name="tampil">Tampilkan</button>
                        </div>
                    </form>
                    <p></p>
                    <div class="content"><?=$content?></div>
                </div>
            </div>
        </div>
        <?php endif ?>
    </div>
    <script src="<?=asset('assets/js/gallery-grid.js')?>"></script>
    <script>
    var kecamatan = e => {
        return document.querySelector('#kecamatan_id')
    }
    
    var kelurahan = e => {
        return document.querySelector('#kelurahan_id')
    } 
    
    var lingkungan = e => { 
        return document.querySelector('#lingkungan_id')
    }

    function handleKecamatan(el)
    {
        kelurahan().selectedIndex = 0
        lingkungan().selectedIndex = 0

        if(el.value == "*")
        {
            kelurahan().disabled = true
            lingkungan().disabled = true
        }
        else
        {
            kelurahan().disabled = false
            lingkungan().disabled = false

            loadKelurahan(el.value)
        }
    }

    function handleKelurahan(el)
    {
        lingkungan().selectedIndex = 0

        if(el.value == "*")
        {
            lingkungan().disabled = true
        }
        else
        {
            lingkungan().disabled = false

            loadLingkungan(el.value)
        }
    }

    function loadKelurahan(kec_id)
    {
        fetch('<?=routeTo('api/referensi/kelurahan')?>?kecamatan_id='+kec_id)
        .then(res => res.json())
        .then(res => {
            var kel = kelurahan()
            var kel_id = "<?=isset($kel)?$kel:0?>";
            kel.innerHTML = `
            <option value="">- Pilih Desa / Kelurahan -</option>
            <option value="*" ${kel_id=='*'?'selected=""':''}>Semua Desa / Kelurahan</option>`

            res.data.forEach(data => {
                kel.innerHTML += `<option value="${data.id}" ${kel_id==data.id?'selected=""':''}>${data.nama}</option>`
            })

            <?php if(isset($kel) && $kel!='*'): ?>
            loadLingkungan(<?=$kel?>);
            <?php endif ?>
        })
    }

    function loadLingkungan(kel_id)
    {
        fetch('<?=routeTo('api/referensi/lingkungan')?>?kelurahan_id='+kel_id)
        .then(res => res.json())
        .then(res => {
            var ling = lingkungan()
            var ling_id = "<?=isset($ling)?$ling:0?>";
            ling.innerHTML = `
            <option value="">- Pilih Dusun / Lingkungan -</option>
            <option value="*" ${ling_id=='*'?'selected=""':''}>Semua Dusun / Lingkungan</option>`

            res.data.forEach(data => {
                ling.innerHTML += `<option value="${data.id}" ${ling_id==data.id?'selected=""':''}>${data.nama}</option>`
            })
        })
    }

    <?php if(isset($k)): ?>
    handleKecamatan(kecamatan());
    <?php endif ?>

    <?php if(isset($kel) && $kel == '*'): ?>
    lingkungan().disabled = true
    <?php endif ?>
    </script>
</body>
</html>