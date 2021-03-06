# SunnyPay数字货币支付平台文档

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
1、第三方平台请求SunnyPay订单接口下单，订单返回一个支付链接（二维码）
2、Biztalk扫描支付链接二维码（如果是H5使用场景则转跳H5钱包）,用户使用钱包支付订单。
3、SunnyPay服务器端回调通知商户端支付成功。

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
asset_code | String | Y | 数字货币代号（HKDT、HKX）
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

### 3、订单下单接口

```
消费者可选的数字货币类型在后台配置；
实际收到的数字货币币种消费者支付的时候选择为准，收到的数量也根据支付时候的汇率进行转换
```

参数 | 类型 | 必填 | 说明
  ---|---|---|---
c | String | Y | 固定值:order
 m | String | Y |  固定值:new_cash_order
mch_id | String | Y | 商户号( 由平台分配 )
out_trade_no | String | Y | 第三方平台(接入方)订单号
desc | String(100) | N | 订单交易商品描述
currency | String | Y | 法币币种
amount | decimal(18,8) | Y | 订单金额（法币）
callback_url | String(255) | Y | 回调通知URL
redirect_url | String(255) | N | 支付后回跳URL（用于H5内支付后跳回去）


- 返回示例
```
{
    "status": true,
    "code": 0,
    "data": {
        "order_sn":"201811291225231234567",
        "qrcode_content": "https://trade-api.bizkey.io/bizwallet/index.html#/?app=apollo&cash_order_sn=201811291225231234567"
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
        "qrcode_content": "https://trade-api.bizkey.io/bizwallet/index.html#/?app=SunnyPay&order_sn=201811291225231234567",
        "mch_id": "10040686401",
        "mch_name":"测试商户",
        "mch_address":"0xdb815ec9Ad1EFDaD80FbE6BbB70E6d3c71835dba",
        "out_trade_no": "123045987654354565430",
        "order_amount": "100.453",
        "asset_id": "6cfe566e-4aad-470b-8c9a-2fd35b49c68d",
        "asset_code": "HKDT",
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
        "asset_code":"HKDT",
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
        "asset_code":"HKDT",
        "amount":"300.563",
        "withdraw_id":1
    }
} 
```


### 7、订单支付/提币审核回调通知（Callback）
```
说明：用户支付订单后SunnyPay服务器端主动向商户平台发起通知；提币申请审核时向渠道商发起通知；
回调URL：订单是下单时提交，提币状态变更通知URL是在渠道商户后台自己设置。
签名规则：与平台接口规则一致
请求方式：POST
编码方式：x-www-form-urlencoded
回复内容: SUCCESS (正常处理完后需要回复SUCCESS，否则会认为推送失败重新发起。)

POST参数示例：
(下面的参数实际可能会有变动)
订单：
ch_id=10001
sign = 8f60c8102d29fcd525162d02eed4566b
timestamp = 1552472763
msg_id = BC20190301121
order_sn=20190301113419226888
mch_id = 1000001
payer_user_id=5bfba04053db95bfba04053df7
out_trade_no=155141125634566443
asset_id=de7d29ba-432d-377d-95cd-e7ad81986bf2
asset_code=HKDT
amount=0.01
trace_id=6325fb18-acf5-4986-b795-2d1a74a80ce3
pay_time=1551432607

提币：
withdraw_id=1
ch_id=10001
sign = 8f60c8102d29fcd525162d02eed4566b
timestamp = 1552472763
mch_id = 1000001
asset_id=de7d29ba-432d-377d-95cd-e7ad81986bf2
asset_code=HKDT
amount=153.23
to_address=0x2f32fD6254344aF21ef2ebD7AdC9D35779435B0c
status=1     // 0待审核、1提币成功、2转账失败、3已拒绝
add_time=1551344807
verify_time=1554956095
```


### 8、买家ERC20地址查询接口

参数 | 类型 | 必填 | 说明
  ---|---|---|---
c | String | Y | 固定值 :  user
m | String | Y |  固定值 :  geterc20
user_id | String | N | 买家用户ID (user_id和phone需要填写其中一个)
phone | String | N | 买家电话（包含国际区号，如：8613800138000）

- 返回示例
```
{
    "status": true,
    "code": 0,
    "data": {
        "address":"0x2f32fD6254344aF21ef2ebD7AdC9D35779435B0c"
    }
}
```

### 附表：

币种 | 简称 | asset_id
---|---|---
Hong Kong Dollar Token | HKDT | 61027bec-d546-3d40-8d9c-4b1add177ce2