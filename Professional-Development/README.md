# Docker專業養成：第11章綜合演練練習

主要是建立由 Nginx + Memcached + Mysql + PHP 組成的小專案，最後將這專案製作成Docker compose.yml

**需注意的是**，起好MySql後還需登入並執行以下SQL

|      url     | 帳號 |    密碼    |
|:------------:|:----:|:----------:|
|localhost:3306| root | screencast |

```SQL
CREATE DATABASE web;
USE web;

CREATE TABLE web.ymdot (
    `key` varchar(100) NOT NULL,
    value varchar(100) NULL,
    CONSTRAINT ymdot_PK PRIMARY KEY (`key`)
);
```

最後進入到<http://localhost>，即可看到簡易的網站
![ex1.png](images/ex1.png)