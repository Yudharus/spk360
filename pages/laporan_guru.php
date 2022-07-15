<!-- get data penilaian -->
<?php
$id_periode = get_tahun_ajar_id();
if (isset($_GET['idta'])) {
	$id_periode = mysqli_real_escape_string($con, htmlspecialchars($_GET['idta']));
}

$nip_user = $_SESSION[md5('user')];


$sql = "SELECT * FROM jenis_kompetensi";
$q = mysqli_query($con, $sql);

while ($row = mysqli_fetch_array($q)) {
	${"b_" . $row['nama_kompetensi']} = $row['bobot_kompetensi'];
}


$sql = "SELECT * FROM penilai a JOIN penilai_detail b ON a.id_penilai = b.id_penilai WHERE a.nip = '$nip_user' ";
$q = mysqli_query($con, $sql);
$id_penilai_detail = '';
$i = 0;
$id_penilai_detail = 0;
while ($row = mysqli_fetch_array($q)) {
	if ($i == 0) {
		$id_penilai_detail .= $row['id_penilai_detail'];
	} else {
		$id_penilai_detail .= ", " . $row['id_penilai_detail'];
	}
	$i++;
}
// membedakan kriteria
$sql = "SELECT 
				tbnilai.nip_penilai,
				tbnilai.penilai,
				tbnilai.level,
				tbnilai.jabatan,
				SUM( IF(tbnilai.nama_kompetensi = 'Kepribadian', tbnilai.nilai, 0) ) AS 'Kepribadian',
				SUM( IF(tbnilai.nama_kompetensi = 'Objektif Bisnis', tbnilai.nilai, 0) ) AS 'Objektif Bisnis',
				SUM( IF(tbnilai.nama_kompetensi = 'Keterampilan', tbnilai.nilai, 0) ) AS 'Keterampilan'
			FROM 
			(SELECT 
				a.id_nilai, 
				h.nip as nip_dinilai,
				h.nama_pegawai as 'dinilai',
				e.nip as nip_penilai, 
				e.nama_pegawai as 'penilai',
				f.jabatan,
				f.level,
				c.id_kompetensi,
				c.nama_kompetensi,
				c.bobot_kompetensi,
				SUM(a.hasil_nilai) as nilai
			FROM penilaian a 
			JOIN isi_kompetensi b ON a.id_isi = b.id_isi
			JOIN jenis_kompetensi c ON b.id_kompetensi = c.id_kompetensi
			JOIN (penilai_detail d JOIN user e ON d.nip = e.nip JOIN jenis_user f ON f.id_jenis_user = e.id_jenis_user) ON d.id_penilai_detail = a.id_penilai_detail 
			JOIN (penilai g JOIN user h ON g.nip = h.nip ) ON d.id_penilai = g.id_penilai
			WHERE 
			a.id_penilai_detail IN ($id_penilai_detail) 
			AND g.id_periode = $id_periode
			GROUP BY a.id_penilai_detail, c.id_kompetensi
			ORDER BY 4) as tbnilai
			GROUP BY tbnilai.penilai";
// membedakan kriteria


//echo $sql;
$q = mysqli_query($con, $sql);
$jumlah = mysqli_num_rows($q);
$nno = 0;
echo "<br>";
$tot_arr['atasan'] = 0;
$tot_arr['guru'] = 0;
$tot_arr['sendiri'] = 0;
$tot_kepribadian = 0;
$tot_objektif = 0;
$tot_keterampilan = 0;
while ($row = mysqli_fetch_array($q)) {
	$tot = 0;
	$kp = ($row['Kepribadian']);
	$ss = ($row['Objektif Bisnis']);
	$pr = ($row['Keterampilan']);

	$tot_kepribadian += $kp;
	$tot_objektif += $ss;
	$tot_keterampilan += $pr;
	global $b_Kepribadian;
	global $b_Sosial;
	global $b_Profesional;

	/* prestasi kinerja individu */
	// $tot = ($kp + $ss + $pr);
	$tot = $kp + $ss  + $pr;

	if ($row['level'] == 2 || $row['level'] == 3) {
		$tot_arr['atasan'] += $tot;
	} else if ($row['level'] == 1 && $row['nip_penilai'] != $nip_user) {
		$tot_arr['guru'] += $tot;
	} else {
		$tot_arr['sendiri'] += $tot;
	}
}

