<?php

namespace since\Operation;

use Redis;
use since\Operation\BaseOperation;

/**
 * Redis Stream操作类
 * 提供所有与Redis Stream数据结构相关的操作方法
 */
class StreamOperation extends BaseOperation
{
    /**
     * 向Stream添加消息
     * 
     * @param string $key 键名
     * @param array $message 消息内容，格式：[字段1 => 值1, 字段2 => 值2, ...]
     * @param array $options 可选参数，包含ID、MAXLEN、TRIM等
     * @return string 添加的消息ID
     * @throws \Exception
     */
    public function xAdd(string $key, array $message, array $options = []): string
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $message, $options) {
            return $redis->xAdd($key, $message, $options);
        });
    }

    /**
     * 修剪Stream，保留指定数量的消息
     * 
     * @param string $key 键名
     * @param int $maxLen 保留的最大消息数量
     * @param array $options 可选参数，包含APPROXIMATE、LIMIT等
     * @return int 实际移除的消息数量
     * @throws \Exception
     */
    public function xTrim(string $key, int $maxLen, array $options = []): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $maxLen, $options) {
            return $redis->xTrim($key, $maxLen, $options);
        });
    }

    /**
     * 从Stream中删除指定ID的消息
     * 
     * @param string $key 键名
     * @param string ...$ids 要删除的消息ID列表
     * @return int 删除的消息数量
     * @throws \Exception
     */
    public function xDel(string $key, string ...$ids): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $ids) {
            return $redis->xDel($key, $ids);
        });
    }

    /**
     * 获取Stream的消息数量
     * 
     * @param string $key 键名
     * @return int 消息数量
     * @throws \Exception
     */
    public function xLen(string $key): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key) {
            return $redis->xLen($key);
        });
    }

    /**
     * 读取Stream中的消息
     * 
     * @param array $streams 要读取的Stream及其起始ID，格式：[stream1 => id1, stream2 => id2, ...]
     * @param array $options 可选参数，包含COUNT、BLOCK等
     * @return array 读取的消息
     * @throws \Exception
     */
    public function xRange(array $streams, array $options = []): array
    {
        return $this->executeCommand(function (Redis $redis) use ($streams, $options) {
            return $redis->xRange($streams, $options);
        });
    }

    /**
     * 反向读取Stream中的消息
     * 
     * @param array $streams 要读取的Stream及其起始ID，格式：[stream1 => id1, stream2 => id2, ...]
     * @param array $options 可选参数，包含COUNT、BLOCK等
     * @return array 读取的消息
     * @throws \Exception
     */
    public function xRevRange(array $streams, array $options = []): array
    {
        return $this->executeCommand(function (Redis $redis) use ($streams, $options) {
            return $redis->xRevRange($streams, $options);
        });
    }

    /**
     * 读取Stream中的消息（支持阻塞读取）
     * 
     * @param array $streams 要读取的Stream及其起始ID，格式：[stream1 => id1, stream2 => id2, ...]
     * @param array $options 可选参数，包含COUNT、BLOCK等
     * @return array 读取的消息
     * @throws \Exception
     */
    public function xRead(array $streams, array $options = []): array
    {
        return $this->executeCommand(function (Redis $redis) use ($streams, $options) {
            return $redis->xRead($streams, $options);
        });
    }

    /**
     * 创建消费者组
     * 
     * @param string $key 键名
     * @param string $groupName 消费者组名
     * @param string $startId 起始ID，默认为0
     * @param array $options 可选参数，包含MKSTREAM等
     * @return string 操作结果
     * @throws \Exception
     */
    public function xGroup(string $key, string $groupName, string $startId = '0', array $options = []): string
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $groupName, $startId, $options) {
            return $redis->xGroup($key, $groupName, $startId, $options);
        });
    }

    /**
     * 从消费者组中读取消息
     * 
     * @param string $groupName 消费者组名
     * @param string $consumerName 消费者名
     * @param array $streams 要读取的Stream及其起始ID，格式：[stream1 => id1, stream2 => id2, ...]
     * @param array $options 可选参数，包含COUNT、BLOCK、NOACK等
     * @return array 读取的消息
     * @throws \Exception
     */
    public function xReadGroup(string $groupName, string $consumerName, array $streams, array $options = []): array
    {
        return $this->executeCommand(function (Redis $redis) use ($groupName, $consumerName, $streams, $options) {
            return $redis->xReadGroup($groupName, $consumerName, $streams, $options);
        });
    }

    /**
     * 确认消费者组中的消息
     * 
     * @param string $key 键名
     * @param string $groupName 消费者组名
     * @param string ...$ids 要确认的消息ID列表
     * @return int 确认的消息数量
     * @throws \Exception
     */
    public function xAck(string $key, string $groupName, string ...$ids): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $groupName, $ids) {
            return $redis->xAck($key, $groupName, $ids);
        });
    }

    /**
     * 获取消费者组中的待处理消息信息
     * 
     * @param string $key 键名
     * @param string $groupName 消费者组名
     * @param array $options 可选参数，包含COUNT、IDLE、HELP等
     * @return array 待处理消息信息
     * @throws \Exception
     */
    public function xPending(string $key, string $groupName, array $options = []): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $groupName, $options) {
            return $redis->xPending($key, $groupName, $options);
        });
    }

    /**
     * 认领消费者组中的待处理消息
     * 
     * @param string $key 键名
     * @param string $groupName 消费者组名
     * @param string $consumerName 消费者名
     * @param int $minIdleTime 最小空闲时间（毫秒）
     * @param array $options 可选参数，包含COUNT、JUSTID等
     * @param string ...$ids 要认领的消息ID列表
     * @return array 认领的消息
     * @throws \Exception
     */
    public function xClaim(string $key, string $groupName, string $consumerName, int $minIdleTime, array $options = [], string ...$ids): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $groupName, $consumerName, $minIdleTime, $options, $ids) {
            return $redis->xClaim($key, $groupName, $consumerName, $minIdleTime, $ids, $options);
        });
    }

    /**
     * 获取Stream或消费者组的信息
     * 
     * @param string $subcommand 子命令，可选值：HELP、STREAM、GROUPS、CONSUMERS
     * @param string $key 键名
     * @param string $groupName 可选的消费者组名
     * @return array Stream或消费者组信息
     * @throws \Exception
     */
    public function xInfo(string $subcommand, string $key, string $groupName = ''): array
    {
        return $this->executeCommand(function (Redis $redis) use ($subcommand, $key, $groupName) {
            if ($groupName) {
                return $redis->xInfo($subcommand, $key, $groupName);
            }
            return $redis->xInfo($subcommand, $key);
        });
    }
}