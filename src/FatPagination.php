<?php

namespace FatPagination;

class FatPagination
{
    /**
     *
     * @var array 各种参数配置 
     */
    protected $params = [];

    public function __construct($params)
    {
        $this->params = $this->formatParams($params);
    }

    /**
     * 获取div格式的分页html
     * 
     * @return string
     */
    public function getDivHtml()
    {
        if ($this->params['totalNum'] == 0) {
            return '';
        }

        $html = '<div'.$this->params['attrs']['div'].'>';
        $html .= '<a href="'.$this->getFirstUrl().'"'.$this->params['attrs']['firstPage'].'>'.$this->params['firstPageText'].'</a>';

        $prevUrl = $this->getPrevUrl();
        if ($prevUrl) {
            $html .= '<a href="'.$prevUrl.'"'.$this->params['attrs']['prevPage'].'>'.$this->params['prevPageText'].'</a>';
        }

        $pages = $this->getPages();
        foreach ($pages as $pageItem) {
            if (! $pageItem['current']) {
                $html .= '<a href="'.$pageItem['url'].'"'.$this->params['attrs']['a'].'>'.$pageItem['page'].'</a>';
            } else {
                $html .= '<a href="'.$pageItem['url'].'"'.$this->params['attrs']['currentPage'].'>'.$pageItem['page'].'</a>';
            }
        }

        $nextUrl = $this->getNextUrl();
        if ($nextUrl) {
            $html .= '<a href="'.$nextUrl.'"'.$this->params['attrs']['nextPage'].'>'.$this->params['nextPageText'].'</a>';
        }

        $html .= '<a href="'.$this->getLastUrl().'"'.$this->params['attrs']['lastPage'].'>'.$this->params['lastPageText'].'</a>';
        $html .= '</div>';

        return $html;
    }

    /**
     * 获取ul格式的分页html
     * 
     * @return string
     */
    public function getUlHtml()
    {
        if ($this->params['totalNum'] == 0) {
            return '';
        }

        $html = '<ul'.$this->params['attrs']['ul'].'>';
        $html .= '<li'.$this->params['attrs']['firstPage'].'><a href="'.$this->getFirstUrl().'">'.$this->params['firstPageText'].'</a></li>';

        $prevUrl = $this->getPrevUrl();
        if ($prevUrl) {
            $html .= '<li'.$this->params['attrs']['prevPage'].'><a href="'.$prevUrl.'">'.$this->params['prevPageText'].'</a></li>';
        }

        $pages = $this->getPages();
        foreach ($pages as $pageItem) {
            if (! $pageItem['current']) {
                $html .= '<li'.$this->params['attrs']['li'].'><a href="'.$pageItem['url'].'">'.$pageItem['page'].'</a></li>';
            } else {
                $html .= '<li'.$this->params['attrs']['currentPage'].'><a href="'.$pageItem['url'].'">'.$pageItem['page'].'</a></li>';
            }
        }

        $nextUrl = $this->getNextUrl();
        if ($nextUrl) {
            $html .= '<li'.$this->params['attrs']['nextPage'].'><a href="'.$nextUrl.'">'.$this->params['nextPageText'].'</a></li>';
        }

        $html .= '<li'.$this->params['attrs']['lastPage'].'><a href="'.$this->getLastUrl().'">'.$this->params['lastPageText'].'</a></li>';
        $html .= '</ul>';

        return $html;
    }

    /**
     * 根据页码获取对应的url
     * 
     * @param integer $page
     * @return string
     */
    public function getPageUrl($page)
    {
        return str_replace('(:num)', $page, $this->params['url']);
    }

    /**
     * 获取上一页url
     * 
     * @return null|string
     */
    public function getPrevUrl()
    {
        if ($this->params['currentPage'] == 1) {
            return null;
        }

        return $this->getPageUrl($this->params['currentPage'] - 1);
    }

    /**
     * 获取下一页url
     * 
     * @return null|string
     */
    public function getNextUrl()
    {
        if ($this->params['currentPage'] == $this->params['totalPage']) {
            return null;
        }

        return $this->getPageUrl($this->params['currentPage'] + 1);
    }

