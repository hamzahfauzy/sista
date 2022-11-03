<?php if(isset($_GET['print'])): ?>
<style>
table {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

table td, table th {
  border: 1px solid #ddd;
  padding: 8px;
}

table tr:nth-child(even){background-color: #f2f2f2;}

table tr:hover {background-color: #ddd;}

table th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
.card-title {
    text-align:center;
    font-weight:bold;
    font-size:20px;
    margin-top:10px;
    margin-bottom:10px;
    display:block;
}
.card-title a {
    text-decoration:none;
    color:#000;
}
</style>
<script>window.print()</script>
<?php endif ?>
<?php if(!isset($_GET['print'])): ?>
<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">PAK SURYA-TAUFIK ASAHAN (PASTA)</h2>
                        <h5 class="text-white op-7 mb-2">Program Aplikasi Survey Pelayanan dan Pemantauan Fase Indeks Kesehatan di Kabupaten Asahan</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row">
                <div class="col-12">
                    <div class="card full-height">
                        <div class="card-body">
                            <div class="card-title">REKAPITULASI MASALAH KESEHATAN BERDASARKAN IKS</div>

                            <div class="filter">
                                <form action="">
                                    <div class="d-flex">
                                        <?php 
                                        $t = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); 
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
                                            <?php foreach($kecamatan as $kec): ?>
                                            <option value="<?=$kec->id?>" <?=$k == $kec->id ? 'selected=""' : '' ?>><?=$kec->nama?></option>
                                            <?php endforeach ?>
                                        </select>
                                        &nbsp;
                                        <select name="kelurahan_id" id="kelurahan_id" class="form-control" onchange="handleKelurahan(this)" required>
                                            <option value="">- Pilih Desa / Kelurahan -</option>
                                        </select>
                                        &nbsp;
                                        <select name="lingkungan_id" id="lingkungan_id" class="form-control" required>
                                            <option value="">- Pilih Dusun / Lingkungan -</option>
                                            <option value="*">Semua Dusun / Lingkungan</option>
                                        </select>
                                        &nbsp;
                                        <button class="btn btn-success" name="tampil">Tampilkan</button>
                                        <?php if($k && $k!='*'): ?>
                                        &nbsp;
                                        <button class="btn btn-success" name="print">Cetak</button>
                                        <?php endif ?>
                                    </div>
                                </form>
                                <p></p>
                                <?php endif ?>
                                <div class="content"><?=$content?></div>
                                <?php if(!isset($_GET['print'])): ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            var kel_id = "<?=$kel?$kel:0?>";
            kel.innerHTML = `
            <option value="">- Pilih Desa / Kelurahan -</option>
            <option value="*" ${kel_id=='*'?'selected=""':''}>Semua Desa / Kelurahan</option>`

            res.data.forEach(data => {
                kel.innerHTML += `<option value="${data.id}" ${kel_id==data.id?'selected=""':''}>${data.nama}</option>`
            })

            <?php if($kel && $kel!='*'): ?>
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
            var ling_id = "<?=$ling?$ling:0?>";
            ling.innerHTML = `
            <option value="">- Pilih Dusun / Lingkungan -</option>
            <option value="*" ${ling_id=='*'?'selected=""':''}>Semua Dusun / Lingkungan</option>`

            res.data.forEach(data => {
                ling.innerHTML += `<option value="${data.id}" ${ling_id==data.id?'selected=""':''}>${data.nama}</option>`
            })
        })
    }

    <?php if($k): ?>
    handleKecamatan(kecamatan());
    <?php endif ?>

    <?php if($kel && $kel == '*'): ?>
    lingkungan().disabled = true
    <?php endif ?>
    </script>
<?php load_templates('layouts/bottom') ?>
<?php endif ?>