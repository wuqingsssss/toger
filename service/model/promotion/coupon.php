<?php
class ModelPromotionCoupon extends Model {

	/**
	 * 获取优惠券
	 * Enter 
	 * @param int customer_id
	 */	
	public function  getCoupon($customer_id)
	{
		
		
	}
	/**
	 * 生成优惠券
	 * Enter
	 * @param int customer_id
	 */
	public function genCoupon($phone,$type)	{
		

		return 'newcode';
	}
	/**
	 * 获取优惠券分享链接
	 * Enter
	 * @param int customer_id
	 */
	public function getShareLink($customer_id){
		
		return HTTPS_SERVER . "index.php?route=common/home&cu=$customer_id&p=coupon";
		
	}
	/**
	 * 领取优惠券(与用户绑定)
	 * Enter
	 * @param int customer_id
	 */
	public function bindUser($phone,$p_code,$pid,$sid){
		
		return true;
	}
	/**
	 * 获取免单优惠券
	 * Enter
	 * @param int customer_id
	 */
	public function getOrderCoupon($customer_id){
		
		
	}
	/**
	 * 获取可用的优惠券
	 * Enter
	 * @param int customer_id
	 */
    public function getAvailableCoupons($customer_id){
		
		
	}
	/**
	 * 获取可用的优惠券数目
	 * Enter
	 * @param int customer_id
	 */
    public function getAvailableCouponsTotal($customer_id){
		
		
	}
	/**
	 * 使用优惠券(结账流程使用)
	 * Enter
	 * @param int customer_id
	 */
    public function applyCoupon($code,$customer_id)	{
		
		
	}

}
?>