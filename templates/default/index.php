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
        <div class="inner-dashboard">Sedang memuat data...</div>
    </div>
    <script>
        setTimeout(() => {
            fetch('<?=routeTo('api/default/index')?>')
            .then(res => res.text())
            .then(res => {
                document.querySelector(".inner-dashbord").innerHTML = res
            })
        }, 1000);
    </script>
<?php load_templates('layouts/bottom') ?>