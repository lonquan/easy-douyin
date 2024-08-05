# 推送示例

<!-- TOC -->

* [推送示例](#推送示例)
    * [WebHook verify_webhook](#webhook-verify_webhook)
    * [生活服务商品审核 life_product_common_audit](#生活服务商品审核-life_product_common_audit)
    * [商品状态变更消息 life_product_status_change](#商品状态变更消息-life_product_status_change)
    * [抖音码退款申请通知 life_hermes_axte_after_sale_audit](#抖音码退款申请通知-life_hermes_axte_after_sale_audit)
    * [预订单状态变更通知 life_hermes_akte_booking_status_notification](#预订单状态变更通知-life_hermes_akte_booking_status_notification)
    * [商户订座业务变更通知 life_hermes_akte_merchant_config_update](#商户订座业务变更通知-life_hermes_akte_merchant_config_update)
    * [订座配置任务完成通知 life_hermes_akte_booking_config_result](#订座配置任务完成通知-life_hermes_akte_booking_config_result)
    * [会员信息变更 SPI 抖音→商家](#会员信息变更-spi-抖音商家)
    * [生活服务订单状态变更通知 life_trade_order_notify](#生活服务订单状态变更通知-life_trade_order_notify)
    * [券消息通知 life_trade_certificate_notify](#券消息通知-life_trade_certificate_notify)
    * [三方码 SPI](#三方码-spi)
    * [商服关系状态回调](#商服关系状态回调)
    * [佣金状态变更回调](#佣金状态变更回调)

<!-- TOC -->

## WebHook verify_webhook

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/preparation/massages.push)

## 生活服务商品审核 life_product_common_audit

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/akte_saas/goods/product_audit_notify)

```json
{"event":"life_product_common_audit","client_key":"aw6ufdiwwmx4x7tf","from_user_id":"","content":"{\"product_id\":\"1805831187321867\",\"status\":\"PASS\",\"reason\":\"\"}","log_id":"021722175771160fdbddc01000b05030000000000000046131125","msg_id":"f82fdfc454814eeda244939578d427c8"}
```

```text
status:
- PASS 审核通过
- FAIL 审核失败
```

## 商品状态变更消息 life_product_status_change

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/akte_saas/goods/goods_status_change_webhook)

```json
{"event":"life_product_status_change","client_key":"aw6ufdiwwmx4x7tf","from_user_id":"","content":"{\"product_id\":\"1805831187321867\",\"status\":\"GOODS_ONLINE\"}","log_id":"021722175771160fdbddc01000b0503000000000000004652c8c8","msg_id":"950bf0c2f0455fe4ab6649f7a3c2b58c"}
```

```text
status:
- GOODS_ONLINE 商品上架
- GOODS_OFFLINE 商品下架
- GOODS_DELETE 商品删除
- GOODS_BAN 商品封禁
```

## 抖音码退款申请通知 life_hermes_axte_after_sale_audit

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/akte_saas/after_sale/audit_notification)

## 预订单状态变更通知 life_hermes_akte_booking_status_notification

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/akte_saas/booking/booking-order-notify)

## 商户订座业务变更通知 life_hermes_akte_merchant_config_update

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/akte_saas/booking/merchant-booking-change-notify)

## 订座配置任务完成通知 life_hermes_akte_booking_config_result

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/akte_saas/booking/booking-config-notify)

## 会员信息变更 SPI 抖音→商家

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/member/member-info-update)

## 生活服务订单状态变更通知 life_trade_order_notify

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/order.query/trade.order.notify)

```json
{"event":"life_trade_order_notify","client_key":"aw6ufdiwwmx4x7tf","from_user_id":"_000gD8s5QbuDg3hh4HcqfAaMklpHu0zayFu","content":"{\"action\":\"pay_success\",\"msg_time\":1722175826,\"order\":{\"order_id\":\"1065946966824669548\",\"account_id\":\"7376546762393143333\",\"pay_amount\":100,\"original_amount\":100,\"create_time\":1722175821,\"pay_time\":1722175822}}","log_id":"021722175823563fdbddc01000b049500000000000000441114c6","msg_id":"13cedde5b8decd82f5c2267a60edd7ad"}
```

```text
action:
- pay_success 用户支付成功
```

## 券消息通知 life_trade_certificate_notify

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/order.query/trade.certificate.notify)

```text
action: 
- verify 核销
- verify_cancel 撤销核销
- refund_success 退款成功
``` 

## 三方码 SPI

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/tripartite.code/precreateorder)

## 商服关系状态回调

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/paterner/ordernotice)

## 佣金状态变更回调

[文档](https://partner.open-douyin.com/docs/resource/zh-CN/local-life/develop/OpenAPI/paterner/commissionnotice)
