<?php
/**
 * Typecho2Wordpress
 * typecho 2 wordpress 转换程序，主要原理是查询typecho里的数据，然后插入到wordpress数据库里。
 * @author hevin <panxianhai@gmail.com>
 * @website http://www.panxianhai.com
 */
error_reporting(E_ALL);
$website_http = explode('//', trim($_POST['website']));
$website = $website_http[1];
if(!isset($_POST['wordpress'])){
    header("Location: index.php");
} else if (gethostbyname($website) == '127.0.0.1') {
    header("Content-type: text/html; charset=utf-8"); 
    die("填写的网址有误，不应该填写本地的网址哦");
}

// 接受参数
$host = trim($_POST['dbhost']);	
$user = trim($_POST['dbuser']);
$password = trim($_POST['dbpass']);
$tp_database = trim($_POST['typecho']);
$tp_prefix = trim($_POST['typecho_prefix']);
$wp_prefix = trim($_POST['wordpress_prefix']);
$wp_database = trim($_POST['wordpress']);
$site = trim($_POST['website']);
	
require_once 'lib/functions.php';
require_once 'lib/mysql.php';

// 数据库链接
$wpdb = new Mysql($host, $user, $password, $wp_database);

// 清空数据表
$wpdb->execute("TRUNCATE {$wp_prefix}commentmeta");
$wpdb->execute("TRUNCATE {$wp_prefix}comments");
$wpdb->execute("TRUNCATE {$wp_prefix}postmeta");
$wpdb->execute("TRUNCATE {$wp_prefix}posts");
$wpdb->execute("TRUNCATE {$wp_prefix}terms");
$wpdb->execute("TRUNCATE {$wp_prefix}term_relationships");
$wpdb->execute("TRUNCATE {$wp_prefix}term_relationships");
$wpdb->execute("TRUNCATE {$wp_prefix}term_taxonomy");

// 连接 typecho 数据库
$tpconn = mysql_connect($host, $user,$password);
mysql_select_db($tp_database, $tpconn);
mysql_query("SET NAMES utf8");
/* 查询所有文章  */
$post_sql = "SELECT * FROM {$tp_prefix}contents order by cid ASC";
$post_result = mysql_query($post_sql);

while ($row = mysql_fetch_assoc($post_result)) {
	if ($row['type'] == "attachment") {
		$img = unserialize($row['text']);
		$imgarr = array_reverse(explode("/", $img['path']));
		for ( $i=0; $i<4; $i++ ) {
			$img2[] = $imgarr[$i];
		}
		$imgpath = implode("/", array_reverse($img2));
		$path = $site . "/wp-content/" . $imgpath;
		$wpdb->insertRecords($wp_prefix . "posts", array(
			'ID'                => $row['cid'],
			'post_author'       => $row['authorId'],
			'post_date'         => change_date_format($row['created']),
			'post_date_gmt'     => change_date_format($row['created']),
			'post_content'      => "",
			'post_title'        => addslashes($row['title']),
			'post_status'       => 'inherit',
			'comment_status'    => change_comment_status($row['allowComment']),
			'ping_status'       => change_ping_status($row['allowPing']),
			'post_password'     => $row['password'],
			'post_name'         => $row['slug'],
            'to_ping'           => "",
            'pinged'            => "",
			'post_modified'     => change_date_format($row['modified']),
			'post_modified_gmt' => change_date_format($row['modified']),
            'post_content_filtered' => "",
			'post_parent'       => $row['parent'],
			'guid'              => $path,
			'menu_order'        => $row['order'],
			'post_type'         => $row['type'],
			'post_mime_type'    => $img['mime'],
			'comment_count'     => $row['commentsNum'],
		));
	} else if( $row['type'] == "post" || $row['type'] == "page" ) {

		// 将文章中的附件地址更改,情况比较复杂，简单处理一下显示问题
		$row['text'] = str_replace('/usr/', '/wp-content/', $row['text']);

		$wpdb->insertRecords($wp_prefix . "posts", array(
			'ID'                => $row['cid'],
			'post_author'       => $row['authorId'],
			'post_date'         => change_date_format($row['created']),
			'post_date_gmt'     => change_date_format($row['created']),
			'post_content'      => addslashes($row['text']),
			'post_title'        => addslashes($row['title']),
			'post_status'       => $row['status'],
			'comment_status'    => change_comment_status($row['allowComment']),
			'ping_status'       => change_ping_status($row['allowPing']),
			'post_password'     => $row['password'],
			'post_name'         => $row['slug'],
            'to_ping'           => "",
            'pinged'            => "",
			'post_modified'     => change_date_format($row['modified']),
			'post_modified_gmt' => change_date_format($row['modified']),
            'post_content_filtered' => "",
			'post_parent'       => $row['parent'],
            'guid'              => "",
			'menu_order'        => $row['order'],
			'post_type'         => $row['type'],
			'comment_count'     => $row['commentsNum'],
		));
	}
}

