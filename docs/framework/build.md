# Change Log

## 3.0

**3.0-dev**

- PoppyTrait 更改为 pyXx 模式, Poppy Function Rename
    - getCache => pyCache
- Symfony error FatalErrorException => FatalError
- Remove lang/zh/http.php
- Add parse_seo function
- Remove str-helper generator
- Remove web-helper
- Remove PoppyServiceProvider@registerConsoleCommand
- 更改为强类型(Strong Type)
- remove `Http\Middlewares\CrossPreflight` : 使用 `EnableCrossRequest` 替代
- Event `PoppyOptimized` move to `src\Events` folder
- 模块支持 composer poppy 文件夹加载, poppy.xxx 为 composer 模块, module.xx 为自定义业务逻辑模块
- Resp 内置参数 `_json`, `_location`, `_time`, `_forget`, `_time` 更改为下划线前缀


## 2.0

**2.0.0**

- for laravel 6.x
- remove agmotto

## 1.0

**1.0.0**

- for laravel 5.5
- Remove `cache_name` function
- Remove similar function with laravel
- Remove Pinyin Component

## 0.9

> Before 1.0

- Remove `SystemTrait`
- Remove update
- Resp
- Doc command remove to System module
- Add Document for command
- Delete Graphql
- Add phplint
- Add php-cs-fixer