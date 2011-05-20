<?php
function change_date_format($date) {
	return date("Y-m-d H:i:s", $date);
}

function change_comment_status($status){
	switch($status){
		case 1:
			return "open";
		break;
		case 0:
			return "closed";
		break;
	}
}

function change_ping_status($status) {
	switch($status){
		case 1:
			return "open";
		break;
		case 0:
			return "closed";
		break;
	}
}

/** 处理comments数据 */
function change_comment_approved($status){
	switch ($status) {
		case "approved":
			return 1;
		break;
		case "waiting":
			return 0;
		break;
		case "spam":
			return "spam";
		break;
	}
}

// tag
function change_taxonomy($type) {
	switch ($type) {
		case "category":
			return "category";
		break;
		case "tag":
			return "post_tag";
		break;
	}
}