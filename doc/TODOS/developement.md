# 开发工作TODO

[TOC]

## 地图使用完善

本项目使用openlayers实现地图功能，目前只支持观看和拖动，希望未来支持定位、点击、搜索等功能。

- 定位：浏览器申请获取当前位置，展示在地图中并自动获取城市名称填入查询起始地点
- 点击：用户可以通过点击的方式添加位置，检测当前未填入的是起始地点还是目的地点，使用该位置对应的城市名称填入
  - 参考代码: [https://openlayers.org/en/latest/examples/popup.html](https://openlayers.org/en/latest/examples/popup.html)，需要注册[https://cloud.maptiler.com/](https://cloud.maptiler.com/)，已有API Key为`qk8lAhOWXOmroYQgxouP`
  - 逆向获取地理信息：API使用免费的可腾API，接口路径：[https://api.kertennet.co/geography/locationInfo_v2?lat=...&lng=...](https://api.kertennet.co/geography/locationInfo_v2?lat=...&lng=...)
- 搜索：用户在输入起始和目的地点后，在地图上同步显示

## UI调整

- 目前空白背景十分单调，寻求优美而不是简约的设计

## 代码与算法优化

- 城市中转查询速度较慢，优化SQL查询算法甚至表结构
- 针对高并发访问，实现队列存储请求，减少数据库压力