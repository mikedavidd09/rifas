<!DOCTYPE HTML>
<html lang="es" xmlns="http:<script src="www.w3.org/1999/html" xmlns="http:<script src="www.w3.org/1999/html">
<head>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
    <title><?php echo $title;?></title>
    <meta charset="utf-8"/>
    <!--NAVEGATION MAIN-->
    <link href="resources/css/dataTableFiltrerByfield.css" rel="stylesheet" />
    <link href="assets/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="resources/css/reports.css" rel="stylesheet" />
    <script src="assets/js/dataTables/dataTables.buttons.min.js"  ></script>
    <!--JS para  aGenerar exportac-->
    <script src="assets/js/dataTables/buttons.flash.min.js" ></script>
    <script src="assets/js/dataTables/jszip.min.js" ></script>
    <script src="assets/js/dataTables/pdfmake.min.js" ></script>
    <script src="assets/js/dataTables/vfs_fonts.js" ></script>
    <script src="assets/js/dataTables/buttons.html5.min.js" ></script>
    <script src="assets/js/dataTables/buttons.print.min.js" ></script>
    <script src="resources/js/dataTableLenguaje.js" ></script>
    <script src="resources/js/reportes_avance.js" ></script>
</head>
<body class="sidebar-mini fixed">
<div>
    <p style="text-align: right;"><?php echo $title;?></p>
    <p><table id="reportes" class="display" cellpadding="0" cellspacing="0" border="0"  width="100%">
        <thead>
            <tr>
            <?php
                foreach ($header as $item)
                   echo "<th>$item</th>";
				   echo "<th>%C Total A</th>";
				   echo "<th>%I Total A</th>";
				   echo "<th>%C Saldo P</th>";
				   echo "<th>%I Saldo P</th>";

					$T_PCTA = 0;
					$T_PITA = 0;
					$T_PCSP = 0;
					$T_PISP = 0;

            ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($data) > 0 && is_array($data)) {
                if (isset($totales)) {
                    foreach ($total_montos as $var){
                        ${$var} = '';
                    }
                }
                foreach ($data as $item) {
                    $counter = 0;
                    echo "<tr>";
                    foreach ($fields as $field) {
                        echo "<td>" . $item->$field . "</td>";
                        if (isset($total_montos) && in_array($field, $total_montos)){
                            ${$field} += $item->$field;

                        }

                        $counter++;
                    }


					$PCTA = round((($item->capital*100/$item->deuda_total)/100*$item->total_abonado),3);

					$PITA = round(((($item->capital*$item->porcentaje/100)*100/$item->deuda_total)/100*$item->total_abonado),3);

					$PCSP = round((($item->capital*100/$item->deuda_total)/100*$item->saldo_pendiente),3);

					$PISP = round(((($item->capital*$item->porcentaje/100)*100/$item->deuda_total)/100*$item->saldo_pendiente),3);


					$T_PCTA += $PCTA;
					$T_PITA += $PCTA;
					$T_PCSP += $PCSP;
					$T_PISP += $PISP;


					echo "<td>".$PCTA."</td>";
					echo "<td>".$PITA."</td>";
					echo "<td>".$PCSP."</td>";
					echo "<td>".$PISP."</td>";

                    echo "</tr>";
                }
            } else if (is_object($data)) {
                $counter = 0;
                if (isset($totales)) {
                    foreach ($total_montos as $var){
                        ${$var} = '';
                    }
                }
                echo "<tr>";
                foreach ($data as $id_assoc => $item) {
                    echo "<td>$item</td>";
                    if (isset($total_montos) && in_array($id_assoc, $total_montos)) {
                        ${$id_assoc} += $data->$id_assoc;
                    }
                    $counter++;
                }
                echo "</tr>";
            }
            if (isset($totales) && isset($counter)) {
                echo "<tr>";
                for ($i = 0; $i < $counter; $i++) {
                    if (($counter - (count($totales)+1)) == $i) {
                        echo "<td>Totales: C$</td>";
                        foreach ($total_montos as $item) {
                            $total = ${$item};
                            echo "<td style=\"border-bottom:1px solid #000000;\">$total</td>";
                        }
				   echo "<td style=\"border-bottom:1px solid #000000;\">$T_PCTA</td>";
				   echo "<td style=\"border-bottom:1px solid #000000;\">$T_PITA</th>";
				   echo "<td style=\"border-bottom:1px solid #000000;\">$T_PCSP</td>";
				   echo "<td style=\"border-bottom:1px solid #000000;\">$T_PISP</td>";
                    } else if(($counter - (count($totales)+1))>$i) {
                        echo "<td></td>";
                    }




                }
                echo "</tr>";
                echo "<tr>";
                for ($i = 0; $i < $counter; $i++) {
                    if (($counter - (count($totales)+1)) == $i) {
                        echo "<td></td>";
                        foreach ($totales as $item) {
                            echo "<td>$item</td>";

                        }
				   echo "<td>T_PCTA</td>";
				   echo "<td>T_PITA</th>";
				   echo "<td>T_PCSP</td>";
				   echo "<td>T_PISP</td>";
                    } else if(($counter - (count($totales)+1))>$i) {
                        echo "<td></td>";
                    }
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
