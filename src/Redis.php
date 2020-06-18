<?php
/**
 * Create by
 * User: 湛工
 * DateTime: 2020/6/18 15:21
 * Email:  1140099248@qq.com
 */

namespace since;


class Redis
{
    public $redis = "";
    protected $options = [
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
    /**** 字符串 string  *****/

    /**获取指定 key 的值
     * @param $key
     * @return bool|string
     */
    public function get($key){
        return $this->redis->get($key);
    }

    /**设置指定 key 的值
     * @param string $key
     * @param string $value
     * @param int $timeout 过期时间
     * @return bool
     */
    public function set($key, $value,$timeout=0){
        if($timeout>0) {//当设置有效期时，当传timeout为0，key直接过期，不传才会永久
            return $this->redis->set($key, $value, $timeout);
        }else{
            return $this->redis->set($key, $value);
        }
    }

    /**返回 key 中字符串值的子字符,相当于substr截取字符串
     * @param $key
     * @param $start
     * @param $end
     * @return string
     */
    public function getRange( $key, $start, $end ){
        return $this->redis->getRange( $key, $start, $end );
    }

    /**将给定 key 的值设为 value ，并返回 key 的旧值,没有旧值就返回 false
     * @param $key
     * @param $value
     * @return string
     */
    public function getSet($key,$value){
        return $this->redis->getSet($key, $value);
    }

    /**对 key 所储存的字符串值，设置或清除指定偏移量上的位(bit)。
     * @param $key
     * @param $offset
     * @param $value
     * @return int
     */
    public function setBit($key,$offset,$value){
        return $this->redis->setBit($key, $offset,$value);
    }
    /**对 key 所储存的字符串值，获取指定偏移量上的位(bit)。
     * @param $key
     * @param $offset
     * @return int
     */
    public function getBit($key,$offset){
        return $this->redis->getBit($key, $offset);
    }
    /**将值 value 关联到 key ，并将 key 的过期时间设为 seconds (以秒为单位)。
     * @param $key
     * @param $ttl
     * @param $value
     * @return bool
     */
    public function setex($key,$ttl,$value){
        return $this->redis->setex($key, $ttl, $value );
    }
    /**将值 value 关联到 key ，并将 key 的过期时间设为 seconds (以毫秒为单位)。
     * @param $key
     * @param $ttl
     * @param $value
     * @return bool
     */
    public function psetex($key,$ttl,$value){
        return $this->redis->psetex($key, $ttl, $value );
    }

    /**只有在 key 不存在时设置 key 的值。
     * @param $key
     * @param $value
     * @return bool
     */
    public function setnx($key,$value){
        return $this->redis->setnx($key, $value );
    }

    /**查看key过期时间（秒）
     * @param $key
     * @return int -2则代表key不存在，-1代表key存在，但是没有设置有效期
     */
    public function ttl($key){
        return $this->redis->ttl($key );
    }
    /**查看key过期时间（毫秒）
     * @param $key
     * @return int -2则代表key不存在，-1代表key存在，但是没有设置有效期
     */
    public function pttl($key){
        return $this->redis->pttl($key );
    }

    /**用 value 参数覆写给定 key 所储存的字符串值，从偏移量 offset 开始。
     * @param $key
     * @param $offset
     * @param $value
     * @return string
     */
    public function setRange($key,$offset,$value){
        return $this->redis->setRange($key,$offset,$value);
    }

    /**返回 key 所储存的字符串值的长度
     * @param $key
     * @return int
     */
    public function strlen($key){
        return $this->redis->strlen($key);
    }

    /** 同时设置多个key
     * @param array $array 键值对形式
     * @return bool
     */
    public function mset($array){
        return $this->redis->mset($array);
    }

    /**同时获取多个key
     * @param $array
     * @return array
     */
    public function mget($array){
        return $this->redis->mget($array);
    }

    /**同时设置一个或多个 key-value 对，当且仅当所有给定 key 都不存在才生效
     * @param $array
     * @return int
     */
    public function msetnx($array){
        return $this->redis->msetnx($array);
    }

    /** 自增
     * @param $key
     * @param null $value
     * @return int
     */
    public function incr($key,$value=null){
        if($value===null) {//null时为自增1
            return $this->redis->incr($key);
        }
        if(is_int($value)) {//整型增加
            return $this->redis->incrBy($key,$value);
        }
        if(is_float($value)) {//浮点型增加
            return $this->redis->incrByFloat($key,$value);
        }
        return false;
    }

    /** 自减少
     * @param $key
     * @param null $value
     * @return int
     */
    public function decr($key,$value=null){
        if($value===null) {//null时为自增1
            return $this->redis->decr($key);
        }
        return $this->redis->decrBy($key,$value);
    }

    /**如果 key 已经存在并且是一个字符串， APPEND 命令将指定的 value 追加到该 key 原来值（value）的末尾。如果不存在key就跟set一样
     * @param $key
     * @param $value
     * @return int
     */
    public function append($key,$value){
        return $this->redis->append($key,$value);
    }
    /**** 哈希 hash  *****/

    /**将哈希表 key 中的字段 field 的值设为 value 。
     * @param $key
     * @param $value
     * @param $hashKey
     * @return bool|int
     */
    public function hset($key,$hashKey,$value){
        return $this->redis->hSet($key,$hashKey,$value);
    }

    /** 从hash里面取某个键的值
     * @param $key
     * @param $hashKey
     * @return string
     */
    public function hget($key,$hashKey){
        return $this->redis->hGet($key,$hashKey);
    }

    /**获取在哈希表中指定 key 的所有字段和值
     * @param $key
     * @return array
     */
    public function hGetAll ($key){
        return $this->redis->hGetAll($key);
    }

    /**判断哈希与键是否存在
     * @param $key
     * @param $hashKey
     * @return bool
     */
    public function hExists($key, $hashKey){
        return $this->redis->hExists($key, $hashKey);
    }

    /**只有在字段 field 不存在时，设置哈希表字段的值。需要覆盖则直接用hSet
     * @param $key
     * @param $hashKey
     * @param $value
     * @return bool
     */
    public function hSetNx($key,$hashKey,$value){
        return $this->redis->hSetNx ($key,$hashKey,$value);
    }

    /**删除一个或多个哈希表字段 hDel('key','a','b,'c')可以无限增加
     * @param $key
     * @param $hashKey
     * @return bool|int
     */
    public function hDel($key,...$hashKey){
        return $this->redis->hDel ($key,...$hashKey);
    }

    /**查看hash里面有多少个域
     * @param $key
     * @return int
     */
    public function hLen($key){
        return $this->redis->hLen($key);
    }

    /**返回哈希表 key 中， 与给定域 field 相关联的值的字符串长度（string length）。
     * @param $key
     * @param $hashKey
     * @return mixed
     */
    public function HSTRLEN($key,$hashKey){
        return $this->redis->HSTRLEN($key,$hashKey);
    }

    /**希表 key 中， 与给定域增加一定数量
     * @param $key
     * @param $hashKey
     * @param $value
     * @return int
     */
    public function hIncr($key,$hashKey,$value){
        if(is_int($value)) {//整型增加
            return $this->redis->hIncrBy($key,$hashKey,$value);
        }
        if(is_float($value)) {//浮点型增加
            return $this->redis->hIncrByFloat($key,$hashKey,$value);
        }
        return false;
    }

    /**批量新增多个
     * @param $key
     * @param array $hashKeys
     * @return bool
     */
    public function hMset($key,$hashKeys){
        return $this->redis->hMset( $key, $hashKeys );
    }
    /**批量新查询多个
     * @param $key
     * @param array $hashKeys
     * @return array
     */
    public function hMget($key,$hashKeys){
        return $this->redis->hMget( $key, $hashKeys );
    }

    /**获取当前哈希的所有域
     * @param $key
     * @return array
     */
    public function hKeys($key){
        return $this->redis->hKeys( $key);
    }
    /**获取当前哈希的所有域的值
     * @param $key
     * @return array
     */
    public function hVals($key){
        return $this->redis->hVals( $key);
    }

    /**
     * @param $key
     * @param $iterator
     * @param string $pattern
     * @param int $count
     * @return array
     */
    public function hScan($key, $iterator, $pattern = '', $count = 0){
        return $this->redis->hScan($key, $iterator, $pattern = '', $count = 0);
    }
    /***** list 队列 ********/

    /**按从左到右的顺序依次插入到表头
     * @param $key
     * @param mixed ...$value
     * @return bool|int
     */
    public function lPush($key, ...$value){
        return $this->redis->lPush( $key, ...$value);
    }

    /**将值 value 插入到列表 key 的表头，当且仅当 key 存在并且是一个列表
     * @param $key
     * @param $value
     * @return int
     */
    public function lPushx($key,$value){
        return $this->redis->lPushx( $key, $value);
    }

    /** 将一个或多个值 value 插入到列表 key 的表尾(最右边)。
     * @param $key
     * @param mixed ...$value
     * @return bool|int
     */
    public function rPush($key,...$value){
        return $this->redis->rPush( $key,...$value);
    }

    /**将值 value 插入到列表 key 的表尾，当且仅当 key 存在并且是一个列表
     * @param $key
     * @param $value
     * @return int
     */
    public function rPushx($key,$value){
        return $this->redis->rPushx( $key, $value );
    }

    /**移除并返回列表 key 的头元素。
     * @param $key
     * @return string
     */
    public function lPop($key){
        return $this->redis->lPop( $key );
    }

    /**移除并返回列表 key 的尾元素。
     * @param $key
     * @return string
     */
    public function rPop($key){
        return $this->redis->rPop( $key );
    }

    /**从列表中弹出一个值，将弹出的元素插入到另外一个列表中并返回它； 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。
     * @param $srcKey
     * @param $dstKey
     * @return string
     */
    public function rpoplpush( $srcKey, $dstKey ){
        return $this->redis->rpoplpush( $srcKey, $dstKey );
    }

    /**根据参数 count 的值，移除列表中与参数 value 相等的元素。
     * @param $key
     * @param $value
     * @param $count
     * @return int
     */
    public function lRem( $key, $value, $count ){
        return $this->redis->lRem( $key, $value, $count );
    }

    /**返回列表 key 的长度。如果 key 不存在，则 key 被解释为一个空列表，返回 0，如果 key 不是列表类型，返回一个错误。
     * @param $key
     * @return int
     */
    public function lLen( $key ){
        return $this->redis->lLen( $key );
    }

    /**返回列表 key 中，下标为 index 的元素。
     * @param $key
     * @param $index
     * @return String
     */
    public function lIndex( $key, $index ){
        return $this->redis->lIndex( $key, $index );
    }

    /**在列表的元素前或者后插入元素
     * @param $key
     * @param string $position  before || after
     * @param string $pivot  参照元素
     * @param string $value  要插入的元素
     * @return int
     */
    public function lInsert( $key, $position, $pivot, $value ){
        return $this->redis->lInsert( $key, $position, $pivot, $value );
    }

    /**获取列表指定范围内的元素
     * @param $key
     * @param $start
     * @param $end
     * @return array
     */
    public function lRange( $key, $start, $end ){
        return $this->redis->lRange( $key, $start, $end );
    }

    /**让列表只保留指定区间内的元素，不在指定区间之内的元素都将被删除。
     * @param $key
     * @param $start
     * @param $end
     * @return array
     */
    public function lTrim( $key, $start, $end ){
        return $this->redis->lTrim( $key, $start, $end );
    }

    /**移出并获取列表的第一个元素， 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。
     * @param array $keys
     * @param $timeout
     * @return array
     */
    public function blPop( array $keys, $timeout){
        return $this->redis->blPop($keys, $timeout);
    }

    /**移出并获取列表的最后一个元素， 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。
     * @param array $keys
     * @param $timeout
     * @return array
     */
    public function brPop( array $keys, $timeout){
        return $this->redis->brPop($keys, $timeout);
    }

    /**从列表中弹出一个值，将弹出的元素插入到另外一个列表中并返回它； 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。
     * @param $srcKey
     * @param $dstKey
     * @param $timeout
     * @return string
     */
    public function brpoplpush( $srcKey, $dstKey, $timeout ){
        return $this->redis->brpoplpush( $srcKey, $dstKey, $timeout );
    }

    /***** sorted set 有序集合 ********/
    /**向有序集合添加一个或多个成员，或者更新已存在成员的分数
     * @param $key
     * @param $score1
     * @param $value1
     * @return int
     */
    public function zadd($key, $score1, $value1){
        return $this->redis->zAdd( $key, $score1, $value1);
    }

    /**获取有序集合的成员数
     * @param $key
     * @return int
     */
    public function zcard($key){
        return $this->redis->zCard($key);
    }

    /**计算在有序集合中指定区间分数的成员数
     * @param $key
     * @param $start
     * @param $end
     * @return int
     */
    public function zcount($key, $start, $end){
        return $this->redis->zCount( $key, $start, $end );
    }

    /**有序集合中对指定成员的分数加上增量 increment
     * @param $key
     * @param $value
     * @param $member
     * @return float
     */
    public function zincrby($key, $value, $member){
        return $this->redis->zIncrBy( $key, $value, $member );
    }

    /**计算给定的一个或多个有序集的交集并将结果集存储在新的有序集合 key 中
     * @param $Output
     * @param $ZSetKeys
     * @param array|null $Weights
     * @param string $aggregateFunction
     * @return int
     */
    public function zInter($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM'){
        return $this->redis->zInter($Output, $ZSetKeys,  $Weights, $aggregateFunction);
    }

    /**通过分数返回有序集合指定区间内的成员
     * @param $key
     * @param $start
     * @param $end
     * @param array $options
     * @return array
     */
    public function zrangebyscore( $key, $start, $end, array $options = array() ){
        return $this->redis->Zrangebyscore($key, $start, $end,$options);
    }

    /**通过索引区间返回有序集合指定区间内的成员
     * @param $key
     * @param $start
     * @param $end
     * @param null $withscores
     * @return array
     */
    public function Zrange($key, $start, $end, $withscores = null ){
        return $this->redis->Zrange($key, $start, $end, $withscores);
    }

    /**通过字典区间返回有序集合的成员
     * @param $key
     * @param $min
     * @param $max
     * @param null $offset
     * @param null $limit
     * @return array
     */
    public function zRangeByLex($key, $min, $max, $offset = null, $limit = null ){
        return $this->redis->zRangeByLex($key, $min, $max, $offset, $limit);
    }

    /**移除有序集合中的一个或多个成员
     * @param $key
     * @param $member
     * @return int
     */
    public function zRem( $key, $member){
        return $this->redis->zRem( $key, $member);
    }

    /**移除有序集合中给定的排名区间的所有成员
     * @param $key
     * @param $start
     * @param $end
     * @return int
     */
    public function zRemRangeByRank( $key, $start, $end ){
        return $this->redis->zRemRangeByRank( $key, $start, $end );
    }

    /**移除有序集合中给定的分数区间的所有成员
     * @param $key
     * @param $start
     * @param $end
     * @return int
     */
    public function zRemRangeByScore( $key, $start, $end ){
        return $this->redis->zRemRangeByScore( $key, $start, $end );
    }

    /**返回有序集中指定区间内的成员，通过索引，分数从高到低
     * @param $key
     * @param $start
     * @param $end
     * @param null $withscore
     * @return array
     */
    public function zRevRange( $key, $start, $end, $withscore = null ){
        return $this->redis->zRevRange( $key, $start, $end, $withscore) ;
    }

    /**返回有序集中指定分数区间内的成员，分数从高到低排序
     * @param $key
     * @param $max
     * @param $min
     * @param array $options
     * @return array
     */
    public function zRevRangeByScore( $key, $max, $min, array $options = array() ){
        return $this->redis->zRevRangeByScore( $key, $max, $min, $options) ;
    }

    /**返回有序集合中指定成员的排名，有序集成员按分数值递减(从大到小)排序
     * @param $key
     * @param $member
     * @return int
     */
    public function zRevRank( $key, $member ){
        return $this->redis->zRevRank( $key, $member ) ;
    }

    /**返回有序集中，成员的分数值
     * @param $key
     * @param $member
     * @return float
     */
    public function zscore( $key, $member ){
        return $this->redis->Zscore( $key, $member ) ;
    }

    /**迭代有序集合中的元素（包括元素成员和元素分值）
     * @param $key
     * @param $iterator
     * @param string $pattern
     * @param int $count
     * @return array|bool
     */
    public function zScan($key, $iterator, $pattern = '', $count = 0){
        return $this->redis->zScan($key, $iterator, $pattern, $count) ;
    }

    public function __clone()
    {
        // TODO: Implement __clone() method.
    }
}