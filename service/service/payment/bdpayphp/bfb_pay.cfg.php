<?php

final class sp_conf{
	// 商户在百付宝的商户ID
	const SP_NO = '';
	// 密钥文件路径，该文件中保存了商户的百付宝合作密钥，该文件需要放在一个安全的地方，切勿让外人知晓或者外网访问
	const SP_KEY_FILE = 'sp.key';             
	// 商户订单支付成功
	const SP_PAY_RESULT_SUCCESS = 1;
	// 商户订单等待支付
	const SP_PAY_RESULT_WAITING = 2;
	// 日志文件
	const LOG_FILE = 'sdk.log';
	
	// 百付宝即时到账支付接口URL（不需要用户登录百付宝）
	const BFB_PAY_DIRECT_NO_LOGIN_URL = "https://wallet.baidu.com/api/0/pay/0/direct";
	// 百付宝即时到账支付接口URL（需要用户登录百付宝，不支付银行网关支付）
	const BFB_PAY_DIRECT_LOGIN_URL = "https://www.baifubao.com/api/0/pay/0/direct/0";
	// 百付宝移动端即时到账支付接口URL（不需要用户登录百付宝，不支付银行网关支付）
	const BFB_PAY_WAP_DIRECT_URL = "https://www.baifubao.com/api/0/pay/0/wapdirect/0";
	// 百付宝订单号查询支付结果接口URL
	const BFB_QUERY_ORDER_URL = "https://www.baifubao.com/api/0/query/0/pay_result_by_order_no";
	// 百付宝O2O扫码付（正扫）即时到帐支付接口URL（不需要用户登录百付宝）
	const BFB_O2O_CODE_CREATE_URL = "https://www.baifubao.com/o2o/0/code/0/create/0";
	// 百付宝O2O付码（反扫）支付接口URL（不需要用户登录百付宝）
	const BFB_O2O_B2C_PAY_URL = "https://www.baifubao.com/o2o/0/b2c/0/api/0/pay/0";
	// 百付宝订单号查询重试次数
	const BFB_QUERY_RETRY_TIME = 3;
	// 百付宝支付成功
	const BFB_PAY_RESULT_SUCCESS = 1;
	// 百付宝支付通知成功后的回执
	const BFB_NOTIFY_META = "<meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\">";
	
	// 签名校验算法
	const SIGN_METHOD_MD5 = 1;
	const SIGN_METHOD_SHA1 = 2;
	// 百付宝即时到账接口服务ID
	const BFB_PAY_INTERFACE_SERVICE_ID = 1;
	// 百付宝查询接口服务ID
	const BFB_QUERY_INTERFACE_SERVICE_ID = 11;
	// 百付宝接口版本
	const BFB_INTERFACE_VERSION = 2;
	// 百付宝接口字符编码
	const BFB_INTERFACE_ENCODING = 1;
	// 百付宝接口返回格式：XML
	const BFB_INTERFACE_OUTPUT_FORMAT = 1;
	// 百付宝接口货币单位：人民币
	const BFB_INTERFACE_CURRENTCY = 1;
	
	// 百付宝O2O支付二维码类型
	const BFB_INTERFACE_O2O_CODE_TYPE = 0;
	// 百付宝O2O支付二维码生成接口输出格式：0：image；1：json
	const BFB_INTERFACE_O2O_OUTPUT_TYPE = 0;
}

?>