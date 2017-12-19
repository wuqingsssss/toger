<?php
class ControllerCheckoutCart extends Controller {
	public function index() {
	    
		if (!$this->customer->isLogged()) {
			//$this->session->data['redirect'] = $this->url->link('checkout/cart', '', 'SSL');
			$this->setback();
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$promotion = array();

			// 设置取菜时间
			if (isset($this->request->post['date']) && $this->request->post['date']) {
				$current_time = $this->request->post['date'];
				//用session记录选择的菜品提菜时间
				$this->cart->setAdditionalDate($current_time);
			} // 设置菜品数量      
			if (isset($this->request->post['quantity'])) {
				//不知道什么时候会执行非数组的代码－李涛20150402
				if (!is_array($this->request->post['quantity'])) {
					// 附购信息
					if (isset($this->request->post['option'])) {
						$option = $this->request->post['option'];
					} else {
						$option = array();
					}

					//促销信息
					if (isset($this->request->post['pr_code'])) {
						$this->load->model('promotion/promotion');
						$price = $this->model_promotion_promotion->getPromotionProductPrice($this->request->post['pr_code'], $this->request->post['product_id']);
						$promotion['promotion_code'] = $this->request->post['pr_code'];
						$promotion['promotion_price'] = $price;
					}

					$this->cart->add($this->request->post['product_id'], $this->request->post['quantity'], $option, $promotion);
				} else {
					$key_exchange_buy = $this->cart->checkPromotionProductNumber(EnumPromotionTypes::EXCHANGE_BUY);
					foreach ($this->request->post['quantity'] as $key => $value) {
						//判断当前取货时间和key中所存时间是否相同，相同时直接更新key，不同时废弃原有key后重新加入
						//处理key的公共逻辑
						if (empty($key_exchange_buy) || $key_exchange_buy != $key) {
							$this->cart->update($key, $value);
						}
					}
				}
			}

			//多个商品批量加入购物车的逻辑
			if (isset($this->request->post['product_ids'])) {
				foreach ($this->request->post['product_ids'] as $product_id) {
					if ($product_id) {
						$option = array();
						$this->cart->add($product_id, 1, $option, $promotion);
					}
				}
			}

			// 从购物车删除商品
			if (isset($this->request->post['remove'])) {
				foreach ($this->request->post['remove'] as $key) {
					$this->cart->remove($key);
				}
			}

			// 收据（目前没用途）
			if (isset($this->request->post['voucher']) && $this->request->post['voucher']) {
				foreach ($this->request->post['voucher'] as $key) {
					if (isset($this->session->data['vouchers'][$key])) {
						unset($this->session->data['vouchers'][$key]);
					}
				}
			}

			// 重新定位
			if (isset($this->request->post['redirect'])) {
				$this->session->data['redirect'] = $this->request->post['redirect'];
			}

			// 清除无关SESSION
			if (isset($this->request->post['remove']) || isset($this->request->post['voucher'])) {
				$this->removeRelatedSessions();
			}

			$this->redirect($this->url->link('checkout/cart'));
		}

		$this->load_language('checkout/cart');
		$this->document->setTitle($this->language->get('heading_title'));
		
		// 页面头
		$header_setting =  array('left'    =>  array( href => $this->url->link('common/home'),
		    text => $this->language->get("header_left")),
		    'center'  =>  array( href => "javascript:location.reload(true);",
		        text => $this->document->getTitle()),
		    'name'    =>  $this->document->getTitle()
		);
			
		$this->data['header'] = $this->getChild('module/header', $header_setting);

		//删除指定商品
		if (isset($this->request->get['remove'])) {
			$this->cart->remove($this->request->get['remove']);

			$this->redirect($this->url->link('checkout/cart'));
		}

		// 换购商品加入购物车
		$key = $this->cart->checkPromotionProductNumber(EnumPromotionTypes::EXCHANGE_BUY);
		if (isset($this->request->get['pr_code']) && isset($this->request->get['product_id']) && empty($key)) {
			//促销信息    
			$prom = array();
			$option = array();

			$this->load->model('promotion/promotion');
			$price = $this->model_promotion_promotion->getPromotionProductPrice($this->request->get['pr_code'], $this->request->get['product_id']);
			$prom['promotion_code'] = $this->request->get['pr_code'];
			$prom['promotion_price'] = $price['price'];

			$this->cart->add($this->request->get['product_id'], 1, $option, $prom);
			$this->redirect($this->url->link('checkout/cart'));
		}

		// 换购处理
		$this->load->model('promotion/promotion');
		$pro_info = $this->model_promotion_promotion->getPromotionRule(EnumPromotionTypes::EXCHANGE_BUY);
		// 存在换购活动?
		if (!empty($pro_info)) {
			$subtotal = $this->cart->getSubTotal();
			if ($subtotal - $pro_info['total'] > -0.0001) { // 是否满足换购条件
				// 获取换购商品列表
				$pro_list = $this->model_promotion_promotion->getPromotionProduct(EnumPromotionTypes::EXCHANGE_BUY);
				if (!empty($pro_list)) {
					if (!empty($key)) { // 已经换购
						$exchange_buy = array(
										'exchange_buy_btn' => $this->language->get('text_cancel_exchange'),
										'text' => $this->language->get('text_exchange_success'),
										'href' => $this->url->link('checkout/cart', 'remove=' . $key)
						);
					} else { //尚未换购
						$btn_name = sprintf($this->language->get('text_exchange_buy'), $this->currency->format($pro_list[0]['price']), $pro_list[0]['name']);
						$exchange_buy = array(
										'exchange_buy_btn' => $btn_name,
										'text' => $this->language->get('text_exchange_info'),
										'href' => $this->url->link('checkout/cart', 'quantity=1&product_id=' . $pro_list[0]['product_id'] . '&pr_code=' . EnumPromotionTypes::EXCHANGE_BUY)
						);
					}
				}
			} else { //未满足换购条件
				$text = sprintf($this->language->get('text_exchange_none'), $this->currency->format($pro_info['total'] - $subtotal));
				$exchange_buy = array(
								'exchange_buy_btn' => $this->language->get('text_exchange_continue'),
								'text' => $text,
								'href' => $this->url->link('common/home'),
				);

				if (!empty($key)) { // 已经换购
					$this->cart->remove($key);
				}
			}

			$this->data['exchange_buy'] = $exchange_buy;
		}


		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
						'href' => $this->url->link('common/home'),
						'text' => $this->language->get('text_home'),
						'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
						'href' => $this->url->link('checkout/cart'),
						'text' => $this->language->get('heading_title'),
						'separator' => $this->language->get('text_separator')
		);




