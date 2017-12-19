<?php
// Heading  
$_['heading_title'] = '优惠券/礼包发放管理';

// Text
$_['text_success'] = '成功: 您已成功添加优惠券/礼包!';
$_['text_percent'] = '订单折扣%';
$_['text_product'] = '单品折扣%';
$_['text_amount'] = '固定金额';
$_['text_caipiao'] = '菜票';
$_['text_dist_coupon'] = '发放优惠券';
$_['text_dist_packet'] = '发放礼包';

// Button
$_['btn_dist_coupon'] = '发放优惠券';
$_['btn_dist_packet'] = '发放礼包';

// Column
$_['column_name'] = '优惠券名称';
$_['column_code'] = '优惠券代码';
$_['column_mobile'] = '电话号码';
$_['column_discount'] = '折扣';
$_['column_date_start'] = '开始日期';
$_['column_date_end'] = '结束日期';
$_['column_free_get'] = '自由领取';
$_['column_status'] = '状态';
$_['column_order_id'] = '订单号';
$_['column_customer'] = '客户';
$_['column_amount'] = '合计';
$_['column_date_added'] = '添加日期';
$_['column_duration'] = '有效期间';
$_['column_action'] = '管理';

// Entry
$_['entry_name'] = '优惠券名称:';
$_['entry_owner'] = '&nbsp;拥有人:';
$_['entry_code'] = '优惠券代码:<br /><span class="help">客人输入本代码后获得折扣</span>';
$_['entry_batch'] = '批量生成:<br /><span class="help">批量生成时，以上两项作为前缀使用</span>';
$_['entry_type'] = '类型:<br /><span class="help">百分比或固定数额</span>';
$_['entry_discount'] = '折扣:';
$_['entry_logged'] = '&nbsp;用户登入:<br /><span class="help">用户必须登入后才能使用优惠券.</span>';
$_['entry_shipping'] = '&nbsp;免费运送:';
$_['entry_total'] = '总计:<br /><span class="help">必须大于本数值，优惠券才能生效.</span>';
$_['entry_product'] = '有效商品:<br /><span class="help">适用商品范围，空表示适用所有商品</span>';
$_['entry_date_start'] = '开始日期:';
$_['entry_date_end'] = '结束日期:';
$_['entry_duration'] = '有效期间（天）:<br /><span class="help">从领用日开始有效天数（含当天），0表示本参数无效，直接使用【结束日期】</span>';
$_['entry_uses_total'] = '&nbsp;每张优惠券可以使用次数:<br /><span class="help">所有客户可使用优惠券的最大次数. 空白既无限制</span>';
$_['entry_uses_customer'] = '&nbsp;每个客户可以使用次数:<br /><span class="help">每个客户可使用优惠券的最大次数. 空白既无限制</span>';
$_['entry_free_get'] = '&nbsp;自由领取:';
$_['entry_status'] = '&nbsp;状态:';
$_['entry_usage'] = '&nbsp;使用说明:<br /><span class="help">限定64个字以内</span>';

// Error
$_['error_permission'] = '警告: 您没有权限更改优惠券设置!';
$_['error_name'] = '优惠券名称必须在1至64个字符之间!';
$_['error_usage'] = '使用说明必须在1至64个字符之间!';
$_['error_code'] = '代码必须在3到10个字符之间!';
$_['error_batch'] = '必须为整数!';
?>