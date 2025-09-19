<?php

namespace since\Operation;

use Redis;
use since\Operation\BaseOperation;

/**
 * Redis有序集合操作类
 * 提供所有与Redis有序集合数据结构相关的操作方法
 */
class SortedSetOperation extends BaseOperation
{
    /**
     * 向有序集合中添加一个或多个成员及其分数
     * 
     * @param string $key 键名
     * @param array $scoreMembers 分数-成员数组，格式：[分数1 => 成员1, 分数2 => 成员2, ...]
     * @return int 添加的成员数量
     * @throws \Exception
     */
    public function zAdd(string $key, array $scoreMembers): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $scoreMembers) {
            return $redis->zAdd($key, $scoreMembers);
        });
    }

    /**
     * 获取有序集合的成员数量
     * 
     * @param string $key 键名
     * @return int 成员数量
     * @throws \Exception
     */
    public function zCard(string $key): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->zCard($key);
        });
    }

    /**
     * 获取有序集合中分数在指定范围内的成员数量
     * 
     * @param string $key 键名
     * @param mixed $min 最小分数
     * @param mixed $max 最大分数
     * @return int 成员数量
     * @throws \Exception
     */
    public function zCount(string $key, mixed $min, mixed $max): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $min, $max) {
            return $redis->zCount($key, $min, $max);
        });
    }

    /**
     * 增加有序集合中指定成员的分数
     * 
     * @param string $key 键名
     * @param float $increment 增加的分数
     * @param string $member 成员名
     * @return float 增加后的分数
     * @throws \Exception
     */
    public function zIncrBy(string $key, float $increment, string $member): float
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $increment, $member) {
            return $redis->zIncrBy($key, $increment, $member);
        });
    }

    /**
     * 计算多个有序集合的交集并存储到目标有序集合
     * 
     * @param string $destination 目标有序集合的键名
     * @param array $keys 源有序集合的键名数组
     * @param array $weights 权重数组，对应源有序集合的权重
     * @param string $aggregate 聚合方式，可选值：SUM、MIN、MAX，默认为SUM
     * @return int 结果有序集合的成员数量
     * @throws \Exception
     */
    public function zInterStore(string $destination, array $keys, array $weights = [], string $aggregate = 'SUM'): int
    {
        return $this->executeCommand(function (Redis $redis) use ($destination, $keys, $weights, $aggregate) {
            return $redis->zInterStore($destination, $keys, $weights, $aggregate);
        });
    }

    /**
     * 计算多个有序集合的并集并存储到目标有序集合
     * 
     * @param string $destination 目标有序集合的键名
     * @param array $keys 源有序集合的键名数组
     * @param array $weights 权重数组，对应源有序集合的权重
     * @param string $aggregate 聚合方式，可选值：SUM、MIN、MAX，默认为SUM
     * @return int 结果有序集合的成员数量
     * @throws \Exception
     */
    public function zUnionStore(string $destination, array $keys, array $weights = [], string $aggregate = 'SUM'): int
    {
        return $this->executeCommand(function (Redis $redis) use ($destination, $keys, $weights, $aggregate) {
            return $redis->zUnionStore($destination, $keys, $weights, $aggregate);
        });
    }

    /**
     * 获取有序集合中指定成员的排名（分数从小到大排序）
     * 
     * @param string $key 键名
     * @param string $member 成员名
     * @return int|false 排名（从0开始）或false（如果成员不存在）
     * @throws \Exception
     */
    public function zRank(string $key, string $member): int|false
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $member) {
            return $redis->zRank($key, $member);
        });
    }

    /**
     * 获取有序集合中指定成员的排名（分数从大到小排序）
     * 
     * @param string $key 键名
     * @param string $member 成员名
     * @return int|false 排名（从0开始）或false（如果成员不存在）
     * @throws \Exception
     */
    public function zRevRank(string $key, string $member): int|false
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $member) {
            return $redis->zRevRank($key, $member);
        });
    }

    /**
     * 获取有序集合中指定排名范围内的成员（分数从小到大排序）
     * 
     * @param string $key 键名
     * @param int $start 起始排名（包含）
     * @param int $stop 结束排名（包含）
     * @param bool $withScores 是否同时返回分数，默认为false
     * @return array 成员数组或成员-分数关联数组
     * @throws \Exception
     */
    public function zRange(string $key, int $start, int $stop, bool $withScores = false): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $start, $stop, $withScores) {
            return $redis->zRange($key, $start, $stop, $withScores);
        });
    }

    /**
     * 获取有序集合中指定排名范围内的成员（分数从大到小排序）
     * 
     * @param string $key 键名
     * @param int $start 起始排名（包含）
     * @param int $stop 结束排名（包含）
     * @param bool $withScores 是否同时返回分数，默认为false
     * @return array 成员数组或成员-分数关联数组
     * @throws \Exception
     */
    public function zRevRange(string $key, int $start, int $stop, bool $withScores = false): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $start, $stop, $withScores) {
            return $redis->zRevRange($key, $start, $stop, $withScores);
        });
    }

    /**
     * 获取有序集合中分数在指定范围内的成员（分数从小到大排序）
     * 
     * @param string $key 键名
     * @param mixed $min 最小分数
     * @param mixed $max 最大分数
     * @param array $options 可选参数，包含LIMIT、WITHSCORES等
     * @return array 成员数组或成员-分数关联数组
     * @throws \Exception
     */
    public function zRangeByScore(string $key, mixed $min, mixed $max, array $options = []): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $min, $max, $options) {
            return $redis->zRangeByScore($key, $min, $max, $options);
        });
    }

    /**
     * 获取有序集合中分数在指定范围内的成员（分数从大到小排序）
     * 
     * @param string $key 键名
     * @param mixed $min 最小分数
     * @param mixed $max 最大分数
     * @param array $options 可选参数，包含LIMIT、WITHSCORES等
     * @return array 成员数组或成员-分数关联数组
     * @throws \Exception
     */
    public function zRevRangeByScore(string $key, mixed $min, mixed $max, array $options = []): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $min, $max, $options) {
            return $redis->zRevRangeByScore($key, $min, $max, $options);
        });
    }

    /**
     * 移除有序集合中指定排名范围内的成员
     * 
     * @param string $key 键名
     * @param int $start 起始排名（包含）
     * @param int $stop 结束排名（包含）
     * @return int 移除的成员数量
     * @throws \Exception
     */
    public function zRemRangeByRank(string $key, int $start, int $stop): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $start, $stop) {
            return $redis->zRemRangeByRank($key, $start, $stop);
        });
    }

    /**
     * 移除有序集合中分数在指定范围内的成员
     * 
     * @param string $key 键名
     * @param mixed $min 最小分数
     * @param mixed $max 最大分数
     * @return int 移除的成员数量
     * @throws \Exception
     */
    public function zRemRangeByScore(string $key, mixed $min, mixed $max): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $min, $max) {
            return $redis->zRemRangeByScore($key, $min, $max);
        });
    }

    /**
     * 移除有序集合中的一个或多个成员
     * 
     * @param string $key 键名
     * @param string ...$members 要移除的成员列表
     * @return int 移除的成员数量
     * @throws \Exception
     */
    public function zRem(string $key, string ...$members): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $members) {
            return $redis->zRem($key, ...$members);
        });
    }

    /**
     * 获取有序集合中指定成员的分数
     * 
     * @param string $key 键名
     * @param string $member 成员名
     * @return float|false 分数或false（如果成员不存在）
     * @throws \Exception
     */
    public function zScore(string $key, string $member): float|false
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $member) {
            return $redis->zScore($key, $member);
        });
    }

    /**
     * 迭代有序集合中的元素
     * 
     * @param string $key 键名
     * @param int &$iterator 迭代器变量（引用传递）
     * @param array $options 可选参数，包含MATCH、COUNT等
     * @return array|bool 元素数组或false（如果没有更多元素）
     * @throws \Exception
     */
    public function zScan(string $key, &$iterator, array $options = []): array|bool
    {
        return $this->executeCommand(function (Redis $redis) use ($key, &$iterator, $options) {
            return $redis->zScan($key, $iterator, $options);
        });
    }
}