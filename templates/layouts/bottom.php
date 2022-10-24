<footer class="footer">
        <div class="container-fluid">
            <nav class="pull-left">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="https://asahankab.go.id">
                            Kabupaten Asahan
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="copyright ml-auto">
                Copyright &copy; 2022
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
	<script src="<?=asset('assets/js/plugin/datatables-pagingtype/full_numbers_no_ellipses.js')?>"></script>
	<script>
		window.anak = []
		<?php if(isset($_GET['table']) && $_GET['table'] == 'penduduk'): ?>
		$('.datatable').dataTable({
			stateSave:true,
			pagingType: 'full_numbers_no_ellipses',
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
			pagingType: 'full_numbers_no_ellipses',
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

		function sortTable() {
			var table, rows, switching, i, x, y, shouldSwitch;
			table = document.querySelector(".tableFixHead");
			switching = true;
			/*Make a loop that will continue until
			no switching has been done:*/
			while (switching) {
				//start by saying: no switching is done:
				switching = false;
				rows = table.rows;
				/*Loop through all table rows (except the
				first, which contains table headers):*/
				for (i = 1; i < (rows.length - 2); i++) {
					//start by saying there should be no switching:
					shouldSwitch = false;
					/*Get the two elements you want to compare,
					one from current row and one from the next:*/
					x = rows[i].getElementsByTagName("TD")[rows[i].getElementsByTagName("TD").length - 1];
					y = rows[i + 1].getElementsByTagName("TD")[rows[i+1].getElementsByTagName("TD").length - 1];
					var xn = x.innerHTML.replace('%','')
					var yn = y.innerHTML.replace('%','')
					//check if the two rows should switch place:
					if (parseFloat(xn) > parseFloat(yn)) {
						//if so, mark as a switch and break the loop:
						shouldSwitch = true;
						break;
					}
				}
				if (shouldSwitch) {
					/*If a switch has been marked, make the switch
					and mark that a switch has been done:*/
					rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
					switching = true;
				}
			}
		}

		<?php if(get_route() == 'rekapitulasi/index'): ?>
		sortTable();
		<?php endif ?>
	</script>
</body>
</html>