## 2014-12-05 update: 有一个更好的typecho的导出插件，生成xml文件，直接导入wordpress。地址：https://github.com/panxianhai/TypExport

typecho 2 wordpress
============================

这不是一个wordpress插件，这是一个PHP程序，用于将typecho的数据转换到wordpress

注意：使用前备份好自己的数据，建议在本地搭建环境进行转换

* typecho 0.8 & Wordpress 3.2.1 测试可用

=== requirement ===
php 5+
mysql 5+

=== usage ===
1.将下载文件夹更名为你喜欢的名字，或者更名为typecho2wordpress放置于可访问的本地或者远程服务器
2.确认安装了wordperss，确认数据库中有wordpress和typecho的数据表
3.访问此程序按照提示进行

=== changelog ===
0.2
修正了内容中有单双引号导致插入数据出错的问题
修正了附件无法在后台显示的问题
添加了更详细的说明文档

0.1
first release

=== todo ===
内容中附件访问链接错误的问题
