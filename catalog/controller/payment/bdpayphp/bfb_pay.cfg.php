<?php

final class sp_conf{
	// �̻��ڰٸ������̻�ID
	const SP_NO = '';
	// ��Կ�ļ�·�������ļ��б������̻��İٸ���������Կ�����ļ���Ҫ����һ����ȫ�ĵط�������������֪�������������
	const SP_KEY_FILE = 'sp.key';             
	// �̻�����֧���ɹ�
	const SP_PAY_RESULT_SUCCESS = 2;
	// �̻������ȴ�֧��
	const SP_PAY_RESULT_WAITING = 16;
	// ��־�ļ�
	const LOG_FILE = 'sdk.log';
	
	// �ٸ�����ʱ����֧���ӿ�URL������Ҫ�û���¼�ٸ�����
	const BFB_PAY_DIRECT_NO_LOGIN_URL = "https://wallet.baidu.com/api/0/pay/0/direct";
	// �ٸ�����ʱ����֧���ӿ�URL����Ҫ�û���¼�ٸ�������֧���������֧����
	const BFB_PAY_DIRECT_LOGIN_URL = "https://www.baifubao.com/api/0/pay/0/direct/0";
	// �ٸ����ƶ��˼�ʱ����֧���ӿ�URL������Ҫ�û���¼�ٸ�������֧���������֧����
	const BFB_PAY_WAP_DIRECT_URL = "https://www.baifubao.com/api/0/pay/0/wapdirect/0";
	// �ٸ��������Ų�ѯ֧�����ӿ�URL
	const BFB_QUERY_ORDER_URL = "https://www.baifubao.com/api/0/query/0/pay_result_by_order_no";
	// �ٸ���O2Oɨ�븶����ɨ����ʱ����֧���ӿ�URL������Ҫ�û���¼�ٸ�����
	const BFB_O2O_CODE_CREATE_URL = "https://www.baifubao.com/o2o/0/code/0/create/0";
	// �ٸ���O2O���루��ɨ��֧���ӿ�URL������Ҫ�û���¼�ٸ�����
	const BFB_O2O_B2C_PAY_URL = "https://www.baifubao.com/o2o/0/b2c/0/api/0/pay/0";
	// �ٸ��������Ų�ѯ���Դ���
	const BFB_QUERY_RETRY_TIME = 3;
	// �ٸ���֧���ɹ�
	const BFB_PAY_RESULT_SUCCESS = 1;
	// �ٸ���֧��֪ͨ�ɹ���Ļ�ִ
	const BFB_NOTIFY_META = "<meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\">";
	
	// ǩ��У���㷨
	const SIGN_METHOD_MD5 = 1;
	const SIGN_METHOD_SHA1 = 2;
	// �ٸ�����ʱ���˽ӿڷ���ID
	const BFB_PAY_INTERFACE_SERVICE_ID = 1;
	// �ٸ�����ѯ�ӿڷ���ID
	const BFB_QUERY_INTERFACE_SERVICE_ID = 11;
	// �ٸ����ӿڰ汾
	const BFB_INTERFACE_VERSION = 2;
	// �ٸ����ӿ��ַ����
	const BFB_INTERFACE_ENCODING = 1;
	// �ٸ����ӿڷ��ظ�ʽ��XML
	const BFB_INTERFACE_OUTPUT_FORMAT = 1;
	// �ٸ����ӿڻ��ҵ�λ�������
	const BFB_INTERFACE_CURRENTCY = 1;
	
	// �ٸ���O2O֧����ά������
	const BFB_INTERFACE_O2O_CODE_TYPE = 0;
	// �ٸ���O2O֧����ά����ɽӿ������ʽ��0��image��1��json
	const BFB_INTERFACE_O2O_OUTPUT_TYPE = 0;
}

?>