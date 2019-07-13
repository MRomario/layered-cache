# layered-cache
1. написать систему кеша в которой можно будет сконфигурировать один объект \Cache
реализующий интерфейс

```php
interface CacheInterface {
    public function get (string $key);
    public function set (string $key, $value, $ttl = 3600);
}
```

и поддерживать 2 системы хранения кеша
1. Static - данные записанные в статичные свойства объекта отвечающего за хранение
2. File - данные записанные в файл на диске

запись данных производить одновременно в оба хранилища

вычитка запрошенных данных должна происходить сначала из StaticCache, 
если данных нет из файла (формат хранения произвольный)

эталонное обращение к функционалу

$cache = new Cache(/*config if you need*/);
/*postconfig or any preparing if you need*/

#usage
$cache->set('key1', 'val1'); #

echo $cache->get('key1'); # val1

Система должна уметь реконфигурироваться 
- по приоритету использования кеша
- по количеству носителей для кеш слоя, например, мы сможем добавить хранение в memcache


2. написать простой роутер на один контроллер-экшн (Index::index), который бы поддерживал работу приложения запрошенного через cli и http (можно все в одном файле run.php)

$ echo '{"param3":"value3"}' |  run.php --param1=value1 --param2=value2 #
>>> {
    "param1" => "value1",
    "param2" => "value2",
    "param3" => "value3",
}

curl http://localhost:8080/run.php?param1=value1&param2=value2 -d '{"param3":"value3"}'
>>> {
    "param1" => "value1",
    "param2" => "value2",
    "param3" => "value3",
}
