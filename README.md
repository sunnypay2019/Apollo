# Apollo数字货币支付平台文档

### 1、接口调用方式及流程
> 接口方法：HTTP POST

> 接口地址：https://api.sunnypay.io/index.php

> 公共参数（必须提交的）

参数 | 说明
---|---
ch_id | 渠道ID 
timestamp | 当前时间戳
sign | 签名

> 签名（sign参数）的说明：
```
将全部GET参数按照参数名ASCII码从小到大排序（sign除外），
使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串
然后在末尾拼接key，再进行MD5
sign = md5('key1=value1&key2=value2&key3=value3…&key=xxxx');

特别注意以下重要规则：
 ◆ 参数名ASCII码从小到大排序（字典序）；
 ◆ 如果参数的值为空不参与签名；
 ◆ 参数名区分大小写；
 ◆ 传送的sign参数不参与签名
 ◆ 签名原始串中，字段名和字段值都采用原始值，不进行URL Encode


```

> 流程
```
1、第三方平台请求Apollo订单接口下单，订单返回一个支付链接（二维码）
2、Biztalk扫描支付链接二维码（如果是H5使用场景则转跳H5钱包）,用户使用钱包支付订单。
3、Apollo服务器端回调通知商户端支付成功。

```

> 错误码

错误码 | 说明
---|---
10000 | 缺失字段(时间)
10001 | 超时(时间相差30秒以上)
10002 | 缺失字段(签名字段)
10003 | 签名不正确
10004 | 系统错误
10005 | 字段缺失(主要是ch_id)
10006 | 渠道不存在
10007 | 该渠道已禁用
10010 | 字段缺失(业务字段)
---


### 2、汇率转换接口

参数 | 类型 | 必填 | 说明
  ---|---|---|---
c | string | Y | 固定值:common
 m | string | Y |  固定值:exchange
currency | String | Y |  币种（HKD、CNY）
asset_code | String | Y | 数字货币代号（HKDT、BZKY）
type | String | Y | to_digital / to_cash
amount | decimal | Y | 数字货币或者法币金额


- 返回示例
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



### 3、订单支付通知接口

参数 | 类型 | 必填 | 说明
  ---|---|---|---
c | String | Y | 固定值:order
 m | String | Y |  固定值:new_order
mch_id | String | Y | 商户号( 由平台分配 )
out_trade_no | String | Y | 第三方平台(接入方)订单号
desc | String(100) | N | 订单交易商品描述
asset_id | String | Y | 数字货币币种ID
amount | decimal(18,8) | Y | 订单金额（数字货币）
callback_url | String(255) | Y | 回调通知URL
redirect_url | String(255) | N | 支付后回跳URL（用于H5内支付后跳回去）


- 返回示例
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


### 4、订单查询接口

参数 | 类型 | 必填 | 说明
  ---|---|---|---
c | string | Y | 固定值 :  order
m | string | Y |  固定值 :  query
mch_id | String | Y | 收款商户的用户ID
order_sn | String | Y | 订单下单时返回的订单号


- 返回示例
```
{
    "status": true,
    "code": 0,
    "data": {
        "order_sn": "201811291225231234567",
        "qrcode_content": "https://trade-api.bizkey.io/bizwallet/index.html#/?app=bizpay&order_sn=201811291225231234567",
        "mch_id": "10040686401",
        "mch_name":"测试商户",
        "mch_address":"0xdb815ec9Ad1EFDaD80FbE6BbB70E6d3c71835dba",
        "out_trade_no": "123045987654354565430",
        "order_amount": "100.453",
        "asset_id": "6cfe566e-4aad-470b-8c9a-2fd35b49c68d",
        "asset_code": "BZKY",
        "status": "10"   //10 待支付、11 已支付
    }
} 

```


### 5、商户数字货币余额查询接口

参数 | 类型 | 必填 | 说明
  ---|---|---|---
c | String | Y | 固定值 :  account
m | String | Y |  固定值 :  balance
asset_id | String | Y | 数字货币ID
mch_id | String | Y | 商户的用户ID


- 返回示例
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

### 6、商户数字货币转出接口

参数 | 类型 | 必填 | 说明
  ---|---|---|---
c | String | Y | 固定值 :  account
m | String | Y | 固定值 :  withdraw
asset_id | String | Y | 数字货币ID
mch_id | String | Y | 商户的用户ID
amount | decimal (18,8) | Y | 要转出的数字货币数量
address | String(64) | Y | 提币转出地址
remark | String(128) | N | 备注说明


- 返回示例
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


### 7、订单支付回调通知（Callback）
```
说明：用户支付订单后Apollo服务器端主动向商户平台发起通知
回调URL：订单下单时提交
签名规则：与平台接口规则一致
请求方式：POST
编码方式：x-www-form-urlencoded

POST参数示例：
(下面的参数实际可能会有变动)

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


### 附表：

币种 | 简称 | asset_id
---|---|---
Bizkey | BZKY | de7d29ba-432d-377d-95cd-e7ad81986bf2
Hong Kong Dollar Token | HKDT | 61027bec-d546-3d40-8d9c-4b1add177ce2