<style type="text/css">
    .top-left-header{
        margin-top: 0px !important;
    }
</style>

<section class="content-header">
    <h3  style="text-align: center;margin-top: 0px">Reporte de Ventas por Mes</h3>
    <hr style="border: 1px solid #3c8dbc;">
    <div class="row">
        <div class="col-md-2">
            <?php echo form_open(base_url() . 'Report/saleReportByMonth') ?>
            <div class="form-group">
                <input tabindex="1" type="text" id="" name="startMonth" readonly class="form-control datepicker_months" placeholder="Mes de Inicio" value="<?php echo set_value('startMonth'); ?>">
            </div>
        </div>
        <div class="col-md-2">

            <div class="form-group">
                <input tabindex="1" type="text" id="endMonth" name="endMonth" readonly class="form-control datepicker_months" placeholder="Mes de fin" value="<?php echo set_value('endMonth'); ?>">
            </div>
        </div>
        <div class="col-md-2">

            <div class="form-group">
                <select tabindex="2" class="form-control select2" id="user_id" name="user_id" style="width: 100%;">
                    <option value="" disabled selected hidden>Seleccionar Usuario</option>
                    <!--<option value="<?= $this->session->userdata['user_id']; ?>"><?= $this->session->userdata['full_name']; ?></option>-->
                    <?php
                    foreach ($users as $value) {
                        ?>
                        <option value="<?php echo $value->id ?>" <?php echo set_select('user_id', $value->id); ?>><?php echo $value->full_name ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <button type="submit" name="submit" value="submit" class="btn btn-block btn-primary pull-left">Submit</button>
            </div>
        </div>
        <div class="hidden-lg">
            <div class="clearfix"></div>
        </div>
        <div class="col-md-offset-3 col-md-2">
            <div class="form-group">
                <a target="_blank" href="<?= base_url() . 'PdfGenerator/saleReportByMonth/' ?><?= isset($start_date) ? $this->custom->encrypt_decrypt($start_date, 'encrypt') : '0'; ?>/<?= isset($end_date) ? $this->custom->encrypt_decrypt($end_date, 'encrypt') : '0'; ?>/<?= isset($user_id) ? $this->custom->encrypt_decrypt($user_id, 'encrypt') : '0'; ?>" class="btn btn-block btn-primary pull-left">Exportar PDF</a>
            </div>
        </div>
    </div>
</section>
<style type="text/css">
    h1,h2,h3,h4,p{
        margin:3px 0px;
        text-align: center;
    }

    .tbl  {
        border-collapse:collapse;
        border-spacing:0;
        width: 100%;

    }
    .tbl tr td{
        padding:14px;
        font-family:Arial, sans-serif;
        font-size:15px;
        border-style:solid;
        border-width:1px;
        word-break:break-all;
    }

    .title{
        font-weight: bold;
    }
    .box-primary{
        border-top-color: white !important;
        margin-top: 5px;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <h3>Reporte de Ventas por Mes</h3>
                    <h4 style="text-align: center;margin-top: 0px"><?php
                    if (isset($user_id) && $user_id):
                        echo "User: " . userName($user_id) . "</span>";
                    endif;
                    ?></h4>
                    <h4><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? "Date: " . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?></h4>
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 2%;text-align: center">SN</th>
                                <th>Mes</th>
                                <th>Total de Venta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grandTotal = 0;

                            if (isset($saleReportByMonth)):
                                foreach ($saleReportByMonth as $key => $value) {
                                    $grandTotal+=$value->total_payable;
                                    $key++;
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><?php echo $key; ?></td>
                                        <td><?= date('M-Y', strtotime($value->sale_date)) ?></td>
                                        <td><?php echo $value->total_payable ?></td>
                                    </tr>
                                    <?php
                                }
                            endif;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 2%;text-align: center"></th>
                                <th style="text-align: right">Total </th>
                                <th><?= number_format($grandTotal, 2) ?></th>
                            </tr>

                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">

<script>
    var jqry = $.noConflict();
    jqry(document).ready(function(){

    var TITLE = "<?php echo ('Informe de Ventas por Mes'); ?> "+today;

    jqry('#datatable').DataTable( {
        'autoWidth'   : false,
        'ordering'    : false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                title: TITLE
            },
            {
                extend: 'excelHtml5',
                title: TITLE
            },
            {
                extend: 'pdfHtml5',
                title: TITLE
            }
        ]
    } );
} );
</script>
