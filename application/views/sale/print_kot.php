<?php
 $order_number = '';
 $order_type = '';
 if($sale_object->order_type=='1'){
    $order_type = 'Comer aca';
    $order_number = 'A '.$sale_object->sale_no;
 }elseif($sale_object->order_type=='2'){
    $order_type = 'Para llevar';
    $order_number = 'B '.$sale_object->sale_no;
 }elseif($sale_object->order_type=='3'){
    $order_type = 'Delivery';
    $order_number = 'C '.$sale_object->sale_no;
 }



$tables_booked = '';
if(count($sale_object->tables_booked)>0){
    $w = 1;
    foreach($sale_object->tables_booked as $single_table_booked){
        if($w == count($sale_object->tables_booked)){
            $tables_booked .= $single_table_booked->table_name;
        }else{
            $tables_booked .= $single_table_booked->table_name.', ';
        }
        $w++;
    }
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Invoice No: <?= $order_number ?></title>
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="pragma" content="no-cache" />
        <script src="/cdn-cgi/apps/head/Bx0hUCX-YaUCcleOh3fM_NqlPrk.js"></script>
        <link rel="stylesheet" href="theme.css" type="text/css" />
        <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
        <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href="print.css" type="text/css" />
        <style type="text/css" media="all">
            body { color: #000; }
            #wrapper { max-width: 480px; margin: 0 auto; padding-top: 20px; }
            .btn { border-radius: 0; margin-bottom: 5px; }
            .bootbox .modal-footer { border-top: 0; text-align: center; }
            h3 { margin: 5px 0; }
            .order_barcodes img { float: none !important; margin-top: 5px; }
            @media print {
                .no-print { display: none; }
                #wrapper { max-width: 480px; width: 100%; min-width: 250px; margin: 0 auto; }
                .no-border { border: none !important; }
                .border-bottom { border-bottom: 1px solid #ddd !important; }
                table tfoot { display: table-row-group; }
            }
        </style>
    </head>
    <body>
        <div id="wrapper">
            <div id="receiptData">

                <div id="receipt-data">
                    <div style="text-align: center;">
                        <h3>Cocina</h3>
                        Order No: <?= $order_number ?><br>
                        Fecha: <?= date($this->session->userdata('date_format'), strtotime($sale_object->sale_date)); ?> <?= date('H:i',strtotime($sale_object->order_time)) ?><br>
                        Cliente: <?php echo "$sale_object->customer_name"; ?><?= $tables_booked!="" ? " // Mesa: " . $tables_booked : '' ?><br>
                        Tipo de Orden: <?php echo $order_type; ?><?= $sale_object->order_type=='1' ? " // Mesero: " . $sale_object->waiter_name : '' ?>
                        <br>
                    </div>

                    <table class="table table-condensed">
                        <thead>
                            <tr style="font-weight: bold;">
                                <td style="width: 5%;">SN</td>
                                <td style="width: 85%;">Item</td>
                                <td style="width: 10%;" style="text-align: center;">Qty</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($sale_object->items)) {
                                $i = 1;
                                $totalItems = 0;
                                foreach ($sale_object->items as $row) {
                                    $totalItems+=$row->qty;
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td>
                                            <?php echo $row->menu_name; ?> <br>
                                            <?php
                                                $modifiers_name = '';
                                                $j=1;
                                                if(count($row->modifiers)>0){
                                                    foreach($row->modifiers as $single_modifier){
                                                        if($j==count($row->modifiers)){
                                                            $modifiers_name .= $single_modifier->name;
                                                        }else{
                                                            $modifiers_name .= $single_modifier->name.',';
                                                        }
                                                        $j++;
                                                    }

                                                }
                                            ?>
                                            <?php if(count($row->modifiers)>0){ echo "Opcion: ". $modifiers_name."<br>";}?>
                                            <?php if($row->menu_note!=""){ echo "Nota: ".$row->menu_note; }?>
                                        </td>
                                        <td style="text-align: center;"><?php echo $row->qty; ?> </td>
                                    </tr>
                                <?php }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="text-align: center;" colspan="6">Total Item(s): <?= $totalItems ?></th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
                <div style="clear:both;"></div>
            </div>

            <div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">
                <hr>
                <span class="pull-right col-xs-12">
                <button onclick="window.print();" class="btn btn-block btn-primary">Imprimir</button> </span>
                <div style="clear:both;"></div>
                <div class="col-xs-12" style="background:#F5F5F5; padding:10px; color: red;">
                    <p style="font-weight:bold; text-transform: none;">
                        Please follow these steps before you print for first time:
                    </p>
                    <p style="text-transform: capitalize;">
                        1. Disable Header and Footer in browser's print setting<br>
                        For Firefox: File &gt; Page Setup &gt; Margins &amp; Header/Footer &gt; Headers & Footers &gt; Make all --blank--<br>
                        For Chrome: Menu &gt; Print &gt; Uncheck Header/Footer in More Options
                    </p>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <script src="<?php echo base_url(); ?>assets/dist/js/print/jquery-2.0.3.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/dist/js/print/custom.js"></script>
        <script type="text/javascript">
            $(window).load(function () {
                window.print();
                return false;
            });
        </script>
    </body>
</html>
