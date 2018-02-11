<?php

namespace FatPagination\Tests;

use PHPUnit\Framework\TestCase;
use FatPagination\FatPagination;

class PaginationTest extends TestCase
{
    public function testHtml()
    {
        $params = [
            'totalNum' => 100,
            'pageSize' => 10,
            'currentPage' => 1,
            'sidePageNum' => 3,
            'url' => '/test/page',
        ];

        $pagination = new FatPagination($params);

        $this->assertEquals('<ul><li><a href="/test/page?page=1">首页</a></li><li class="active"><a href="/test/page?page=1">1</a></li><li><a href="/test/page?page=2">2</a></li><li><a href="/test/page?page=3">3</a></li><li><a href="/test/page?page=4">4</a></li><li><a href="/test/page?page=2">下一页</a></li><li><a href="/test/page?page=10">末页</a></li></ul>', $pagination->getUlHtml());
        $this->assertEquals('<div><a href="/test/page?page=1">首页</a><a href="/test/page?page=1" class="active">1</a><a href="/test/page?page=2">2</a><a href="/test/page?page=3">3</a><a href="/test/page?page=4">4</a><a href="/test/page?page=2">下一页</a><a href="/test/page?page=10">末页</a></div>', $pagination->getDivHtml());
    }

    public function testHtmlNoTotalNum()
    {
        $params = [
            'pageSize' => 10,
            'currentPage' => 1,
            'sidePageNum' => 3,
            'url' => '/test/page',
        ];

        $pagination = new FatPagination($params);

        $this->assertEquals('', $pagination->getUlHtml());
        $this->assertEquals('', $pagination->getDivHtml());
    }

    public function testHtmlDefaultUrl()
    {
        $pagination = $this->getMockBuilder(FatPagination::class)
                ->setMethods(['getCurrentUrl'])
                ->disableOriginalConstructor()
                ->getMock();
        $pagination->expects($this->once())->method('getCurrentUrl')
                ->willReturn('/order/search?type=7&page=2&order=desc');

        $params = [
            'totalNum' => 101,
            'pageSize' => 10,
            'currentPage' => 1,
            'sidePageNum' => 3,
        ];

        $pagination->__construct($params);

        $this->assertEquals('<ul><li><a href="/order/search?type=7&order=desc&page=1">首页</a></li><li class="active"><a href="/order/search?type=7&order=desc&page=1">1</a></li><li><a href="/order/search?type=7&order=desc&page=2">2</a></li><li><a href="/order/search?type=7&order=desc&page=3">3</a></li><li><a href="/order/search?type=7&order=desc&page=4">4</a></li><li><a href="/order/search?type=7&order=desc&page=2">下一页</a></li><li><a href="/order/search?type=7&order=desc&page=11">末页</a></li></ul>', $pagination->getUlHtml());
        $this->assertEquals('<div><a href="/order/search?type=7&order=desc&page=1">首页</a><a href="/order/search?type=7&order=desc&page=1" class="active">1</a><a href="/order/search?type=7&order=desc&page=2">2</a><a href="/order/search?type=7&order=desc&page=3">3</a><a href="/order/search?type=7&order=desc&page=4">4</a><a href="/order/search?type=7&order=desc&page=2">下一页</a><a href="/order/search?type=7&order=desc&page=11">末页</a></div>', $pagination->getDivHtml());
    }