		$period = $this->cart->getPeriod();
		$this->data['dates'] = $period['picktime']; //getPickTimeOptions($period);方法已内置20150402


		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';

		if ($period && ($this->cart->hasProducts() || (isset($this->session->data['vouchers']) && $this->session->data['vouchers']))) {

			if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
				$this->data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
			} else {
				$this->data['attention'] = '';
			}

			$this->data['action'] = $this->url->link('checkout/cart');
			$this->data['remove'] = $this->url->link('checkout/cart/remove');

			if ($this->config->get('config_cart_weight')) {
				$this->data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			} else {
				$this->data['weight'] = false;
			}

			$this->load->model('tool/image');

			$this->data['products'] = array();

			foreach ($this->cart->getProducts() as $result) {
				$image = resizeThumbImage($result['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'), TRUE);

				$option_data = array();

				if ($result['minimum'] > $result['quantity']) {
					$this->session->data['error'] = sprintf($this->language->get('error_minimum'), $result['name'], $result['minimum']);
				}

				foreach ($result['option'] as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
										'name' => $option['name'],
										'value' => (strlen($option['option_value']) > 20 ? substr($option['option_value'], 0, 20) . '..' : $option['option_value'])
						);
					} else {
						$this->load->library('encryption');

						$encryption = new Encryption($this->config->get('config_encryption'));

						$file = substr($encryption->decrypt($option['option_value']), 0, strrpos($encryption->decrypt($option['option_value']), '.'));

						$option_data[] = array(
										'name' => $option['name'],
										'value' => (strlen($file) > 20 ? substr($file, 0, 20) . '..' : $file)
						);
					}
				}

				$promotion = array();
				$promotion['promotion_code'] = $result['promotion']['promotion_code'];
				$price_value = floatval($result['price']);//未格式化的金额
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				$total = $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax')));

				if (isset($result['promotion']['promotion_price'])) {
					$promotion['promotion_value'] = floatval($result['promotion']['promotion_price']);//未格式化的金额
					$promotion['promotion_price'] = $this->currency->format($this->tax->calculate((float) $result['promotion']['promotion_price'], $result['tax_class_id'], $this->config->get('config_tax')));
				}
				$this->data['products'][] = array(
								'product_id' => $result['product_id'],
								'key' => $result['key'],
								'thumb' => $image,
								'name' => $result['name'],
								'model' => $result['model'],
								'donation' => $result['donation'],
								'option' => $option_data,
								'quantity' => $result['quantity'],
								'stock' => $result['stock'],
								'points' => ($result['points'] ? sprintf($this->language->get('text_points'), $result['points']) : ''),
								'price' => $price,
								'price_value' => $price_value,
								'total' => $total,
								'promotion' => $promotion,
								'remove' => $this->url->link('checkout/cart', 'remove=' . $result['key']),
								'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			/* 对结果按照时间重新排序 */
			$products = $this->data['products'];

			$this->data['groups'] = array();

			foreach ($products as $result) {
				if (isset($result['additional']['date']) && $result['additional']['date']) {
					$this->data['groups'][$result['additional']['date']][] = $result;
				} else {
					$this->data['groups'][0][] = $result;
				}
			}
			
			ksort($this->data['groups']);
//			var_dump($this->data['groups']);exit;
			// Gift Voucher
			$this->data['vouchers'] = array();

			if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$this->data['vouchers'][] = array(
									'key' => $key,
									'description' => $voucher['description'],
									'amount' => $this->currency->format($voucher['amount'])
					);
				}
			}

			$total_data = array();
			$total = array();
			$total['promotion'] = 0;
			$total['general'] = 0;
			$total['fee'] = 0;
			$total['discount'] = 0;
			$total['total'] = 0;
			$taxes = $this->cart->getTaxes();

			$this->load->model('total/sub_total');
			$this->model_total_sub_total->getTotal($total_data, $total, $taxes);

			$this->data['totals'] = $total_data;


			// Modules
			$this->data['modules'] = array();


