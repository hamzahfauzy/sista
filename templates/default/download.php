<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indeks Keluarga Sehat</title>
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
    </style>
</head>
<body>
    <h2 align="center">INDEKS KELUARGA SEHAT</h2>
    <table width="100%">
        <tr>
            <th>NO</th>
            <th>NIK</th>
            <th>NAMA</th>
            <th>STATUS</th>
        </tr>
        <?php foreach($data->nilai[0]->rekap_penduduk as $index => $k): ?>
        <tr>
            <td><?=$index+1?></td>
            <td><?=$k->penduduk->NIK?></td>
            <td><?=$k->penduduk->nama?></td>
            <td><?=$k->penduduk->sebagai?></td>
        </tr>
        <?php endforeach ?>
    </table>

    <br><br>

    <table width="100%">
        <thead>
            <tr>
                <th style="text-align:center;width:30%" rowspan="3">INDIKATOR</th>
                <th style="text-align:center" colspan="<?=count($data->nilai[0]->rekap_penduduk)*3?>">VARIABEL PENILAIAN</th>
                <th rowspan="3">Skor</th>
            </tr>
            <tr>
                <?php foreach($data->nilai[0]->rekap_penduduk as $k): ?>
                <th style="text-align:center" colspan="3"><?=$k->penduduk->nama?></th>
                <?php endforeach ?>
            </tr>
            <tr>
                <?php foreach($data->nilai[0]->rekap_penduduk as $k): ?>
                <th style="text-align:center;color:#FFF;background:blue;">N</th>
                <th style="text-align:center;color:#FFF;background:green;">Y</th>
                <th style="text-align:center;color:#FFF;background:red;">T</th>
                <?php endforeach ?>
            </tr>
        </thead>
        <?php 
        $all_skor = [];
        foreach($data->nilai as $nilai): 
            $all_skor[] = $nilai->skor;
        ?>
        <tr>
            <td><?=$nilai->indikator->nama?></td>
            <?php 
            foreach($nilai->rekap_penduduk as $penduduk): 
                if($penduduk->jawaban != 'disable'): 
            ?>
            <td style="text-align:center;">
                <input type="radio" <?=$penduduk->jawaban == 'N' ? 'checked' : 'disabled' ?> value="N" style="transform:scale(1.5)">
            </td>
            <td style="text-align:center;">
                <input type="radio" <?=$penduduk->jawaban == 'Y' ? 'checked' : 'disabled' ?> value="Y" style="transform:scale(1.5)">
            </td>
            <td style="text-align:center;">
                <input type="radio" <?=$penduduk->jawaban == 'T' ? 'checked' : 'disabled' ?> value="T" style="transform:scale(1.5)">
            </td>
            <?php else: ?>
            <td style="background:silver;"></td>
            <td style="background:silver;"></td>
            <td style="background:silver;"></td>
            <?php endif; ?>
            <?php endforeach ?>
            <td><?=$nilai->skor?></td>
        </tr>
        <?php 
        endforeach;
        $nilai = array_count_values($all_skor);
        $question = array_sum($nilai) - ($nilai['N']??0);
        if(isset($nilai['N'])) unset($nilai['N']);
        // $label = $nilai[1] ." / ". $question;
        $nilai = $nilai[1] / $question;
        ?>
        <tr>
            <td>Total Nilai IKS</td>
            <td style="text-align:center" colspan="<?=count($data->nilai[0]->rekap_penduduk)*3?>"></td>
            <td ><?=number_format($nilai,3)?></td>
        </tr>
        <tr>
            <td>Keterangan IKS</td>
            <td style="text-align:center" colspan="<?=count($data->nilai[0]->rekap_penduduk)*3?>"><?=$data->kategori->nama?></td>
            <td style="background:<?=$data->kategori->warna?>"></td>
        </tr>
    </table>
</body>
</html>