    public function testHtmlCurrentPage()
    {
        $params = [
            'totalNum' => 101,
            'pageSize' => 10,
            'currentPage' => 1,
            'sidePageNum' => 3,
            'url' => '/test/page',
        ];

        $pagination = new FatPagination($params);

        $this->assertEquals('<ul><li><a href="/test/page?page=1">首页</a></li><li class="active"><a href="/test/page?page=1">1</a></li><li><a href="/test/page?page=2">2</a></li><li><a href="/test/page?page=3">3</a></li><li><a href="/test/page?page=4">4</a></li><li><a href="/test/page?page=2">下一页</a></li><li><a href="/test/page?page=11">末页</a></li></ul>', $pagination->getUlHtml());
        $this->assertEquals('<div><a href="/test/page?page=1">首页</a><a href="/test/page?page=1" class="active">1</a><a href="/test/page?page=2">2</a><a href="/test/page?page=3">3</a><a href="/test/page?page=4">4</a><a href="/test/page?page=2">下一页</a><a href="/test/page?page=11">末页</a></div>', $pagination->getDivHtml());

        $params['currentPage'] = 3;
        $pagination = new FatPagination($params);
        $this->assertEquals('<ul><li><a href="/test/page?page=1">首页</a></li><li><a href="/test/page?page=2">上一页</a></li><li><a href="/test/page?page=1">1</a></li><li><a href="/test/page?page=2">2</a></li><li class="active"><a href="/test/page?page=3">3</a></li><li><a href="/test/page?page=4">4</a></li><li><a href="/test/page?page=5">5</a></li><li><a href="/test/page?page=6">6</a></li><li><a href="/test/page?page=4">下一页</a></li><li><a href="/test/page?page=11">末页</a></li></ul>', $pagination->getUlHtml());
        $this->assertEquals('<div><a href="/test/page?page=1">首页</a><a href="/test/page?page=2">上一页</a><a href="/test/page?page=1">1</a><a href="/test/page?page=2">2</a><a href="/test/page?page=3" class="active">3</a><a href="/test/page?page=4">4</a><a href="/test/page?page=5">5</a><a href="/test/page?page=6">6</a><a href="/test/page?page=4">下一页</a><a href="/test/page?page=11">末页</a></div>', $pagination->getDivHtml());

        $params['currentPage'] = 5;
        $pagination = new FatPagination($params);
        $this->assertEquals('<ul><li><a href="/test/page?page=1">首页</a></li><li><a href="/test/page?page=4">上一页</a></li><li><a href="/test/page?page=2">2</a></li><li><a href="/test/page?page=3">3</a></li><li><a href="/test/page?page=4">4</a></li><li class="active"><a href="/test/page?page=5">5</a></li><li><a href="/test/page?page=6">6</a></li><li><a href="/test/page?page=7">7</a></li><li><a href="/test/page?page=8">8</a></li><li><a href="/test/page?page=6">下一页</a></li><li><a href="/test/page?page=11">末页</a></li></ul>', $pagination->getUlHtml());
        $this->assertEquals('<div><a href="/test/page?page=1">首页</a><a href="/test/page?page=4">上一页</a><a href="/test/page?page=2">2</a><a href="/test/page?page=3">3</a><a href="/test/page?page=4">4</a><a href="/test/page?page=5" class="active">5</a><a href="/test/page?page=6">6</a><a href="/test/page?page=7">7</a><a href="/test/page?page=8">8</a><a href="/test/page?page=6">下一页</a><a href="/test/page?page=11">末页</a></div>', $pagination->getDivHtml());

        $params['currentPage'] = 8;
        $pagination = new FatPagination($params);
        $this->assertEquals('<ul><li><a href="/test/page?page=1">首页</a></li><li><a href="/test/page?page=7">上一页</a></li><li><a href="/test/page?page=5">5</a></li><li><a href="/test/page?page=6">6</a></li><li><a href="/test/page?page=7">7</a></li><li class="active"><a href="/test/page?page=8">8</a></li><li><a href="/test/page?page=9">9</a></li><li><a href="/test/page?page=10">10</a></li><li><a href="/test/page?page=11">11</a></li><li><a href="/test/page?page=9">下一页</a></li><li><a href="/test/page?page=11">末页</a></li></ul>', $pagination->getUlHtml());
        $this->assertEquals('<div><a href="/test/page?page=1">首页</a><a href="/test/page?page=7">上一页</a><a href="/test/page?page=5">5</a><a href="/test/page?page=6">6</a><a href="/test/page?page=7">7</a><a href="/test/page?page=8" class="active">8</a><a href="/test/page?page=9">9</a><a href="/test/page?page=10">10</a><a href="/test/page?page=11">11</a><a href="/test/page?page=9">下一页</a><a href="/test/page?page=11">末页</a></div>', $pagination->getDivHtml());

        $params['currentPage'] = 11;
        $pagination = new FatPagination($params);
        $this->assertEquals('<ul><li><a href="/test/page?page=1">首页</a></li><li><a href="/test/page?page=10">上一页</a></li><li><a href="/test/page?page=8">8</a></li><li><a href="/test/page?page=9">9</a></li><li><a href="/test/page?page=10">10</a></li><li class="active"><a href="/test/page?page=11">11</a></li><li><a href="/test/page?page=11">末页</a></li></ul>', $pagination->getUlHtml());
        $this->assertEquals('<div><a href="/test/page?page=1">首页</a><a href="/test/page?page=10">上一页</a><a href="/test/page?page=8">8</a><a href="/test/page?page=9">9</a><a href="/test/page?page=10">10</a><a href="/test/page?page=11" class="active">11</a><a href="/test/page?page=11">末页</a></div>', $pagination->getDivHtml());
    }

