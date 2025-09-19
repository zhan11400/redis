<?php

namespace since\Operation;

use Redis;
use since\Operation\BaseOperation;

/**
 * Redis字符串操作类
 * 提供所有与Redis字符串数据结构相关的操作方法
 */
class StringOperation extends BaseOperation
{
    /**
     * 设置键值对
     * 
     * @param string $key 键名
     * @param mixed $value 值
     * @param int|null $expire 过期时间（秒），默认为null（永不过期）
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function set(string $key, mixed $value, ?int $expire = null): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $value, $expire) {
            if ($expire === null) {
                return $redis->set($key, $value);
            }
            return $redis->set($key, $value, $expire);
        });
    }

    /**
     * 获取键对应的值
     * 
     * @param string $key 键名
     * @return mixed|false 值或false（如果键不存在）
     * @throws \Exception
     */
    public function get(string $key): mixed
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->get($key);
        });
    }

    /**
     * 仅当键不存在时设置键值对
     * 
     * @param string $key 键名
     * @param mixed $value 值
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function setNx(string $key, mixed $value): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $value) {
            return $redis->setNx($key, $value);
        });
    }

    /**
     * 设置键值对并返回旧值
     * 
     * @param string $key 键名
     * @param mixed $value 值
     * @return mixed|false 旧值或false（如果键不存在）
     * @throws \Exception
     */
    public function getSet(string $key, mixed $value): mixed
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $value) {
            return $redis->getSet($key, $value);
        });
    }

    /**
     * 批量设置键值对
     * 
     * @param array $array 键值对数组
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function mSet(array $array): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($array) {
            return $redis->mSet($array);
        });
    }

    /**
     * 批量获取多个键的值
     * 
     * @param array $keys 键名数组
     * @return array 值数组
     * @throws \Exception
     */
    public function mGet(array $keys): array
    {
        return $this->executeCommand(function (Redis $redis) use ($keys) {
            return $redis->mGet($keys);
        });
    }

    /**
     * 仅当所有给定键都不存在时，批量设置键值对
     * 
     * @param array $array 键值对数组
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function mSetNx(array $array): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($array) {
            return $redis->mSetNx($array);
        });
    }

    /**
     * 将键的值增加1
     * 
     * @param string $key 键名
     * @return int 增加后的值
     * @throws \Exception
     */
    public function incr(string $key): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->incr($key);
        });
    }

    /**
     * 将键的值增加指定步长
     * 
     * @param string $key 键名
     * @param int $value 步长值
     * @return int 增加后的值
     * @throws \Exception
     */
    public function incrBy(string $key, int $value): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $value) {
            return $redis->incrBy($key, $value);
        });
    }

    /**
     * 将键的值增加指定的浮点数步长
     * 
     * @param string $key 键名
     * @param float $value 浮点数步长值
     * @return float 增加后的值
     * @throws \Exception
     */
    public function incrByFloat(string $key, float $value): float
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $value) {
            return $redis->incrByFloat($key, $value);
        });
    }

    /**
     * 将键的值减少1
     * 
     * @param string $key 键名
     * @return int 减少后的值
     * @throws \Exception
     */
    public function decr(string $key): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->decr($key);
        });
    }

    /**
     * 将键的值减少指定步长
     * 
     * @param string $key 键名
     * @param int $value 步长值
     * @return int 减少后的值
     * @throws \Exception
     */
    public function decrBy(string $key, int $value): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $value) {
            return $redis->decrBy($key, $value);
        });
    }

    /**
     * 向键的值追加字符串
     * 
     * @param string $key 键名
     * @param string $value 要追加的字符串
     * @return int 追加后字符串的长度
     * @throws \Exception
     */
    public function append(string $key, string $value): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $value) {
            return $redis->append($key, $value);
        });
    }

    /**
     * 获取键的值的长度
     * 
     * @param string $key 键名
     * @return int 值的长度
     * @throws \Exception
     */
    public function strlen(string $key): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->strlen($key);
        });
    }

    /**
     * 获取键的值的子字符串
     * 
     * @param string $key 键名
     * @param int $start 起始位置（包含）
     * @param int $end 结束位置（包含），-1表示最后一个字符
     * @return string 子字符串
     * @throws \Exception
     */
    public function getRange(string $key, int $start, int $end): string
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $start, $end) {
            return $redis->getRange($key, $start, $end);
        });
    }

    /**
     * 用指定的字符串覆盖键的值的一部分
     * 
     * @param string $key 键名
     * @param int $offset 覆盖的起始位置
     * @param string $value 覆盖的字符串
     * @return int 覆盖后字符串的长度
     * @throws \Exception
     */
    public function setRange(string $key, int $offset, string $value): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $offset, $value) {
            return $redis->setRange($key, $offset, $value);
        });
    }

    /**
     * 设置键的值的指定位上的比特位
     * 
     * @param string $key 键名
     * @param int $offset 比特位的位置
     * @param int $value 比特位的值（0或1）
     * @return bool 设置是否成功
     * @throws \Exception
     */
    public function setBit(string $key, int $offset, int $value): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $offset, $value) {
            return $redis->setBit($key, $offset, $value);
        });
    }

    /**
     * 获取键的值的指定位上的比特位
     * 
     * @param string $key 键名
     * @param int $offset 比特位的位置
     * @return int 比特位的值（0或1）
     * @throws \Exception
     */
    public function getBit(string $key, int $offset): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $offset) {
            return $redis->getBit($key, $offset);
        });
    }

    /**
     * 计算键的值的指定范围内的比特位的和
     * 
     * @param string $key 键名
     * @param int $start 起始位置
     * @param int $end 结束位置
     * @return int 比特位的和
     * @throws \Exception
     */
    public function bitCount(string $key, int $start = 0, int $end = -1): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $start, $end) {
            return $redis->bitCount($key, $start, $end);
        });
    }
}