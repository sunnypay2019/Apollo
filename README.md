# Apollo���ֻ���֧��ƽ̨�ĵ�

### 1���ӿڵ��÷�ʽ������
> �ӿڷ�����HTTP POST

> �ӿڵ�ַ��https://api.sunnypay.io/index.php

> ���������������ύ�ģ�

���� | ˵��
---|---
ch_id | ����ID 
timestamp | ��ǰʱ���
sign | ǩ��

> ǩ����sign��������˵����
```
��ȫ��GET�������ղ�����ASCII���С��������sign���⣩��
ʹ��URL��ֵ�Եĸ�ʽ����key1=value1&key2=value2����ƴ�ӳ��ַ���
Ȼ����ĩβƴ��key���ٽ���MD5
sign = md5('key1=value1&key2=value2&key3=value3��&key=xxxx');

�ر�ע��������Ҫ����
 �� ������ASCII���С���������ֵ��򣩣�
 �� ���������ֵΪ�ղ�����ǩ����
 �� ���������ִ�Сд��
 �� ���͵�sign����������ǩ��
 �� ǩ��ԭʼ���У��ֶ������ֶ�ֵ������ԭʼֵ��������URL Encode


```

> ����
```
1��������ƽ̨����Apollo�����ӿ��µ�����������һ��֧�����ӣ���ά�룩
2��Biztalkɨ��֧�����Ӷ�ά�루�����H5ʹ�ó�����ת��H5Ǯ����,�û�ʹ��Ǯ��֧��������
3��Apollo�������˻ص�֪ͨ�̻���֧���ɹ���

```

> ������

������ | ˵��
---|---
10000 | ȱʧ�ֶ�(ʱ��)
10001 | ��ʱ(ʱ�����30������)
10002 | ȱʧ�ֶ�(ǩ���ֶ�)
10003 | ǩ������ȷ
10004 | ϵͳ����
10005 | �ֶ�ȱʧ(��Ҫ��ch_id)
10006 | ����������
10007 | �������ѽ���
10010 | �ֶ�ȱʧ(ҵ���ֶ�)
---


### 2������ת���ӿ�

���� | ���� | ���� | ˵��
  ---|---|---|---
c | string | Y | �̶�ֵ:common
 m | string | Y |  �̶�ֵ:exchange
currency | String | Y |  ���֣�HKD��CNY��
asset_code | String | Y | ���ֻ��Ҵ��ţ�HKDT��BZKY��
type | String | Y | to_digital / to_cash
amount | decimal | Y | ���ֻ��һ��߷��ҽ��


- ����ʾ��
```
{
    "status": true,
    "code": 0,
    "data": {
        "currency": "CNY",
        "asset_code ": "ETH",
        "digital_amount": "1",
        "cash_amount": "923.45"
    }
} 
```



### 3������֧��֪ͨ�ӿ�

���� | ���� | ���� | ˵��
  ---|---|---|---
c | String | Y | �̶�ֵ:order
 m | String | Y |  �̶�ֵ:new_order
mch_id | String | Y | �̻���( ��ƽ̨���� )
out_trade_no | String | Y | ������ƽ̨(���뷽)������
desc | String(100) | N | ����������Ʒ����
asset_id | String | Y | ���ֻ��ұ���ID
amount | decimal(18,8) | Y | ���������ֻ��ң�
callback_url | String(255) | Y | �ص�֪ͨURL
redirect_url | String(255) | N | ֧�������URL������H5��֧��������ȥ��


- ����ʾ��
```
{
    "status": true,
    "code": 0,
    "data": {
        "order_sn":"201811291225231234567",
        "qrcode_content": "https://trade-api.bizkey.io/bizwallet/index.html#/?app=apollo&order_sn=201811291225231234567"
    }
} 

```


### 4��������ѯ�ӿ�

���� | ���� | ���� | ˵��
  ---|---|---|---
c | string | Y | �̶�ֵ :  order
m | string | Y |  �̶�ֵ :  query
mch_id | String | Y | �տ��̻����û�ID
order_sn | String | Y | �����µ�ʱ���صĶ�����


- ����ʾ��
```
{
    "status": true,
    "code": 0,
    "data": {
        "order_sn": "201811291225231234567",
        "qrcode_content": "https://trade-api.bizkey.io/bizwallet/index.html#/?app=bizpay&order_sn=201811291225231234567",
        "mch_id": "10040686401",
        "mch_name":"�����̻�",
        "mch_address":"0xdb815ec9Ad1EFDaD80FbE6BbB70E6d3c71835dba",
        "out_trade_no": "123045987654354565430",
        "order_amount": "100.453",
        "asset_id": "6cfe566e-4aad-470b-8c9a-2fd35b49c68d",
        "asset_code": "BZKY",
        "status": "10"   //10 ��֧����11 ��֧��
    }
} 

```


### 5���̻����ֻ�������ѯ�ӿ�

���� | ���� | ���� | ˵��
  ---|---|---|---
c | String | Y | �̶�ֵ :  account
m | String | Y |  �̶�ֵ :  balance
asset_id | String | Y | ���ֻ���ID
mch_id | String | Y | �̻����û�ID


- ����ʾ��
```
{
    "status": true,
    "code": 0,
    "data": {
        "asset_code":"BZKY",
        "balance":"300.563"
    }
} 
```

### 6���̻����ֻ���ת���ӿ�

���� | ���� | ���� | ˵��
  ---|---|---|---
c | String | Y | �̶�ֵ :  account
m | String | Y | �̶�ֵ :  withdraw
asset_id | String | Y | ���ֻ���ID
mch_id | String | Y | �̻����û�ID
amount | decimal (18,8) | Y | Ҫת�������ֻ�������
address | String(64) | Y | ���ת����ַ
remark | String(128) | N | ��ע˵��


- ����ʾ��
```
{
    "status": true,
    "code": 0,
    "data": {
        "asset_code":"BZKY",
        "amount":"300.563",
        "withdraw_id":1
    }
} 
```


### 7������֧���ص�֪ͨ��Callback��
```
˵�����û�֧��������Apollo���������������̻�ƽ̨����֪ͨ
�ص�URL�������µ�ʱ�ύ
ǩ��������ƽ̨�ӿڹ���һ��
����ʽ��POST
���뷽ʽ��x-www-form-urlencoded

POST����ʾ����
(����Ĳ���ʵ�ʿ��ܻ��б䶯)

ch_id=10001
sign = 8f60c8102d29fcd525162d02eed4566b
timestamp = 1552472763
msg_id = BC20190301121
order_sn=20190301113419226888
mch_id = 1000001
payer_user_id=bzky_5bfba04053db95bfba04053df7
out_trade_no=155141125634566443
asset_id=de7d29ba-432d-377d-95cd-e7ad81986bf2
asset_code=BZKY
amount=0.01
trace_id=6325fb18-acf5-4986-b795-2d1a74a80ce3
pay_time=1551432607
```


### ����

���� | ��� | asset_id
---|---|---
Bizkey | BZKY | de7d29ba-432d-377d-95cd-e7ad81986bf2
Hong Kong Dollar Token | HKDT | 61027bec-d546-3d40-8d9c-4b1add177ce2