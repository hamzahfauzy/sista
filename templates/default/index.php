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
        <div class="inner-dashboard"><span class="p-4">Sedang memuat data...</span></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const controller = new AbortController();
        // Make a request for a user with a given ID
        setTimeout(() => {
            axios.get('<?=routeTo('api/default/index')?>',{
               signal: controller.signal
            }).then(res => {
                document.querySelector(".inner-dashboard").innerHTML = res.data
            })
        }, 1000);

        window.onbeforeunload  = e => {
            controller.abort()
        }
    </script>
<?php load_templates('layouts/bottom') ?>