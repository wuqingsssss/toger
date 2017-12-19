<?php
//只用于显示
$GLOBALS['PERM_GROUPS'] = array(
    'others' => '其他',
    'sale_orders' => '销售订单',
    'refund_orders' => '退单',
    'purchase_orders' => '生产发注',
    'self_help_points' => '自提点',
    'sorting_orders' => '分拣单',
    'coupons' => '优惠券'
);

//超级管理可见的菜单权限为 super_admin,不作为配置项

$GLOBALS['PERM_DEFINES'] = array(
    array('code' => 'product_admin', 'display' => '产品管理', 'groupCode' => 'others'),
    array('code' => 'info_admin', 'display' => '信息管理', 'groupCode' => 'others'),
    array('code' => 'system_admin', 'display' => '系统设置', 'groupCode' => 'others'),

    array('code' => 'sale_manage_home', 'display' => '营销管理首页', 'groupCode' => 'others'),
    array('code' => 'other_sale_funcs', 'display' => '其他营销功能', 'groupCode' => 'others'),

    array('code' => 'sale_orders', 'display' => '订单一览', 'groupCode' => 'sale_orders'),
    array('code' => 'sale_orders:detail', 'display' => '订单详细', 'groupCode' => 'sale_orders'),
    array('code' => 'sale_orders:add', 'display' => '订单追加', 'groupCode' => 'sale_orders'),
    array('code' => 'sale_orders:update', 'display' => '订单修改/删除', 'groupCode' => 'sale_orders'),

    array('code' => 'refund_orders', 'display' => '退单一览', 'groupCode' => 'refund_orders'),
    array('code' => 'refund_orders:phase1:confirm', 'display' => '退单审核(销售)', 'groupCode' => 'refund_orders'),
    array('code' => 'refund_orders:phase2:confirm', 'display' => '退单审核(财务)', 'groupCode' => 'refund_orders'),

    array('code' => 'purchase_orders', 'display' => '发注一览', 'groupCode' => 'purchase_orders'),
    array('code' => 'purchase_orders:detail', 'display' => '发注详细', 'groupCode' => 'purchase_orders'),
    array('code' => 'purchase_orders:add', 'display' => '生成发注', 'groupCode' => 'purchase_orders'),
    array('code' => 'purchase_orders:source_order:confirm', 'display' => '订单审核', 'groupCode' => 'purchase_orders'),

    array('code' => 'sorting_orders', 'display' => '分拣单', 'groupCode' => 'sorting_orders'),

    array('code' => 'self_help_points', 'display' => '自提点一览', 'groupCode' => 'self_help_points'),
    array('code' => 'self_help_points:detail', 'display' => '自提点详细', 'groupCode' => 'self_help_points'),
    array('code' => 'self_help_points:modify', 'display' => '自提点修改', 'groupCode' => 'self_help_points'),

    array('code' => 'coupons', 'display' => '优惠券一览', 'groupCode' => 'coupons'),
    array('code' => 'coupons:add', 'display' => '优惠券增加', 'groupCode' => 'coupons'),
    array('code' => 'coupons:modify', 'display' => '优惠券修改', 'groupCode' => 'coupons'),

);

function getGroupedPermDefines() {
    global $PERM_DEFINES, $PERM_GROUPS;

    $result = array();

    $tempPerms = array();
    $tempGroup = null;
    foreach ($PERM_DEFINES as $perm) {
        $group = $PERM_GROUPS[$perm['groupCode']];
        if (is_null($tempGroup)) {
            $tempGroup = $group;
        } else if ($tempGroup != $group) {
            $result[] = array(
                "group" => $tempGroup,
                "perms" => $tempPerms
            );
            $tempGroup = $group;
            $tempPerms = array();
        }
        $tempPerms[] = $perm;
    }

    //clear
    if(!empty($tempPerms)){
        $result[] = array(
            "group" => $tempGroup,
            "perms" => $tempPerms
        );
    }

    return $result;
}