$sql = "SELECT * FROM periode WHERE id_periode = $id_periode";
$q = mysqli_query($con, $sql);
$row = mysqli_fetch_array($q);
if ($row['setting'] != '') {
	$set = explode(";", $row['setting']);

	$set[0] = $set[0] / 100;
	$set[1] = $set[1] / 100;
	$set[2] = $set[2] / 100;
} else {
	$set[0] = 0.5;
	$set[1] = 0.3;
	$set[2] = 0.2;
}

$ak = ($tot_arr['atasan'] + $tot_arr['guru'] + $tot_arr['sendiri']) / $jumlah;

//$ak = ($tot_arr['atasan']*0.5) + ($tot_arr['guru']*0.3) + ($tot_arr['sendiri']*0.2);		
?>
<script>
	<?php
	echo "var data_bar = [";
	echo "{oleh: 'Atasan', nilai: $tot_arr[atasan] },";
	echo "{oleh: 'Rekan Kerja', nilai: $tot_arr[guru] },";
	echo "{oleh: 'Diri Sendiri', nilai: $tot_arr[sendiri] }";
	echo "];";


	echo "var data_kompetensi = [";
	echo "{oleh: 'Kepribadian', nilai: " . number_format($tot_kepribadian, 2) . " },";
	echo "{oleh: 'Sosial', nilai: " . number_format($tot_objektif, 2) . " },";
	echo "{oleh: 'Profesional', nilai: " . number_format($tot_keterampilan, 2) . " }";
	echo "];";
	?>
</script>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-print"></i> Data Laporan</h1>

	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-cube"></i> Rentang Nilai</button>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle"><i class="fa fa-eye"></i> Rentang Nilai Akhir dan Keterangan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered">
					<thead class="bg-info text-white">
						<tr align="center">
							<th scope="col" width="5%">No</th>
							<th scope="col">Rentang Nilai</th>
							<th scope="col">Keterangan</th>
						</tr>
					</thead>
					<tbody align="center">
						<tr>
							<td scope="row">1</td>
							<td>9.6 - 10</td>
							<td>(A) Outstanding</td>
						</tr>
						<tr>
							<td scope="row">2</td>
							<td>7.6 – 9.5</td>
							<td>(B) Exceed Expectation</td>
						</tr>
						<tr>
							<td scope="row">3</td>
							<td>5.6 – 7.5</td>
							<td>(C) Meets Expectation</td>
						</tr>
						<tr>
							<td scope="row">4</td>
							<td>3.6 – 5.5</td>
							<td>(D) Needs Improvement</td>
						</tr>
						<tr>
							<td scope="row">5</td>
							<td>0 – 3.5</td>
							<td>(E) Problematic</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="card shadow mb-4">
			<!-- /.card-header -->

			<div class="card-body">
				<div class="d-flex justify-content-start align-items-center mt-2 mb-5 mx-1">
					<h4 class="m-0 font-weight-bold">Pilih Daftar Periode</h6>
				</div>
				<form action="index.php?p=laporanpen" method="get" id="frm_ta">
					<div class="form-group">
						<select class="form-control cb_periode" name="idta">
							<?php
							$sql = "SELECT * FROM periode";
							$q = mysqli_query($con, $sql);
							while ($row = mysqli_fetch_array($q)) {
								$sel = '';
								if (isset($_GET['idta'])) {
									if ($_GET['idta'] == $row['id_periode']) {
										$sel = 'selected';
									}
								} else {
									if ($row['status_periode'] == 1) {
										$sel = 'selected';
									}
								}
								if ($row['status_periode'] == 1) {
									echo "<option value='$row[id_periode]' $sel >$row[tahun_ajar] (Aktif)</option>";
								} else {
									echo "<option value='$row[id_periode]' $sel >$row[tahun_ajar]</option>";
								}
							}
							?>
						</select>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card shadow mb-4">
			<!-- /.card-header -->

			<div class="card-body">
				<div class="d-flex justify-content-start align-items-center mt-2 mb-5 mx-1">
					<h4 class="m-0 font-weight-bold">Nilai Akhir</h6>
				</div>
				<div id="chart-nilai-akhir"></div>
			</div>
		</div>
	</div>

	<!-- <div class="col-md-6">
		<div class="card shadow mb-4">

			<div class="card-body">
				<div class="d-flex justify-content-start align-items-center mt-2 mb-5 mx-1">
					<h4 class="m-0 font-weight-bold">Nilai Perwakilan</h6>
				</div>
				<div id="chart-nilai-perwakilan"></div>
			</div>
		</div>
	</div> -->
	<div class="col-md-6">
		<div class="card shadow mb-4">
			<!-- /.card-header -->

			<div class="card-body">
				<div class="d-flex justify-content-start align-items-center mt-2 mb-5 mx-1">
					<h4 class="m-0 font-weight-bold">Nilai Perkompetensi</h6>
				</div>
				<div id="chart-nilai-perkompetensi"></div>
			</div>
		</div>
	</div>
