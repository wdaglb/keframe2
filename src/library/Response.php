<?php
// +----------------------------------------------------------------------
// | jd
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;


class Response
{
    private $code = 200;
    private $header = [];
    private $data = '';

    public function __construct()
    {
    }

    /**
     * 设置http状态码
     * @param number $code
     */
    public function code($code)
    {
        $this->code = $code;
    }

    /**
     * 设置内容类型
     * @param string $str
     */
    public function contentType($str)
    {
        $this->header[] = 'Content-type: ' . $str . ';charset=UTF-8';
    }


    public function data($str)
    {
        $this->data = $str;
    }


    /**
     * 展示
     */
    public function send()
    {
        http_response_code($this->code);
        foreach ($this->header as $g)
            header($g);

        echo $this->data;
    }

}