/* 查询所有评论 */
$comment_sql = "SELECT * FROM {$tp_prefix}comments";
mysql_select_db($tp_database, $tpconn);
$comment_result = mysql_query($comment_sql);
while ($row = mysql_fetch_assoc($comment_result)) {

	$wpdb->insertRecords($wp_prefix . "comments", array(
		'comment_ID'           => $row['coid'],
		'comment_post_id'      => $row['cid'],
		'comment_author'       => $row['author'],
		'comment_author_email' => $row['mail'],
		'comment_author_url'   => $row['url'],
		'comment_author_IP'    => $row['ip'],
		'comment_date'         => change_date_format($row['created']),
		'comment_date_gmt'     => change_date_format($row['created']),
		'comment_content'      => addslashes($row['text']),
		'comment_approved'     => change_comment_approved($row['status']),
		'comment_agent'        => $row['agent'],
		'comment_type'         => '',
		'comment_parent'       => $row['parent'],
		'user_id'              => $row['authorId'],
	));
}
// 分类和tag
$cat_tag = "SELECT * FROM {$tp_prefix}metas WHERE type = 'category' OR type = 'tag'";
mysql_select_db($tp_database, $tpconn);
$cat_tag_result = mysql_query($cat_tag);
while ($row = mysql_fetch_assoc($cat_tag_result)) {

	// wp_terms
	$wpdb->insertRecords($wp_prefix . "terms", array(
		'term_id'    => $row['mid'],
		'name'       => $row['name'],
		'slug'       => $row['slug'],
		'term_group' => 0,
	));
	
	// 查询post_id
	$post_id = "SELECT * FROM {$tp_prefix}relationships WHERE mid = " . $row['mid'];
    mysql_select_db($tp_database, $tpconn);
	$tmp_1 = mysql_query($post_id);
	while ($row2 = mysql_fetch_assoc($tmp_1)) {
		$check = "select * from {$wp_prefix}term_taxonomy where term_id = " . $row2['mid'];
        $results = $wpdb->selectRecords($check);
		if(empty($results[0])){
			// wp_term_taxonomy
			$taxonomy_id = $wpdb->insertRecords($wp_prefix . "term_taxonomy", array(
				'term_id'     => $row2['mid'],
				'taxonomy'    => change_taxonomy($row['type']),
				'description' => addslashes($row['description']),
				'parent'      => 0,
				'count'       => $row['count'],
			));
			// wp_term_relationships
			$wpdb->insertRecords($wp_prefix . "term_relationships", array(
				'object_id'        => $row2['cid'],
				'term_taxonomy_id' => $taxonomy_id,
				'term_order'       => $row['order'],
			));
		} else {
            // wp_term_relationships
			$wpdb->insertRecords($wp_prefix . "term_relationships", array(
				'object_id'        => $row2['cid'],
				'term_taxonomy_id' => $results[0]['term_taxonomy_id'],
				'term_order'       => $row['order'],
			));
        }
	}
} // end wile
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <title>转换完成</title>
</head>
<body>
<p>转换完成，请按下面的步骤进行操作：</p>
<ol>
    <li>在新的空间安装wordpress。</li>
    <li>进入转换好的数据库，导出除了wp_options以外（避免网址的错误）的其他数据。</li>
    <li>进入空间的phpmyadmin，导入刚才导出的数据。</li>
    <li>完成。</li>
</ol>
<p><a href="http://www.panxianhai.com/typecho-2-wordpress.html">问题反馈</a></p>
</body>
</html>