    /**
     * 获取首页url
     * 
     * @return string
     */
    public function getFirstUrl()
    {
        return $this->getPageUrl(1);
    }

    /**
     * 获取末页url
     * 
     * @return string
     */
    public function getLastUrl()
    {
        return $this->getPageUrl($this->params['totalPage']);
    }

    /**
     * 获取数字页码信息数组
     * 
     * @return array
     */
    public function getPages()
    {
        $pages = [];

        for ($page = $this->params['slidingStart']; $page <= $this->params['slidingEnd']; $page++) {
            $pages []= [
                'page' => $page,
                'url' => $this->getPageUrl($page),
                'current' => ($page === $this->params['currentPage']),
            ];
        }

        return $pages;
    }

    /**
     * 参数格式化处理
     * 
     * @param array $params
     * @return array
     */
    protected function formatParams($params)
    {
        //page参数
        if (! isset($params['pageParam'])) {
            $params['pageParam'] = 'page';
        }

        //url，没有则默认当前url
        if (! isset($params['url'])) {
            $params['url'] = preg_replace('/'.$params['pageParam'].'=\d+&?/ui', '', $this->getCurrentUrl());
        }
        $params['url'] = trim($params['url'], '&?');
        $params['url'] .= (strpos($params['url'], '?') !== false ? '&' : '?').$params['pageParam'].'=(:num)';

        //totalNum， 数据总条数
        if (! isset($params['totalNum']) || ! is_numeric($params['totalNum'])) {
            $params['totalNum'] = 0;
            return $params;
        } else {
            $params['totalNum'] = intval($params['totalNum']);
        }

        //pageSize, 每页数据条数
        if (! isset($params['pageSize']) || ! is_numeric($params['pageSize'])) {
            $params['pageSize'] = 10;
        } else {
            $params['pageSize'] = intval($params['pageSize']);
        }

        $params['totalPage'] = intval(ceil($params['totalNum']/$params['pageSize']));

        //当前页码
        if (! isset($params['currentPage']) || ! is_numeric($params['currentPage']) || $params['currentPage'] < 1) {
            $params['currentPage'] = 1;
        } else {
            $params['currentPage'] = intval($params['currentPage']);
        }

        if ($params['currentPage'] > $params['totalPage']) {
            $params['currentPage'] = $params['totalPage'];
        }

        //前后数字页数展示个数
        if (! isset($params['sidePageNum']) || ! is_numeric($params['sidePageNum'])) {
            $params['sidePageNum'] = 3;
        } else {
            $params['sidePageNum'] = intval($params['sidePageNum']);
        }

        //计算展示的起止页码
        $params['slidingStart'] = $params['currentPage'] - $params['sidePageNum'];
        if ($params['slidingStart'] < 1) {
            $params['slidingStart'] = 1;
        }

        $params['slidingEnd'] = $params['currentPage'] + $params['sidePageNum'];
        if ($params['slidingEnd'] > $params['totalPage']) {
            $params['slidingEnd'] = $params['totalPage'];
        }

        //上，下一页文字
        if (! isset($params['prevPageText'])) {
            $params['prevPageText'] = '上一页';
        }

        if (! isset($params['nextPageText'])) {
            $params['nextPageText'] = '下一页';
        }

        //首，末页文字
        if (! isset($params['firstPageText'])) {
            $params['firstPageText'] = '首页';
        }

        if (! isset($params['lastPageText'])) {
            $params['lastPageText'] = '末页';
        }

        //附加标签 div ul li a 未选中页 选中页 首末页 上一页 下一页
        foreach (['div', 'ul', 'li', 'a', 'currentPage', 'prevPage', 'nextPage', 'firstPage', 'lastPage'] as $attrItem) {
            if (! isset($params['attrs'][$attrItem])) {
                $params['attrs'][$attrItem] = '';
            } else {
                $params['attrs'][$attrItem] = ' '.$params['attrs'][$attrItem];
            }
        }

        if (strlen($params['attrs']['currentPage']) === 0) {
            $params['attrs']['currentPage'] = ' class="active"';
        }

        return $params;
    }

    /**
     * 获取当前url，用于单元测试
     * 
     * @return string
     */
    public function getCurrentUrl()
    {
        return $_SERVER["REQUEST_URI"];
    }      
}