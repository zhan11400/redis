<?php

namespace since\Operation;

use Redis;
use since\Operation\BaseOperation;

/**
 * Redis列表操作类
 * 提供所有与Redis列表数据结构相关的操作方法
 */
class ListOperation extends BaseOperation
{
    /**
     * 在列表头部插入一个或多个值
     * 
     * @param string $key 键名
     * @param mixed ...$values 要插入的值列表
     * @return int 插入后列表的长度
     * @throws \Exception
     */
    public function lPush(string $key, mixed ...$values): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $values) {
            return $redis->lPush($key, ...$values);
        });
    }

    /**
     * 在列表尾部插入一个或多个值
     * 
     * @param string $key 键名
     * @param mixed ...$values 要插入的值列表
     * @return int 插入后列表的长度
     * @throws \Exception
     */
    public function rPush(string $key, mixed ...$values): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $values) {
            return $redis->rPush($key, ...$values);
        });
    }

    /**
     * 仅当列表存在时，在列表头部插入一个或多个值
     * 
     * @param string $key 键名
     * @param mixed ...$values 要插入的值列表
     * @return int 插入后列表的长度，0表示列表不存在
     * @throws \Exception
     */
    public function lPushx(string $key, mixed ...$values): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $values) {
            return $redis->lPushx($key, ...$values);
        });
    }

    /**
     * 仅当列表存在时，在列表尾部插入一个或多个值
     * 
     * @param string $key 键名
     * @param mixed ...$values 要插入的值列表
     * @return int 插入后列表的长度，0表示列表不存在
     * @throws \Exception
     */
    public function rPushx(string $key, mixed ...$values): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $values) {
            return $redis->rPushx($key, ...$values);
        });
    }

    /**
     * 移除并返回列表头部的元素
     * 
     * @param string $key 键名
     * @return mixed|false 头部元素或false（如果列表为空）
     * @throws \Exception
     */
    public function lPop(string $key): mixed
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->lPop($key);
        });
    }

    /**
     * 移除并返回列表尾部的元素
     * 
     * @param string $key 键名
     * @return mixed|false 尾部元素或false（如果列表为空）
     * @throws \Exception
     */
    public function rPop(string $key): mixed
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->rPop($key);
        });
    }

    /**
     * 移除并返回列表头部的元素，如果列表为空则阻塞等待
     * 
     * @param array $keys 键名数组
     * @param int $timeout 阻塞超时时间（秒），0表示无限等待
     * @return array|false 包含键名和元素值的数组或false（如果超时）
     * @throws \Exception
     */
    public function blPop(array $keys, int $timeout = 0): array|false
    {
        return $this->executeCommand(function (Redis $redis) use ($keys, $timeout) {
            return $redis->blPop($keys, $timeout);
        });
    }

    /**
     * 移除并返回列表尾部的元素，如果列表为空则阻塞等待
     * 
     * @param array $keys 键名数组
     * @param int $timeout 阻塞超时时间（秒），0表示无限等待
     * @return array|false 包含键名和元素值的数组或false（如果超时）
     * @throws \Exception
     */
    public function brPop(array $keys, int $timeout = 0): array|false
    {
        return $this->executeCommand(function (Redis $redis) use ($keys, $timeout) {
            return $redis->brPop($keys, $timeout);
        });
    }

    /**
     * 移除列表尾部的元素，并将其添加到另一个列表的头部
     * 
     * @param string $source 源列表键名
     * @param string $destination 目标列表键名
     * @return mixed|false 移动的元素值或false（如果源列表为空）
     * @throws \Exception
     */
    public function rPopLPush(string $source, string $destination): mixed
    {
        return $this->executeCommand(function (Redis $redis) use ($source, $destination) {
            return $redis->rPopLPush($source, $destination);
        });
    }

    /**
     * 移除列表尾部的元素，并将其添加到另一个列表的头部，如果源列表为空则阻塞等待
     * 
     * @param string $source 源列表键名
     * @param string $destination 目标列表键名
     * @param int $timeout 阻塞超时时间（秒），0表示无限等待
     * @return mixed|false 移动的元素值或false（如果超时）
     * @throws \Exception
     */
    public function brPopLPush(string $source, string $destination, int $timeout = 0): mixed
    {
        return $this->executeCommand(function (Redis $redis) use ($source, $destination, $timeout) {
            return $redis->brPopLPush($source, $destination, $timeout);
        });
    }

    /**
     * 获取列表的长度
     * 
     * @param string $key 键名
     * @return int 列表长度
     * @throws \Exception
     */
    public function lLen(string $key): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->lLen($key);
        });
    }

    /**
     * 获取列表中指定范围内的元素
     * 
     * @param string $key 键名
     * @param int $start 起始位置（包含），0表示第一个元素，负数表示从末尾开始
     * @param int $end 结束位置（包含），-1表示最后一个元素
     * @return array 元素数组
     * @throws \Exception
     */
    public function lRange(string $key, int $start, int $end): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $start, $end) {
            return $redis->lRange($key, $start, $end);
        });
    }

    /**
     * 移除列表中指定位置以外的所有元素，只保留指定范围内的元素
     * 
     * @param string $key 键名
     * @param int $start 起始位置（包含）
     * @param int $end 结束位置（包含）
     * @return bool 操作是否成功
     * @throws \Exception
     */
    public function lTrim(string $key, int $start, int $end): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $start, $end) {
            return $redis->lTrim($key, $start, $end);
        });
    }

    /**
     * 获取列表中指定索引位置的元素
     * 
     * @param string $key 键名
     * @param int $index 索引位置，0表示第一个元素，负数表示从末尾开始
     * @return mixed|false 元素值或false（如果索引超出范围）
     * @throws \Exception
     */
    public function lIndex(string $key, int $index): mixed
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $index) {
            return $redis->lIndex($key, $index);
        });
    }

    /**
     * 设置列表中指定索引位置的元素值
     * 
     * @param string $key 键名
     * @param int $index 索引位置
     * @param mixed $value 元素值
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function lSet(string $key, int $index, mixed $value): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $index, $value) {
            return $redis->lSet($key, $index, $value);
        });
    }

    /**
     * 从列表中移除指定数量的指定值的元素
     * 
     * @param string $key 键名
     * @param int $count 移除的数量，正数表示从头部开始移除，负数表示从尾部开始移除，0表示移除所有
     * @param mixed $value 要移除的元素值
     * @return int 实际移除的元素数量
     * @throws \Exception
     */
    public function lRem(string $key, int $count, mixed $value): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $count, $value) {
            return $redis->lRem($key, $count, $value);
        });
    }
}