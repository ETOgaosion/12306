# Thoughts

## about pages

### by Requirement Analysis

**根本设计原则：简洁唯美**

打开时要求登陆或注册，可选择记住并自动登录，以后打开时不会要求登陆，利用已有信息直接打开主界面。管理员统一入口登陆，但需输入二次验证信息，成功登陆后跳转到管理员主页面。注册，登陆使用列tab。

主界面只有背景图、中间为中国地图和查询tabs，地图下方有一个查询支持城市的块；查询tabs默认为两地之间车次查询，查询输入包括文本框内的出发、到达城市名，后期结合bootstrap提供自动补全；出发日期可采用文本框或者下拉日历的形式，后期可运用bootstrap对后者予以实现；查询时间也是两种；查询tab通过两个按钮开启查询事件，分别查询去程和返程车票。

而后跳转到查询结果页面，两种结果页面分别对应两种查询tab，可分立。表格信息如notion设计。车次查询中点击余票之后跳转到订单预生成界面，可以调整始发终点站，输入乘客信息（自己不用输入）生成多张票的订单，而后跳转到订单确认界面，确认生成的多张订单，完成或取消后返回主界面。车次查询按照notion中设计显示表格，之后过程同上，可重用。

主界面右上角有浮动用户头像，可以有弹出side bar，点击浮动用户头像后跳转到用户界面，使用左右栏样式，左边两个tab，用户信息更改和订单查询，订单查询界面会显示所有订单，可提供筛选或查找文本框。若订单未完成可显示取消连接，弹窗确认后取消订单，取消订单仍然在订单数据库。

管理员主页面同样由双栏tab bar构成，每个都有刷新按钮，总订单数/总票价一个tab；热点车次排序一个tab；注册用户一个tab。点击注册用户跳转到用户信息界面，显示公开信息和订单。

因此页面如下：

```
UCAS_Database
|- index.php: login & register
|----- userMain.php: user main view, query train info
|----- userQueryRes.php: user query result
|----- userOrderGenerate.php: user generate orders
|----- userOrderConfirm.php: user confirm order
|----- userSpace.php: user space
|
|----- adminMain.php: admin main view
|----- adminUserDetail.php: admin view user info
|
|- errorPage.php
```

MVC模式: Model-View-Controller, but we separate them

使用Bootstrap

```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
```

第一阶段：

并行：
- [ ] 车辆信息预处理
- [ ] 车站，城市基本信息提取

第二阶段：
- [ ] city reach table
- [ ] train, train_full_info

tools version:
Pgsql 14
php 8.0.2

city reach table requirements:

input:
|train_id|city_id|
|:-:|:-:|
|1|1|
|1|2|
|1|3|
|1|4|
|2|3|
|2|5|
|3|5|
|3|6|

output:
|city_id|reach_table|
|:-:|:-:|
|1|[f,t,t,t,t,f]|
|2|[f,f,t,t,t,f]|
|3|[f,f,f,t,t,t]|
|4|[f,f,f,f,f,f]|
|5|[f,f,f,f,f,f]|
|6|[f,f,f,f,f,f]|