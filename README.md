# redis
用单例模式封装redis

####使用示例

```
  //要先 use since\Redis;
        $redis=Redis::getInstance();
        $keys=$redis->keys("*");
        var_dump($keys);
        $a=$redis->info('server');
        var_dump($a);
        $a=$redis->set('test',123);
        var_dump($a);
        $a=$redis->get('test');
```
