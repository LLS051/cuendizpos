<style type="text/css">
    .top-left-header{
        margin-top: 0px !important;
    }
</style>

<section class="content-header">
    <h3  style="text-align: center;margin-top: 0px">Reporte Detallado de Compra</h3>
    <hr style="border: 1px solid #3c8dbc;">
    <div class="row">
        <div class="col-md-2">
            <?php echo form_open(base_url() . 'Report/detailedPurchaseReport') ?>
            <div class="form-group">
                <input tabindex="1" type="text" id="" name="startDate" readonly class="form-control customDatepicker" placeholder="Fecha de Inicio" value="<?php echo set_value('startDate'); ?>">
            </div>
        </div>
        <div class="col-md-2">

            <div class="form-group">
                <input tabindex="2" type="text" id="endMonth" name="endDate" readonly class="form-control customDatepicker" placeholder="Fecha de Fin" value="<?php echo set_value('endDate'); ?>">
            </div>
        </div>
        <div class="col-md-2">

            <div class="form-group">
                <select tabindex="2" class="form-control select2" id="user_id" name="user_id" style="width: 100%;">
                    <option value="" disabled selected hidden>Todos los Usuarios</option>
                    <option value="<?= $this->session->userdata['user_id']; ?>"><?= $this->session->userdata['full_name']; ?></option>
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
        <!--<div class="col-md-offset-3 col-md-2">
            <div class="form-group">
                <a target="_blank" href="<?= base_url() . 'PdfGenerator/detailedPurchaseReport/' ?><?= isset($start_date) && $start_date ? $this->custom->encrypt_decrypt($start_date, 'encrypt') : '0'; ?>/<?= isset($end_date) && $end_date ? $this->custom->encrypt_decrypt($end_date, 'encrypt') : '0'; ?>/<?= isset($user_id) && $user_id ? $this->custom->encrypt_decrypt($user_id, 'encrypt') : '0'; ?>" class="btn btn-block btn-primary pull-left">Export PDF</a>
            </div>
        </div>
    </div>-->
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
                    <h3>Reporte Detallado de Compra</h3>
                    <h4><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? "Date: " . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? "Date: " . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? "Date: " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?></h4>
                    <h4 style="text-align: center;margin-top: 0px"><?php
                    if (isset($user_id) && $user_id):
                        echo "User: " . userName($user_id);
                    else:
                        echo "User: Todos";
                    endif;
                    ?></h4>
                    <br>
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                            <th style="width: 2%;text-align: center;"><?php echo lang('sn'); ?></th>
                                <th style="width: 10%;"><?php echo lang('ref_no'); ?></th>
                                <th style="width: 5%;"><?php echo lang('date'); ?></th>
                                <th style="width: 10%;"><?php echo lang('supplier'); ?></th>
                                <th style="width: 12%;"><?php echo lang('grand_total'); ?></th>
                               <!-- <th style="width: 7%;"><?php echo lang('paid'); ?></th>
                                <th style="width: 7%;"><?php echo lang('due'); ?></th>-->
                                <th style="width: 32%;"><?php echo lang('ingredients'); ?></th>
                                <th style="width: 15%;"><?php echo lang('purchased_by'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($detailedPurchaseReport) && !empty($detailedPurchaseReport)) {
                                $i = count($detailedPurchaseReport);
                            }
                            $pGrandTotal = 0;
                            $paidGrandTotal = 0;
                            $dueGrandTotal = 0;
                            $vatGrandTotal = 0;
                            if (isset($detailedPurchaseReport)):
                                foreach ($detailedPurchaseReport as $value) {
                                    $pGrandTotal+=$value->grand_total;
                                    $paidGrandTotal+=$value->paid;
                                    $dueGrandTotal+=$value->due;
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><?php echo $i--; ?></td>
                                        <td><?php echo $value->reference_no; ?></td> 
                                        <td><?= date($this->session->userdata('date_format'), strtotime($value->date)) ?></td>
                                        <td><?php echo getSupplierNameById($value->supplier_id); ?></td> 
                                        <td><?php echo ('C$ '),$value->grand_total ?></td>
                                        <!--<td><?php echo $value->paid ?></td>
                                        <td><?php echo $value->due ?></td>-->
                                        <td><?php echo (getPurchaseIngredients($value->id)) ?></td>  
                                        <td><?php echo userName($value->user_id) ?></td>
                                    </tr>
                                    <?php
                                }
                            endif;
                            ?>
                        </tbody>
                        <tfoot>
                            <td>&nbsp;</td>
                            
                        <th style="width: 15%;text-align: center"></th>
                        <th></th>
                        <th style="text-align: right">Total </th>
                        <th><?= number_format($paidGrandTotal, 2) ?></th>
                        <!--<th><?= number_format($dueGrandTotal, 2) ?></th>
                        <th><?= number_format($pGrandTotal, 2) ?></th>-->
                        <td>&nbsp;</td>
                        <th></th>
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

    var TITLE = "<?php echo lang('purchase_report'); ?> "+today; 

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