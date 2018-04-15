<?php
// +----------------------------------------------------------------------
// | jd
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke\route;


use ke\App;
use ke\Response;
use think\slog;


/**
 * @method RouteItem as(string $name)
 */
class RouteItem
{
    private $list = [];
    private $index; // 当前索引

    private $makeUrl = [];

    private $cacheMakeUrl = [];

    public function register(array $option)
    {
        $this->index = array_push($this->list, $option) - 1;

        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return RouteItem
     */
    public function __call($name, $arguments)
    {
        $calls = ['as'];
        if (in_array($name, $calls)) {
            call_user_func_array([$this, '___' . $name], $arguments);
            return $this;
        }
    }


    /**
     * 路由命名
     * @param string $name
     */
    public function ___as($name)
    {
        $this->list[$this->index]['as'] = $name;
        $this->makeUrl[$name] = $this->index;
    }


    /**
     * 生成URL
     * @param string $rule
     * @param array $param
     * @return mixed
     */
    public function url($rule, array $param = [])
    {
        $q = http_build_query($param);
        if (isset($this->cacheMakeUrl[$rule . $q])) { // 存在内存中缓存
            list($uri, $param) = $this->cacheMakeUrl[$rule . $q];

            var_dump('cache');
        } else {
            if (isset($this->makeUrl[$rule])) { // 命名路由
                $rule = $this->list[$this->makeUrl[$rule]]['rule'];
            }
            $uri = $rule;
            foreach ($this->list as $g) {
                if ($rule == $g['class']) {
                    $uri = $g['rule'];
                    break;
                }
            }

            // 替换uri规则参数
            foreach ($param as $key => $v) {
                $uri = preg_replace_callback('#\[' . $key . ':(\w)\]#', function ($match) use(&$param, $key, $v) {
                    unset($param[$key]);
                    return $v;
                }, $uri);
            }

            $this->cacheMakeUrl[$rule . $q] = [$uri, $param];
        }

        if (!empty($param)) {
            $u = parse_url($uri);
            $query = isset($u['query']) ? $u['query'] . '&' : '?';
            $uri = $u['path'] . ($query . http_build_query($param));
        }



        return $uri;
    }


    /**
     * 启动引导
     */
    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->list as $index => $item)
            if ($item['rule'] === $uri) {
                $this->boot($index);
            } else {
                $param = [];
                $preg = preg_replace_callback('#\[(\w+):(\w)\]#', function ($match) use(&$param) {
                    $param[] = $match[1];
                    if ($match[2] == 'n')
                        return '(\d+)';
                    elseif ($match[2] == 's')
                        return '(\w+)';
                    else
                        return '(.*)';
                }, $item['rule']);

                if (preg_match('#^' . str_replace('/', '\/', $preg) . '$#', $uri, $match)) {
                    $len = count($match);
                    for ($i = 1; $i < $len; $i++) {
                        $_GET[$param[$i - 1]] = $match[$i];
                    }
                    $this->list[$index]['path'] = $match[0];
                    $this->boot($index);
                }
            }

        // 404页面
        $res = new Response();
        $res->code(404);
        $res->data('Page Not Exist');
        $res->contentType('text/html');
        $res->send();
        exit;
    }





    /**
     * 路由进入控制器操作
     * @param $index
     */
    private function boot($index)
    {
        $route = $this->list[$index];

        if ($_SERVER['REQUEST_METHOD'] !== $route['method']) // 判断请求是否准确
            return;


        list($controller, $action) = explode('@', $route['class']);

        $module = 'index';

        static $info = [];
        if (strpos($controller, '/') !== false) {
            list($module, $controller) = explode('/', $controller);

            $info['module'] = $module;

            if (strpos($module, '.') !== false) {
                $module = str_replace('.', '\\', $module);
            }
        } else {
            $info['module'] = $module;
        }
        $info['controller'] = $controller;
        $info['action'] = $action;

        App::route($info);

        App::route_info($route);
        $namespace = 'app\\' . $module . '\\controller\\' . str_replace('/', '\\', $controller);

        if (!class_exists($namespace)) {
            throw new \Exception('Controller Not Exist:' . $namespace);
        }
        $class = new $namespace();
        if (!method_exists($class, $action)) {
            throw new \Exception('Controller Not Exist:' . $namespace . '::' . $action);
        }
        $content = $class->$action();

        $res = new Response();
        $res->code(200);
        if (is_array($content)) {
            $res->contentType('application/json');
            $res->data(json_encode($content));
        } else {
            $res->contentType('text/html');
            $res->data($content);
        }
        $res->send();
        exit;

    }

}