</div>

<!-- tampil grafik -->
<script type="text/javascript">
	var size = $("#chart-nilai-akhir").width() / 2; //150,
	thickness = 60;

	//console.log(size);
	var color = d3.scaleLinear()
		.domain([0, 5.5, 7.5, 9.5, 10])
		.range(['#db4639', '#FFCD42', '#48ba17', '#12ab24', '#0f9f59']);

	var arc = d3.arc()
		.innerRadius(size - thickness)
		.outerRadius(size)
		.startAngle(-Math.PI / 2);

	var svg = d3.select('#chart-nilai-akhir').append('svg')
		.attr('width', size * 2)
		.attr('height', size + 20)
		.attr('class', 'gauge');


	var chart = svg.append('g')
		.attr('transform', 'translate(' + size + ',' + size + ')')

	var background = chart.append('path')
		.datum({
			endAngle: Math.PI / 2
		})
		.attr('class', 'background')
		.style('fill', '#ddd')
		.attr('d', arc);

	var foreground = chart.append('path')
		.datum({
			endAngle: -Math.PI / 2
		})
		.style('fill', '#db2828')
		.attr('d', arc);

	var value = svg.append('g')
		.attr('transform', 'translate(' + size + ',' + (size * .9) + ')')
		.append('text')
		.text(0)
		.attr('text-anchor', 'middle')
		.attr('class', 'value');


	var kete = svg.append('g')
		.attr('transform', 'translate(' + size + ',' + (size * 1.05) + ')')
		.append('text')
		.text(0)
		.attr('text-anchor', 'middle')
		.attr('class', 'nhuruf');

	var scale = svg.append('g')
		.attr('transform', 'translate(' + size + ',' + (size + 15) + ')')
		.attr('class', 'scale');

	scale.append('text')
		.text(10)
		.attr('text-anchor', 'middle')
		.attr('x', (size - thickness / 2));

	scale.append('text')
		.text(0)
		.attr('text-anchor', 'middle')
		.attr('x', -(size - thickness / 2));
	update_gauge(<?= $ak; ?>);

	function update_gauge(v) {
		v = d3.format('.1f')(v);
		//console.log("update", v);
		foreground.transition()
			.duration(750)
			.style('fill', function() {
				return color(v);
			})
			.call(arcTween, v);

		value.transition()
			.duration(750)
			.call(textTween, v);

		kete.transition()
			.duration(750)
			.call(textKet, rentang(v));
	}

	function arcTween(transition, v) {
		var newAngle = v / 10 * Math.PI - Math.PI / 2;
		transition.attrTween('d', function(d) {
			var interpolate = d3.interpolate(d.endAngle, newAngle);
			return function(t) {
				d.endAngle = interpolate(t);
				return arc(d);
			};
		});
	}

	function textTween(transition, v) {
		//console.log(v);
		transition.tween('text', function() {
			var interpolate = d3.interpolate(this.innerHTML, v),
				split = (v + '').split('.'),
				round = (split.length > 1) ? Math.pow(10, split[1].length) : 1;
			return function(t) {
				this.innerHTML = d3.format('.1f')(Math.round(interpolate(t) * round) / round);
			};
		});
	}

	function textKet(transition, v) {
		//console.log(v);
		transition.tween('text', function() {
			var interpolate = d3.interpolate(this.innerHTML, v),
				split = (v + '').split('.'),
				round = (split.length > 1) ? Math.pow(10, split[1].length) : 1;
			return function(t) {
				this.innerHTML = v //d3.format('.1f')(Math.round(interpolate(t) * round) / round);
			};
		});
	}

	function rentang(v) {
		v = Number(v);

		if (v <= 10 && v >= 9.6) {
			return "Outstanding";
		} else if (v <= 9.5 && v >= 7.6) {
			return "Exceed Expectation";
		} else if (v <= 7.5 && v >= 5.6) {
			return "Meets Expectation";
		} else if (v <= 5.5 && v >= 3.6) {
			return "Needs Improvement";
		} else if (v <= 3.6) {
			return "Problematic";
		} else {
			return "#";
		}
	}