//			$this->data['modules'][] = $this->getChild('custom/libao');

			if (isset($this->session->data['redirect'])) {
				$this->data['continue'] = $this->session->data['redirect'];

				unset($this->session->data['redirect']);
			} else {
				$this->data['continue'] = $this->url->link('common/home');
			}

			if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$this->data['error_warning'] = $this->language->get('error_stock');
			} elseif (isset($this->session->data['error'])) {
				$this->data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$this->data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$this->data['success'] = '';
			}

			$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

			//增加取菜时间的设定
//            $this->data['dates'] = getTakeTimeOptions();





			/*
			  if (isset($this->request->post['date']) && $this->request->post['date']) {
			  $this->data['select_date'] = $this->request->post['date'];
			  } else {
			  $this->data['select_date'] = $this->data['dates'][0];
			  } */

			if ($this->cart->getAdditionalDate()) {
				$this->data['select_date'] = $this->cart->getAdditionalDate();
			} else {
				$this->data['select_date'] = $this->data['dates'][0];
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/cart.tpl';
			} else {
				$this->template = 'default/template/checkout/cart.tpl';
			}

			$this->children = array(
							'common/column_left',
							'common/column_right',
							'common/content_top',
							'common/content_bottom',
							'common/footer35',
							'common/header35'
			);

			$this->response->setOutput($this->render());
		} 
		else 
		{
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart_empty.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/cart_empty.tpl';
			} else {
				$this->template = 'default/template/checkout/cart_empty.tpl';
			}

			$this->children = array(
							'common/column_left',
							'common/column_right',
							'common/content_top',
							'common/content_bottom',
							'common/footer35',
							'common/header35'
			);

			$this->response->setOutput($this->render());
		}
	}

	/**
	 * 从购物车删除指定商品
	 */
	public function remove() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['remove'])) {
				foreach ($this->request->post['remove'] as $key) {
					$this->cart->remove($key);
				}
			}
			
			//手机端购物车 用于删除商品 并且返回购物车剩余商品数量 返回json
			if(isset($this->request->post['type']) && $this->request->post['type'] == 'mobile'){
				$json['count'] = $this->cart->countProducts();
				echo json_encode($json);
				return;
			}
		}

		$this->redirect($this->url->link('checkout/cart'));
	}

	/**
	 * 更新购物车
	 */
	public function update() {
		$this->load_language('checkout/cart');

		$json = array();
		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('account/login', '', 'SSL');
			$this->response->setOutput(json_encode($json));
			return;
		}


		$promotion = array();

		// 设置取菜时间
		if (isset($this->request->post['date']) && $this->request->post['date']) {
			$current_time = $this->request->post['date'];
			//用session记录选择的菜品提菜时间
			$this->cart->setAdditional($current_time);
		}

		if (isset($this->request->post['product_id'])) {
			$this->load->model('catalog/product');

			$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

			if ($product_info) {
				//设置商品信息里的优惠信息
				$promotion = $product_info['promotion'];
				// Minimum quantity validation
				if (isset($this->request->post['quantity'])) {
					$quantity = $this->request->post['quantity'];
				} else {
					$quantity = 1;
				}
				//获取该商品购物车的数量
				$num = $this->cart->getNum($this->request->post['product_id']);
				//数量超过限购 重新加载页面参数
				$json['refresh'] = 0;
				if(!empty($promotion) && ($num >= $promotion['limited'])){
					$json['refresh'] = 1;
				}
				// Option validation
				if (isset($this->request->post['option'])) {
					$option = array_filter($this->request->post['option']);
				} else {
					$option = array();
				}

				//TODO:寻找减去商品的修改点
				if ($quantity < 0) {
					$key = $this->request->post['product_id'];


					$this->cart->down($key);
				}

				$product_total = 0;

				foreach ($this->cart->getGoods() as $key => $value) {
					$product = $this->cart->key_decode($key);

					if ($product[0] == $this->request->post['product_id']) {
						$product_total += $value;
					}
				}
//购物车 页面从2个减到1  就会报错 所有 注释掉
//				if ($product_info['minimum'] > ($product_total + $quantity)) {
//					$json['error']['warning'] = sprintf($this->language->get('error_minimum'), $product_info['name'], $product_info['minimum']);
//				}

				// Option validation
				if (isset($this->request->post['option'])) {
					$option = array_filter($this->request->post['option']);
				} else {
					$option = array();
				}

				$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

				foreach ($product_options as $product_option) {
					if ($product_option['required'] && (!isset($this->request->post['option'][$product_option['product_option_id']]) || !$this->request->post['option'][$product_option['product_option_id']])) {
						$json['error'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
					}
				}

				// 特殊促销处理
				if (isset($this->request->post['promotion_code']) && $this->request->post['promotion_code']) {
					//$promotion1 = array();
					$promotion_code = $this->request->post['promotion_code'];
					// 使用折扣券
					if ($promotion_code == EnumPromotionTypes::ZERO_BUY) {
						$this->session->data['coupon'] = $this->session->data['freepromotion'];
						$this->session->data['coupon_product'] = $this->request->post['product_id'];
					}

					//检测是否存在满足促销规则的商品
					// 无条件适用 #TBD
					$existed = checkPromotionProduct($this->request->post['product_id'], $promotion_code);

					if ($existed) {
						//如果是首赠的代码 需要做首赠的条件检测
						if ($promotion_code == EnumPromotionTypes::REGISTER_DONATION) {
							//是否已经有过订单
							$FIRST_BUY = isFirstBuy($this->customer->getId());


							//购物车是否已经存在首赠商品
							if ($this->checkRegisterDonationExisted() || !$FIRST_BUY) {
								$json['error']['alert'] = sprintf($this->language->get('error_register_donation'), 1);
							}
						}

						//增加促销商品检测逻辑
						if ($promotion_code == EnumPromotionTypes::ZERO_BUY) {
							//对于0元抢购商品，每次只能购买1个
							if ($this->checkZerobuyExisted()) {
								$json['error']['alert'] = sprintf($this->language->get('error_zerobuy_limit'), 1);
							}
						}

						// 如果是满赠的代码，需要加满赠的检测
						if ($promotion_code == EnumPromotionTypes::TOTAL_DONATION) {
							$donation_limit = $this->config->get('config_donation_limit');

							if ($this->cart->getTotal() < $donation_limit) {
								$json['error']['alert'] = sprintf($this->language->get('error_donation_total'), $donation_limit);
							}

							if ($this->checkTotalDonationExisted()) {
								$json['error']['alert'] = $this->language->get('error_donation_limit');
							}
						}

						//设定促销代码到序列化key值
						$promotion['promotion_code'] = $promotion_code;
						$promotion['promotion_price'] = 0;
						$promotion['limited'] = 1;
					}
				}
			}

			if (!isset($json['error'])) {
				//添加到购物车
				$this->cart->add($this->request->post['product_id'], $quantity, $option, $promotion);

				// $this->cart->setAdditional($additional['date']);   
				// $this->log_sys->debug('this->cart->setAdditional::serialize($additional):' .serialize($additional));
				//print_r($this->session->data['cart']);die();

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

//				unset($this->session->data['shipping_methods']);
//				unset($this->session->data['shipping_method']);
//				unset($this->session->data['payment_methods']);
//				unset($this->session->data['payment_method']);	

				$this->removeRelatedSessions();
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}

		if (isset($this->request->post['product_key'])) {
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}

			if ($quantity < 0) {
				$this->cart->down($this->request->post['product_key']);
			} else {
				$this->cart->up($this->request->post['product_key']);
			}

			$json['success'] = true;

			$this->removeRelatedSessions();
		}


		if (isset($this->request->post['remove'])) {

			$this->cart->remove($this->request->post['remove']);
		}

		if (isset($this->request->post['voucher'])) {
			if ($this->session->data['vouchers'][$this->request->post['voucher']]) {
				unset($this->session->data['vouchers'][$this->request->post['voucher']]);
			}
		}

		$this->load->model('tool/image');

		$this->data['products'] = array();

		foreach ($this->cart->getProducts() as $result) {

			$image = resizeThumbImage($result['image'], 40, 40, TRUE);

			$option_data = array();

			foreach ($result['option'] as $option) {
				if ($option['type'] != 'file') {
					$option_data[] = array(
									'name' => $option['name'],
									'value' => (strlen($option['option_value']) > 20 ? substr($option['option_value'], 0, 20) . '..' : $option['option_value'])
					);
				} else {
					$this->load->library('encryption');

					$encryption = new Encryption($this->config->get('config_encryption'));

					$file = substr($encryption->decrypt($option['option_value']), 0, strrpos($encryption->decrypt($option['option_value']), '.'));

					$option_data[] = array(
									'name' => $option['name'],
									'value' => (strlen($file) > 20 ? substr($file, 0, 20) . '..' : $file)
					);
				}
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$total = $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$total = false;
			}

			$this->data['products'][] = array(
							'product_id' => $result['product_id'],
							'key' => $result['key'],
							'thumb' => $image,
							'name' => $result['name'],
							'model' => $result['model'],
							'option' => $option_data,
							'quantity' => $result['quantity'],
							'stock' => $result['stock'],
							'price' => $price,
							'promotion' => $result['promotion'],
							'total' => $total,
							'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);
		}


		// Gift Voucher
		$this->data['vouchers'] = array();

		if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
			foreach ($this->session->data['vouchers'] as $key => $voucher) {
				$this->data['vouchers'][] = array(
								'key' => $key,
								'description' => $voucher['description'],
								'amount' => $this->currency->format($voucher['amount'])
				);
			}
		}

		// Calculate Totals
		$total_data = array();
		$total = array();
		$total['promotion'] = 0;
		$total['general'] = 0;
		$total['fee'] = 0;
		$total['discount'] = 0;
		$total['total'] = 0;
		$taxes = $this->cart->getTaxes();

		$this->load->model('total/sub_total');
		$this->model_total_sub_total->getTotal($total_data, $total, $taxes);

		$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total['total']));
		
		$this->data['totals'] = $total_data;

		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/cart.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/cart.tpl';
		} else {
			$this->template = 'default/template/common/cart.tpl';
		}
		$json['value'] = $total_data[0]['value'];
		$json['output'] = $this->render();


		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
	}

	private function checkTotalDonationExisted() {
		$products = $this->cart->getProducts();

		$result = false;

		foreach ($products as $key => $product) {
			$product = $this->cart->key_decode($key);

			if (isset($product[2]['promotion_code']) && $product[2]['promotion_code'] == EnumPromotionTypes::TOTAL_DONATION) {
				$result = true;

				break;
			}
		}

		return $result;
	}

	private function checkRegisterDonationExisted() {
		$products = $this->cart->getProducts();

		$result = false;

		foreach ($products as $key => $product) {
			$product = $this->cart->key_decode($key);

			if (isset($product[2]['promotion_code']) && $product[2]['promotion_code'] == EnumPromotionTypes::REGISTER_DONATION) {
				$result = true;

				break;
			}
		}

		return $result;
	}

	private function checkZerobuyExisted() {
		$products = $this->cart->getProducts();

		$result = false;

		foreach ($products as $key => $product) {

			$product = $this->cart->key_decode($key);

			if (isset($product[2]['promotion_code']) && $product[2]['promotion_code'] == EnumPromotionTypes::ZERO_BUY) {
				$result = true;

				break;
			}
		}

		return $result;
	}

	/**
	 * 零元购，加入购物车
	 */
	public function zerobuy() {
		$this->load_language('checkout/cart');

		$json = array();

		if (isset($this->request->post['product_id'])) {
			$this->load->model('catalog/product');

			$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

			if ($product_info) {
				// Minimum quantity validation
				if (isset($this->request->post['quantity'])) {
					$quantity = $this->request->post['quantity'];
				} else {
					$quantity = 1;
				}

				//TODO:寻找减去商品的修改点
				if ($quantity < 0) {
					$this->cart->down($this->request->post['product_id']);
				}

				$product_total = 0;

				foreach ($this->cart->getGoods() as $key => $value) {
					$product = explode(':', $key);

					if ($product[0] == $this->request->post['product_id']) {
						$product_total += $value;
					}
				}
				/* 检测库存是否充足 */
				if ($product_info['minimum'] > ($product_total + $quantity)) {
					$json['error']['warning'] = sprintf($this->language->get('error_minimum'), $product_info['name'], $product_info['minimum']);
				}

				// Option validation
				if (isset($this->request->post['option'])) {
					$option = array_filter($this->request->post['option']);
				} else {
					$option = array(
									'rule_code' => EnumConsulationRules::getZeroBuy()
					);
				}
				//判读购物车中是否有产品
				$key = $this->cart->key_encode($this->request->post['product_id'], $options);
				if (!$this->cart->hasProducts()) {
					$json['error']['alert'] = $this->language->get('error_have_buy');
					$this->load->library('json');

					$this->response->setOutput(Json::encode($json));
				}

				//当前用户是否购买过0元购产品
				$this->load->model('promotion/zeroproduct');

				$order_info = $this->model_promotion_zeroproduct->havePayZeroBuy(EnumConsulationRules::getZeroBuy(), $this->customer->isLogged());
				//已经付款状态
				if ($order_info) {
					$json['error']['alert'] = $this->language->get('error_have_buy');
					$this->load->library('json');
					$this->response->setOutput(Json::encode($json));
				}


				$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

				foreach ($product_options as $product_option) {
					if ($product_option['required'] && (!isset($this->request->post['option'][$product_option['product_option_id']]) || !$this->request->post['option'][$product_option['product_option_id']])) {
						$json['error'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
					}
				}
			}

			if (!isset($json['error'])) {
				$this->cart->add($this->request->post['product_id'], $quantity, $option);

				$json['success']['text'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

				unset($this->session->data['shipping_methods']);
				unset($this->session->data['shipping_method']);
//				unset($this->session->data['payment_methods']);
//				unset($this->session->data['payment_method']);
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}

		if (isset($this->request->post['remove'])) {
			$this->cart->remove($this->request->post['remove']);
		}

		if (isset($this->request->post['voucher'])) {
			if ($this->session->data['vouchers'][$this->request->post['voucher']]) {
				unset($this->session->data['vouchers'][$this->request->post['voucher']]);
			}
		}
		$this->load->model('tool/image');

		$this->data['products'] = array();
		foreach ($this->cart->getProducts() as $result) {
			$image = resizeThumbImage($result['image'], 40, 40, TRUE);

			$option_data = array();

			foreach ($result['option'] as $option) {
				if ($option['type'] != 'file') {
					$option_data[] = array(
									'name' => $option['name'],
									'value' => (strlen($option['option_value']) > 20 ? substr($option['option_value'], 0, 20) . '..' : $option['option_value'])
					);
				} else {
					$this->load->library('encryption');

					$encryption = new Encryption($this->config->get('config_encryption'));

					$file = substr($encryption->decrypt($option['option_value']), 0, strrpos($encryption->decrypt($option['option_value']), '.'));

					$option_data[] = array(
									'name' => $option['name'],
									'value' => (strlen($file) > 20 ? substr($file, 0, 20) . '..' : $file)
					);
				}
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$total = $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$total = false;
			}

			$this->data['products'][] = array(
							'product_id' => $result['product_id'],
							'key' => $result['key'],
							'thumb' => $image,
							'name' => $result['name'],
							'model' => $result['model'],
							'option' => $option_data,
							'quantity' => $result['quantity'],
							'stock' => $result['stock'],
							'price' => $price,
							'rule_code' => $result['rule_code'],
							'total' => $total,
							'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);
		}

		// Gift Voucher
		$this->data['vouchers'] = array();

		if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
			foreach ($this->session->data['vouchers'] as $key => $voucher) {
				$this->data['vouchers'][] = array(
								'key' => $key,
								'description' => $voucher['description'],
								'amount' => $this->currency->format($voucher['amount'])
				);
			}
		}

		// Calculate Totals
		$total_data = array();
		$total = array();
		$total['promotion'] = 0;
		$total['general'] = 0;
		$total['fee'] = 0;
		$total['discount'] = 0;
		$total['total'] = 0;
		$taxes = $this->cart->getTaxes();

		$this->load->model('total/sub_total');
		$this->model_total_sub_total->getTotal($total_data, $total, $taxes);

		$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total['total']));

		$this->data['totals'] = $total_data;

		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/cart.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/cart.tpl';
		} else {
			$this->template = 'default/template/common/cart.tpl';
		}

		$json['output'] = $this->render();

		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
	}

	private function removeRelatedSessions() {
		unset($this->session->data['checkout_token']);
	}

	public function updateOrderPickTime() {
		$pick_time = $this->request->post['pick_time'];

		$this->session->data['pick_time'] = $pick_time;
	}

}

?>