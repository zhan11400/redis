<?php

namespace since\Operation;

use Redis;
use since\Operation\BaseOperation;

/**
 * Redis哈希表操作类
 * 提供所有与Redis哈希表数据结构相关的操作方法
 */
class HashOperation extends BaseOperation
{
    /**
     * 设置哈希表中指定字段的值
     * 
     * @param string $key 键名
     * @param string $field 字段名
     * @param mixed $value 字段值
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function hSet(string $key, string $field, mixed $value): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $field, $value) {
            return $redis->hSet($key, $field, $value);
        });
    }

    /**
     * 仅当哈希表中指定字段不存在时设置其值
     * 
     * @param string $key 键名
     * @param string $field 字段名
     * @param mixed $value 字段值
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function hSetNx(string $key, string $field, mixed $value): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $field, $value) {
            return $redis->hSetNx($key, $field, $value);
        });
    }

    /**
     * 获取哈希表中指定字段的值
     * 
     * @param string $key 键名
     * @param string $field 字段名
     * @return mixed|false 字段值或false（如果字段不存在）
     * @throws \Exception
     */
    public function hGet(string $key, string $field): mixed
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $field) {
            return $redis->hGet($key, $field);
        });
    }

    /**
     * 批量设置哈希表中的多个字段值
     * 
     * @param string $key 键名
     * @param array $array 字段值数组
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function hMSet(string $key, array $array): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $array) {
            return $redis->hMSet($key, $array);
        });
    }

    /**
     * 批量获取哈希表中多个字段的值
     * 
     * @param string $key 键名
     * @param array $fields 字段名数组
     * @return array 字段值数组
     * @throws \Exception
     */
    public function hMGet(string $key, array $fields): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $fields) {
            return $redis->hMGet($key, $fields);
        });
    }

    /**
     * 获取哈希表中所有字段和值
     * 
     * @param string $key 键名
     * @return array 字段值数组
     * @throws \Exception
     */
    public function hGetAll(string $key): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->hGetAll($key);
        });
    }

    /**
     * 判断哈希表中是否存在指定字段
     * 
     * @param string $key 键名
     * @param string $field 字段名
     * @return bool 字段是否存在
     * @throws \Exception
     */
    public function hExists(string $key, string $field): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $field) {
            return $redis->hExists($key, $field);
        });
    }

    /**
     * 删除哈希表中一个或多个字段
     * 
     * @param string $key 键名
     * @param string ...$fields 要删除的字段名列表
     * @return int 删除的字段数量
     * @throws \Exception
     */
    public function hDel(string $key, string ...$fields): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $fields) {
            return $redis->hDel($key, ...$fields);
        });
    }

    /**
     * 获取哈希表中字段的数量
     * 
     * @param string $key 键名
     * @return int 字段数量
     * @throws \Exception
     */
    public function hLen(string $key): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->hLen($key);
        });
    }

    /**
     * 获取哈希表中所有字段名
     * 
     * @param string $key 键名
     * @return array 字段名数组
     * @throws \Exception
     */
    public function hKeys(string $key): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->hKeys($key);
        });
    }

    /**
     * 获取哈希表中所有字段值
     * 
     * @param string $key 键名
     * @return array 字段值数组
     * @throws \Exception
     */
    public function hVals(string $key): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->hVals($key);
        });
    }

    /**
     * 将哈希表中指定字段的值增加指定步长
     * 
     * @param string $key 键名
     * @param string $field 字段名
     * @param int $value 步长值
     * @return int 增加后的值
     * @throws \Exception
     */
    public function hIncrBy(string $key, string $field, int $value): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $field, $value) {
            return $redis->hIncrBy($key, $field, $value);
        });
    }

    /**
     * 将哈希表中指定字段的值增加指定的浮点数步长
     * 
     * @param string $key 键名
     * @param string $field 字段名
     * @param float $value 浮点数步长值
     * @return float 增加后的值
     * @throws \Exception
     */
    public function hIncrByFloat(string $key, string $field, float $value): float
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $field, $value) {
            return $redis->hIncrByFloat($key, $field, $value);
        });
    }

    /**
     * 迭代哈希表中的字段值对
     * 
     * @param string $key 键名
     * @param int &$iterator 迭代器变量（引用传递）
     * @param string|null $pattern 匹配模式，默认为null
     * @param int $count 每次迭代返回的字段数量，默认为0（由Redis决定）
     * @return array|bool 字段值对数组或false（如果没有更多字段）
     * @throws \Exception
     */
    public function hScan(string $key, &$iterator, ?string $pattern = null, int $count = 0): array|bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, &$iterator, $pattern, $count) {
            return $redis->hScan($key, $iterator, $pattern, $count);
        });
    }
}