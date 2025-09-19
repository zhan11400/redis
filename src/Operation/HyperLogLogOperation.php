<?php

namespace since\Operation;

use Redis;
use since\Operation\BaseOperation;

/**
 * Redis HyperLogLog操作类
 * 提供所有与Redis HyperLogLog数据结构相关的操作方法
 */
class HyperLogLogOperation extends BaseOperation
{
    /**
     * 将一个或多个元素添加到指定的HyperLogLog中
     * 
     * @param string $key 键名
     * @param string ...$elements 元素列表
     * @return int 如果HyperLogLog内部被修改了则返回1，否则返回0
     * @throws \Exception
     */
    public function pfAdd(string $key, string ...$elements): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $elements) {
            return $redis->pfAdd($key, $elements);
        });
    }

    /**
     * 获取HyperLogLog中元素的基数估算值
     * 
     * @param string ...$keys 键名列表
     * @return int 基数估算值
     * @throws \Exception
     */
    public function pfCount(string ...$keys): int
    {
        return $this->executeCommand(function (Redis $redis) use ($keys) {
            return $redis->pfCount($keys);
        });
    }

    /**
     * 将多个HyperLogLog合并到一个HyperLogLog中
     * 
     * @param string $destination 目标HyperLogLog的键名
     * @param string ...$sources 源HyperLogLog的键名列表
     * @return bool 操作是否成功
     * @throws \Exception
     */
    public function pfMerge(string $destination, string ...$sources): bool
    {
        return $this->executeCommand(function (Redis $redis) use ($destination, $sources) {
            return $redis->pfMerge($destination, $sources);
        });
    }
}