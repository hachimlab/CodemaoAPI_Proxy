# CodemaoAPI_Proxy
反向代理编程猫API服务

公共服务：[https://api.hachimlab.top/](https://api.hachimlab.top)

PHP版本公共服务：[https://phpapi.hachimlab.top/](https://phpapi.hachimlab.top)

PHP版本公共服务部署在美国虚拟主机，速度略慢

## 这是什么？

众所周知编程猫CoCo编辑器限制了所有发往 `api.codemao.cn` 的请求，为了保证开发者能更舒适的开发工具，故出此项目

当然，玩法不只是这一个

**不过注意！如果你要在CoCo内使用此反代，请在虚拟主机上部署PHP服务，发生了什么？[点击这里](https://shequ.codemao.cn/community/1646110)**

## 使用方法

将所有API的URL替换为自己部署的服务即可，这里使用公共服务演示：

原API地址：`https://api.codemao.cn/web/forums/notice-boards`

代理后的API地址：`https://api.hachimlab.top/web/forums/notice-boards`

所有请求方式/Header都和请求官方API时一样，这个东西只不过就是为了骗过某些检测

## 部署

如果要在Cloudflare上部署，请Fork本仓库，并自行搜索如何从GitHub部署Pages项目

如果要在虚拟主机/服务器上部署，请下载 `index.php` 和 `.htaccess` 文件，然后直接放到目录即可

## 测试

使用GET方式请求 `/testcma`，返回为 `{"status":"ok"}` 则为测试通过

`curl https://api.hachimlab.top/testcma`

## 合规

本项目仅用于反向代理编程猫API服务，无任何破解/绕过行为

## 截图

| **在CoCo使用官方API** | **在CoCo使用代理API** |
|:------------------:|:------------------:|
| ![601](assets/601.png)            | ![200](assets/200.png)            |
