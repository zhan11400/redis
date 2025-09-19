<?php

namespace since\Operation;

use Redis;
use since\Operation\BaseOperation;

/**
 * Redis地理位置操作类
 * 提供所有与Redis GEO地理位置数据结构相关的操作方法
 */
class GeoOperation extends BaseOperation
{
    /**
     * 将指定的地理空间位置（经度、纬度、名称）添加到指定的键
     * 
     * @param string $key 键名
     * @param array $locations 地理位置数组，格式：[经度1, 纬度1, 名称1, 经度2, 纬度2, 名称2, ...]
     * @return int 添加的地理位置数量
     * @throws \Exception
     */
    public function geoAdd(string $key, array $locations): int
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $locations) {
            return $redis->geoAdd($key, ...$locations);
        });
    }

    /**
     * 从键里返回所有给定位置元素的位置（经度和纬度）
     * 
     * @param string $key 键名
     * @param string ...$members 成员名列表
     * @return array 成员位置数组
     * @throws \Exception
     */
    public function geoPos(string $key, string ...$members): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $members) {
            return $redis->geoPos($key, ...$members);
        });
    }

    /**
     * 计算两个给定位置之间的距离
     * 
     * @param string $key 键名
     * @param string $member1 第一个成员名
     * @param string $member2 第二个成员名
     * @param string $unit 距离单位，可选值：m（米）、km（千米）、mi（英里）、ft（英尺），默认为m
     * @return float|false 距离值或false（如果成员不存在）
     * @throws \Exception
     */
    public function geoDist(string $key, string $member1, string $member2, string $unit = 'm'): float|false
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $member1, $member2, $unit) {
            return $redis->geoDist($key, $member1, $member2, $unit);
        });
    }

    /**
     * 以给定的经纬度为中心，找出某一半径内的元素
     * 
     * @param string $key 键名
     * @param float $longitude 经度
     * @param float $latitude 纬度
     * @param float $radius 半径
     * @param string $unit 半径单位，可选值：m（米）、km（千米）、mi（英里）、ft（英尺）
     * @param array $options 可选参数，包含WITHCOORD、WITHDIST、WITHHASH、COUNT等
     * @return array 符合条件的成员数组
     * @throws \Exception
     */
    public function geoRadius(string $key, float $longitude, float $latitude, float $radius, string $unit, array $options = []): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $longitude, $latitude, $radius, $unit, $options) {
            return $redis->geoRadius($key, $longitude, $latitude, $radius, $unit, $options);
        });
    }

    /**
     * 以给定的成员的位置为中心，找出某一半径内的元素
     * 
     * @param string $key 键名
     * @param string $member 成员名
     * @param float $radius 半径
     * @param string $unit 半径单位，可选值：m（米）、km（千米）、mi（英里）、ft（英尺）
     * @param array $options 可选参数，包含WITHCOORD、WITHDIST、WITHHASH、COUNT等
     * @return array 符合条件的成员数组
     * @throws \Exception
     */
    public function geoRadiusByMember(string $key, string $member, float $radius, string $unit, array $options = []): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $member, $radius, $unit, $options) {
            return $redis->geoRadiusByMember($key, $member, $radius, $unit, $options);
        });
    }

    /**
     * 返回一个或多个位置元素的Geohash表示
     * 
     * @param string $key 键名
     * @param string ...$members 成员名列表
     * @return array Geohash值数组
     * @throws \Exception
     */
    public function geoHash(string $key, string ...$members): array
    {
        return $this->executeCommand(function (Redis $redis) use ($key, $members) {
            return $redis->geoHash($key, ...$members);
        });
    }
}