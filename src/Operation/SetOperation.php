<?php

namespace since\Operation;

use Redis;
use since\Operation\BaseOperation;

/**
 * Redis无序集合操作类
 * 提供所有与Redis无序集合数据结构相关的操作方法
 */
class SetOperation extends BaseOperation
{
    /**
     * 向集合中添加一个或多个成员
     * 
     * @param string $key 键名
     * @param mixed ...$members 要添加的成员列表
     * @return int 添加的成员数量
     * @throws \Exception
     */
    public function sAdd(string $key, mixed ...$members): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $members) {
            return $redis->sAdd($key, ...$members);
        });
    }

    /**
     * 判断成员是否在集合中
     * 
     * @param string $key 键名
     * @param mixed $member 要判断的成员
     * @return bool 成员是否在集合中
     * @throws \Exception
     */
    public function sIsMember(string $key, mixed $member): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $member) {
            return $redis->sIsMember($key, $member);
        });
    }

    /**
     * 获取集合中的所有成员
     * 
     * @param string $key 键名
     * @return array 成员数组
     * @throws \Exception
     */
    public function sMembers(string $key): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->sMembers($key);
        });
    }

    /**
     * 移除集合中的一个或多个成员
     * 
     * @param string $key 键名
     * @param mixed ...$members 要移除的成员列表
     * @return int 移除的成员数量
     * @throws \Exception
     */
    public function sRem(string $key, mixed ...$members): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $members) {
            return $redis->sRem($key, ...$members);
        });
    }

    /**
     * 随机移除并返回集合中的一个成员
     * 
     * @param string $key 键名
     * @return mixed|false 随机成员或false（如果集合为空）
     * @throws \Exception
     */
    public function sPop(string $key): mixed
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->sPop($key);
        });
    }

    /**
     * 随机返回集合中的一个或多个成员，不移除
     * 
     * @param string $key 键名
     * @param int $count 要返回的成员数量，默认为1
     * @return array|string|bool 成员数组、单个成员或false（如果集合为空）
     * @throws \Exception
     */
    public function sRandMember(string $key, int $count = 1): array|bool|string
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $count) {
            return $redis->sRandMember($key, $count);
        });
    }

    /**
     * 获取集合的成员数量
     * 
     * @param string $key 键名
     * @return int 成员数量
     * @throws \Exception
     */
    public function sCard(string $key): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->sCard($key);
        });
    }

    /**
     * 计算多个集合的交集
     * 
     * @param string $key 第一个集合的键名
     * @param string ...$otherKeys 其他集合的键名列表
     * @return array 交集成员数组
     * @throws \Exception
     */
    public function sInter(string $key, string ...$otherKeys): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $otherKeys) {
            return $redis->sInter($key, ...$otherKeys);
        });
    }

    /**
     * 计算多个集合的交集并存储到目标集合
     * 
     * @param string $destination 目标集合的键名
     * @param string $key1 第一个集合的键名
     * @param string ...$otherKeys 其他集合的键名列表
     * @return int 交集成员数量
     * @throws \Exception
     */
    public function sInterStore(string $destination, string $key1, string ...$otherKeys): int
    {
        return $this->executeCommand(function (Redis $redis) use ($destination, $key1, $otherKeys) {
            return $redis->sInterStore($destination, $key1, ...$otherKeys);
        });
    }

    /**
     * 计算多个集合的并集
     * 
     * @param string $key 第一个集合的键名
     * @param string ...$otherKeys 其他集合的键名列表
     * @return array 并集成员数组
     * @throws \Exception
     */
    public function sUnion(string $key, string ...$otherKeys): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $otherKeys) {
            return $redis->sUnion($key, ...$otherKeys);
        });
    }

    /**
     * 计算多个集合的并集并存储到目标集合
     * 
     * @param string $destination 目标集合的键名
     * @param string $key1 第一个集合的键名
     * @param string ...$otherKeys 其他集合的键名列表
     * @return int 并集成员数量
     * @throws \Exception
     */
    public function sUnionStore(string $destination, string $key1, string ...$otherKeys): int
    {
        return $this->executeCommand(function (Redis $redis) use ($destination, $key1, $otherKeys) {
            return $redis->sUnionStore($destination, $key1, ...$otherKeys);
        });
    }

    /**
     * 计算多个集合的差集
     * 
     * @param string $key 第一个集合的键名
     * @param string ...$otherKeys 其他集合的键名列表
     * @return array 差集成员数组
     * @throws \Exception
     */
    public function sDiff(string $key, string ...$otherKeys): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $otherKeys) {
            return $redis->sDiff($key, ...$otherKeys);
        });
    }

    /**
     * 计算多个集合的差集并存储到目标集合
     * 
     * @param string $destination 目标集合的键名
     * @param string $key1 第一个集合的键名
     * @param string ...$otherKeys 其他集合的键名列表
     * @return int 差集成员数量
     * @throws \Exception
     */
    public function sDiffStore(string $destination, string $key1, string ...$otherKeys): int
    {
        return $this->executeCommand(function (Redis $redis) use ($destination, $key1, $otherKeys) {
            return $redis->sDiffStore($destination, $key1, ...$otherKeys);
        });
    }

    /**
     * 迭代集合中的元素
     * 
     * @param string $key 键名
     * @param int &$iterator 迭代器变量（引用传递）
     * @param string|null $pattern 匹配模式，默认为null
     * @param int $count 每次迭代返回的元素数量，默认为0（由Redis决定）
     * @return array|bool 元素数组或false（如果没有更多元素）
     * @throws \Exception
     */
    public function sScan(string $key, &$iterator, ?string $pattern = null, int $count = 0): array|bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, &$iterator, $pattern, $count) {
            return $redis->sScan($key, $iterator, $pattern, $count);
        });
    }
}