</script>
<!-- tampil grafik -->

<!-- tampil grafik -->
<script>
	var size_bar = $("#chart-nilai-perwakilan").width() / 2; //150,
	thickness_bar = 60;
	margin = 10;
	bar_width = (size_bar * 2) - 2 * margin;
	bar_height = (size_bar + 2) - 1 * margin;

	var svg_bar = d3.select('#chart-nilai-perwakilan').append('svg')
		.attr('width', size_bar * 2)
		.attr('height', size_bar + 20)
		.attr('class', 'bar');

	var chart_bar = svg_bar.append('g')
		.attr('transform', 'translate(' + (margin + 25) + ',' + margin + ')')

	var xScale = d3.scaleBand()
		.range([0, bar_width])
		.domain(data_bar.map((s) => s.oleh))
		.padding(0.4)

	var yScale = d3.scaleLinear()
		.range([bar_height, 0])
		.domain([0, 10]);


	var makeYLines = () => d3.axisLeft()
		.scale(yScale)

	chart_bar.append('g')
		.attr('transform', 'translate(0, ' + bar_height + ')')
		.call(d3.axisBottom(xScale));

	chart_bar.append('g')
		.call(d3.axisLeft(yScale));

	chart_bar.append('g')
		.attr('class', 'grid')
		.call(makeYLines()
			.tickSize(-bar_width, 0, 0)
			.tickFormat('')
		)


	var barGroups = chart_bar.selectAll()
		.data(data_bar)
		.enter()
		.append('g')

	barGroups
		.append('rect')
		.attr('class', 'bar_red')
		.attr('x', function(g) {
			return xScale(g.oleh)
		})
		.attr('width', xScale.bandwidth())
		.on('mouseenter', mouseOver)
		.on('mouseleave', mouseLeave)
		.attr('y', function(g) {
			return yScale(0)
		})
		.attr('height', function(g) {
			return bar_height - yScale(0)
		})
		.transition()
		.ease(d3.easeExp)
		.duration(750)
		.delay(function(g, i) {
			//console.log(i+" "+yScale(g.nilai));
			return i * 50;
		})
		.attr('y', function(g) {
			return yScale(g.nilai)
		})
		.attr('height', function(g) {
			return bar_height - yScale(g.nilai)
		})
		.attr("fill", function(g) {
			//['#db4639', '#FFCD42', '#48ba17', '#12ab24', '#0f9f59']
			var v = g.nilai;
			if (v >= 9.6) {
				return "#0f9f59";
			} else if (v >= 7.6) {
				return "#12ab24";
			} else if (v >= 5.6) {
				return "#48ba17";
			} else if (v >= 3.6) {
				return "#FFCD42";
			} else {
				return "#db4639";
			}
		})

	barGroups
		.append('text')
		.attr('class', 'value_bar')
		.attr('x', (a) => xScale(a.oleh) + xScale.bandwidth() / 2)
		.attr('y', (a) => yScale(a.nilai) + 30)
		.attr('fill', 'white')
		.attr('text-anchor', 'middle')
		.attr('opacity', 1)
		.text((a) => a.nilai)


	function mouseOver(actual, i) {
		var y = yScale(actual.nilai)

		line = chart_bar.append('line')
			.attr('id', 'limit')
			.attr('x1', 0)
			.attr('y1', y)
			.attr('x2', bar_width)
			.attr('y2', y)

		barGroups.append('text')
			.attr('class', 'divergence')
			.attr('x', (a) => xScale(a.oleh) + xScale.bandwidth() / 2)
			.attr('y', (a) => yScale(a.nilai) + 30)
			.attr('fill', 'white')
			.attr('text-anchor', 'middle')
			.text((a, idx) => {
				var divergence = (a.nilai - actual.nilai).toFixed(1)

				var text = ''
				if (divergence > 0) text += '+'
				text += ' ' + divergence + ' '
				text = a.nilai;
				return idx !== i ? text : '';
			})
	}

	function mouseLeave() {
		d3.selectAll('.value_bar')
			.attr('opacity', 1)
	}
