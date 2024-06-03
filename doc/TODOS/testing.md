# 测试工作TODO

[TOC]

## hierarchical Test 分级测试

- 正确性测试
    - Sql测试
        - Sql Unit Test
            - [pgTAP](https://pgtap.org/)
        - Sql Function Test
            - Calling SQL Functions with python
    - PHP测试
        - PHP Unit Test
            - [PHPUnit](https://phpunit.de/index.html)
        - PHP 页面跳转与攻击测试
- 性能测试
    - 单次查询性能测试
        - 通过页面代码代理
    - 高并发压力测试
        - [WebBench](https://github.com/EZLippi/WebBench)