<footer class="footer">
        <div class="container-fluid">
            <nav class="pull-left">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="https://htd-official.com">
                            Hamzah Tech Development
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="copyright ml-auto">
                Copyright &copy; 2022 | made with <i class="fa fa-heart heart text-danger"></i> by <a href="https://www.htd-official.com">HTD</a>
            </div>				
        </div>
    </footer>
</div>
<!-- End Custom template -->
</div>
	<!--   Core JS Files   -->
	<script src="<?=asset('assets/js/core/jquery.3.2.1.min.js')?>"></script>
	<script src="<?=asset('assets/js/core/popper.min.js')?>"></script>
	<script src="<?=asset('assets/js/core/bootstrap.min.js')?>"></script>

	<!-- jQuery UI -->
	<script src="<?=asset('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js')?>"></script>
	<script src="<?=asset('assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js')?>"></script>

	<!-- jQuery Scrollbar -->
	<script src="<?=asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')?>"></script>


	<!-- Chart JS -->
	<script src="<?=asset('assets/js/plugin/chart.js/chart.min.js')?>"></script>

	<!-- jQuery Sparkline -->
	<script src="<?=asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js')?>"></script>

	<!-- Chart Circle -->
	<script src="<?=asset('assets/js/plugin/chart-circle/circles.min.js')?>"></script>

	<!-- Datatables -->
	<script src="<?=asset('assets/js/plugin/datatables/datatables.min.js')?>"></script>

	<!-- Bootstrap Notify -->
	<script src="<?=asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js')?>"></script>

	<!-- jQuery Vector Maps -->
	<script src="<?=asset('assets/js/plugin/jqvmap/jquery.vmap.min.js')?>"></script>
	<script src="<?=asset('assets/js/plugin/jqvmap/maps/jquery.vmap.world.js')?>"></script>

	<!-- Sweet Alert -->
	<script src="<?=asset('assets/js/plugin/sweetalert/sweetalert.min.js')?>"></script>

	<!-- Atlantis JS -->
	<script src="<?=asset('assets/js/atlantis.min.js')?>"></script>

	<!-- Atlantis DEMO methods, don't include it in your project! -->
	<script src="<?=asset('assets/js/setting-demo.js')?>"></script>
	<script src="<?=asset('assets/js/demo.js')?>"></script>
	<script>
		window.anak = []
		<?php if(isset($_GET['table']) && $_GET['table'] == 'penduduk'): ?>
		$('.datatable').dataTable({
			processing: true,
			search: {
				return: true
			},
			serverSide: true,
			ajax: "<?=routeTo('api/penduduk/index')?>"
		})
		<?php elseif(startWith(get_route(),'survey/')): ?>
		var surveydatatable = $('.datatable').DataTable({
			stateSave:true,
			processing: true,
			search: {
				return: true
			},
			serverSide: true,
			ajax: "<?=routeTo('api/survey/index')?>"
		})

		function publishData(id)
		{
			if(confirm('apakah anda yakin akan mempublish data ini ?'))
			{
				fetch("<?=routeTo('api/survey/publish')?>?id="+id).then(res => res.json()).then(res => {
					surveydatatable.ajax.reload( null, false );
				})
			}
			else
			{
				return false
			}
		}

		function deleteData(id)
		{
			if(confirm('apakah anda yakin akan menghapus data ini ?'))
			{
				fetch("<?=routeTo('api/survey/delete')?>?id="+id).then(res => res.json()).then(res => {
					surveydatatable.ajax.reload( null, false );
				})
			}
			else
			{
				return false
			}
		}
		<?php else: ?>
		$('.datatable').dataTable();
		<?php endif ?>

		$('.datatable-anak').dataTable({
			processing: true,
			search: {
				return: true
			},
			serverSide: true,
			ajax: "<?=routeTo('api/penduduk/pilih-anak')?>",
			drawCallback: function( settings ) {
				var anak = window.anak
				anak.forEach(an => {
					if(document.querySelector("#NIK-"+an))
						document.querySelector("#NIK-"+an).checked = true
				})
			}
		})

		$('.datatable-penduduk').dataTable({
			processing: true,
			search: {
				return: true
			},
			serverSide: true,
			ajax: "<?=routeTo('api/penduduk/pilih-penduduk')?>"
		})

		function targetPenduduk(name)
		{
			window.target_input = name
		}

		function pilihPenduduk(NIK)
		{
			document.querySelector('input[name=nik_'+window.target_input+']').value = NIK
			window.target_input = ''
			$('#exampleModal').modal('hide')
		}

		function appendAnak(NIK)
		{
			if(window.anak.includes(NIK))
			{
				window.anak.splice(window.anak.indexOf(NIK), 1);
			}
			else
			{
				window.anak.push(NIK)
			}
		}

		function pilihAnak()
		{
			document.querySelector('input[name=nik_anak]').value = window.anak.join(",")
			$('#exampleModal1').modal('hide')
		}
	</script>
</body>
</html>