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
    <style>
    .select2 option {
        display: none;
    }
    </style>
	<!--   Core JS Files   -->
	<script src="<?=asset('assets/js/core/jquery.3.2.1.min.js')?>"></script>
	<script src="<?=asset('assets/js/core/popper.min.js')?>"></script>
	<script src="<?=asset('assets/js/core/bootstrap.min.js')?>"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
	<?php if(startWith(get_route(),'timeline/')): ?>
	<script src="<?=asset('assets/js/gallery-grid.js')?>"></script>
	<?php endif ?>
	<!-- <script src="<?=asset('assets/js/demo.js')?>"></script> -->
	<script src="<?=asset('assets/js/plugin/datatables-pagingtype/full_numbers_no_ellipses.js')?>"></script>
	<script src="https://cdn.tiny.cloud/1/rsb9a1wqmvtlmij61ssaqj3ttq18xdwmyt7jg23sg1ion6kn/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
	<script>
		$('.select2-bulan').select2({
			placeholder: {
				id: 'Pilih', // the value of the option
				text: 'Pilih'
			}
		});
		// $('.select2-multiple').select2({
		// 	placeholder: {
		// 		id: 'Pilih', // the value of the option
		// 		text: 'Pilih'
		// 	}
		// });
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

		function targetPenduduk(name)
		{
			var tanggal = document.querySelector('#tanggal_pelaksanaan_survey')
			if(tanggal.value)
			{
				$('#exampleModal').modal('show')
				window.target_input = name
				$('.datatable-penduduk').dataTable({
					processing: true,
					search: {
						return: true
					},
					serverSide: true,
					ajax: "<?=routeTo('api/penduduk/pilih-penduduk')?>?tanggal="+tanggal.value
				})
			}
			else
			{
				alert('Input Tanggal Survey Terlebih Dahulu');
			}
		}

		function targetAnak()
		{
			var tanggal = document.querySelector('#tanggal_pelaksanaan_survey')
			if(tanggal.value)
			{
				$('#exampleModal1').modal('show')
				$('.datatable-anak').dataTable({
					processing: true,
					search: {
						return: true
					},
					serverSide: true,
					ajax: "<?=routeTo('api/penduduk/pilih-anak')?>?tanggal="+tanggal.value,
					drawCallback: function( settings ) {
						var anak = window.anak
						anak.forEach(an => {
							if(document.querySelector("#NIK-"+an))
								document.querySelector("#NIK-"+an).checked = true
						})
					}
				})
			}
			else
			{
				alert('Input Tanggal Survey Terlebih Dahulu');
			}
		}

		function loadAnak()
		{
			$('#exampleModal').modal('show')
			if ($.fn.DataTable.isDataTable( '.datatable-anak' ) ) {
				return
			}
			$('.datatable-anak').dataTable({
				processing: true,
				search: {
					return: true
				},
				serverSide: true,
				ajax: "<?=routeTo('api/imunisasi/load-anak',$_GET)?>"
			})
		}

		function loadAnakBian()
		{
			$('#exampleModal').modal('show')
			if ($.fn.DataTable.isDataTable( '.datatable-anak' ) ) {
				return
			}
			$('.datatable-anak').dataTable({
				processing: true,
				search: {
					return: true
				},
				serverSide: true,
				ajax: "<?=routeTo('api/bian/load-anak',$_GET)?>"
			})
		}
		
		function loadAnakBias()
		{
			$('#exampleModal').modal('show')
			if ($.fn.DataTable.isDataTable( '.datatable-anak' ) ) {
				return
			}
			$('.datatable-anak').dataTable({
				processing: true,
				search: {
					return: true
				},
				serverSide: true,
				ajax: "<?=routeTo('api/bias/load-anak',$_GET)?>"
			})
		}

		function loadAnakPemantauan()
		{
			$('#exampleModal').modal('show')
			if ($.fn.DataTable.isDataTable( '.datatable-anak' ) ) {
				return
			}
			$('.datatable-anak').dataTable({
				processing: true,
				search: {
					return: true
				},
				serverSide: true,
				ajax: "<?=routeTo('api/pemantauan-gizi/load-anak',$_GET)?>"
			})
		}

		function loadPendudukKb()
		{
			$('#exampleModal').modal('show')
			if ($.fn.DataTable.isDataTable( '.datatable-penduduk' ) ) {
				return
			}
			$('.datatable-penduduk').dataTable({
				processing: true,
				search: {
					return: true
				},
				serverSide: true,
				ajax: "<?=routeTo('api/kb/load-penduduk',$_GET)?>"
			})
		}

		function loadPendudukLansia()
		{
			$('#exampleModal').modal('show')
			if ($.fn.DataTable.isDataTable( '.datatable-penduduk' ) ) {
				return
			}
			$('.datatable-penduduk').dataTable({
				processing: true,
				search: {
					return: true
				},
				serverSide: true,
				ajax: "<?=routeTo('api/posyandu-lansia/load-penduduk',$_GET)?>"
			})
		}

		function loadPendudukIbuHamil()
		{
			$('#exampleModal').modal('show')
			if ($.fn.DataTable.isDataTable( '.datatable-penduduk' ) ) {
				return
			}
			$('.datatable-penduduk').dataTable({
				processing: true,
				search: {
					return: true
				},
				serverSide: true,
				ajax: "<?=routeTo('api/ibu-hamil/load-penduduk',$_GET)?>"
			})
		}

		function loadJenisVaksin(data)
		{
			$('#modalJenisVaksin').modal('show')
			var html = '<table class="table table-bordered">'
			for(vaksin in data)
			{
				console.log(vaksin)
				// var vaksin = data[i]
				html += `<tr><td>${vaksin}</td><td>`
				for(v in data[vaksin])
				{
					console.log(v)
					html += `<label class="mr-3"><input type="checkbox" checked disabled> ${data[vaksin][v]}</label>`
				}
				html += `</td></tr>`
			}
			html += `</table>`
			$(".jenis_vaksin_content").html(html)
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
					x = rows[i].getElementsByTagName("TD")[rows[i].getElementsByTagName("TD").length - 2];
					y = rows[i + 1].getElementsByTagName("TD")[rows[i+1].getElementsByTagName("TD").length - 2];
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
		var rekapPenduduk = $('.rekap-penduduk').DataTable({
			processing: true,
			search: {
				return: true
			},
			serverSide: true,
			ajax:'<?=routeTo('rekapitulasi/penduduk')?>?tampil=true&tahun=<?=date('Y')?>'
		});

		function loadRekapPenduduk(form)
		{
			event.preventDefault();
			const formData = new FormData(form);
			const params = new URLSearchParams(formData);
			rekapPenduduk.ajax.url("<?=routeTo('rekapitulasi/penduduk')?>?tampil=true&"+params.toString()).load();

			return false;
		}

		<?php if(get_route() == 'rekapitulasi/index'): ?>
		sortTable();
		<?php endif ?>

		<?php if(in_array(get_route(),['crud/create','crud/edit']) && isset($_GET['table']) && in_array($_GET['table'],['posyandu','penduduk'])): ?>
		document.querySelector('[name="<?=$_GET['table']?>[kecamatan_id]"]').onchange = e => {
			var value = document.querySelector('[name="<?=$_GET['table']?>[kecamatan_id]"]').value
			document.querySelector('[name="<?=$_GET['table']?>[kelurahan_id]"]').querySelectorAll('option').forEach(opt => { opt.style.display = 'none' })
			document.querySelector('[name="<?=$_GET['table']?>[kelurahan_id]"]').querySelectorAll('.kec-'+value).forEach(opt => { opt.style.display = 'block' })
		}
		
		document.querySelector('[name="<?=$_GET['table']?>[kelurahan_id]"]').onchange = e => {
			var value = document.querySelector('[name="<?=$_GET['table']?>[kelurahan_id]"]').value
			document.querySelector('[name="<?=$_GET['table']?>[lingkungan_id]"]').querySelectorAll('option').forEach(opt => { opt.style.display = 'none' })
			document.querySelector('[name="<?=$_GET['table']?>[lingkungan_id]"]').querySelectorAll('.kel-'+value).forEach(opt => { opt.style.display = 'block' })
		}
		<?php endif ?>

		<?php if(in_array(get_route(),['crud/create','crud/edit']) && isset($_GET['table']) && $_GET['table'] == 'petugas'): ?>
		document.querySelector('[name="petugas[kecamatan_id]"]').onchange = e => {
			var value = document.querySelector('[name="petugas[kecamatan_id]"]').value
			document.querySelector('[name="petugas[kelurahan_id]"]').querySelectorAll('option').forEach(opt => { opt.style.display = 'none' })
			document.querySelector('[name="petugas[kelurahan_id]"]').querySelectorAll('.kec-'+value).forEach(opt => { opt.style.display = 'block' })
		}
		
		<?php endif ?>

		<?php if(get_route() == 'feedbacks/create'): ?>
		if(document.querySelector('[name="feedbacks[kecamatan_id]"]'))
		document.querySelector('[name="feedbacks[kecamatan_id]"]').onchange = e => {
			var value = document.querySelector('[name="feedbacks[kecamatan_id]"]').value
			document.querySelector('[name="feedbacks[kelurahan_id]"]').querySelectorAll('option').forEach(opt => { opt.style.display = 'none' })
			document.querySelector('[name="feedbacks[kelurahan_id]"]').querySelectorAll('.kec-'+value).forEach(opt => { opt.style.display = 'block' })
		}
		
		if(document.querySelector('[name="feedbacks[kelurahan_id]"]'))
		document.querySelector('[name="feedbacks[kelurahan_id]"]').onchange = e => {
			var value = document.querySelector('[name="feedbacks[kelurahan_id]"]').value
			document.querySelector('[name="feedbacks[lingkungan_id]"]').querySelectorAll('option').forEach(opt => { opt.style.display = 'none' })
			document.querySelector('[name="feedbacks[lingkungan_id]"]').querySelectorAll('.kel-'+value).forEach(opt => { opt.style.display = 'block' })
		}

		if(document.querySelector('[name="feedbacks[clause_dest]"]'))
		document.querySelector('[name="feedbacks[clause_dest]"]').onchange = async e => {
			var value = e.target.value

			if(value != 'Pilih')
			{
			    $('.select2').html('').select2({data: [{id: '', text: ''}]});
			    if(value == 'Semua Pembina')
			    {
			        $('.select2').prop("disabled", true)
			     //   document.querySelector('[name="feedbacks[clause_dest_item]"]').setAttribute('readonly','readonly')
			    }
			    else
			    {
			        $('.select2').prop("disabled", false)
			        var request = await fetch("<?=routeTo('api/referensi/get-user-by-role')?>?role="+value)
    				if(request.ok)
    				{
    				    var allOption = new Option('Semua', 'Semua', true, true);
                        $('.select2').append(allOption)
                        
    					var response = await request.json()
        				for(var i=0;i<response.data.length;i++)
        				{
        				    var d = response.data[i]
                            var newOption = new Option(d.name, d.id, false, false);
                            $('.select2').append(newOption);
    					}
                        
                        $('.select2').val('Semua').trigger('change')
    				}
			    }
			}
			
		}
		
		$( ".select2" ).select2({
		    placeholder: '- Pilih -',
		    allowClear: true
		});
		
		$('.select2').on('select2:select', function (e) {
            var data = e.params.data
            console.log(data);
            if(data.id == 'Semua')
            {
                $('.select2').val(null).trigger('change');
                $('.select2').val('Semua').trigger('change')
            }
            else
            {
                var wanted_option = $('.select2 option[value="Semua"]');
                wanted_option.prop('selected', false);
                $('.select2').trigger('change.select2');
            }
        });
		<?php endif ?>

		// tinymce.init({
		// 	selector: '.tinymce',
			// images_upload_url: '<?=routeTo('crud/upload')?>',
			// document_base_url: '<?=routeTo()?>',
			// relative_urls: false,
			// remove_script_host: false,
			// plugins: [
			// 'advlist','autolink',
			// 'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
			// 'fullscreen','insertdatetime','media','table','help','wordcount'
			// ],
			// toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
			// 'alignleft aligncenter alignright alignjustify | ' +
			// 'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
		// });

		function initResponseButton()
		{
			if(document.querySelector('.response-btn'))
			{
				document.querySelectorAll('.response-btn').forEach(el => {
					el.onclick = ev => {
						// alert(ev.target)
						var responseType = el.dataset.type == 'like' ? '.dislike' : '.like'
						var parent = el.parentElement
						parent.querySelector(responseType).classList.remove('active')
						el.classList.toggle('active')
	
						// post to database
						var formData = new FormData
						formData.append('post_id',el.dataset.id)
						formData.append('response_type',el.dataset.type)
						fetch('<?=routeTo('timeline/post-response')?>',{
							method:'POST',
							body:formData
						})
						.then(res => res.json())
						.then(res => {
							parent.querySelector('.like').innerHTML = '<i class="fas fa-fw fa-thumbs-up"></i> Suka ('+res.data.post_like_count+')'
							parent.querySelector('.dislike').innerHTML = '<i class="fas fa-fw fa-thumbs-down"></i> Tidak Suka ('+res.data.post_dislike_count+')'
						})
					}
				})
			}
		}

		var DateDiff = {
 
			inDays: function(d1, d2) {
				var t2 = d2.getTime();
				var t1 = d1.getTime();

				return Math.floor((t2-t1)/(24*3600*1000));
			},

			inWeeks: function(d1, d2) {
				var t2 = d2.getTime();
				var t1 = d1.getTime();

				return parseInt((t2-t1)/(24*3600*1000*7));
			},

			inMonths: function(d1, d2) {
				var d1Y = d1.getFullYear();
				var d2Y = d2.getFullYear();
				var d1M = d1.getMonth();
				var d2M = d2.getMonth();

				return (d2M+12*d2Y)-(d1M+12*d1Y);
			},

			inYears: function(d1, d2) {
				return d2.getFullYear()-d1.getFullYear();
			}
			}

		$('[name="ibu_hamil[hpht]"]').change(e => {
			var d1 = new Date(document.querySelector('[name="ibu_hamil[hpht]"]').value);
			var d2 = new Date();

			$('[name="ibu_hamil[usia_kandungan]"]').val(DateDiff.inDays(d1,d2) + ' Hari')
		})

	</script>
</body>
</html>