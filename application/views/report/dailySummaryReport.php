<style type="text/css">
    .top-left-header{
        margin-top: 0px !important;
    }
</style>

<section class="content-header">
    <h3><?php echo lang('daily_summary_report'); ?></h3>
    <hr style="border: 1px solid #3c8dbc;">
    <div class="row">
        <div class="col-md-2">
            <?php echo form_open(base_url() . 'Report/dailySummaryReport') ?>
            <div class="form-group">
                <input tabindex="1" type="text" id="date" name="date" readonly class="form-control" placeholder="<?php echo lang('date'); ?>" value="<?php echo set_value('date'); ?>">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <button type="submit" name="submit" value="submit" class="btn btn-block btn-primary pull-left"><?php echo lang('submit'); ?></button>
            </div>
        </div>
        <div class="hidden-lg">
            <div class="clearfix"></div>
        </div>
        <div class="col-md-offset-8 col-md-1">
            <div class="form-group">
                <a class="btn btn-block btn-primary pull-left" href="<?php echo base_url(); ?>Report/printDailySummaryReport/<?php echo $selectedDate; ?>"><?php echo lang('print'); ?></a>
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
        padding:5px;
        font-family:Arial, sans-serif;
        font-size:15px;
        border-style:solid;
        border-width:1px;
        word-break:break-all;
    }
    .tbl tr th{
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
    @media only screen and (min-width: 992px) and (max-width: 1300px) {

    }


/*		Tablet Layout: 768px.
			*/
@media only screen and (min-width: 768px) and (max-width: 991px) {
	.tbl tr td{
        padding:5px;
        font-family:Arial, sans-serif;
        font-size:10px !important;
        border-style:solid;
        border-width:1px;
        word-break:normal !important;
    }
    .tbl tr th{
        padding:5px;
        font-family:Arial, sans-serif;
        font-size:10px !important;
        border-style:solid;
        border-width:1px;
        word-break:normal !important;
    }
    }


@media only screen and (max-width: 767px) {
	.tbl tr td{
        padding:5px;
        font-family:Arial, sans-serif;
        font-size:10px !important;
        border-style:solid;
        border-width:1px;
        word-break:normal !important;
    }
    .tbl tr th{
        padding:5px;
        font-family:Arial, sans-serif;
        font-size:10px !important;
        border-style:solid;
        border-width:1px;
        word-break:normal !important;
    }
    }

@media only screen and (min-width: 480px) and (max-width: 767px) {
	.tbl tr td{
        padding:5px;
        font-family:Arial, sans-serif;
        font-size:10px !important;
        border-style:solid;
        border-width:1px;
        word-break:normal !important;
    }
    .tbl tr th{
        padding:5px;
        font-family:Arial, sans-serif;
        font-size:10px !important;
        border-style:solid;
        border-width:1px;
        word-break:normal !important;
    }
    }


</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">


                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <h1><?php echo $this->session->userdata('outlet_name'); ?></h1>
                    <hr>
                    <h3 style="text-align: center;"><?php echo lang('daily_summary_report'); ?></h3>
                    <h4><?= isset($selectedDate) && $selectedDate ? "Date: " . date($this->session->userdata('date_format'), strtotime($selectedDate)) : '' ?></h4>
                    <hr>
                    <h4 style="font-weight: bold; text-align: center; margin-top: 20px;">Compras</h4>
                    <hr>
                    <table style="width: 100%" class="tbl">
                        <tr>
                            <th style="font-weight: bold; text-align: center;"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('ref_no'); ?></th>
                            <th><?php echo lang('supplier'); ?></th>
                            <th><?php echo lang('g_total'); ?></th>
                            <th><?php echo ('Nota'); ?></th>
                            <!--<th><?php echo ('Nota'); ?></th> -->
                        </tr>
                        <?php
                            $sum_of_gtotal = 0;
                            $sum_of_given = 0;
                            $sum_of_due = 0;
                            if (!empty($result['purchases']) && isset($result['purchases'])):
                                foreach ($result['purchases'] as $key => $value):
                                    $sum_of_gtotal += $value->grand_total;
                                    $sum_of_given += $value->given;
                                    $sum_of_due += $value->due;
                                    $key++;
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><?= $key ?></td>
                                        <td><?= $value->reference_no; ?></td>
                                        <td><?= getSupplierNameById($value->supplier_id) ?></td>
                                        <td><?= $value->grand_total ?></td>
                                        <!--<td><?= $value->given ?></td> -->
                                        <td><?= $value->note ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="font-weight: bold; text-align: right;"><?php echo lang('sum'); ?></td>
                            <td>&nbsp;<?= $sum_of_gtotal ?></td>
                            <td>&nbsp;<!--<?= $sum_of_given ?>--></td>
                            <!-- <td>&nbsp;<?= $sum_of_change ?></td> -->
                        </tr>
                    </table>

                    <hr>
                    <h4 style="font-weight: bold; text-align: center; margin-top: 20px;"><?php echo lang('sales'); ?></h4>
                    <hr>
                    <table style="width: 100%" class="tbl">
                        <tr>
                            <th style="font-weight: bold; text-align: center;"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('ref_no'); ?></th>
                            <!-- <th><?php echo lang('order_type'); ?></th> -->
                            <!--<th><?php echo lang('table'); ?></th>-->
                            <th><?php echo lang('customer'); ?></th>
                            <th><?php echo ('Total a pagarse'); ?></th>
                            <!--<th><?php echo lang('discount'); ?></th> -->
                            <th><?php echo ('Total Pagado / Total Vendido'); ?></th>
                            <th><?php echo lang('paid'); ?></th>
                            <th><?php echo lang('due'); ?></th>
                            <th><?php echo ('Metodo de Pago'); ?></th>
                        </tr>
                        <?php
                            $sum_of_stotal_payable = 0;
                            $sum_of_spaid_amount = 0;
                            $sum_of_sgiven_amount = 0;
                            $sum_of_schange_amount = 0;
                            if (!empty($result['sales']) && isset($result['sales'])):
                                foreach ($result['sales'] as $key => $value):
                                    $sum_of_stotal_payable += $value->total_payable;
                                    $sum_of_spaid_amount += $value->paid_amount;
                                    $sum_of_sgiven_amount += $value->given_amount;
                                    $sum_of_schange_amount += $value->change_amount;
                                    $key++;


                                    ?>
                                    <?php
                                      $method_sale = '';
                                      if($value->payment_method_id == 3){
                                          $method_sale = 'Efectivo';
                                      }elseif($value->payment_method_id == 4){
                                          $method_sale = 'Tarjeta';
                                      }elseif($value->payment_method_id == 5){
                                          $method_sale = 'Credito';
                                      }elseif($value->payment_method_id == 6){
                                          $method_sale = 'Regalia';
                                      };
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><?= $key ?></td>
                                        <td><?= $value->sale_no; ?></td>
                                        <!-- <td><?php echo getOrderType($value->order_type); ?></td> -->
                                        <!--<td><?php if(!empty($value->table_id)){ echo getTableName($value->table_id); } ?></td>-->
                                        <td><?= getCustomerName($value->customer_id) ?></td>
                                        <td><?= $value->total_payable ?></td>
                                        <td><?= $value->paid_amount ?></td>
                                        <!--<td><?= $value->total_discount_amount ?></td> -->
                                        <td><?= $value->given_amount ?></td>
                                        <td><?= $value->change_amount ?></td>
                                        <td><?= $method_sale ?></td>

                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>

                            <td style="font-weight: bold; text-align: right;"><?php echo lang('sum'); ?></td>
                            <td>&nbsp;<?= $sum_of_stotal_payable ?></td>
                            <td>&nbsp;<strong><?= $sum_of_spaid_amount ?></strong></td>
                            <td>&nbsp;<!--<?= $sum_of_sgiven_amount ?>--></td>
                            <td>&nbsp;<!--<?= $sum_of_schange_amount ?>--></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>


                    <hr>
                    <h4 style="font-weight: bold; text-align: center; margin-top: 20px;"><?php echo lang('expenses'); ?></h4>
                    <hr>
                    <table style="width: 100%" class="tbl">
                        <tr>
                            <th style="font-weight: bold; text-align: center;"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('amount'); ?></th>
                            <th><?php echo lang('category'); ?></th>
                            <th><?php echo lang('responsible_person'); ?></th>
                            <th><?php echo lang('note'); ?></th>
                        </tr>
                        <?php
                            $sum_of_eamount = 0;
                            if (!empty($result['expenses']) && isset($result['expenses'])):
                                foreach ($result['expenses'] as $key => $value):
                                    $sum_of_eamount += $value->amount;
                                    $key++;
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><?= $key ?></td>
                                        <td><?= $value->amount; ?></td>
                                        <td><?php echo expenseItemName($value->category_id); ?></td>
                                        <td><?php echo employeeName($value->employee_id); ?></td>
                                        <td><?= $value->note ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                        ?>
                        <tr>
                            <td style="font-weight: bold; text-align: right;"><?php echo lang('sum'); ?></td>
                            <td>&nbsp;<?= $sum_of_eamount ?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>

                   <!-- <hr>
                    <h4 style="font-weight: bold; text-align: center; margin-top: 20px;"><?php echo lang('supplier_due_payments'); ?></h4>
                    <hr>
                    <table style="width: 100%" class="tbl">
                        <tr>
                            <th style="font-weight: bold; text-align: center;"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('amount'); ?></th>
                            <th><?php echo lang('supplier'); ?></th>
                            <th><?php echo lang('note'); ?></th>
                        </tr>
                        <?php
                            $sum_of_samount = 0;
                            if (!empty($result['supplier_due_payments']) && isset($result['supplier_due_payments'])):
                                foreach ($result['supplier_due_payments'] as $key => $value):
                                    $sum_of_samount += $value->amount;
                                    $key++;
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><?= $key ?></td>
                                        <td><?= $value->amount; ?></td>
                                        <td><?php echo getSupplierNameById($value->supplier_id); ?></td>
                                        <td><?= $value->note ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                        ?>
                        <tr>
                            <td style="font-weight: bold; text-align: right;"><?php echo lang('sum'); ?></td>
                            <td>&nbsp;<?= $sum_of_samount ?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>  -->

                    <hr>
                    <h4 style="font-weight: bold; text-align: center; margin-top: 20px;"><?php echo lang('customer_due_receives'); ?></h4>
                    <hr>
                    <table style="width: 100%" class="tbl">
                        <tr>
                            <th style="font-weight: bold; text-align: center;"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('amount'); ?></th>
                            <th><?php echo lang('customer'); ?></th>
                            <th><?php echo lang('note'); ?></th>
                        </tr>
                        <?php
                            $sum_of_camount = 0;
                            if (!empty($result['customer_due_receives']) && isset($result['customer_due_receives'])):
                                foreach ($result['customer_due_receives'] as $key => $value):
                                    $sum_of_camount += $value->amount;
                                    $key++;
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><?= $key ?></td>
                                        <td><?= $value->amount; ?></td>
                                        <td><?php echo getCustomerName($value->customer_id); ?></td>
                                        <td><?= $value->note ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                        ?>
                        <tr>
                            <td style="font-weight: bold; text-align: right;"><?php echo lang('sum'); ?></td>
                            <td>&nbsp;<?= $sum_of_camount ?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>

                    <hr>
                    <!-- <h4 style="font-weight: bold; text-align: center; margin-top: 20px;"><?php echo lang('wastes'); ?></h4>
                    <hr>
                    <table style="width: 100%" class="tbl">
                        <tr>
                            <th style="font-weight: bold; text-align: center;"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('ref_no'); ?></th>
                            <th><?php echo lang('loss_amount'); ?></th>
                            <th><?php echo lang('responsible_person'); ?></th>
                            <th><?php echo lang('note'); ?></th>
                        </tr>
                        <?php
                            $sum_of_wamount = 0;
                            if (!empty($result['wastes']) && isset($result['wastes'])):
                                foreach ($result['wastes'] as $key => $value):
                                    $sum_of_wamount += $value->total_loss;
                                    $key++;
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><?= $key ?></td>
                                        <td><?= $value->reference_no; ?></td>
                                        <td><?= $value->total_loss; ?></td>
                                        <td><?php echo employeeName($value->employee_id); ?></td>
                                        <td><?= $value->note ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td style="font-weight: bold; text-align: right;"><?php echo lang('sum'); ?></td>
                            <td>&nbsp;<?= $sum_of_wamount ?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table> -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>
