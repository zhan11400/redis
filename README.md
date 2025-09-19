# since/redis

一个基于PHP 8+的高性能Redis客户端库，提供连接池+门面模式和原始单例模式两种使用方式，支持Redis所有数据结构操作，包含完整的类型声明和异常处理机制。

## 特性

- ✅ 支持连接池管理，有效控制连接资源
- ✅ 提供门面模式设计，简化API调用
- ✅ 支持单例模式，兼容传统使用习惯
- ✅ 完整支持Redis所有数据结构操作
- ✅ 包含完善的类型声明，支持IDE自动补全
- ✅ 提供异常处理机制，提高代码健壮性
- ✅ 基于PHP 8+语法，充分利用现代PHP特性

## 安装

使用Composer安装：

```bash
composer require since/redis
```

## 系统要求

- PHP 8.0+ 
- Redis扩展 (`ext-redis`)
- JSON扩展 (`ext-json`)

## 使用方法

### 1. 门面模式（连接池）使用方式

门面模式是推荐的使用方式，它通过连接池管理Redis连接，适合高并发场景。

```php
// 引入自动加载文件
require_once 'vendor/autoload.php';

use since\RedisFacade;

// 获取RedisFacade实例（可配置连接参数）
$redis = RedisFacade::getInstance([
    'host' => '127.0.0.1',       // Redis服务器地址
    'port' => 6379,              // Redis端口
    'password' => '',            // Redis密码（如无密码则为空）
    'database' => 0,             // 数据库索引
    'timeout' => 2,              // 连接超时时间（秒）
    'max_connections' => 10,     // 最大连接数
]);

// 字符串操作示例
$redis->string()->set('name', 'RedisClient');
$name = $redis->string()->get('name');

// 哈希操作示例
$redis->hash()->hSet('user:1', 'name', '张三');
$redis->hash()->hSet('user:1', 'age', 25);
$userInfo = $redis->hash()->hGetAll('user:1');

// 列表操作示例
$redis->list()->lpush('list', 'item1');
$redis->list()->lpush('list', 'item2');
$items = $redis->list()->lrange('list', 0, -1);

// 键操作示例
$redis->key()->expire('name', 3600);
$exists = $redis->key()->exists('name');
```

### 2. 单例模式使用方式

单例模式是传统的使用方式，适合简单场景：

```php
// 引入自动加载文件
require_once 'vendor/autoload.php';

use since\Redis;

// 获取Redis实例（可配置连接参数）
$redis = Redis::getInstance([
    'host' => '127.0.0.1',       // Redis服务器地址
    'port' => 6379,              // Redis端口
    'password' => '',            // Redis密码（如无密码则为空）
    'select' => 0,               // 数据库索引
    'timeout' => 0,              // 连接超时时间（秒）
    'expire' => 0,               // 默认过期时间（秒）
    'persistent' => false,       // 是否使用持久连接
    'prefix' => '',              // 键前缀
    'serialize' => true,         // 是否自动序列化
]);

// 直接调用Redis方法
$redis->set('name', 'RedisClient');
$name = $redis->get('name');
$redis->expire('name', 3600);
```

## 支持的数据结构和操作

本库支持Redis的所有数据结构操作，通过不同的操作类提供：

| 数据结构 | 操作类 | 门面方法 | 描述 |
|---------|--------|---------|------|
| String | StringOperation | string() | 字符串类型操作 |
| Hash | HashOperation | hash() | 哈希表类型操作 |
| List | ListOperation | list() | 列表类型操作 |
| Set | SetOperation | set() | 集合类型操作 |
| SortedSet | SortedSetOperation | sortedSet() | 有序集合类型操作 |
| Geo | GeoOperation | geo() | 地理空间类型操作 |
| HyperLogLog | HyperLogLogOperation | hyperLogLog() | 基数统计类型操作 |
| Stream | StreamOperation | stream() | 流类型操作 |
| Key | KeyOperation | key() | 键操作 |

## 连接池配置

连接池模式下的主要配置参数：

```php
$config = [
    'host' => '127.0.0.1',       // Redis服务器地址
    'port' => 6379,              // Redis端口
    'password' => '',            // Redis密码
    'database' => 0,             // 数据库索引
    'timeout' => 2,              // 连接超时时间（秒）
    'max_connections' => 10,     // 最大连接数
];

$redis = RedisFacade::getInstance($config);
```

## 单例模式配置

单例模式下的主要配置参数：

```php
$options = [
    'host' => '127.0.0.1',       // Redis服务器地址
    'port' => 6379,              // Redis端口
    'password' => '',            // Redis密码
    'select' => 0,               // 数据库索引
    'timeout' => 0,              // 连接超时时间（秒）
    'expire' => 0,               // 默认过期时间（秒）
    'persistent' => false,       // 是否使用持久连接
    'prefix' => '',              // 键前缀
    'serialize' => true,         // 是否自动序列化
];

$redis = Redis::getInstance($options);
```

## 异常处理

本库提供了完善的异常处理机制，使用时建议添加try-catch块捕获可能的异常：

```php
try {
    $redis->string()->set('name', 'RedisClient');
    $name = $redis->string()->get('name');
} catch (\Exception $e) {
    // 处理异常
    echo 'Redis操作失败: ' . $e->getMessage();
}
```

## 最佳实践

1. **高并发场景推荐使用门面模式（连接池）**：
   - 连接池能有效管理和复用连接资源
   - 避免频繁建立和关闭连接的开销
   - 控制最大连接数，防止连接耗尽

2. **合理设置连接池参数**：
   - 根据系统负载和Redis服务器性能设置`max_connections`
   - 适当设置`timeout`，避免连接等待时间过长

3. **使用键前缀**：
   - 在多项目或多环境共用Redis时，设置不同的键前缀避免键冲突

4. **合理设置过期时间**：
   - 对临时数据设置合适的过期时间，避免Redis内存占用过高

5. **错误处理**：
   - 始终使用try-catch捕获Redis操作可能抛出的异常
   - 考虑添加重试机制，处理临时性网络问题

## 性能优化

1. **使用管道（Pipeline）**：
   - 对于批量操作，使用管道减少网络往返次数

2. **合理使用事务**：
   - 对需要原子性的操作，使用Redis事务

3. **避免大键**：
   - 尽量避免存储过大的值，影响Redis性能

## 版本历史

### 1.0.0
- 初始版本
- 支持连接池和单例模式
- 支持所有Redis数据结构操作
- 提供完整的类型声明和异常处理

## 许可证

本项目使用MIT许可证 - 详见[LICENSE](LICENSE)文件

## 作者信息

- 作者：湛工
- 邮箱：1140099248@qq.com