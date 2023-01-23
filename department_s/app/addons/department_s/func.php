<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

use Tygh\Languages\Languages;

defined('BOOTSTRAP') or die('Access denied');

function fn_get_departments($params = array(), $items_per_page = 0, $lang_code = CART_LANGUAGE)
{
    // Set default values to input params
    $default_params = array(
        'page' => 1,
        'items_per_page' => $items_per_page
    );

    $params = array_merge($default_params, $params);

    if (AREA == 'C') {
        $params['status'] = 'A';
    }

    $sortings = array(
        'id' => '?:departments.department_id',
        'name' => '?:departments.name',
        'timestamp' => '?:departments.timestamp',
        'status' => '?:departments.status',
    );

    $condition = $limit = $join = '';

    if (!empty($params['limit'])) {
        $limit = db_quote(' LIMIT 0, ?i', $params['limit']);
    }

    $sorting = db_sort($params, $sortings, 'name', 'asc');


    if (!empty($params['item_ids'])) {
        $condition .= db_quote(' AND ?:departments.department_id IN (?n)', explode(',', $params['item_ids']));
    }

    if (!empty($params['department_id'])) {
        $condition .= db_quote(' AND ?:departments.department_id = ?i', $params['department_id']);
    }

    if (!empty($params['status'])) {
        $condition .= db_quote(' AND ?:departments.status = ?s', $params['status']);
    }

    $fields = array (
        '?:departments.*',
        '?:department_lead.user_id as lead_id',
        '?:department_staff.user_id as staff_id',
    );

    $join .= db_quote(' 
    LEFT JOIN ?:department_lead 
    ON ?:department_lead.department_id = ?:departments.department_id 
    LEFT JOIN ?:department_staff 
    ON ?:department_staff.department_id = ?:departments.department_id ');

    if (!empty($params['items_per_page'])) {
        $params['total_items'] = db_get_field("SELECT COUNT(*) FROM ?:departments $join WHERE 1 $condition");
        $limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
    }

    $departments = db_get_hash_array(
        'SELECT ?p FROM ?:departments ' .
        $join .
        'WHERE 1 ?p ?p ?p',
        'department_id', implode(', ', $fields), $condition, $sorting, $limit
    );

    $department_image_ids = array_keys($departments);
    $images = fn_get_image_pairs($department_image_ids, 'department', 'M', true, false, $lang_code);

    foreach ($departments as $department_id => $department) {
       $departments[$department_id]['main_pair'] = !empty($images[$department_id]) ? reset($images[$department_id]) : array();
    }

    

    return array($departments, $params);
}

function fn_get_department_data($department_id = 0, $lang_code = CART_LANGUAGE)
{
    if (!empty($department_id)){
        list($departments) = fn_get_departments(['department_id' => $department_id], 1, $lang_code);
        if(!empty($departments)){
            $department = reset($departments);
            $department['staff_id'] = fn_department_get_staff($department['department_id']);
        }
    }
    
    return $department;
}

function fn_update_department($data, $department_id, $lang_code = DESCR_SL)
{
    $creation_time = (isset($data['timestamp'])) ? fn_parse_date($data['timestamp']) : 0;
        if (empty($department_id) &&
            (empty($creation_time) || $creation_time == mktime(0, 0, 0, date("m"), date("d"), date("Y")))) {
            $data['timestamp'] = time();
        } elseif (!empty($creation_time) && $creation_time != fn_get_product_timestamp($department_id, true)) {
            $data['timestamp'] = $creation_time;
        } else {
            unset($data['timestamp']);
        }
    
    if (!empty($department_id)) {
        db_query('UPDATE ?:departments SET ?u WHERE department_id = ?i', $data, $department_id);
    } else {
        foreach (Languages::getAll() as $data['lang_code'] => $v) {
            $department_id = $data['department_id'] = db_replace_into('departments', $data);
            db_query('REPLACE INTO ?:departments ?e', $data);
        }
    }


    if (!empty($department_id)) {
        fn_attach_image_pairs('department', 'department', $department_id, $lang_code);
    }

    $lead_id = !empty($data['lead_id']) ? $data['lead_id'] : [];
    $lead_table = 'lead';
    fn_department_delete_user_id($department_id, $lead_table);
    fn_department_add_user_id($department_id, $lead_id, $lead_table);

    $staff_id = !empty($data['staff_id']) ? $data['staff_id'] : [];
    $staff_id = !empty($staff_id) ? explode(',', $staff_id) : [];
    $staff_table = 'staff';
    fn_department_delete_user_id($department_id, $staff_table);
    fn_department_add_user_id($department_id, $staff_id, $staff_table);
    return $department_id;
}

function fn_department_add_user_id($department_id, $user_ids, $table)
{
    if(!empty($user_ids) && is_array($user_ids)){
        foreach($user_ids as $user_id){
            db_query('REPLACE INTO ?:department_' . $table . ' ?e', [
                'department_id' => $department_id,
                'user_id' => $user_id,
            ]);
        } 
    } elseif (!empty($user_ids)) {
        db_query('REPLACE INTO ?:department_' . $table . ' ?e', [
            'department_id' => $department_id,
            'user_id' => $user_ids,
        ]);
    }
}

function fn_department_delete_user_id($department_id, $table)
{
    db_query('DELETE FROM ?:department_' . $table . ' WHERE department_id = ?i', $department_id);
}

function fn_department_get_staff($department_id)
{
    return !empty($department_id) ? db_get_fields('SELECT user_id FROM ?:department_staff WHERE department_id = ?i', $department_id) : [];
}

function fn_delete_department($department_id, $department_ids)
{    
    if (!empty($department_id)) {
        db_query('DELETE FROM ?:departments WHERE department_id = ?i', $department_id);
        db_query('DELETE FROM ?:department_lead WHERE department_id = ?i', $department_id);
        db_query('DELETE FROM ?:department_staff WHERE department_id = ?i', $department_id);
    }
}