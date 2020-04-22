<?php
/**
 * Create by
 * User: 湛工
 * DateTime: 2020/4/20 15:21
 * Email:  1140099248@qq.com
 */

namespace since;



class Redis
{
    public $redis = "";
    protected $options=[
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'password'   => '',
        'select'     => 0,
        'timeout'    => 0,
        'expire'     => 0,
        'persistent' => false,
        'prefix'     => '',
        'serialize'  => true,
    ];
    /**
     * 定义单例模式的变量
     * @var null
     */
    private static $_instance = null;
    public static function getInstance() {
        if(empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        $this->redis = new \Redis();
        $result = $this->redis->connect($this->options['host'], $this->options['port'], $this->options['timeout']);
        if($result === false) {
            throw new \Exception('redis connect error');
        }

    }
    public function __clone()
    {
        // TODO: Implement __clone() method.
    }
#### string 字符串
    /**
     * 赋值
     * @param $key
     * @param $value
     * @param int $time
     * @return bool|string
     */
    public function set($key, $value, $time = 0 ) {
        if(!$key) {
            return '';
        }
        if(is_array($value)) {
            $value = json_encode($value);
        }
        if(!$time) {
            return $this->redis->set($key, $value);
        }

        return $this->redis->setex($key, $time, $value);
    }

    /**
     * 取值
     * @param $key
     * @return bool|string
     */
    public function get($key) {
        if(!$key) {
            return '';
        }

        return $this->redis->get($key);
    }
    /**
     * 取值，并赋值
     * @param $key
     * @return bool|string
     */
    public function getset($key,$value) {
        if(!$key) {
            return '';
        }

        return $this->redis->getSet($key,$value);
    }

    /**
     * 设置一个有过期时间的key
     * @param $key
     * @param $expire
     * @param $value
     * @return bool
     */
    public function setex($key,$expire,$value)
    {
        return $this->redis->setex($key,$expire,$value);
    }
    /**设置一个key,如果key存在,不做任何操作.
     * @param $key
     * @param $value
     * @return bool
     */
    public function setnx($key,$value)
    {
        return $this->redis->setnx($key,$value);
    }
    /**
     * 批量设置key
     * @param $arr
     * @return bool
     */
    public function mset($arr)
    {
        return $this->redis->mset($arr);
    }
### list 队列

    /**在队列尾部插入一个元素
     * @param $key
     * @param $value
     * @return bool|int 返回队列长度
     */
    public function rPush($key,$value)
    {
        return $this->redis->rPush($key,$value);
    }

    /**在队列尾部插入一个元素 如果key不存在，什么也不做
     * @param $key
     * @param $value
     * @return int 返回队列长度
     */
    public function rPushx($key,$value)
    {
        return $this->redis->rPushx($key,$value);
    }

    /**在队列头部插入一个元素
     * @param $key
     * @param $value
     * @return bool|int 返回队列长度
     */
    public function lPush($key,$value)
    {
        return $this->redis->lPush($key,$value);
    }

    /**在队列头插入一个元素
     * @param $key
     * @param $value
     * @return int 返回队列长度
     */
    public function lPushx($key,$value)
    {
        return $this->redis->lPushx($key,$value);
    }

    /**返回队列长度
     * @param $key
     * @return int
     */
    public function lLen($key)
    {
        return $this->redis->lLen($key);
    }

    /**返回队列指定区间的元素
     * @param $key
     * @param $start
     * @param $end
     * @return array
     */
    public function lRange($key,$start,$end)
    {
        return $this->redis->lrange($key,$start,$end);
    }
    /**返回队列中指定索引的元素
     * @param $key
     * @param $index
     * @return String
     */
    public function lIndex($key,$index)
    {
        return $this->redis->lIndex($key,$index);
    }

    /**设定队列中指定index的值
     * @param $key
     * @param $index
     * @param $value
     * @return bool
     */
    public function lSet($key,$index,$value)
    {
        return $this->redis->lSet($key,$index,$value);
    }

    /**删除值为vaule的count个元素
     * count>0 从尾部开始
     *  >0　从头部开始
     *  =0　删除全部
     * @param $key
     * @param $value
     * @param $count
     * @return int
     */
    public function lRem($key,$value,$count)
    {
        return $this->redis->lRem($key,$value,$count);
    }

    /**删除并返回队列中的头元素
     * @param $key
     * @return string
     */
    public function lPop($key)
    {
        return $this->redis->lPop($key);
    }

    /**删除并返回队列中的尾元素
     * @param $key
     * @return string
     */
    public function rPop($key)
    {
        return $this->redis->rPop($key);
    }

### 魔术方法 在外部调用一个类里面不存在的函数时调用此函数
    /**
     * @param $name
     * @param $arguments
     * @return bool
     */
    public function __call($name, $arguments) {
        //echo $name.PHP_EOL;
        //print_r($arguments);
        if(count($arguments) != 2) {
            return false;
        }
        $this->redis->$name($arguments[0], $arguments[1]);
    }

}