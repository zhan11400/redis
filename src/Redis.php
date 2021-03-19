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
    public function __clone()
    {
        // TODO: Implement __clone() method.
    }
   ###系统

    /**redis信息
     * @param null $option 前缀
     * @return string
     */
    public function info($option=null)
    {
        return $this->redis->info($option);
    }
    public function save()
    {
        return $this->redis->save();
    }
    # => 连接命令

    /**连接命令校验
     * @param $password
     * @return bool
     */
    public function auth($password)
    {
        return $this->redis->auth($password);
    }
    /**命令用于打印给定的字符
     * @param $message
     * @return string
     */
    public function echo($message)
    {
        return $this->redis->echo($message);
    }

    /**查看服务是否运行
     * @param $message
     * @return bool|string
     * @throws \RedisException
     */
    public function ping($message)
    {
        return $this->redis->ping($message);
    }

    /**切换数据库
     * @param $dbIndex
     * @return bool
     */
    public function select($dbIndex)
    {
        return $this->redis->select($dbIndex);
    }

    ### 键(key) 开始

    /**在 key 存在时删除 key
     * @param $key
     * @return int
     */
    public function del($key)
    {
        return $this->redis->del($key);
    }

    /**序列化给定 key ，并返回被序列化的值。
     * @param $key
     * @return bool|string
     */
    public function dump($key)
    {
        return $this->redis->dump($key);
    }

    /**检查给定 key 是否存在
     * @param $key
     * @return bool|int
     */
    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    /**为给定 key 设置过期时间，秒
     * @param $key
     * @param int $second 过期时间 秒
     * @return bool
     */
    public function expire($key,int $second)
    {
        return $this->redis->expire($key,$second);
    }

    /**为给定 key 设置过期时间，时间戳
     * @param $key
     * @param int $timestamp 过期的时间戳
     * @return bool
     */
    public function expireAt($key, int $timestamp)
    {
        return $this->redis->expireAt($key, $timestamp);
    }

    /**为给定 key 设置过期时间，毫秒
     * @param $key
     * @param int $ttl
     * @return bool
     */
    public function pExpire($key, int $ttl)
    {
        return $this->redis->pExpire($key, $ttl);
    }
    /**为给定 key 设置过期时间，毫秒时间戳
     * @param $key
     * @param int $timestamp 过期的时间戳
     * @return bool
     */
    public function pExpireAt($key, int $timestamp)
    {
        return $this->redis->pExpireAt($key, $timestamp);
    }

    /**查找所有符合给定模式( pattern)的 key
     * @param $pattern
     * @return array
     */
    public function keys($pattern)
    {
        return $this->redis->keys($pattern);
    }

    /**将当前数据库的 key 移动到给定的数据库 db 当中。
     * @param $key
     * @param $dbIndex
     * @return bool
     */
    public function move($key, $dbIndex)
    {
        return $this->redis->move($key, $dbIndex);
    }

    /**移除给定 key 的过期时间，使得 key 永不过期。
     * @param $key
     * @return bool
     */
    public function persist($key)
    {
        return $this->redis->persist($key);
    }

    /**以毫秒为单位返回 key 的剩余的过期时间。
     * @param $key
     * @return bool|int
     */
    public function pttl($key)
    {
        return $this->redis->pttl($key);
    }
    /**以秒为单位返回 key 的剩余的过期时间。
     * @param $key
     * @return bool|int
     */
    public function ttl($key)
    {
        return $this->redis->ttl($key);
    }

    /**从当前数据库中随机返回一个 key 。
     * @return string
     */
    public function randomKey()
    {
        return $this->redis->randomKey();
    }

    /**把key重命名
     * @param $srcKey
     * @param $dstKey
     * @return bool
     */
    public function rename($srcKey, $dstKey)
    {
        return $this->redis->rename($srcKey, $dstKey);
    }

    /**仅当 newkey 不存在时，将 key 改名为 newkey 。
     * @param $srcKey
     * @param $dstKey
     * @return bool
     */
    public function renameNx($srcKey, $dstKey)
    {
        return $this->redis->renameNx($srcKey, $dstKey);
    }
    /**迭代数据库中的数据库键
     * @param $iterator
     * @param null $pattern
     * @param int $count
     * @return array|bool
     */
    public function scan(&$iterator, $pattern = null, $count = 0)
    {
        return $this->redis->scan($iterator, $pattern, $count);
    }

    /**返回 key 所储存的值的类型。
     * @param $key
     * @return int 0为空，1 string，2 set 3 list 4 zset，5 hash
     */
    public function type($key)
    {
        return $this->redis->type($key);
    }
    ### 键(key) 结束

    ###自定义封装 开始
    /**获取key的过期时间
     * @param $key
     * @param bool $type false为秒，true为毫秒
     * @return bool|int
     */
    public function get_key_expire($key,bool $type=false){
        if($type){
            return $this->redis->pttl($key);
        }else{
            return $this->redis->ttl($key);
        }
    }
    /**设置key的过期时间
     * @param $key
     * @param int $ttl 设置过期的时间,0则为永久不过期
     * @param bool $type false为秒，true为毫秒
     * @return bool|int
     */
    public function set_key_expire($key,int $ttl=0,bool $type=false){
        if($ttl>0) {
            if ($type) {
                return $this->redis->expire($key, $ttl);
            } else {
                return $this->redis->pExpire($key, $ttl);
            }
        }else{
            return $this->redis->persist($key);
        }
    }
    ###自定义封装 结束

    ### HyperLogLog 开始
    /**
     * 添加指定元素到 HyperLogLog 中
     * @param $key
     * @param $arr
     * @return bool
     */
    public function pfAdd($key,$arr)
    {
        return $this->redis->pfAdd($key,$arr);
    }
    /**返回给定 HyperLogLog 的基数估算值。
     * @param $key
     * @return int
     */
    public function pfCount($key)
    {
        return $this->redis->pfCount($key);
    }

    /**将多个 HyperLogLog 合并为一个 HyperLogLog
     * @param string $key
     * @param array $arr 其他key数组
     * @return bool
     */
    public function pfMerge($key,$arr)
    {
        return $this->redis->pfMerge($key,$arr);
    }
    ### HyperLogLog结束
    ### GEO 开始

    /**添加地理位置的坐标
     * @param $key
     * @param $longitude
     * @param $latitude
     * @param $member
     * @return int
     */
    public function geoadd($key, $longitude, $latitude, $member){
        return $this->redis->geoadd($key, $longitude, $latitude, $member);
    }

    /**获取地理位置的坐标
     * @param string $key
     * @param string $member
     * @return array
     */
    public function geopos(string $key, string $member){
        return $this->redis->geopos($key,$member);
    }

    /**计算两个位置之间的距离
     * @param $key
     * @param $member1
     * @param $member2
     * @param null $unit
     * @return float
     */
    public function geodist($key, $member1, $member2, $unit = null){
        return $this->redis->geodist($key, $member1, $member2, $unit);
    }

    /**根据用户给定的经纬度坐标来获取指定范围内的地理位置集合。
     * @param $key
     * @param $longitude
     * @param $latitude
     * @param $radius
     * @param $unit
     * @param array|null $options
     * @return mixed
     */
    public function georadius($key, $longitude, $latitude, $radius, $unit, array $options = null){
        return $this->redis->georadius($key, $longitude, $latitude, $radius, $unit,$options);
    }

    /**根据储存在位置集合里面的某个地点获取指定范围内的地理位置集合。
     * @param $key
     * @param $member
     * @param $radius
     * @param $units
     * @param array|null $options
     * @return array
     */
    public function georadiusbymember($key, $member, $radius, $units, array $options = null){
        return $this->redis->georadiusbymember($key, $member, $radius, $units, $options);
    }

    /**返回一个或多个位置对象的 geohash 值
     * @param $key
     * @param mixed ...$member
     * @return array
     */
    public function geohash($key, ...$member){
        return $this->redis->geohash($key, ...$member);
    }
    ### GEO 结束
    ### hash 开始
    /**同时将多个 field-value (域-值)对设置到哈希表 key 中。
     * @param $key
     * @param $hashKeys
     * @return bool
     */
    public function hMSet($key, $hashKeys){
        return $this->redis->hMSet($key, $hashKeys);
    }

    /**将哈希表 key 中的字段 field 的值设为 value 。
     * @param $key
     * @param $hashKey
     * @param $value
     * @return bool|int
     */
    public function hSet($key, $hashKey, $value){
        return $this->redis->hSet($key, $hashKey, $value);
    }
    /**获取在哈希表中指定 key 的所有字段和值
     * @param $key
     * @return array
     */
    public function hGetAll($key){
        return $this->redis->hGetAll ($key);
    }

    /** 获取存储在哈希表中指定字段的值。
     * @param $key
     * @param $hashKey
     * @return string
     */
    public function hGet($key,$hashKey){
        return $this->redis->hGet($key,$hashKey);
    }

    /**获取所有哈希表中的字段
     * @param $key
     * @return array
     */
    public function hKeys($key){
        return $this->redis->hKeys($key);
    }

    /**查看哈希表 key 中，指定的字段是否存在。
     * @param $key
     * @param $hashKey
     * @return bool
     */
    public function hExists($key,$hashKey){
        return $this->redis->hExists($key,$hashKey);
    }

    /**获取哈希表中字段的数量
     * @param $key
     * @return bool|int
     */
    public function hLen($key){
        return $this->redis->hLen($key);
    }

    /**获取所有给定字段的值
     * @param $key
     * @param $hashKeys
     * @return array
     */
    public function hMGet($key, array $hashKeys){
        return $this->redis->hMGet($key, $hashKeys);
    }

    /**只有在字段 field 不存在时，设置哈希表字段的值。
     * @param $key
     * @param $hashKey
     * @param $value
     * @return bool
     */
    public function hSetNx($key, $hashKey, $value){
        return $this->redis->hSetNx($key, $hashKey, $value);
    }

    /**Redis HSCAN 命令用于迭代哈希表中的键值对。
     * @param $key
     * @param $iterator
     * @param null $pattern
     * @param int $count
     * @return array
     */
    public function hScan($key, &$iterator, $pattern = null, $count = 0){
        return $this->redis->hScan($key,$iterator, $pattern , $count);
    }
    /**获取哈希表中所有值。
     * @param $key
     * @return array
     */
    public function hVals($key){
        return $this->redis->hVals($key);
    }

    /**为哈希表 key 中的指定字段的整数值加上增量 increment 。
     * @param $key
     * @param $hashKey
     * @param int $value
     * @return int
     */
    public function hIncrBy($key, $hashKey, int $value){
        return $this->redis->hIncrBy($key, $hashKey, $value);
    }

    /**为哈希表 key 中的指定字段的浮点数值加上增量 increment 。
     * @param $key
     * @param $field
     * @param $increment
     * @return float
     */
    public function hIncrByFloat($key, $field, $increment){
        return $this->redis->hIncrByFloat($key, $field, $increment);
    }

    /**删除一个或多个哈希表字段
     * @param $key
     * @param $hashKey1
     * @param mixed ...$otherHashKeys
     * @return bool|int
     */
    public function hDel($key, $hashKey1, ...$otherHashKeys){
        return $this->redis->hDel($key, $hashKey1, ...$otherHashKeys);
    }

    /**
     * @param $key
     * @param $hashKey
     * @return int
     */
    public function hStrLen($key, $hashKey){
        return $this->redis->hStrLen($key, $hashKey);
    }

    ### hash 结束
    ### string 开始
    /**获取key的内容
     * @param $key
     * @return bool|mixed|string
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }
    /**设置指定 key 的值
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key,$value): bool
    {
        return $this->redis->set($key,$value);
    }
    /**返回 key 中字符串某范围的字符串
     * @param $key
     * @param int $start 开始位置
     * @param int $end 结束位置
     * @return string
     */
    public function getRange($key,int $start,int $end): string
    {
        return $this->redis->getRange($key,$start,$end);
    }

    /**将给定 key 的值设置新的值，并返回旧值)。
     * @param $key
     * @param $value
     * @return mixed|string
     */
    public function getSet($key,$value): string
    {
        return $this->redis->getSet($key,$value);
    }

    /**对 key 所储存的字符串值，获取指定偏移量上的位(bit)。
     * @param $key
     * @param $offset
     * @return int
     */
    public function getBit($key,$offset)
    {
        return $this->redis->getBit($key,$offset);
    }

    /**获取所有(一个或多个)给定 key 的值。
     * @param array $array
     * @return array
     */
    public function mget(array $array): array
    {
        return $this->redis->mget($array);
    }

    /**同时设置一个或多个 key-value 对。
     * @param array $array
     * @return bool
     */
    public function mset(array $array)
    {
        return $this->redis->mset($array);
    }

    /**时设置一个或多个 key-value 对，当且仅当所有给定 key 都不存在。
     * @param array $array
     * @return int
     */
    public function msetnx(array $array)
    {
        return $this->redis->msetnx($array);
    }
    /**对 key 所储存的字符串值，设置或清除指定偏移量上的位(bit)。
     * @param $key
     * @param $offset
     * @param $value
     * @return int
     */
    public function setBit($key,$offset,$value)
    {
        return $this->redis->setBit($key,$offset,$value);
    }

    /**只有在 key 不存在时设置 key 的值。
     * @param $key
     * @param $value
     * @return bool
     */
    public function setnx($key,$value)
    {
        return $this->redis->setnx($key, $value);
    }

    /**将值 value 关联到 key ，并将 key 的过期时间设为 seconds (以秒为单位)
     * @param $key
     * @param int $ttl 过期的秒数
     * @param $value
     * @return bool
     */
    public function setex($key, int $ttl, $value)
    {
        return $this->redis->setex($key, $ttl, $value);
    }
    /**将值 value 关联到 key ，并将 key 的过期时间设为 seconds (以毫秒为单位)
     * @param $key
     * @param int $ttl 过期的毫秒数
     * @param $value
     * @return bool
     */
    public function psetex($key, int $ttl, $value)
    {
        return $this->redis->psetex($key, $ttl, $value);
    }
    /**字符串值的长度。
     * @param $key
     * @return int
     */
    public function strlen($key)
    {
        return $this->redis->strlen($key);
    }

    /**用 value 参数覆写给定 key 所储存的字符串值，从偏移量 offset 开始。
     * @param $key
     * @param $offset
     * @param $value
     * @return int
     */
    public function setRange($key,$offset,$value)
    {
        return $this->redis->setRange($key,$offset,$value);
    }

    /**将 key 中储存的数字值增一。
     * @param $key
     * @return int
     */
    public function incr($key)
    {
        return $this->redis->incr($key);
    }

    /**将 key 中储存的数字值增指定数值。整型
     * @param $key
     * @param int $value
     * @return int
     */
    public function incrBy($key,int $value)
    {
        return $this->redis->incrBy($key,$value);
    }

    /**将 key 中储存的数字值增指定数值。浮点型
     * @param $key
     * @param float $value
     * @return float
     */
    public function incrByFloat($key,float $value)
    {
        return $this->redis->incrByFloat($key,$value);
    }
    /**将 key 中储存的数字值减一
     * @param $key
     * @return int
     */
    public function decr($key)
    {
        return $this->redis->decr($key);
    }

    /**key 所储存的值减去给定的减量值 。
     * @param $key
     * @param $value
     * @return int
     */
    public function decrBy($key,$value)
    {
        return $this->redis->decrBy($key,$value);
    }

    /**如果 key 已经存在并且是一个字符串， APPEND 命令将指定的 value 追加到该 key 原来值（value）的末尾。
     * @param $key
     * @param $value
     * @return int
     */
    public function append($key,$value)
    {
        return $this->redis->append($key,$value);
    }
    ### string 结束
    ### sorted set 有序集合 开始
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
     */
    public function zInter($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM'){
        return $this->redis->zInter($Output, $ZSetKeys,  $Weights, $aggregateFunction);
    }

    /**结果集中某个成员的分数值是所有给定集下该成员分数值之和
     * @param $Output
     * @param $ZSetKeys
     * @param array|null $Weights
     * @param string $aggregateFunction
     * @return int
     */
    public function zInterStore($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM'){
        return $this->redis->zInterStore($Output, $ZSetKeys,  $Weights, $aggregateFunction);
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
    ### sorted set 有序集合 结束

    ### LIST 队列 开始
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

    ### list 结束

    ### set 无序集合开始
    /**向集合添加一个或多个成员
     * @param $key
     * @param mixed ...$value1
     * @return bool|int
     */
    public function sadd($key, ...$value1){
        return $this->redis->sAdd($key, ...$value1);
    }

    /**获取集合的成员数
     * @param $key
     * @return int
     */
    public function sCard($key){
        return $this->redis->sCard($key);
    }

    /**返回第一个集合与其他集合之间的差异。
     * @param $key
     * @param mixed ...$otherKeys
     * @return array
     */
    public function sDiff($key,...$otherKeys){
        return $this->redis->sDiff($key,...$otherKeys);
    }

    /**返回给定所有集合的差集并存储在 第一个key中
     * @param $dstKey
     * @param $key
     * @param mixed ...$otherKeys
     * @return bool|int
     */
    public function sDiffStore($dstKey,$key,...$otherKeys){
        return $this->redis->sDiffStore($dstKey,$key,...$otherKeys);
    }

    /**返回给定所有集合的交集
     * @param $key
     * @param mixed ...$otherKeys
     * @return array
     */
    public function sInter($key, ...$otherKeys){
        return $this->redis->sInter($key, ...$otherKeys);
    }

    /**返回集合中的所有成员
     * @param $key
     * @return array
     */
    public function sMembers($key){
        return $this->redis->sMembers($key);
    }

    /**判断某个值是否在集合中
     * @param $key
     * @param $value
     * @return bool
     */
    public function sIsMember($key, $value){
        return $this->redis->sIsMember($key, $value);
    }

    /**将元素从原集合移动到另外一个集合
     * @param $srcKey
     * @param $dstKey
     * @param $member
     * @return bool
     */
    public function sMove($srcKey, $dstKey, $member){
        return $this->redis->sMove($srcKey, $dstKey, $member);
    }

    /**移除并返回集合中的N个随机元素
     * @param $key
     * @param int $count 移除并返回的数量
     * @return array|bool|mixed|string
     */
    public function sPop($key, $count = 1){
        return $this->redis->sPop($key, $count);
    }

    /**返回集合中的N个随机元素
     * @param $key
     * @param int $count 返回的数量
     * @return array|bool|mixed|string
     */
    public function sRandMember($key, $count = 1){
        return $this->redis->sRandMember($key, $count);
    }

    /**移除集合中一个或多个成员
     * @param $key
     * @param mixed ...$member1
     * @return int
     */
    public function sRem($key,  ...$member1){
        return $this->redis->sRem($key,  ...$member1);
    }

    /**多个合集合并的数据，返回所有给定集合的并集
     * @param $key
     * @param mixed ...$member1
     * @return array
     */
    public function sUnion($key,  ...$member1){
        return $this->redis->sUnion($key,  ...$member1);
    }

    /**多个合集合并的数据返回到一个集合里面
     * @param $dstKey
     * @param $key1
     * @param mixed ...$otherKeys
     * @return int
     */
    public function sUnionStore($dstKey, $key1, ...$otherKeys){
        return $this->redis->sUnionStore($dstKey, $key1, ...$otherKeys);
    }

    /**迭代集合中的元素
     * @param $key
     * @param $iterator
     * @param null $pattern
     * @param int $count
     * @return array|bool
     */
    public function sScan($key, &$iterator, $pattern = null, $count = 0){
        return $this->redis->sScan($key, $iterator, $pattern , $count);
    }

    ### 无序集合 set 结束

    /**添加消息到末尾
     * @param $key
     * @param string|int $id 消息 id，我们使用 * 表示由 redis 生成，可以自定义，但是要自己保证递增性。
     * @param array $messages 记录
     * @param int $maxLen 长度
     * @param false $isApproximate
     * @return string
     */
    public function xAdd($key, $id,array $messages, $maxLen = 0, $isApproximate = false): string
    {
        return $this->redis->xAdd($key, $id, $messages, $maxLen , $isApproximate);
    }

    /**对流进行修剪，限制长度
     * @param $stream
     * @param int $maxLen 长度
     * @param int $isApproximate 数量
     * @return int
     */
    public function xTrim($stream, int $maxLen, int $isApproximate): int
    {
        return $this->redis->xTrim($stream, $maxLen, $isApproximate);
    }

    /**删除信息
     * @param $key
     * @param  array $ids
     * @return int
     */
    public function xDel($key, array $ids): int
    {
        return $this->redis->xDel($key, $ids);
    }

    /**获取流包含的元素数量，即消息长度
     * @param $stream
     * @return int
     */
    public function xLen($stream)
    {
        return $this->redis->xLen($stream);
    }

    /**获取消息列表，会自动过滤已经删除的消息
     * @param $stream
     * @param $start
     * @param $end
     * @param null $count
     * @return array
     */
    public function xRange($stream, $start, $end, $count = null)
    {
        return $this->redis->xRange($stream, $start, $end, $count);
    }

    /**ID从大到小获取消息列表
     * @param $stream
     * @param $end
     * @param $start
     * @param null $count
     * @return array
     */
    public function xRevRange($stream, $end, $start, $count = null)
    {
        return $this->redis->xRevRange($stream, $end, $start, $count);
    }

    /**以阻塞或非阻塞方式获取消息列表
     * @param $streams
     * @param null $count
     * @param null $block
     * @return array
     */
    public function xRead($streams, $count = null, $block = null)
    {
        return $this->redis->xRead($streams, $count = null, $block = null);
    }

    /**消费者组
     * @param $operation
     * @param $key
     * @param $group
     * @param string $msgId
     * @param false $mkStream
     * @return false|mixed
     */
    public function xGroup($operation, $key, $group, $msgId = '', $mkStream = false)
    {
        $operations=[
            'CREATE',//创建消费者组
            'SETID',//为消费者组设置新的最后递送消息ID
            'DELCONSUMER',//删除消费者
            'DELGROUP',//删除消费者组
            'HELP',//
            'DESTROY'//删除消费者组
        ];
        if(!in_array($operation,$operations)){
            return false;
        }
        return $this->redis->xGroup($operation, $key, $group, $msgId = '', $mkStream = false);
    }

    /**读取消费者组中的消息
     * @param $group
     * @param $consumer
     * @param $streams
     * @param null $count
     * @param null $block
     * @return array
     */
    public function xReadGroup($group, $consumer, $streams, $count = null, $block = null){
        return $this->redis->xReadGroup($group, $consumer, $streams, $count, $block);
    }

    /**将消息标记为"已处理"
     * @param $stream
     * @param $group
     * @param $messages
     * @return int
     */
    public function xAck($stream, $group, $messages){
        return $this->redis->xAck($stream, $group, $messages);
    }

    /**显示待处理消息的相关信息
     * @param $stream
     * @param $group
     * @param null $start
     * @param null $end
     * @param null $count
     * @param null $consumer
     * @return array
     */
    public function xPending($stream, $group, $start = null, $end = null, $count = null, $consumer = null){
        return $this->redis->xPending($stream, $group, $start, $end, $count, $consumer);
    }

    /**转移消息的归属权
     * @param $key
     * @param $group
     * @param $consumer
     * @param $minIdleTime
     * @param $ids
     * @param array $options
     * @return array
     */
    public function xClaim($key, $group, $consumer, $minIdleTime, $ids, $options = []){
        return $this->redis->xClaim($key, $group, $consumer, $minIdleTime, $ids, $options);
    }

    /**查看流和消费者组的相关信息；
     * @param $operation
     * @param $stream
     * @param $group
     * @return mixed
     */
    public function xInfo($operation, $stream, $group){
        $operations=[
            'GROUPS',//打印消费者组的信息
            'STREAM',//打印流信息
            'CONSUMERS',//
            'HELP',//
        ];
        if(!in_array($operation,$operations)){
            return false;
        }
        return $this->redis->xInfo($operation, $stream, $group);
    }

    ### Stream 结束
}
