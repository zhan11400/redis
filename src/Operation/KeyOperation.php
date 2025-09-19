<?php

namespace since\Operation;

use Redis;
use since\Operation\BaseOperation;

/**
 * Redis键操作类
 * 提供所有与Redis键相关的操作方法
 */
class KeyOperation extends BaseOperation
{
    /**
     * 删除一个或多个key
     * 
     * @param string ...$keys 要删除的键名列表
     * @return int 删除的键数量
     * @throws \Exception
     */
    public function del(string ...$keys): int
    {
        return $this->executeCommand(function (Redis $redis) use ($keys) {
            return $redis->del(...$keys);
        });
    }

    /**
     * 判断一个key是否存在
     * 
     * @param string $key 键名
     * @return bool 键是否存在
     * @throws \Exception
     */
    public function exists(string $key): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->exists($key);
        });
    }

    /**
     * 设置key的过期时间（秒）
     * 
     * @param string $key 键名
     * @param int $seconds 过期时间（秒）
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function expire(string $key, int $seconds): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $seconds) {
            return $redis->expire($key, $seconds);
        });
    }

    /**
     * 设置key的过期时间（毫秒）
     * 
     * @param string $key 键名
     * @param int $milliseconds 过期时间（毫秒）
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function pExpire(string $key, int $milliseconds): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $milliseconds) {
            return $redis->pExpire($key, $milliseconds);
        });
    }

    /**
     * 设置key的过期时间点（Unix时间戳，秒）
     * 
     * @param string $key 键名
     * @param int $timestamp 过期时间点（Unix时间戳）
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function expireAt(string $key, int $timestamp): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $timestamp) {
            return $redis->expireAt($key, $timestamp);
        });
    }

    /**
     * 设置key的过期时间点（Unix时间戳，毫秒）
     * 
     * @param string $key 键名
     * @param int $timestamp 过期时间点（Unix时间戳，毫秒）
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function pExpireAt(string $key, int $timestamp): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $timestamp) {
            return $redis->pExpireAt($key, $timestamp);
        });
    }

    /**
     * 获取key的剩余生存时间（秒）
     * 
     * @param string $key 键名
     * @return int 剩余生存时间（秒），-1表示永不过期，-2表示键不存在
     * @throws \Exception
     */
    public function ttl(string $key): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->ttl($key);
        });
    }

    /**
     * 获取key的剩余生存时间（毫秒）
     * 
     * @param string $key 键名
     * @return int 剩余生存时间（毫秒），-1表示永不过期，-2表示键不存在
     * @throws \Exception
     */
    public function pTtl(string $key): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->pTtl($key);
        });
    }

    /**
     * 移除key的过期时间，使其永不过期
     * 
     * @param string $key 键名
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function persist(string $key): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->persist($key);
        });
    }

    /**
     * 对key进行重命名
     * 
     * @param string $key 原键名
     * @param string $newKey 新键名
     * @return bool 重命名是否成功
     * @throws \Exception
     */
    public function rename(string $key, string $newKey): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $newKey) {
            return $redis->rename($key, $newKey);
        });
    }

    /**
     * 仅当新键名不存在时对key进行重命名
     * 
     * @param string $key 原键名
     * @param string $newKey 新键名
     * @return bool 重命名是否成功
     * @throws \Exception
     */
    public function renameNx(string $key, string $newKey): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $newKey) {
            return $redis->renameNx($key, $newKey);
        });
    }

    /**
     * 获取key所存储的值的类型
     * 
     * @param string $key 键名
     * @return string 类型字符串，可能的值有：string、list、set、zset、hash和none
     * @throws \Exception
     */
    public function type(string $key): string
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->type($key);
        });
    }

    /**
     * 查找所有符合给定模式的key
     * 
     * @param string $pattern 匹配模式
     * @return array 匹配的键名列表
     * @throws \Exception
     */
    public function keys(string $pattern): array
    {
        return $this->executeCommand(function (Redis $redis) use ($pattern) {
            return $redis->keys($pattern);
        });
    }

    /**
     * 迭代数据库中的键
     * 
     * @param int &$iterator 迭代器变量（引用传递）
     * @param string|null $pattern 匹配模式，默认为null
     * @param int $count 每次迭代返回的键数量，默认为0（由Redis决定）
     * @return array|bool 匹配的键名列表或false（如果没有更多键）
     * @throws \Exception
     */
    public function scan(&$iterator, ?string $pattern = null, int $count = 0): array|bool
    {
        return $this->executeCommand(function (Redis $redis) use (&$iterator, $pattern, $count) {
            return $redis->scan($iterator, $pattern, $count);
        });
    }

    /**
     * 移动键到指定数据库
     * 
     * @param string $key 键名
     * @param int $dbIndex 目标数据库索引
     * @return bool 移动是否成功
     * @throws \Exception
     */
    public function move(string $key, int $dbIndex): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $dbIndex) {
            return $redis->move($key, $dbIndex);
        });
    }

    /**
     * 序列化给定键，并返回序列化的值
     * 
     * @param string $key 键名
     * @return string|false 序列化后的值或false（如果键不存在）
     * @throws \Exception
     */
    public function dump(string $key): string|false
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->dump($key);
        });
    }

    /**
     * 反序列化给定的序列化值，并将其与给定的键关联
     * 
     * @param string $key 键名
     * @param string $value 序列化后的值
     * @param int|null $ttl 过期时间（毫秒），默认为null（永不过期）
     * @return bool 反序列化是否成功
     * @throws \Exception
     */
    public function restore(string $key, string $value, ?int $ttl = null): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $value, $ttl) {
            if ($ttl === null) {
                return $redis->restore($key, 0, $value);
            }
            return $redis->restore($key, $ttl, $value);
        });
    }

    /**
     * 获取键的过期时间（自定义封装方法）
     * 
     * @param string $key 键名
     * @return int 过期时间（秒），-1表示永不过期，-2表示键不存在
     * @throws \Exception
     */
    public function get_key_expire(string $key): int
    {
        return $this->ttl($key);
    }

    /**
     * 设置键的过期时间（自定义封装方法）
     * 
     * @param string $key 键名
     * @param int $seconds 过期时间（秒）
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function set_key_expire(string $key, int $seconds): bool
    {
        return $this->expire($key, $seconds);
    }
}