    public function testHtmlSidePageNum()
    {
        $params = [
            'totalNum' => 101,
            'pageSize' => 10,
            'currentPage' => 6,
            'sidePageNum' => 4,
            'url' => '/test/page',
        ];

        $pagination = new FatPagination($params);

        $this->assertEquals('<ul><li><a href="/test/page?page=1">首页</a></li><li><a href="/test/page?page=5">上一页</a></li><li><a href="/test/page?page=2">2</a></li><li><a href="/test/page?page=3">3</a></li><li><a href="/test/page?page=4">4</a></li><li><a href="/test/page?page=5">5</a></li><li class="active"><a href="/test/page?page=6">6</a></li><li><a href="/test/page?page=7">7</a></li><li><a href="/test/page?page=8">8</a></li><li><a href="/test/page?page=9">9</a></li><li><a href="/test/page?page=10">10</a></li><li><a href="/test/page?page=7">下一页</a></li><li><a href="/test/page?page=11">末页</a></li></ul>', $pagination->getUlHtml());
        $this->assertEquals('<div><a href="/test/page?page=1">首页</a><a href="/test/page?page=5">上一页</a><a href="/test/page?page=2">2</a><a href="/test/page?page=3">3</a><a href="/test/page?page=4">4</a><a href="/test/page?page=5">5</a><a href="/test/page?page=6" class="active">6</a><a href="/test/page?page=7">7</a><a href="/test/page?page=8">8</a><a href="/test/page?page=9">9</a><a href="/test/page?page=10">10</a><a href="/test/page?page=7">下一页</a><a href="/test/page?page=11">末页</a></div>', $pagination->getDivHtml());
    }

    public function testHtmlPageText()
    {
        $params = [
            'totalNum' => 101,
            'pageSize' => 10,
            'currentPage' => 5,
            'sidePageNum' => 3,
            'url' => '/test/page',
            'firstPageText' => 'First Page',
            'lastPageText' => 'Last Page',
            'prevPageText' => 'Prev Page',
            'nextPageText' => 'Next Page',
        ];

        $pagination = new FatPagination($params);

        $this->assertEquals('<ul><li><a href="/test/page?page=1">First Page</a></li><li><a href="/test/page?page=4">Prev Page</a></li><li><a href="/test/page?page=2">2</a></li><li><a href="/test/page?page=3">3</a></li><li><a href="/test/page?page=4">4</a></li><li class="active"><a href="/test/page?page=5">5</a></li><li><a href="/test/page?page=6">6</a></li><li><a href="/test/page?page=7">7</a></li><li><a href="/test/page?page=8">8</a></li><li><a href="/test/page?page=6">Next Page</a></li><li><a href="/test/page?page=11">Last Page</a></li></ul>', $pagination->getUlHtml());
        $this->assertEquals('<div><a href="/test/page?page=1">First Page</a><a href="/test/page?page=4">Prev Page</a><a href="/test/page?page=2">2</a><a href="/test/page?page=3">3</a><a href="/test/page?page=4">4</a><a href="/test/page?page=5" class="active">5</a><a href="/test/page?page=6">6</a><a href="/test/page?page=7">7</a><a href="/test/page?page=8">8</a><a href="/test/page?page=6">Next Page</a><a href="/test/page?page=11">Last Page</a></div>', $pagination->getDivHtml());
    }

    public function testAttrs()
    {
        $params = [
            'totalNum' => 101,
            'pageSize' => 10,
            'currentPage' => 3,
            'sidePageNum' => 1,
            'url' => '/test/page',
            'attrs' => [
                'div' => 'class="page"',
                'ul' => 'class="pagelist"',
                'a' => 'class="normal-href"',
                'li' => 'class="normal-li"',
                'currentPage' => 'class="current"',
                'prevPage' => 'class="prev"',
                'nextPage' => 'class="next"',
                'firstPage' => 'class="first"',
                'lastPage' => 'class="last"'
            ]
        ];

        $pagination = new FatPagination($params);

        $this->assertEquals('<ul class="pagelist"><li class="first"><a href="/test/page?page=1">首页</a></li><li class="prev"><a href="/test/page?page=2">上一页</a></li><li class="normal-li"><a href="/test/page?page=2">2</a></li><li class="current"><a href="/test/page?page=3">3</a></li><li class="normal-li"><a href="/test/page?page=4">4</a></li><li class="next"><a href="/test/page?page=4">下一页</a></li><li class="last"><a href="/test/page?page=11">末页</a></li></ul>', $pagination->getUlHtml());
        $this->assertEquals('<div class="page"><a href="/test/page?page=1" class="first">首页</a><a href="/test/page?page=2" class="prev">上一页</a><a href="/test/page?page=2" class="normal-href">2</a><a href="/test/page?page=3" class="current">3</a><a href="/test/page?page=4" class="normal-href">4</a><a href="/test/page?page=4" class="next">下一页</a><a href="/test/page?page=11" class="last">末页</a></div>', $pagination->getDivHtml());
    }
}
