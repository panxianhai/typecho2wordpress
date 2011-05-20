<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Typecho2wordpress</title>
    <style type="text/css">
        *{margin: 0; padding: 0;}
        body{background: #666; font: 14px/160% Arial;}
        #wrapper{width: 760px; margin: 20px auto; background: #FFF; padding: 20px 40px;}
        #intro{margin: 20px 0;}
        #intro h1{text-align: center; height: 40px; line-height: 40px; margin-bottom: 30px;}
        #steps fieldset{padding: 20px;}
        #steps p{height: 40px; border-bottom: 1px solid #DDD;padding-top: 20px;}
        #steps label{float: left; width: 200px;}
        #steps input{float: left; padding: 3px;}
        #steps span{margin-left: 15px;}
    </style>
</head>
<body>
    <div id="wrapper">
        <div id="content">
            <div id="intro">
                <h1>Typecho 2 Wordpress</h1>
                <h3>程序说明</h3>
                <p>1.本程序将你的typecho数据转换到wordpress，强烈建议在<strong>本地环境进行转换</strong>，并且<strong>做好原数据的备份</strong>。</p>
                <p>2.测试条件：<em>typecho 0.8 & Wordpress 3.1.2</em></p>
                <p>3.wordpress版本要求：> 3.0.建议使用最新版本。</p>
                <p>4.<strong>重要：程序目前只能够全新转换，将清空除wp_users,wp_usermeta,wp_options,wp_links以外的所有表，请将重要数据备份。</strong></p>
                <p>5.程序将转换你的全部文章，包括附件，你只需要复制usr/uploads文件夹下文件到wordpress的wp-content/uploads下即可。</p>
                <p>6.程序将转换你的全部评论，分类和全部tag，没有使用的tag将不被转换。</p>
            </div>
            <div id="steps">
                <form action="done.php" method="post">
                    <fieldset>	<legend>转换设置</legend>
                        <p><label for="dbhost">数据库地址：</label>
                        <input type="text" name="dbhost" value="localhost" /></p>
                        <p><label for="dbuser">数据库用户名：</label>
                        <input type="text" name="dbuser" value="" /></p>
                        <p><label for="dbpass">数据库密码：</label>
                            <input type="text" name="dbpass" value="" /></p>
                        <p>
                        <label for="typecho">Typecho数据库：</label>
                        <input type="text" name="typecho" value="" />
                        </p>
                        <p>
                        <label for="typecho_prefix">Typecho数据库前缀：</label>
                        <input type="text" name="typecho_prefix" value="typecho_" /><span>如果是其他的请更改</span>
                        </p>
                        <p>
                        <label for="wordpress">Wordpress数据库：</label>
                        <input type="text" name="wordpress" value="" />
                        </p>
                        <p>
                        <label for="wordpress_prefix">Wordpress数据库前缀：</label>
                        <input type="text" name="wordpress_prefix" value="wp_" /><span>如果是其他的请更改</span>
                        </p>
                        <p>
                        <label for="website">新的Wordpress网址：</label>
                        <input type="text" name="website" value="" /><span>例如：http://wp.com,<strong>不要添加后面的反斜杠</strong></span>
                        </p>
                    </fieldset>
                    <p>
                    <input type="submit" value="开始转换" />
                    <input type="reset" value="重新填写" /></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>