</script>
<!-- tampil grafik -->

<script>
	var size_bar = $("#chart-nilai-perkompetensi").width() / 2; //150,
	thickness_bar = 60;
	margin = 30;
	bar_width = (size_bar * 2) - 2 * margin;
	bar_height = (size_bar + 2) - 1 * margin;

	var svg_bar = d3.select('#chart-nilai-perkompetensi').append('svg')
		.attr('width', size_bar * 2)
		.attr('height', size_bar + 20)
		.attr('class', 'bar');

	var chart_bar = svg_bar.append('g')
		.attr('transform', 'translate(' + margin + ',' + margin + ')')

	var xScale = d3.scaleBand()
		.range([0, bar_width])
		.domain(data_kompetensi.map((s) => s.oleh))
		.padding(0.4)

	var yScale = d3.scaleLinear()
		.range([bar_height, 0])
		.domain([0, 10]);

	var makeYLines = () => d3.axisLeft()
		.scale(yScale)

	chart_bar.append('g')
		.attr('transform', 'translate(0, ' + bar_height + ')')
		.call(d3.axisBottom(xScale));

	chart_bar.append('g')
		.call(d3.axisLeft(yScale));

	chart_bar.append('g')
		.attr('class', 'grid')
		.call(makeYLines()
			.tickSize(-bar_width, 0, 0)
			.tickFormat('')
		)


	var barGroups = chart_bar.selectAll()
		.data(data_kompetensi)
		.enter()
		.append('g')


	barGroups
		.append('rect')
		.attr('class', 'bar')
		//.attr('fill', 'red')
		.attr('x', function(g) {
			return xScale(g.oleh)
		})
		.attr('width', xScale.bandwidth())
		.on('mouseenter', mouseOver)
		.on('mouseleave', mouseLeave)
		.attr('y', function(g) {
			return yScale(0)
		})
		.attr('height', function(g) {
			return bar_height - yScale(0)
		})
		.transition()
		.ease(d3.easeExp)
		.duration(750)
		.delay(function(g, i) {
			//console.log(i+" "+yScale(g.nilai));
			return i * 50;
		})
		.attr('y', function(g) {
			return yScale(g.nilai)
		})
		.attr('height', function(g) {
			return bar_height - yScale(g.nilai)
		})
		.attr("fill", function(g) {
			//['#db4639', '#FFCD42', '#48ba17', '#12ab24', '#0f9f59']
			var v = g.nilai;
			if (v >= 10) {
				return "#0f9f59";
			} else if (v >= 9.5) {
				return "#12ab24";
			} else if (v >= 7.5) {
				return "#48ba17";
			} else if (v >= 5.5) {
				return "#FFCD42";
			} else {
				return "#db4639";
			}
		})

	barGroups
		.append('text')
		.attr('class', 'value_bar')
		.attr('x', (a) => xScale(a.oleh) + xScale.bandwidth() / 2)
		.attr('y', (a) => yScale(a.nilai) + 30)
		.attr('fill', 'white')
		.attr('text-anchor', 'middle')
		.attr('opacity', 1)
		.text((a) => a.nilai)


	function mouseOver(actual, i) {
		var y = yScale(actual.nilai)

		line = chart_bar.append('line')
			.attr('id', 'limit')
			.attr('x1', 0)
			.attr('y1', y)
			.attr('x2', bar_width)
			.attr('y2', y)

		barGroups.append('text')
			.attr('class', 'divergence')
			.attr('x', (a) => xScale(a.oleh) + xScale.bandwidth() / 2)
			.attr('y', (a) => yScale(a.nilai) + 30)
			.attr('fill', 'white')
			.attr('text-anchor', 'middle')
			.text((a, idx) => {
				var divergence = (a.nilai - actual.nilai).toFixed(1)

				var text = ''
				if (divergence > 0) text += '+'
				text += ' ' + divergence + ' '
				text = a.nilai;
				return idx !== i ? text : '';
			})
	}

	function mouseLeave() {
		d3.selectAll('.value_bar')
			.attr('opacity', 1)
	}
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$(".cb_periode").change(function() {
			var idta = $(this).val();
			document.location = "index.php?p=laporanpen&idta=" + idta;
		});
	});
</script>