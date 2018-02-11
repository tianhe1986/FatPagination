# 安装
确保PHP版本在5.5以上。

推荐通过[Composer](https://getcomposer.org/)进行安装

Composer的安装请参考官方配置。

安装好Composer后，在你的项目中创建一个 composer.json 文件：
```json
{
    "require": {
        "tianhe1986/fatpagination": "*"
    }
}
```

然后在项目文件夹下执行:
```bash
$ composer install
```

之后添加autoloader:
```php
<?php
require "vendor/autoload.php";
```

#分页处理
简单的用法示例如下：
```php
use FatPagination\FatPagination;

$params = [
    'totalNum' => 101,
    'pageSize' => 10,
    'currentPage' => 3,
    'url' => '/test/page',
];

$pagination = new FatPagination($params);

$ulHtml = $pagination->getUlHtml();
$divHtml = $pagination->getDivHtml();
```

则`$urlHtml`对应如下html代码：
```html
<ul>
    <li><a href="/test/page?page=1">首页</a></li>
    <li><a href="/test/page?page=2">上一页</a></li>
    <li><a href="/test/page?page=1">1</a></li>
    <li><a href="/test/page?page=2">2</a></li>
    <li class="active"><a href="/test/page?page=3">3</a></li>
    <li><a href="/test/page?page=4">4</a></li>
    <li><a href="/test/page?page=5">5</a></li>
    <li><a href="/test/page?page=6">6</a></li>
    <li><a href="/test/page?page=4">下一页</a></li>
    <li><a href="/test/page?page=11">末页</a></li>
</ul>
```
`$divHtml`对应如下html代码：
```html
<div>
    <a href="/test/page?page=1">首页</a>
    <a href="/test/page?page=2">上一页</a>
    <a href="/test/page?page=1">1</a>
    <a href="/test/page?page=2">2</a>
    <a href="/test/page?page=3" class="active">3</a>
    <a href="/test/page?page=4">4</a>
    <a href="/test/page?page=5">5</a>
    <a href="/test/page?page=6">6</a>
    <a href="/test/page?page=4">下一页</a>
    <a href="/test/page?page=11">末页</a>
</div>
```

默认情况下，会使用`page`作为分页的参数，当前页码会加上class="active"，当前页左右最多各展示3个数字页码。可以通过传入参数更改这些配置，具体的参数列表见下一小节。

#传入参数
所有支持的参数列表如下：

| 参数名 | 说明 |
| :- | :- |
| pageParam | 分页的参数名，默认为 page |
| url | 分页的url，不设置的话默认为当前url，会自动去除query_string中的分页参数部分 |
| totalNum | 数据总条数 |
| pageSize | 每页数据展示条数，默认为10 |
| currentPage | 当前页码，默认为1 |
| sidePageNum | 当前页码左右两边最多展示多少个数字页码。以设置为2举例， 当前页码为1，则展示 1 2 3，如果当前页码为2，则展示 1 2 3 4， 为3则展示 1 2 3 4 5 |
| prevPageText | 上一页链接展示文本， 默认为“上一页” |
| nextPageText | 下一页链接展示文本， 默认为“下一页” |
| firstPageText | 首页链接展示文本， 默认为“首页” |
| lastPageText | 末页链接展示文本， 默认为“末页” |
| attrs | 数组，给标签附加的额外属性，具体参数见下表 |

所有attrs参数列表如下：

| 参数名 | 说明 |
| :- | :- |
| div | div标签附加属性，仅生成div列表时有效 |
| ul | ul标签附加属性，仅生成ul列表时有效 |
| a | 非当前数字页码a标签附加属性，仅div列表有效 |
| li | 非当前数字页码li标签附加属性，仅ul列表有效 |
| currentPage | 当前数字页码附加属性，div列表附加在a标签上，ul列表附加在li标签上。 默认会附加 class="active" |
| prevPage | 上一页附加属性，div列表附加在a标签上，ul列表附加在li标签上 |
| nextPage | 下一页附加属性，div列表附加在a标签上，ul列表附加在li标签上 |
| firstPage | 首页附加属性，div列表附加在a标签上，ul列表附加在li标签上 |
| lastPage | 末页附加属性，div列表附加在a标签上，ul列表附加在li标签上 |

以下是包含全部参数的ul列表生成示例：
```php
use FatPagination\FatPagination;

$params = [
    'pageParam' => 'p',
    'url' => '/order/list?type=1',
    'totalNum' => 432,
    'pageSize' => 15,
    'currentPage' => 6,
    'sidePageNum' => 2,
    'prevPageText' => 'prev',
    'nextPageText' => 'next',
    'firstPageText' => 'first',
    'lastPageText' => 'last',
    'attrs' => [
        'ul' => 'class="page-ul"',
        'li' => 'class="page-li"',
        'currentPage' => 'class="page-li current"',
        'prevPage' => 'class="prev"',
        'nextPage' => 'class="next"',
        'firstPage' => 'class="first"',
        'lastPage' => 'class="last"',
    ]
];

$pagination = new FatPagination($params);

$ulHtml = $pagination->getUlHtml();
```

将生成
```html
<ul class="page-ul">
    <li class="first"><a href="/order/list?type=1&p=1">first</a></li>
    <li class="prev"><a href="/order/list?type=1&p=5">prev</a></li>
    <li class="page-li"><a href="/order/list?type=1&p=4">4</a></li>
    <li class="page-li"><a href="/order/list?type=1&p=5">5</a></li>
    <li class="page-li current"><a href="/order/list?type=1&p=6">6</a></li>
    <li class="page-li"><a href="/order/list?type=1&p=7">7</a></li>
    <li class="page-li"><a href="/order/list?type=1&p=8">8</a></li>
    <li class="next"><a href="/order/list?type=1&p=7">next</a></li>
    <li class="last"><a href="/order/list?type=1&p=29">last</a></li>
</ul>
```

div列表生成示例：
```php
use FatPagination\FatPagination;

$params = [
    'pageParam' => 'p',
    'url' => '/order/list?type=1',
    'totalNum' => 432,
    'pageSize' => 15,
    'currentPage' => 6,
    'sidePageNum' => 2,
    'prevPageText' => 'prev',
    'nextPageText' => 'next',
    'firstPageText' => 'first',
    'lastPageText' => 'last',
    'attrs' => [
        'div' => 'class="page-div"',
        'a' => 'class="page-a"',
        'currentPage' => 'class="page-a current"',
        'prevPage' => 'class="prev"',
        'nextPage' => 'class="next"',
        'firstPage' => 'class="first"',
        'lastPage' => 'class="last"',
    ]
];

$pagination = new FatPagination($params);

$divHtml = $pagination->getDivHtml();
```

将生成
```html
<div class="page-div">
    <a href="/order/list?type=1&p=1" class="first">first</a>
    <a href="/order/list?type=1&p=5" class="prev">prev</a>
    <a href="/order/list?type=1&p=4" class="page-a">4</a>
    <a href="/order/list?type=1&p=5" class="page-a">5</a>
    <a href="/order/list?type=1&p=6" class="page-a current">6</a>
    <a href="/order/list?type=1&p=7" class="page-a">7</a>
    <a href="/order/list?type=1&p=8" class="page-a">8</a>
    <a href="/order/list?type=1&p=7" class="next">next</a>
    <a href="/order/list?type=1&p=29" class="last">last</a>
</div>
```

#自行构造分页html
如果提供的参数依旧不能满足需要，可以按照如下方式，获取首/末页，上/下一页，以及数字页码，然后自行拼接html:
```php
use FatPagination\FatPagination;

$params = [
    'totalNum' => 101,
    'pageSize' => 10,
    'currentPage' => 3,
    'url' => '/test/page',
];

$pagination = new FatPagination($params);

$html = '<ul>';
//首页
$html .= '<li><a class="XXX" href="'.$pagination->getFirstUrl().'">首页</a></li>';

//上一页
$prevUrl = $pagination->getPrevUrl();
if ($prevUrl) {
    $html .= '<li><a class="XXX" href="'.$prevUrl.'">上一页</a></li>';
}

//数字页
$pages = $pagination->getPages();
foreach ($pages as $pageItem) {
    if (! $pageItem['current']) { //不是当前页
        $html .= '<li><a class="XXX" href="'.$pageItem['url'].'">第'.$pageItem['page'].'页</a></li>';
    } else {
        $html .= '<li>第'.$pageItem['page'].'页</li>';
    }
}

//下一页
$nextUrl = $pagination->getNextUrl();
if ($nextUrl) {
    $html .= '<li><a class="XXX" href="'.$nextUrl.'">下一页</a></li>';
}

//末页
$html .= '<li><a class="XXX" href="'.$pagination->getLastUrl().'">末页</a></li>';
$html .= '</ul>';

```

则最终`$html`为:
```html
<ul>
    <li><a class="XXX" href="/test/page?page=1">首页</a></li>
    <li><a class="XXX" href="/test/page?page=2">上一页</a></li>
    <li><a class="XXX" href="/test/page?page=1">第1页</a></li>
    <li><a class="XXX" href="/test/page?page=2">第2页</a></li>
    <li>第3页</li>
    <li><a class="XXX" href="/test/page?page=4">第4页</a></li>
    <li><a class="XXX" href="/test/page?page=5">第5页</a></li>
    <li><a class="XXX" href="/test/page?page=6">第6页</a></li>
    <li><a class="XXX" href="/test/page?page=4">下一页</a></li>
    <li><a class="XXX" href="/test/page?page=11">末页</a></li>
</ul>
```