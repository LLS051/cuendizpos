<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kitchen extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Kitchen_model');
        $this->Common_model->setDefaultTimezone();
        $this->load->library('form_validation');
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }


    }

    /* ----------------------Dashboard Menu Start-------------------------- */

    public function panel(){
        $data = array();
        $data['getUnReadyOrders'] = $this->get_new_orders();
        $data['notifications'] = $this->get_new_notification();
        // $data['main_content'] = $this->load->view('kitchen/panel', $data, TRUE);
        $this->load->view('kitchen/panel', $data);
    }

    public function get_new_orders_ajax(){
        $data1 = $this->get_new_orders();
        echo json_encode($data1);
    }

    public function get_order_details_kitchen_ajax(){
        $sale_id = $this->input->post('sale_id');
        $sale_object = $this->get_all_information_of_a_sale_kitchen_type($sale_id);
        echo json_encode($sale_object);
    }
    public function get_all_information_of_a_sale_kitchen_type($sales_id){
        $sales_information = $this->Kitchen_model->getSaleBySaleId($sales_id);
        $items_by_sales_id = $this->Kitchen_model->getAllKitchenItemsFromSalesDetailBySalesId($sales_id);
        foreach($items_by_sales_id as $single_item_by_sale_id){
            $modifier_information = $this->Kitchen_model->getModifiersBySaleAndSaleDetailsId($sales_id,$single_item_by_sale_id->sales_details_id);
            $single_item_by_sale_id->modifiers = $modifier_information;
        }
        $sales_details_objects = $items_by_sales_id;
        $sale_object = $sales_information[0];
        $sale_object->items = $sales_details_objects;
        return $sale_object;
    }
    public function get_new_orders(){
        $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $data1 = $this->Kitchen_model->getNewOrders($outlet_id);
        $i = 0;
        for($i;$i<count($data1);$i++){
               //update for bell
               $data_bell = array();
               $data_bell['is_kitchen_bell'] = 2;
               $this->Common_model->updateInformation($data_bell, $data1[$i]->sale_id, "tbl_sales");


            $data1[$i]->total_kitchen_type_items = $this->Kitchen_model->get_total_kitchen_type_items($data1[$i]->sale_id);
            $data1[$i]->total_kitchen_type_done_items = $this->Kitchen_model->get_total_kitchen_type_done_items($data1[$i]->sale_id);
            $data1[$i]->total_kitchen_type_started_cooking_items = $this->Kitchen_model->get_total_kitchen_type_started_cooking_items($data1[$i]->sale_id);
            $data1[$i]->tables_booked = $this->Kitchen_model->get_all_tables_of_a_sale_items($data1[$i]->sale_id);
            $items_by_sales_id = $this->Kitchen_model->getAllKitchenItemsFromSalesDetailBySalesId($data1[$i]->sale_id);

            foreach($items_by_sales_id as $single_item_by_sale_id){
                $modifier_information = $this->Kitchen_model->getModifiersBySaleAndSaleDetailsId($data1[$i]->sale_id,$single_item_by_sale_id->sales_details_id);
                $single_item_by_sale_id->modifiers = $modifier_information;
            }
            $data1[$i]->items = $items_by_sales_id;
        }
        return $data1;
    }
    public function update_cooking_status_ajax()
    {
        $previous_id = $this->input->post('previous_id');
        $previous_id_array = explode(",",$previous_id);
        $cooking_status = $this->input->post('cooking_status');
        $total_item = count($previous_id_array);

        // $item_info = $this->Kitchen_model->getItemInfoByPreviousId($previous_id);
        // $sale_id = $item_info->sales_id;
        // $sale_info = $this->get_all_information_of_a_sale_kitchen_type($sale_id);

        foreach($previous_id_array as $single_previous_id){
            $previous_id = $single_previous_id;
            $item_info = $this->Kitchen_model->getItemInfoByPreviousId($previous_id);
            $sale_id = $item_info->sales_id;
            $sale_info = $this->Kitchen_model->getSaleBySaleId($sale_id);
            $sale_info[0]->tables_booked = $this->Kitchen_model->get_all_tables_of_a_sale_items($sale_id);
            $tables_booked = '';
            if(count($sale_info[0]->tables_booked)>0){
                $w = 1;
                foreach($sale_info[0]->tables_booked as $single_table_booked){
                    if($w == count($sale_info[0]->tables_booked)){
                        $tables_booked .= $single_table_booked->table_name;
                    }else{
                        $tables_booked .= $single_table_booked->table_name.', ';
                    }
                    $w++;
                }
            }else{
                $tables_booked = 'None';
            }
            if($cooking_status=="Started Cooking"){
                $cooking_status_update_array = array('cooking_status' => $cooking_status, 'cooking_start_time' => date('Y-m-d H:i:s'));

                $this->db->where('previous_id', $previous_id);
                $this->db->update('tbl_sales_details', $cooking_status_update_array);

                if($sale_info[0]->date_time==strtotime('0000-00-00 00:00:00')){
                    $cooking_update_array_sales_tbl = array('cooking_start_time' => date('Y-m-d H:i:s'));
                    $this->db->where('id', $sale_id);
                    $this->db->update('tbl_sales', $cooking_update_array_sales_tbl);
                }

            }else{

                $cooking_status_update_array = array('cooking_status' => $cooking_status, 'cooking_done_time' => date('Y-m-d H:i:s'));

                $this->db->where('previous_id', $previous_id);
                $this->db->update('tbl_sales_details', $cooking_status_update_array);

                $cooking_update_array_sales_tbl = array('cooking_done_time' => date('Y-m-d H:i:s'));
                $this->db->where('id', $sale_id);
                $this->db->update('tbl_sales', $cooking_update_array_sales_tbl);

                if($sale_info[0]->order_type==1){
                    $order_name = "A ".$sale_info[0]->sale_no;
                }elseif($sale_info[0]->order_type==2){
                    $order_name = "B ".$sale_info->sale_no;
                }elseif($sale_info[0]->order_type==3){
                    $order_name = "C ".$sale_info[0]->sale_no;
                }
                $notification = 'Item: '.$item_info->menu_name.' is ready to serve, Table: '.$tables_booked.', Customer: '.$sale_info[0]->customer_name.', Order: '.$order_name;
                $notification_data = array();
                $notification_data['notification'] = $notification;
                $notification_data['sale_id'] = $sale_id;
                $notification_data['outlet_id'] = $this->session->userdata('outlet_id');
                $this->db->insert('tbl_notifications', $notification_data);
                // if($i==$total_item){
                //     $sale_info = $this->get_all_information_of_a_sale_kitchen_type($sale_id);

                //     if($sale_info->order_type==1){
                //         $order_name = "A ".$sale_info->sale_no;
                //     }elseif($sale_info->order_type==2){
                //         $order_name = "B ".$sale_info->sale_no;
                //     }elseif($sale_info->order_type==3){
                //         $order_name = "C ".$sale_info->sale_no;
                //     }

                //     $notification = 'Customer: '.$sale_info->customer_name.', Order: '.$order_name.' is ready to serve';
                //     $notification_data = array();
                //     $notification_data['notification'] = $notification;
                //     $notification_data['outlet_id'] = $this->session->userdata('outlet_id');
                //     $this->db->insert('tbl_notifications', $notification_data);
                // }
            }
            $i++;
        }





        // $cooking_status = $this->input->post('cooking_status');
        // if($cooking_status=="Started Cooking"){
        //     $cooking_status_update_array = array('cooking_status' => $cooking_status, 'cooking_start_time' => date('Y-m-d H:i:s'));
        // }elseif($cooking_status=="Done"){
        //     $cooking_status_update_array = array('cooking_status' => $cooking_status, 'cooking_done_time' => date('Y-m-d H:i:s'));

        //     if($sale_info->order_type==1){
        //         $order_name = "A ".$sale_info->sale_no;
        //     }elseif($sale_info->order_type==2){
        //         $order_name = "B ".$sale_info->sale_no;
        //     }elseif($sale_info->order_type==3){
        //         $order_name = "C ".$sale_info->sale_no;
        //     }
        //     $notification = 'Item: '.$item_info->menu_name.' is ready to serve, Table: '.$sale_info->table_name.', Customer: '.$sale_info->customer_name.', Order: '.$order_name;
        //     $notification_data = array();
        //     $notification_data['notification'] = $notification;
        //     $notification_data['outlet_id'] = $this->session->userdata('outlet_id');
        //     $this->db->insert('tbl_notifications', $notification_data);
        // }
        // $this->db->where('previous_id', $previous_id);
        // $this->db->update('tbl_sales_details', $cooking_status_update_array);



        // $kitchen_type_items_in_one_order = $this->Kitchen_model->get_total_kitchen_type_items($sale_id);
        // $kitchen_type_items_done = $this->Kitchen_model->get_total_kitchen_type_done_items($sale_id);
        // $kitchen_type_items_started = $this->Kitchen_model->get_total_kitchen_type_started_cooking_items($sale_id);

        // $progressed_items = $kitchen_type_items_done+$kitchen_type_items_started;
        // if($progressed_items==1 && $cooking_status=="Started Cooking"){
        //     $cooking_update_array_sales_tbl = array('cooking_start_time' => date('Y-m-d H:i:s'));
        //     $this->db->where('id', $sale_id);
        //     $this->db->update('tbl_sales', $cooking_update_array_sales_tbl);
        // }elseif($progressed_items>=1 && $cooking_status=="Done"){
        //     $cooking_update_array_sales_tbl = array('cooking_done_time' => date('Y-m-d H:i:s'));
        //     $this->db->where('id', $sale_id);
        //     $this->db->update('tbl_sales', $cooking_update_array_sales_tbl);
        // }
    }
    public function update_cooking_status_delivery_take_away_ajax(){
        $previous_id = $this->input->post('previous_id');
        $previous_id_array = explode(",",$previous_id);
        $cooking_status = $this->input->post('cooking_status');
        $total_item = count($previous_id_array);
        $i = 1;
        foreach($previous_id_array as $single_previous_id){
            $previous_id = $single_previous_id;
            $item_info = $this->Kitchen_model->getItemInfoByPreviousId($previous_id);
            $sale_id = $item_info->sales_id;
            if($cooking_status=="Started Cooking"){
                $cooking_status_update_array = array('cooking_status' => $cooking_status, 'cooking_start_time' => date('Y-m-d H:i:s'));

                $this->db->where('previous_id', $previous_id);
                $this->db->update('tbl_sales_details', $cooking_status_update_array);

                $cooking_update_array_sales_tbl = array('cooking_start_time' => date('Y-m-d H:i:s'));
                $this->db->where('id', $sale_id);
                $this->db->update('tbl_sales', $cooking_update_array_sales_tbl);
            }else{
                $cooking_status_update_array = array('cooking_status' => $cooking_status, 'cooking_done_time' => date('Y-m-d H:i:s'));

                $this->db->where('previous_id', $previous_id);
                $this->db->update('tbl_sales_details', $cooking_status_update_array);

                $cooking_update_array_sales_tbl = array('cooking_done_time' => date('Y-m-d H:i:s'));
                $this->db->where('id', $sale_id);
                $this->db->update('tbl_sales', $cooking_update_array_sales_tbl);

                if($i==$total_item){
                    $sale_info = $this->get_all_information_of_a_sale_kitchen_type($sale_id);
                    $order_type_operation = '';
                    if($sale_info->order_type==1){
                        $order_name = "A ".$sale_info->sale_no;
                    }elseif($sale_info->order_type==2){
                        $order_name = "B ".$sale_info->sale_no;
                        $order_type_operation = 'El pedido para llevar esta listo para llevar';
                    }elseif($sale_info->order_type==3){
                        $order_name = "C ".$sale_info->sale_no;
                        $order_type_operation = 'La orden de entrega está lista para entrega';
                    }
                    $notification = 'Customer: '.$sale_info->customer_name.', Order Number: '.$order_name.' '.$order_type_operation;
                    $notification_data = array();
                    $notification_data['notification'] = $notification;
                    $notification_data['sale_id'] = $sale_id;
                    $notification_data['outlet_id'] = $this->session->userdata('outlet_id');
                    $this->db->insert('tbl_notifications', $notification_data);
                }
            }
            $i++;
        }
    }
    public function getFoodMenuBySaleId(){
        $sale_id = $this->input->get('sale_id');
        $data = $this->Kitchen_model->getFoodMenuBySaleId($sale_id);
        echo  json_encode($data);
    }

    public function getCurrentFood(){
        $data = $this->Kitchen_model->getUnReadyOrders();
        echo  json_encode($data);
    }
    public function checkUnreadyFoodMenus(){
        $sale_id = $this->input->get('sale_id');
        $getData = getUnreadyFoodStatus($sale_id);
        $data['TotalUnreadyFood'] = $getData;
        echo json_encode($data);
    }
    public function setOrderReady(){
        $sale_details_id = $this->input->get('sale_details_id');
        $data = $this->Kitchen_model->setOrderReady($sale_details_id);
         $data['status'] = 'true';
        echo json_encode($data);
    }
    public function setOrderReadyAll(){
        $sale_id = $this->input->get('sale_id');
        $data = $this->Kitchen_model->setOrderReadyAll($sale_id);
         $data['status'] = 'true';
        echo json_encode($data);
    }
    public function get_new_notification()
    {
        $outlet_id = $this->session->userdata('outlet_id');
        $notifications = $this->Kitchen_model->getNotificationByOutletId($outlet_id);
        return $notifications;
    }
    public function get_new_notifications_ajax()
    {
        echo json_encode($this->get_new_notification());
    }
    public function remove_notication_ajax()
    {
        $notification_id = $this->input->post('notification_id');
        $this->db->delete('tbl_notification_bar_kitchen_panel', array('id' => $notification_id));
        echo $notification_id;
    }
    public function remove_multiple_notification_ajax()
    {
        $notifications = $this->input->post('notifications');
        $notifications_array = explode(",",$notifications);
        foreach($notifications_array as $single_notification){
            $this->db->delete('tbl_notification_bar_kitchen_panel', array('id' => $single_notification));
        }
    }

    /* ----------------------Dashboard Menu End-------------------------- */
}
