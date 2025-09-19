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
    public \Redis $redis;
    protected array $options = [
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
    private static ?self $_instance = null;

    /**
     * 获取Redis实例
     * 
     * @param array|null $options 连接选项，支持：host, port, password, select, timeout, expire, persistent, prefix, serialize
     * @return self
     */
    public static function getInstance(?array $options = null): self {
        if(empty(self::$_instance)) {
            self::$_instance = new self($options);
        }
        return self::$_instance;
    }

    /**
     * 私有构造函数，防止外部实例化
     * 
     * @param array|null $options 连接选项
     */
    private function __construct(?array $options = null) {
        // 如果提供了选项，则更新默认选项
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        
        $this->redis = new \Redis();
        $result = $this->redis->connect($this->options['host'], $this->options['port'], $this->options['timeout']);
        if($result === false) {
            throw new \Exception('redis connect error');
        }
        
        // 如果有密码，则进行认证
        if (!empty($this->options['password'])) {
            $this->auth($this->options['password']);
        }
        
        // 如果有选择数据库，则切换数据库
        if (!empty($this->options['select'])) {
            $this->select($this->options['select']);
        }
    }
    public function __clone()
    {
        // 阻止克隆单例实例
        throw new \Exception('Cloning of singleton instance is not allowed');
    }
    
    // 防止反序列化
    public function __wakeup()
    {
        throw new \Exception('Deserialization of singleton instance is not allowed');
    }
   ###系统

    /**redis信息
     * @param string|null $option 一下参数
     * server 服务器信息
     * clients 已连接客户端信息
     * memory内存信息
     * persistence RDB 和 AOF 的相关信息
     * stats 一般统计信息
     * replication 主从复制信息
     * cpu CPU 计算量统计信息
     *  commandstatsRedis 命令统计信息
     * clusterRedis 集群信息
     * keyspace数据库相关的统计信息
     *
     * @return string|array
     */
    public function info(?string $option=null): string|array
    {
        return $this->redis->info($option);
    }
    
    /**将数据同步保存到磁盘
     * @return bool
     */
    public function save(): bool
    {
        return $this->redis->save();
    }
    
    /**在后台异步保存当前数据库的数据到磁盘
     * @return bool
     */
    public function bgsave(): bool
    {
        return $this->redis->bgsave();
    }
    
    # => 连接命令

    /**连接命令校验
     * @param string $password
     * @return bool
     */
    public function auth(string $password): bool
    {
        return $this->redis->auth($password);
    }
    
    /**命令用于打印给定的字符
     * @param string $message
     * @return string
     */
    public function echo_(string $message): string
    {
        // echo是PHP关键字，所以重命名方法
        return $this->redis->echo($message);
    }

    /**查看服务是否运行
     * @param string $message
     * @return bool|string
     * @throws \RedisException
     */
    public function ping(string $message): bool|string
    {
        return $this->redis->ping($message);
    }

    /**切换数据库
     * @param int $dbIndex
     * @return bool
     */
    public function select(int $dbIndex): bool
    {
        return $this->redis->select($dbIndex);
    }

    ### 键(key) 开始

    /**在 key 存在时删除 key
     * @param string|array $key
     * @return int
     */
    public function del(string|array $key): int
    {
        return $this->redis->del($key);
    }

    /**序列化给定 key ，并返回被序列化的值。
     * @param string $key
     * @return bool|string
     */
    public function dump(string $key): bool|string
    {
        return $this->redis->dump($key);
    }

    /**检查给定 key 是否存在
     * @param string|array $key
     * @return bool|int
     */
    public function exists(string|array $key): bool|int
    {
        return $this->redis->exists($key);
    }

    /**为给定 key 设置过期时间，秒
     * @param string $key
     * @param int $second 过期时间 秒
     * @return bool
     */
    public function expire(string $key, int $second): bool
    {
        return $this->redis->expire($key, $second);
    }

    /**为给定 key 设置过期时间，时间戳
     * @param string $key
     * @param int $timestamp 过期的时间戳
     * @return bool
     */
    public function expireAt(string $key, int $timestamp): bool
    {
        return $this->redis->expireAt($key, $timestamp);
    }

    /**为给定 key 设置过期时间，毫秒
     * @param string $key
     * @param int $ttl
     * @return bool
     */
    public function pExpire(string $key, int $ttl): bool
    {
        return $this->redis->pExpire($key, $ttl);
    }
    /**为给定 key 设置过期时间，毫秒时间戳
     * @param string $key
     * @param int $timestamp 过期的时间戳
     * @return bool
     */
    public function pExpireAt(string $key, int $timestamp): bool
    {
        return $this->redis->pExpireAt($key, $timestamp);
    }

    /**查找所有符合给定模式( pattern)的 key
     * @param string $pattern
     * @return array
     */
    public function keys(string $pattern='*'): array
    {
        return $this->redis->keys($pattern);
    }

    /**将当前数据库的 key 移动到给定的数据库 db 当中。
     * @param string $key
     * @param int $dbIndex
     * @return bool
     */
    public function move(string $key, int $dbIndex): bool
    {
        return $this->redis->move($key, $dbIndex);
    }

    /**移除给定 key 的过期时间，使得 key 永不过期。
     * @param string $key
     * @return bool
     */
    public function persist(string $key): bool
    {
        return $this->redis->persist($key);
    }

    /**以毫秒为单位返回 key 的剩余的过期时间。
     * @param string $key
     * @return bool|int
     */
    public function pttl(string $key): bool|int
    {
        return $this->redis->pttl($key);
    }
    /**以秒为单位返回 key 的剩余的过期时间。
     * @param string $key
     * @return bool|int
     */
    public function ttl(string $key): bool|int
    {
        return $this->redis->ttl($key);
    }

    /**从当前数据库中随机返回一个 key 。
     * @return string
     */
    public function randomKey(): string
    {
        return $this->redis->randomKey();
    }

    /**把key重命名
     * @param string $srcKey
     * @param string $dstKey
     * @return bool
     */
    public function rename(string $srcKey, string $dstKey): bool
    {
        return $this->redis->rename($srcKey, $dstKey);
    }

    /**仅当 newkey 不存在时，将 key 改名为 newkey 。
     * @param string $srcKey
     * @param string $dstKey
     * @return bool
     */
    public function renameNx(string $srcKey, string $dstKey): bool
    {
        return $this->redis->renameNx($srcKey, $dstKey);
    }
    
    /**迭代数据库中的数据库键
     * @param int &$iterator
     * @param string|null $pattern
     * @param int $count
     * @return array|bool
     */
    public function scan(int &$iterator, ?string $pattern = null, int $count = 0): array|bool
    {
        return $this->redis->scan($iterator, $pattern, $count);
    }

    /**返回 key 所储存的值的类型。
     * @param string $key
     * @return int 0为空，1 string，2 set 3 list 4 zset，5 hash
     */
    public function type(string $key): int
    {
        return $this->redis->type($key);
    }
    ### 键(key) 结束

    ###自定义封装 开始
    /**获取key的过期时间
     * @param string $key
     * @param bool $type false为秒，true为毫秒
     * @return bool|int
     */
    public function get_key_expire(string $key, bool $type=false): bool|int {
        if($type){
            return $this->redis->pttl($key);
        }else{
            return $this->redis->ttl($key);
        }
    }
    
    /**设置key的过期时间
     * @param string $key
     * @param int $ttl 设置过期的时间,0则为永久不过期
     * @param bool $type false为秒，true为毫秒
     * @return bool
     */
    public function set_key_expire(string $key, int $ttl=0, bool $type=false): bool {
        if($ttl > 0) {
            if ($type) {
                return $this->redis->expire($key, $ttl);
            } else {
                return $this->redis->pExpire($key, $ttl);
            }
        } else {
            return $this->redis->persist($key);
        }
    }
    ###自定义封装 结束

    ### HyperLogLog 开始
    /**
     * 添加指定元素到 HyperLogLog 中
     * @param string $key
     * @param array $arr
     * @return bool
     */
    public function pfAdd(string $key, array $arr): bool
    {
        return $this->redis->pfAdd($key, $arr);
    }
    
    /**返回给定 HyperLogLog 的基数估算值。
     * @param string|array $key
     * @return int
     */
    public function pfCount(string|array $key): int
    {
        return $this->redis->pfCount($key);
    }

    /**将多个 HyperLogLog 合并为一个 HyperLogLog
     * @param string $key
     * @param array $arr 其他key数组
     * @return bool
     */
    public function pfMerge(string $key, array $arr): bool
    {
        return $this->redis->pfMerge($key, $arr);
    }
    ### HyperLogLog结束
    ### GEO 开始

    /**添加地理位置的坐标
     * @param string $key
     * @param float $longitude
     * @param float $latitude
     * @param string $member
     * @return int
     */
    public function geoadd(string $key, float $longitude, float $latitude, string $member): int {
        return $this->redis->geoadd($key, $longitude, $latitude, $member);
    }

    /**获取地理位置的坐标
     * @param string $key
     * @param string $member
     * @return array
     */
    public function geopos(string $key, string $member): array {
        return $this->redis->geopos($key, $member);
    }

    /**计算两个位置之间的距离
     * @param string $key
     * @param string $member1
     * @param string $member2
     * @param string|null $unit
     * @return float
     */
    public function geodist(string $key, string $member1, string $member2, ?string $unit = null): float {
        return $this->redis->geodist($key, $member1, $member2, $unit);
    }

    /**根据用户给定的经纬度坐标来获取指定范围内的地理位置集合。
     * @param string $key
     * @param float $longitude
     * @param float $latitude
     * @param float $radius
     * @param string $unit
     * @param array|null $options
     * @return array
     */
    public function georadius(string $key, float $longitude, float $latitude, float $radius, string $unit, ?array $options = null): array {
        return $this->redis->georadius($key, $longitude, $latitude, $radius, $unit, $options);
    }

    /**根据储存在位置集合里面的某个地点获取指定范围内的地理位置集合。
     * @param string $key
     * @param string $member
     * @param float $radius
     * @param string $units
     * @param array|null $options
     * @return array
     */
    public function georadiusbymember(string $key, string $member, float $radius, string $units, ?array $options = null): array {
        return $this->redis->georadiusbymember($key, $member, $radius, $units, $options);
    }

    /**返回一个或多个位置对象的 geohash 值
     * @param string $key
     * @param string ...$member
     * @return array
     */
    public function geohash(string $key, string ...$member): array {
        return $this->redis->geohash($key, ...$member);
    }
    ### GEO 结束
    ### hash 开始
    /**同时将多个 field-value (域-值)对设置到哈希表 key 中。
     * @param string $key
     * @param array $hashKeys
     * @return bool
     */
    public function hMSet(string $key, array $hashKeys): bool {
        return $this->redis->hMSet($key, $hashKeys);
    }

    /**将哈希表 key 中的字段 field 的值设为 value 。
     * @param string $key
     * @param string $hashKey
     * @param mixed $value
     * @return bool|int
     */
    public function hSet(string $key, string $hashKey, mixed $value): bool|int {
        return $this->redis->hSet($key, $hashKey, $value);
    }
    /**获取在哈希表中指定 key 的所有字段和值
     * @param string $key
     * @return array
     */
    public function hGetAll(string $key): array {
        return $this->redis->hGetAll($key);
    }

    /** 获取存储在哈希表中指定字段的值。
     * @param string $key
     * @param string $hashKey
     * @return string|false
     */
    public function hGet(string $key, string $hashKey): string|false {
        return $this->redis->hGet($key, $hashKey);
    }

    /**获取所有哈希表中的字段
     * @param string $key
     * @return array
     */
    public function hKeys(string $key): array {
        return $this->redis->hKeys($key);
    }

    /**查看哈希表 key 中，指定的字段是否存在。
     * @param string $key
     * @param string $hashKey
     * @return bool
     */
    public function hExists(string $key, string $hashKey): bool {
        return $this->redis->hExists($key, $hashKey);
    }

    /**获取哈希表中字段的数量
     * @param string $key
     * @return int
     */
    public function hLen(string $key): int {
        return $this->redis->hLen($key);
    }

    /**获取所有给定字段的值
     * @param string $key
     * @param array $hashKeys
     * @return array
     */
    public function hMGet(string $key, array $hashKeys): array {
        return $this->redis->hMGet($key, $hashKeys);
    }

    /**只有在字段 field 不存在时，设置哈希表字段的值。
     * @param string $key
     * @param string $hashKey
     * @param mixed $value
     * @return bool
     */
    public function hSetNx(string $key, string $hashKey, mixed $value): bool {
        return $this->redis->hSetNx($key, $hashKey, $value);
    }

    /**Redis HSCAN 命令用于迭代哈希表中的键值对。
     * @param string $key
     * @param int $iterator
     * @param string|null $pattern
     * @param int $count
     * @return array
     */
    public function hScan(string $key, &$iterator, ?string $pattern = null, int $count = 0): array {
        return $this->redis->hScan($key, $iterator, $pattern, $count);
    }
    /**获取哈希表中所有值。
     * @param string $key
     * @return array
     */
    public function hVals(string $key): array {
        return $this->redis->hVals($key);
    }

    /**为哈希表 key 中的指定字段的整数值加上增量 increment 。
     * @param string $key
     * @param string $hashKey
     * @param int $value
     * @return int
     */
    public function hIncrBy(string $key, string $hashKey, int $value): int {
        return $this->redis->hIncrBy($key, $hashKey, $value);
    }

    /**为哈希表 key 中的指定字段的浮点数值加上增量 increment 。
     * @param string $key
     * @param string $field
     * @param float $increment
     * @return float
     */
    public function hIncrByFloat(string $key, string $field, float $increment): float {
        return $this->redis->hIncrByFloat($key, $field, $increment);
    }

    /**删除一个或多个哈希表字段
     * @param string $key
     * @param string $hashKey1
     * @param string ...$otherHashKeys
     * @return int
     */
    public function hDel(string $key, string $hashKey1, string ...$otherHashKeys): int {
        return $this->redis->hDel($key, $hashKey1, ...$otherHashKeys);
    }

    /**
     * @param string $key
     * @param string $hashKey
     * @return int
     */
    public function hStrLen(string $key, string $hashKey): int {
        return $this->redis->hStrLen($key, $hashKey);
    }

    ### hash 结束
    ### string 开始
    /**获取key的内容
     * @param string $key
     * @return string|false
     */
    public function get(string $key): string|false
    {
        return $this->redis->get($key);
    }
    /**设置指定 key 的值
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function set(string $key, mixed $value): bool
    {
        return $this->redis->set($key, $value);
    }
    /**返回 key 中字符串某范围的字符串
     * @param string $key
     * @param int $start 开始位置
     * @param int $end 结束位置
     * @return string
     */
    public function getRange(string $key, int $start, int $end): string
    {
        return $this->redis->getRange($key, $start, $end);
    }

    /**将给定 key 的值设置新的值，并返回旧值)。
     * @param string $key
     * @param mixed $value
     * @return string|false
     */
    public function getSet(string $key, mixed $value): string|false
    {
        return $this->redis->getSet($key, $value);
    }

    /**对 key 所储存的字符串值，获取指定偏移量上的位(bit)。
     * @param string $key
     * @param int $offset
     * @return int
     */
    public function getBit(string $key, int $offset): int
    {
        return $this->redis->getBit($key, $offset);
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
    public function mset(array $array): bool
    {
        return $this->redis->mset($array);
    }

    /**时设置一个或多个 key-value 对，当且仅当所有给定 key 都不存在。
     * @param array $array
     * @return bool
     */
    public function msetnx(array $array): bool
    {
        return $this->redis->msetnx($array);
    }
    /**对 key 所储存的字符串值，设置或清除指定偏移量上的位(bit)。
     * @param string $key
     * @param int $offset
     * @param int $value
     * @return int
     */
    public function setBit(string $key, int $offset, int $value): int
    {
        return $this->redis->setBit($key, $offset, $value);
    }

    /**只有在 key 不存在时设置 key 的值。
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function setnx(string $key, mixed $value): bool
    {
        return $this->redis->setnx($key, $value);
    }

    /**将值 value 关联到 key ，并将 key 的过期时间设为 seconds (以秒为单位)
     * @param string $key
     * @param int $ttl 过期的秒数
     * @param mixed $value
     * @return bool
     */
    public function setex(string $key, int $ttl, mixed $value): bool
    {
        return $this->redis->setex($key, $ttl, $value);
    }
    /**将值 value 关联到 key ，并将 key 的过期时间设为 seconds (以毫秒为单位)
     * @param string $key
     * @param int $ttl 过期的毫秒数
     * @param mixed $value
     * @return bool
     */
    public function psetex(string $key, int $ttl, mixed $value): bool
    {
        return $this->redis->psetex($key, $ttl, $value);
    }
    /**字符串值的长度。
     * @param string $key
     * @return int
     */
    public function strlen(string $key): int
    {
        return $this->redis->strlen($key);
    }

    /**用 value 参数覆写给定 key 所储存的字符串值，从偏移量 offset 开始。
     * @param string $key
     * @param int $offset
     * @param string $value
     * @return int
     */
    public function setRange(string $key, int $offset, string $value): int
    {
        return $this->redis->setRange($key, $offset, $value);
    }

    /**将 key 中储存的数字值增一。
     * @param string $key
     * @return int
     */
    public function incr(string $key): int
    {
        return $this->redis->incr($key);
    }

    /**将 key 中储存的数字值增指定数值。整型
     * @param string $key
     * @param int $value
     * @return int
     */
    public function incrBy(string $key, int $value): int
    {
        return $this->redis->incrBy($key, $value);
    }

    /**将 key 中储存的数字值增指定数值。浮点型
     * @param string $key
     * @param float $value
     * @return float
     */
    public function incrByFloat(string $key, float $value): float
    {
        return $this->redis->incrByFloat($key, $value);
    }
    /**将 key 中储存的数字值减一
     * @param string $key
     * @return int
     */
    public function decr(string $key): int
    {
        return $this->redis->decr($key);
    }

    /**key 所储存的值减去给定的减量值 。
     * @param string $key
     * @param int $value
     * @return int
     */
    public function decrBy(string $key, int $value): int
    {
        return $this->redis->decrBy($key, $value);
    }

    /**如果 key 已经存在并且是一个字符串， APPEND 命令将指定的 value 追加到该 key 原来值（value）的末尾。
     * @param string $key
     * @param string $value
     * @return int
     */
    public function append(string $key, string $value): int
    {
        return $this->redis->append($key, $value);
    }
    ### string 结束
    ### sorted set 有序集合 开始
    /**向有序集合添加一个或多个成员，或者更新已存在成员的分数
     * @param string $key
     * @param float $score1
     * @param string $value1
     * @return int
     */
    public function zadd(string $key, float $score1, string $value1): int{
        return $this->redis->zAdd( $key, $score1, $value1);
    }

    /**获取有序集合的成员数
     * @param string $key
     * @return int
     */
    public function zcard(string $key): int{
        return $this->redis->zCard($key);
    }

    /**计算在有序集合中指定区间分数的成员数
     * @param string $key
     * @param string|int|float $start
     * @param string|int|float $end
     * @return int
     */
    public function zcount(string $key, string|int|float $start, string|int|float $end): int{
        return $this->redis->zCount( $key, $start, $end );
    }

    /**有序集合中对指定成员的分数加上增量 increment
     * @param string $key
     * @param float $value
     * @param string $member
     * @return float
     */
    public function zincrby(string $key, float $value, string $member): float{
        return $this->redis->zIncrBy( $key, $value, $member );
    }

    /**计算给定的一个或多个有序集的交集并将结果集存储在新的有序集合 key 中
     * @param string $Output
     * @param array $ZSetKeys
     * @param array|null $Weights
     * @param string $aggregateFunction
     * @return mixed
     */
    public function zInter(string $Output, array $ZSetKeys, ?array $Weights = null, string $aggregateFunction = 'SUM'): mixed{
        return $this->redis->zInter($Output, $ZSetKeys,  $Weights, $aggregateFunction);
    }

    /**结果集中某个成员的分数值是所有给定集下该成员分数值之和
     * @param string $Output
     * @param array $ZSetKeys
     * @param array|null $Weights
     * @param string $aggregateFunction
     * @return int
     */
    public function zInterStore(string $Output, array $ZSetKeys, ?array $Weights = null, string $aggregateFunction = 'SUM'): int{
        return $this->redis->zInterStore($Output, $ZSetKeys,  $Weights, $aggregateFunction);
    }

    /**通过分数返回有序集合指定区间内的成员
     * @param string $key
     * @param string|int|float $start
     * @param string|int|float $end
     * @param array $options
     * @return array
     */
    public function zrangebyscore(string $key, string|int|float $start, string|int|float $end, ?array $options = null): array{
        return $this->redis->Zrangebyscore($key, $start, $end, $options);
    }

    /**通过索引区间返回有序集合指定区间内的成员
     * @param string $key
     * @param int $start
     * @param int $end
     * @param mixed $withscores
     * @return array
     */
    public function Zrange(string $key, int $start, int $end, mixed $withscores = null): array{
        return $this->redis->Zrange($key, $start, $end, $withscores);
    }

    /**通过字典区间返回有序集合的成员
     * @param string $key
     * @param string $min
     * @param string $max
     * @param int|null $offset
     * @param int|null $limit
     * @return array
     */
    public function zRangeByLex(string $key, string $min, string $max, ?int $offset = null, ?int $limit = null): array{
        return $this->redis->zRangeByLex($key, $min, $max, $offset, $limit);
    }

    /**移除有序集合中的一个或多个成员
     * @param string $key
     * @param string $member
     * @return int
     */
    public function zRem(string $key, string $member): int{
        return $this->redis->zRem( $key, $member);
    }

    /**移除有序集合中给定的排名区间的所有成员
     * @param string $key
     * @param int $start
     * @param int $end
     * @return int
     */
    public function zRemRangeByRank(string $key, int $start, int $end): int{
        return $this->redis->zRemRangeByRank( $key, $start, $end );
    }

    /**移除有序集合中给定的分数区间的所有成员
     * @param string $key
     * @param string|int|float $start
     * @param string|int|float $end
     * @return int
     */
    public function zRemRangeByScore(string $key, string|int|float $start, string|int|float $end): int{
        return $this->redis->zRemRangeByScore( $key, $start, $end );
    }

    /**返回有序集中指定区间内的成员，通过索引，分数从高到低
     * @param string $key
     * @param int $start
     * @param int $end
     * @param mixed $withscore
     * @return array
     */
    public function zRevRange(string $key, int $start, int $end, mixed $withscore = null): array{
        return $this->redis->zRevRange( $key, $start, $end, $withscore) ;
    }

    /**返回有序集中指定分数区间内的成员，分数从高到低排序
     * @param string $key
     * @param string|int|float $max
     * @param string|int|float $min
     * @param array $options
     * @return array
     */
    public function zRevRangeByScore(string $key, string|int|float $max, string|int|float $min, ?array $options = null): array{
        return $this->redis->zRevRangeByScore( $key, $max, $min, $options) ;
    }

    /**返回有序集合中指定成员的排名，有序集成员按分数值递减(从大到小)排序
     * @param string $key
     * @param string $member
     * @return int|false
     */
    public function zRevRank(string $key, string $member): int|false{
        return $this->redis->zRevRank( $key, $member ) ;
    }

    /**返回有序集中，成员的分数值
     * @param string $key
     * @param string $member
     * @return float|false
     */
    public function zscore(string $key, string $member): float|false{
        return $this->redis->Zscore( $key, $member ) ;
    }

    /**迭代有序集合中的元素（包括元素成员和元素分值）
     * @param string $key
     * @param int $iterator
     * @param string $pattern
     * @param int $count
     * @return array|bool
     */
    public function zScan(string $key, &$iterator, ?string $pattern = '', ?int $count = 0): array|bool{
        return $this->redis->zScan($key, $iterator, $pattern, $count) ;
    }
    ### sorted set 有序集合 结束

    ### LIST 队列 开始

    /***** list 队列 ********/

    /**按从左到右的顺序依次插入到表头
     * @param string $key
     * @param mixed ...$value
     * @return bool|int
     */
    public function lPush(string $key, mixed ...$value): bool|int{
        return $this->redis->lPush( $key, ...$value);
    }

    /**将值 value 插入到列表 key 的表头，当且仅当 key 存在并且是一个列表
     * @param string $key
     * @param mixed $value
     * @return int
     */
    public function lPushx(string $key, mixed $value): int{
        return $this->redis->lPushx( $key, $value);
    }

    /** 将一个或多个值 value 插入到列表 key 的表尾(最右边)。
     * @param string $key
     * @param mixed ...$value
     * @return bool|int
     */
    public function rPush(string $key, mixed ...$value): bool|int{
        return $this->redis->rPush( $key,...$value);
    }

    /**将值 value 插入到列表 key 的表尾，当且仅当 key 存在并且是一个列表
     * @param string $key
     * @param mixed $value
     * @return int
     */
    public function rPushx(string $key, mixed $value): int{
        return $this->redis->rPushx( $key, $value );
    }

    /**移除并返回列表 key 的头元素。
     * @param string $key
     * @return string|false
     */
    public function lPop(string $key): string|false{
        return $this->redis->lPop( $key );
    }

    /**移除并返回列表 key 的尾元素。
     * @param string $key
     * @return string|false
     */
    public function rPop(string $key): string|false{
        return $this->redis->rPop( $key );
    }

    /**从列表中弹出一个值，将弹出的元素插入到另外一个列表中并返回它； 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。
     * @param string $srcKey
     * @param string $dstKey
     * @return string|false
     */
    public function rpoplpush(string $srcKey, string $dstKey): string|false{
        return $this->redis->rpoplpush( $srcKey, $dstKey );
    }

    /**根据参数 count 的值，移除列表中与参数 value 相等的元素。
     * @param string $key
     * @param mixed $value
     * @param int $count
     * @return int
     */
    public function lRem(string $key, mixed $value, int $count): int{
        return $this->redis->lRem( $key, $value, $count );
    }

    /**返回列表 key 的长度。如果 key 不存在，则 key 被解释为一个空列表，返回 0，如果 key 不是列表类型，返回一个错误。
     * @param string $key
     * @return int
     */
    public function lLen(string $key): int{
        return $this->redis->lLen( $key );
    }

    /**返回列表 key 中，下标为 index 的元素。
     * @param string $key
     * @param int $index
     * @return string|false
     */
    public function lIndex(string $key, int $index): string|false{
        return $this->redis->lIndex( $key, $index );
    }

    /**在列表的元素前或者后插入元素
     * @param string $key
     * @param string $position  before || after
     * @param string $pivot  参照元素
     * @param string $value  要插入的元素
     * @return int
     */
    public function lInsert(string $key, string $position, string $pivot, string $value): int{
        return $this->redis->lInsert( $key, $position, $pivot, $value );
    }

    /**获取列表指定范围内的元素
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array
     */
    public function lRange(string $key, int $start, int $end): array{
        return $this->redis->lRange( $key, $start, $end );
    }

    /**让列表只保留指定区间内的元素，不在指定区间之内的元素都将被删除。
     * @param string $key
     * @param int $start
     * @param int $end
     * @return bool
     */
    public function lTrim(string $key, int $start, int $end): bool{
        return $this->redis->lTrim( $key, $start, $end );
    }

    /**移出并获取列表的第一个元素， 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。
     * @param array $keys
     * @param int $timeout
     * @return array|false
     */
    public function blPop(array $keys, int $timeout): array|false{
        return $this->redis->blPop($keys, $timeout);
    }

    /**移出并获取列表的最后一个元素， 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。
     * @param array $keys
     * @param int $timeout
     * @return array|false
     */
    public function brPop(array $keys, int $timeout): array|false{
        return $this->redis->brPop($keys, $timeout);
    }

    /**从列表中弹出一个值，将弹出的元素插入到另外一个列表中并返回它； 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。
     * @param string $srcKey
     * @param string $dstKey
     * @param int $timeout
     * @return string|false
     */
    public function brpoplpush(string $srcKey, string $dstKey, int $timeout): string|false{
        return $this->redis->brpoplpush( $srcKey, $dstKey, $timeout );
    }

    ### list 结束

    ### set 无序集合开始
    /**向集合添加一个或多个成员
     * @param string $key
     * @param mixed ...$value1
     * @return bool|int
     */
    public function sadd(string $key, ...$value1): bool|int {
        return $this->redis->sAdd($key, ...$value1);
    }

    /**获取集合的成员数
     * @param string $key
     * @return int
     */
    public function sCard(string $key): int {
        return $this->redis->sCard($key);
    }

    /**返回第一个集合与其他集合之间的差异。
     * @param string $key
     * @param string ...$otherKeys
     * @return array
     */
    public function sDiff(string $key, ...$otherKeys): array {
        return $this->redis->sDiff($key, ...$otherKeys);
    }

    /**返回给定所有集合的差集并存储在 第一个key中
     * @param string $dstKey
     * @param string $key
     * @param string ...$otherKeys
     * @return bool|int
     */
    public function sDiffStore(string $dstKey, string $key, ...$otherKeys): bool|int {
        return $this->redis->sDiffStore($dstKey, $key, ...$otherKeys);
    }

    /**返回给定所有集合的交集
     * @param string $key
     * @param string ...$otherKeys
     * @return array
     */
    public function sInter(string $key, ...$otherKeys): array {
        return $this->redis->sInter($key, ...$otherKeys);
    }

    /**返回集合中的所有成员
     * @param string $key
     * @return array
     */
    public function sMembers(string $key): array {
        return $this->redis->sMembers($key);
    }

    /**判断某个值是否在集合中
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function sIsMember(string $key, mixed $value): bool {
        return $this->redis->sIsMember($key, $value);
    }

    /**将元素从原集合移动到另外一个集合
     * @param string $srcKey
     * @param string $dstKey
     * @param mixed $member
     * @return bool
     */
    public function sMove(string $srcKey, string $dstKey, mixed $member): bool {
        return $this->redis->sMove($srcKey, $dstKey, $member);
    }

    /**移除并返回集合中的N个随机元素
     * @param string $key
     * @param int $count 移除并返回的数量
     * @return array|bool|mixed|string
     */
    public function sPop(string $key, int $count = 1): array|bool|mixed|string {
        return $this->redis->sPop($key, $count);
    }

    /**返回集合中的N个随机元素
     * @param string $key
     * @param int $count 返回的数量
     * @return array|bool|mixed|string
     */
    public function sRandMember(string $key, int $count = 1): array|bool|mixed|string {
        return $this->redis->sRandMember($key, $count);
    }

    /**移除集合中一个或多个成员
     * @param string $key
     * @param mixed ...$member1
     * @return int
     */
    public function sRem(string $key,  ...$member1): int {
        return $this->redis->sRem($key,  ...$member1);
    }

    /**多个合集合并的数据，返回所有给定集合的并集
     * @param string $key
     * @param string ...$member1
     * @return array
     */
    public function sUnion(string $key,  ...$member1): array {
        return $this->redis->sUnion($key,  ...$member1);
    }

    /**多个合集合并的数据返回到一个集合里面
     * @param string $dstKey
     * @param string $key1
     * @param string ...$otherKeys
     * @return int
     */
    public function sUnionStore(string $dstKey, string $key1, ...$otherKeys): int {
        return $this->redis->sUnionStore($dstKey, $key1, ...$otherKeys);
    }

    /**迭代集合中的元素
     * @param string $key
     * @param int $iterator
     * @param string|null $pattern
     * @param int $count
     * @return array|bool
     */
    public function sScan(string $key, &$iterator, ?string $pattern = null, int $count = 0): array|bool {
        return $this->redis->sScan($key, $iterator, $pattern , $count);
    }

    ### 无序集合 set 结束

    /**添加消息到末尾
     * @param string $key
     * @param string|int $id 消息 id，我们使用 * 表示由 redis 生成，可以自定义，但是要自己保证递增性。
     * @param array $messages 记录
     * @param int $maxLen 长度
     * @param bool $isApproximate
     * @return string
     */
    public function xAdd(string $key, string|int $id, array $messages, int $maxLen = 0, bool $isApproximate = false): string
    {
        return $this->redis->xAdd($key, $id, $messages, $maxLen, $isApproximate);
    }

    /**对流进行修剪，限制长度
     * @param string $stream
     * @param int $maxLen 长度
     * @param int $isApproximate 数量
     * @return int
     */
    public function xTrim(string $stream, int $maxLen, int $isApproximate): int
    {
        return $this->redis->xTrim($stream, $maxLen, $isApproximate);
    }

    /**删除信息
     * @param string $key
     * @param array $ids
     * @return int
     */
    public function xDel(string $key, array $ids): int
    {
        return $this->redis->xDel($key, $ids);
    }

    /**获取流包含的元素数量，即消息长度
     * @param string $stream
     * @return int
     */
    public function xLen(string $stream): int
    {
        return $this->redis->xLen($stream);
    }

    /**获取消息列表，会自动过滤已经删除的消息
     * @param string $stream
     * @param string $start
     * @param string $end
     * @param int|null $count
     * @return array
     */
    public function xRange(string $stream, string $start, string $end, ?int $count = null): array
    {
        return $this->redis->xRange($stream, $start, $end, $count);
    }

    /**ID从大到小获取消息列表
     * @param string $stream
     * @param string $end
     * @param string $start
     * @param int|null $count
     * @return array
     */
    public function xRevRange(string $stream, string $end, string $start, ?int $count = null): array
    {
        return $this->redis->xRevRange($stream, $end, $start, $count);
    }

    /**以阻塞或非阻塞方式获取消息列表
     * @param array $streams
     * @param int|null $count
     * @param int|null $block
     * @return array
     */
    public function xRead(array $streams, ?int $count = null, ?int $block = null): array
    {
        return $this->redis->xRead($streams, $count, $block);
    }

    /**消费者组
     * @param string $operation
     * @param string $key
     * @param string $group
     * @param string $msgId
     * @param bool $mkStream
     * @return false|mixed
     */
    public function xGroup(string $operation, string $key, string $group, string $msgId = '', bool $mkStream = false)
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
        return $this->redis->xGroup($operation, $key, $group, $msgId, $mkStream);
    }

    /**读取消费者组中的消息
     * @param string $group
     * @param string $consumer
     * @param array $streams
     * @param int|null $count
     * @param int|null $block
     * @return array
     */
    public function xReadGroup(string $group, string $consumer, array $streams, ?int $count = null, ?int $block = null): array {
        return $this->redis->xReadGroup($group, $consumer, $streams, $count, $block);
    }

    /**将消息标记为"已处理"
     * @param string $stream
     * @param string $group
     * @param array $messages
     * @return int
     */
    public function xAck(string $stream, string $group, array $messages): int {
        return $this->redis->xAck($stream, $group, $messages);
    }

    /**显示待处理消息的相关信息
     * @param string $stream
     * @param string $group
     * @param string|null $start
     * @param string|null $end
     * @param int|null $count
     * @param string|null $consumer
     * @return array
     */
    public function xPending(string $stream, string $group, ?string $start = null, ?string $end = null, ?int $count = null, ?string $consumer = null): array {
        return $this->redis->xPending($stream, $group, $start, $end, $count, $consumer);
    }

    /**转移消息的归属权
     * @param string $key
     * @param string $group
     * @param string $consumer
     * @param int $minIdleTime
     * @param array $ids
     * @param array $options
     * @return array
     */
    public function xClaim(string $key, string $group, string $consumer, int $minIdleTime, array $ids, array $options = []): array {
        return $this->redis->xClaim($key, $group, $consumer, $minIdleTime, $ids, $options);
    }

    /**查看流和消费者组的相关信息；
     * @param string $operation
     * @param string $stream
     * @param string $group
     * @return mixed
     */
    public function xInfo(string $operation, string $stream, string $group): mixed {
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
