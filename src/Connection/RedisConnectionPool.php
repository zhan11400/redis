<?php

namespace since\Connection;

use Redis;

/**
 * Redis连接池管理器
 * 负责创建、管理和分配Redis连接资源
 */
class RedisConnectionPool
{
    /**
     * @var RedisConnectionPool|null 单例实例
     */
    private static ?RedisConnectionPool $instance = null;

    /**
     * @var array 连接池配置
     */
    private array $config = [];

    /**
     * @var array 可用连接池
     */
    private array $availableConnections = [];

    /**
     * @var array 正在使用的连接
     */
    private array $inUseConnections = [];

    /**
     * @var int 最大连接数
     */
    private int $maxConnections = 10;

    /**
     * @var int 连接超时时间（秒）
     */
    private int $timeout = 2;

    /**
     * RedisConnectionPool constructor.
     * 私有构造函数，防止外部直接实例化
     * 
     * @param array $config Redis连接配置
     */
    private function __construct(array $config = [])
    {
        $this->config = array_merge([
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => '',
            'database' => 0,
            'timeout' => $this->timeout,
            'max_connections' => $this->maxConnections,
        ], $config);
        
        $this->maxConnections = $this->config['max_connections'];
        $this->timeout = $this->config['timeout'];
    }

    /**
     * 获取连接池单例实例
     * 
     * @param array $config Redis连接配置
     * @return RedisConnectionPool
     */
    public static function getInstance(array $config = []): RedisConnectionPool
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        
        return self::$instance;
    }

    /**
     * 从连接池获取一个Redis连接
     * 
     * @return Redis
     * @throws \Exception
     */
    public function getConnection(): Redis
    {
        // 尝试从可用连接池中获取连接
        if (!empty($this->availableConnections)) {
            $connection = array_shift($this->availableConnections);
            
            // 检查连接是否有效
            if ($this->isValidConnection($connection)) {
                $this->inUseConnections[spl_object_hash($connection)] = $connection;
                return $connection;
            }
        }

        // 如果没有可用连接且未达到最大连接数，创建新连接
        if (count($this->inUseConnections) < $this->maxConnections) {
            $connection = $this->createConnection();
            $this->inUseConnections[spl_object_hash($connection)] = $connection;
            return $connection;
        }

        // 等待一段时间后重试获取连接
        $startTime = microtime(true);
        while (microtime(true) - $startTime < $this->timeout) {
            usleep(10000); // 10ms
            
            if (!empty($this->availableConnections)) {
                $connection = array_shift($this->availableConnections);
                if ($this->isValidConnection($connection)) {
                    $this->inUseConnections[spl_object_hash($connection)] = $connection;
                    return $connection;
                }
            }
        }

        throw new \Exception('Failed to get Redis connection: connection pool is full and timed out');
    }

    /**
     * 将Redis连接归还到连接池
     * 
     * @param Redis $connection 要归还的连接
     */
    public function releaseConnection(Redis $connection): void
    {
        $connectionId = spl_object_hash($connection);
        if (isset($this->inUseConnections[$connectionId])) {
            unset($this->inUseConnections[$connectionId]);
            $this->availableConnections[] = $connection;
        }
    }

    /**
     * 创建一个新的Redis连接
     * 
     * @return Redis
     * @throws \Exception
     */
    private function createConnection(): Redis
    {
        $redis = new Redis();
        
        // 连接Redis服务器
        if (!$redis->connect($this->config['host'], $this->config['port'], $this->timeout)) {
            throw new \Exception('Failed to connect to Redis server: ' . $this->config['host'] . ':' . $this->config['port']);
        }

        // 认证（如果有密码）
        if (!empty($this->config['password']) && !$redis->auth($this->config['password'])) {
            $redis->close();
            throw new \Exception('Failed to authenticate with Redis server');
        }

        // 选择数据库
        if (isset($this->config['database']) && !$redis->select($this->config['database'])) {
            $redis->close();
            throw new \Exception('Failed to select Redis database: ' . $this->config['database']);
        }

        return $redis;
    }

    /**
     * 检查连接是否有效
     * 
     * @param Redis $connection 要检查的连接
     * @return bool 连接是否有效
     */
    private function isValidConnection(Redis $connection): bool
    {
        try {
            return $connection->ping() === '+PONG';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 关闭所有连接并清空连接池
     */
    public function closeAllConnections(): void
    {
        // 关闭并清空可用连接池
        foreach ($this->availableConnections as $connection) {
            try {
                $connection->close();
            } catch (\Exception $e) {
                // 忽略关闭连接时的异常
            }
        }
        $this->availableConnections = [];

        // 关闭并清空正在使用的连接
        foreach ($this->inUseConnections as $connection) {
            try {
                $connection->close();
            } catch (\Exception $e) {
                // 忽略关闭连接时的异常
            }
        }
        $this->inUseConnections = [];
    }

    /**
     * 获取连接池状态信息
     * 
     * @return array 连接池状态信息
     */
    public function getStatus(): array
    {
        return [
            'available_connections' => count($this->availableConnections),
            'in_use_connections' => count($this->inUseConnections),
            'max_connections' => $this->maxConnections,
            'config' => $this->config,
        ];
    }

    /**
     * 防止克隆实例
     */
    private function __clone() {}

    /**
     * 防止反序列化创建实例
     */
    public function __wakeup() {}
}