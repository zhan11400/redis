<?php

namespace since;

use since\Connection\RedisConnectionPool;
use since\Operation\KeyOperation;
use since\Operation\StringOperation;
use since\Operation\HashOperation;
use since\Operation\ListOperation;
use since\Operation\SetOperation;
use since\Operation\SortedSetOperation;
use since\Operation\GeoOperation;
use since\Operation\HyperLogLogOperation;
use since\Operation\StreamOperation;

/**
 * Redis门面类
 * 作为所有Redis操作的统一入口，实现门面模式
 * 提供获取各个操作类实例的方法
 */
class RedisFacade
{
    /**
     * 单例实例
     * 
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * 连接池实例
     * 
     * @var RedisConnectionPool
     */
    private RedisConnectionPool $connectionPool;

    /**
     * 操作类实例缓存
     * 
     * @var array
     */
    private array $operationInstances = [];

    /**
     * RedisFacade构造函数
     * 私有构造函数，防止外部实例化
     * 
     * @param array $options Redis连接选项
     */
    private function __construct(array $options = [])
    {
        // 初始化连接池
        $this->connectionPool = RedisConnectionPool::getInstance($options);
    }

    /**
     * 获取RedisFacade单例实例
     * 
     * @param array $options Redis连接选项
     * @return self
     */
    public static function getInstance(array $options = []): self
    {
        if (self::$instance === null) {
            self::$instance = new self($options);
        }
        return self::$instance;
    }

    /**
     * 获取键操作类实例
     * 
     * @return KeyOperation
     */
    public function key(): KeyOperation
    {
        return $this->getOperationInstance(KeyOperation::class);
    }

    /**
     * 获取字符串操作类实例
     * 
     * @return StringOperation
     */
    public function string(): StringOperation
    {
        return $this->getOperationInstance(StringOperation::class);
    }

    /**
     * 获取哈希表操作类实例
     * 
     * @return HashOperation
     */
    public function hash(): HashOperation
    {
        return $this->getOperationInstance(HashOperation::class);
    }

    /**
     * 获取列表操作类实例
     * 
     * @return ListOperation
     */
    public function list(): ListOperation
    {
        return $this->getOperationInstance(ListOperation::class);
    }

    /**
     * 获取集合操作类实例
     * 
     * @return SetOperation
     */
    public function set(): SetOperation
    {
        return $this->getOperationInstance(SetOperation::class);
    }

    /**
     * 获取有序集合操作类实例
     * 
     * @return SortedSetOperation
     */
    public function sortedSet(): SortedSetOperation
    {
        return $this->getOperationInstance(SortedSetOperation::class);
    }

    /**
     * 获取地理位置操作类实例
     * 
     * @return GeoOperation
     */
    public function geo(): GeoOperation
    {
        return $this->getOperationInstance(GeoOperation::class);
    }

    /**
     * 获取HyperLogLog操作类实例
     * 
     * @return HyperLogLogOperation
     */
    public function hyperLogLog(): HyperLogLogOperation
    {
        return $this->getOperationInstance(HyperLogLogOperation::class);
    }

    /**
     * 获取Stream操作类实例
     * 
     * @return StreamOperation
     */
    public function stream(): StreamOperation
    {
        return $this->getOperationInstance(StreamOperation::class);
    }

    /**
     * 获取操作类实例
     * 使用反射动态创建实例
     * 
     * @param string $className 操作类名称
     * @return mixed
     */
    private function getOperationInstance(string $className): mixed
    {
        if (!isset($this->operationInstances[$className])) {
            $this->operationInstances[$className] = new $className($this->connectionPool);
        }
        return $this->operationInstances[$className];
    }

    /**
     * 获取Redis服务器信息
     * 
     * @param string $section 指定信息部分，默认为空
     * @return array Redis服务器信息
     * @throws \Exception
     */
    public function info(string $section = ''): array
    {
        $redis = $this->connectionPool->getConnection();
        try {
            $result = $redis->info($section);
        } catch (\Exception $e) {
            $this->connectionPool->returnConnection($redis, true);
            throw $e;
        }
        $this->connectionPool->returnConnection($redis);
        return $result;
    }

    /**
     * 发送Ping命令
     * 
     * @param string $message 可选的消息，默认为空
     * @return string|PONG Ping响应
     * @throws \Exception
     */
    public function ping(string $message = ''): string
    {
        $redis = $this->connectionPool->getConnection();
        try {
            $result = $redis->ping($message);
        } catch (\Exception $e) {
            $this->connectionPool->returnConnection($redis, true);
            throw $e;
        }
        $this->connectionPool->returnConnection($redis);
        return $result;
    }

    /**
     * 执行Redis命令
     * 直接执行任意Redis命令
     * 
     * @param string $command Redis命令
     * @param mixed ...$args 命令参数
     * @return mixed 命令执行结果
     * @throws \Exception
     */
    public function executeCommand(string $command, mixed ...$args): mixed
    {
        $redis = $this->connectionPool->getConnection();
        try {
            $result = $redis->rawCommand($command, ...$args);
        } catch (\Exception $e) {
            $this->connectionPool->returnConnection($redis, true);
            throw $e;
        }
        $this->connectionPool->returnConnection($redis);
        return $result;
    }

    /**
     * 获取连接池状态信息
     * 
     * @return array 连接池状态信息
     */
    public function getPoolStatus(): array
    {
        return $this->connectionPool->getStatus();
    }

    /**
     * 关闭连接池中的所有连接
     * 
     * @return void
     */
    public function closeAllConnections(): void
    {
        $this->connectionPool->closeAllConnections();
    }

    /**
     * 防止克隆实例
     */
    private function __clone()
    {
        // 禁止克隆
    }

    /**
     * 防止反序列化
     */
    public function __wakeup()
    {
        // 禁止反序列化
    }
}