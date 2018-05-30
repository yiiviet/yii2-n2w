# YII2 Việt Nam N2W
**Yii2 Extension hổ trợ bạn chuyển đổi số sang chữ số.**

[![Latest Stable Version](https://poser.pugx.org/yiiviet/yii2-n2w/v/stable)](https://packagist.org/packages/yiiviet/yii2-n2w)
[![Total Downloads](https://poser.pugx.org/yiiviet/yii2-n2w/downloads)](https://packagist.org/packages/yiiviet/yii2-n2w)
[![Build Status](https://travis-ci.org/yiiviet/yii2-n2w.svg?branch=master)](https://travis-ci.org/yiiviet/yii2-n2w)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiiviet/yii2-n2w/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiiviet/yii2-n2w/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yiiviet/yii2-n2w/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yiiviet/yii2-n2w/?branch=master)
[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)

Chuyển đổi số sang chữ số là một phần không thể thiếu khi xây dựng 
chức năng in hóa đơn hoặc các chức năng liên quan đến báo cáo, kê khai. Chính 
vì thế mà extension này được xây dựng nên để cung cấp cho bạn tính năng
chuyển đổi số sang chữ số một cách đơn giản nhất và có thể tái sử dụng
trên nhiều thuộc tính.

## Cài đặt

Cài đặt thông qua `composer` nếu như đó là một khái niệm mới với bạn xin click vào 
[đây](http://getcomposer.org/download/) để tìm hiểu nó.

```sh
composer require "yiiviet/yii2-n2w"
```

hoặc thêm

```json
"yiiviet/yii2-n2w": "*"
```

vào phần `require` trong file composer.json.

## Cách sử dụng

Extension này là một `behavior` hổ trợ cho tất cả các `components` của `yii2`
nên để sử dụng nó thì bạn phải khái báo nó vào bên trong phương thức
`behaviors` của `component` (model, active record...).

Ví dụ:

```php

/**
* @property int $amount
*/

class Order extends ActiveRecord {

    public function behaviors() {
        return [
            'n2w' => [
                'class' => 'yiiviet\n2w\Behavior',
                'property' => 'amount',
                'unit' => 'đồng'
            ]
        
        ];
    
    }

}

$order = Order::findOne(1);

print $order->amount; // 100000
print $order->amountFormat; // Một trăm ngàn đồng

```

Như bạn thấy sau khi khai báo attribute `amount` vào `property` thì ngay
lập tức bạn có thể sử dụng attribute `amountFormat` để chuyển đổi số
sang chữ số mà không cần khai báo gì thêm.


Cách khai báo nhiều thuộc tính cùng lúc:

```php

/**
* @property int $amount
* @property int $tax
*/

class Order extends ActiveRecord {

    public function behaviors() {
        return [
            'n2w' => [
                'class' => 'yiiviet\n2w\Behavior',
                'properties' => ['amount', 'tax'],
                'unit' => 'đồng'
            ]
        
        ];
    
    }

}

$order = Order::findOne(1);

print $order->amount; // 100000
print $order->amountFormat; // Một trăm ngàn đồng

print $order->tax; // 10000
print $order->taxFormat; // Mười ngàn đồng

```

Nếu như bạn muốn sửa `suffix` thành một chuỗi khác thì khai báo như sau:

```php

/**
* @property int $amount
* @property int $tax
*/

class Order extends ActiveRecord {

    public function behaviors() {
        return [
            'n2w' => [
                'class' => 'yiiviet\n2w\Behavior',
                'properties' => ['amount', 'tax'],
                'unit' => 'đồng',
                'suffix' => 'Convert'
            ]
        
        ];
    
    }

}

$order = Order::findOne(1);

print $order->amount; // 100000
print $order->amountConvert; // Một trăm ngàn đồng

print $order->tax; // 10000
print $order->taxConvert; // Mười ngàn đồng

```
