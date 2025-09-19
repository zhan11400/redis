<?php

namespace since\Operation;

use Redis;
use since\Connection\RedisConnectionPool;

/**
 * Redis操作基础类
 * 所有Redis操作类型的类都将继承此类
 */
abstract class BaseOperation
{
    /**
     * @var RedisConnectionPool 连接池实例
     */
    protected RedisConnectionPool $connectionPool;

    /**
     * BaseOperation constructor.
     */
    public function __construct()
    {
        $this->connectionPool = RedisConnectionPool::getInstance();
    }

    /**
     * 从连接池获取一个Redis连接
     * 
     * @return Redis
     * @throws \Exception
     */
    protected function getConnection(): Redis
    {
        return $this->connectionPool->getConnection();
    }

    /**
     * 将Redis连接归还到连接池
     * 
     * @param Redis $connection 要归还的连接
     */
    protected function releaseConnection(Redis $connection): void
    {
        $this->connectionPool->releaseConnection($connection);
    }

    /**
     * 执行Redis命令的通用方法
     * 
     * @param callable $callback 要执行的Redis命令回调函数
     * @param mixed ...$args 回调函数参数
     * @return mixed 命令执行结果
     * @throws \Exception
     */
    protected function executeCommand(callable $callback, ...$args)
    {
        $connection = null;
        try {
            $connection = $this->getConnection();
            return call_user_func($callback, $connection, ...$args);
        } catch (\Exception $e) {
            throw $e;
        } finally {
            if ($connection !== null) {
                $this->releaseConnection($connection);
            }
        }
    }

    /**
     * 获取连接池状态信息
     * 
     * @return array
     */
    public function getConnectionPoolStatus(): array
    {
        return $this->connectionPool->getStatus();
    }

    /**
     * 关闭所有连接
     */
    public function closeAllConnections(): void
    {
        $this->connectionPool->closeAllConnections();
    }
}