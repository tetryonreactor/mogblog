<?php
/*
Copyright (c) 2014, Magnus Oberg <moggen@tetryonreactor.com>

Permission to use, copy, modify, and/or distribute this software for any
purpose with or without fee is hereby granted, provided that the above
copyright notice and this permission notice appear in all copies.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
*/

$config=array("configured" => 0);
if(file_exists('config.php')) {
  require 'config.php';
}

$parsedown = false;
if(file_exists('Parsedown.php')) {
  require 'Parsedown.php';
  $parsedown = true;
}

$skin = false;
if(file_exists('skin.php')) {
  require 'skin.php';
  $skin = true;
}

function mb_uesc($str) {
  return urlencode($str);
}

function mb_hesc($str) {
  return htmlspecialchars($str, ENT_COMPAT, 'UTF-8');
}

function mb_get($var) {
  if(key_exists($var, $_GET)) {
    return $_GET[$var];
  }
  return "";
}

function mb_post($var) {
  if(key_exists($var, $_POST)) {
    return $_POST[$var];
  }
  return "";
}

function mb_cookie($var) {
  if(key_exists($var, $_COOKIE)) {
    return $_COOKIE[$var];
  }
  return "";
}

function mb_write_config() {
  global $config;

  $config['configured'] = 1;

  $f = fopen("config.php","wb");
  if($f != false) {
    fwrite($f, "<?php /* MogBlog config file */\n");
    fwrite($f, '$config = ');
    fwrite($f, var_export($config, true));
    fwrite($f, ";\n");
    fwrite($f, "?>\n");
    fclose($f);
    sleep(1);    // Give the web server some time to discover that this file has changed before doing anything else.
  } else {
    print '<span style="color: red">Error: Cant write to config.php. Please check your permissions</span>';
  }
}

function mb_img_warning() {
  return "data:image/png;base64,".
    "iVBORw0KGgoAAAANSUhEUgAAAA8AAAANBAMAAACEMClyAAAAAXNSR0IArs4c6QAAABtQTFRFIAAA".
    "AAEAXV0Bc3UBjYsAra4A0NEA5uMA//8BHfwBsAAAAAF0Uk5TAEDm2GYAAABRSURBVAjXY2BgYGBk".
    "gAJRAQjNGBEIZVQUQhjije1gOUaNxAxFMKMjMaMRxBABMlqAcowWHY0dHYZARkYHECQyMAgByYyO".
    "JgGgEhAAKhKEAAEAQXAUEg/8O64AAAAASUVORK5CYII=";
}

function mb_header() {
  header("Content-Type: text/html; charset=utf-8"); 
?>
<html>
  <head>
    <title>MogBlog Admin</title>
    <style type="text/css">
<?php print mb_default_style(); ?>
form.std span, form.login span {
  display: inline-block;
  width: 8em;
  text-align: right;
  vertical-align: top;
  margin-top: 0.2em;
}

form.wide span {
  display: inline-block;
  width: 12em;
  text-align: right;
  vertical-align: top;
  margin-top: 0.2em;
}

input[type="text"], input[type="password"] {
  width: 15em;
}

form.login input[type="text"], form.login input[type="password"] {
  width: 7em;
}

form.edit textarea {
  width: 100%;
  height: 34em;
}

.posts a {
  color: #444444;
}

.status_new {
  background-color: #ffffff;
}
.status_unsaved {
  background-color: #ffff80;
}
.status_notpublished {
  background-color: #b0b0b0;
}
.status_updated {
  background-color: #ff8080;
}
.status_published {
  background-color: #80ff80;
}
.status_nosource {
  background-color: #ff80ff;
}
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <div class="main">
<?php
}

function mb_footer() {
?>
    </div>
  </body>
</html>
<?php
}

function mb_header_menu() {
  mb_header();
?>
      <div class="titlebox">
        <div class="title">
          <h1 class="main_title"><a href=".">MogBlog Admin</a></h1>
        </div>
        <div class="menu">
          <ul>
            <li><a href=".">Posts</a></li>
            <li><a href="?cmd=admins">Manage admins</a></li>
            <li><a href="?cmd=settings">Settings</a></li>
            <li><a href="?cmd=logout">Log out</a></li>
          </ul>
        </div>
      </div>
<?php
}

function mb_footer_menu() {
  mb_footer();
}

function mb_default_style() {
  return <<<END
body {
  background-color: #F0F0F0;
  background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAAAAACoBHk5AAAAAXNSR0IArs4c6QAAACVJREFUCNcFwTESACAMwzDDZemU/z+zk0eQzluZux2MFIOUjNIPrBoKCWyToNYAAAAASUVORK5CYII=');
  color: #444444;
  font-family: sans serif;
}
.main {
  margin: 0 auto;
  width: 980px;
}
.titlebox {
  border: solid #111111 1px;
  margin: 0 0 10px;
  box-shadow: 0 15px 10px -15px rgba(0, 0, 0, 0.4);
}
.title {
  background-color: #444444;
  padding: 5px 15px;
}
.menu {
  background-color: #666666;
  padding: 5px 15px;
}
.menu ul {
  margin: 0;
  padding: 0;
  list-style: none;
}
.menu li {
  display: inline-block;
  padding: 0 30px 0 0;
}
.menu li a {
  color: #DDDDDD;
}
.art {
  background-color: #FFFFFF;
  border: solid #DDDDDD 1px;
  padding: 5px 15px 0px;
  margin: 0 0 10px;
  box-shadow: 0 15px 10px -15px rgba(0, 0, 0, 0.4);
}
.art_footer {
  height: 0px;
  margin: 15px 0 0 0;
}
.date {
  font-size: 60%;
  color: #BBBBBB;
}
a {
  color: #6060ff;
  text-decoration: none;
}
a:hover {
  text-decoration: underline;
}
h1.main_title {
  color: #EEEEEE;
  margin: 15px 0;
}
h1.main_title a {
  color: #EEEEEE;
}
h1.main_title a:hover {
  text-decoration: none;
}
h1, h2, h3 {
  color: #222255;
  margin: 10px 0 15px;
}
h2 a {
  color: #222255;
}
END;
}

function mb_default_header($rooturl=".") {
  $style = mb_default_style();
  return <<<END
<html>
  <head>
    <title>[:mb:main_title:]</title>
    <style type="text/css">
$style
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="$rooturl/atom.xml" type="application/atom+xml" rel="alternate" title="ATOM Feed" />
  </head>
  <body>
    <div class="main">
      <div class="titlebox">
        <div class="title">
          <h1 class="main_title"><a href="$rooturl">[:mb:main_title:]</a></h1>
        </div>
        <div class="menu">
          <ul>
            <li><a href="$rooturl">Home</a></li>
          </ul>
        </div>
      </div>
END;
}

function mb_default_footer() {
  return <<<END
    </div>
  </body>
</html>
END;
}

function mb_default_frontpage() {
  $header = mb_default_header();
  $footer = mb_default_footer();
  return <<<END
$header
[:mb:articles{:]
      <div class="art">
        <span class="date">[:mb:date:]</span><br />
        <h2><a href="[:mb:url:]">[:mb:title:]</a></h2>
[:mb:contents:]
        <div class="art_footer"></div>
      </div>
[:mb:}articles:]
$footer

END;
}

function mb_render_atom() {
  return <<<END
<?xml version="1.0" encoding="UTF-8"?>
<feed xml:lang="en-US" xmlns="http://www.w3.org/2005/Atom"> 
  <title>[:mb:main_title:]</title>
  <id>[:mb:base_abs_url:]/</id>
  <link href="[:mb:atom_abs_url:]" rel="self" />
  <link href="[:mb:base_abs_url:]/" />
  <updated>[:mb:atom_now:]</updated>
  <author><name>A. Blogger</name></author>
[:mb:articles{:]
  <entry>
    <title>[:mb:title:]</title>
    <id>[:mb:base_abs_url:]/[:mb:url:]</id>
    <link href="[:mb:base_abs_url:]/[:mb:url:]" />
    <updated>[:mb:atom_date:]</updated>
    <content type="html">
      <![CDATA[[:mb:contents:]]]>
    </content>
  </entry>
[:mb:}articles:]
</feed>

END;
}

function mb_default_article() {
  $header = mb_default_header("../..");
  $footer = mb_default_footer();
  return <<<END
$header
<div class="art">
<span class="date">[:mb:date:]</span><br />
<h2><a href="">[:mb:title:]</a></h2>
[:mb:contents:]
  <div class="art_footer"></div>
</div>
$footer
<!-- [:mb:checksum:] -->

END;
}

function mb_redirect($to = ".") {
?>
<html>
  <head>
    <title>MogBlog redirect</title>
  </head>
  <body onload="location.replace('<?php print $to; ?>');">
    <a href="<?php print $to; ?>">Klick here if page is not redirected automatically...</a><br />
    MogBlog works best with Javascript enabled!.
  </body>
</html>
<?php
  flush();
  exit(0);
}

function mb_random_name($len = 20) {
  $str = "";
  for($i=0;$i<$len;$i++) {
    $r = rand(0,35);
    if($r < 10) {
      $str .= chr($r + ord('0'));
    } else {
      $str .= chr($r - 10 + ord('a'));
    }
  }
  return $str;
}

function mb_installer() {
  global $config;

  $installer = mb_get("installer");
  $err_msg = "";

  if($installer == "1") {
    $username = mb_post("username");
    $password = mb_post("password");
    $password2 = mb_post("password2");

    if($username=="" or $password=="" or $password != $password2) {
      $err_msg = '<span style="color: red">Error: Bad input. Try again</span>';
    } else {
      $crypto = crypt($password);
      $config['admins'] = array($username => $crypto);
      $config['datadir'] = mb_random_name();
      $config['datapattern'] = 'YYYYMMDDhhmm';
      $config['publishdir'] = '..';
      $config['publishurl'] = 'http://example.com';
      $config['publishpattern'] = 'YYYYMMDDhhmm';
      $config['timeout'] = 600;
      $config['utcoffset'] = '+00:00';
      $config['main_title'] = "Powered by MogBlog";

      mb_write_config();

      setcookie("u", $username, time()+$config["timeout"]);
      setcookie("ph", $crypto, time()+$config["timeout"]);

      mb_redirect($to="?cmd=settings");
    }
  }
  mb_header();
?>
<div class="art">
<h2>Welcome to the MogBlog installer!</h2>
<?php print $err_msg; ?>
<p>Please enter a user name and a password for the site administrator</p>
<form class="std" action="?installer=1" method="post">
<span>User name:</span><input name="username" type="text" \><br \>
<span>Password:</span><input name="password" type="password" \><br \>
<span>Confirm:</span><input name="password2" type="password" \><br \>
<span></span><input type="submit" value="Done" \><br \>
</form>
</div>
<?php
  mb_footer();
  flush();
  exit(0);
}

function mb_login() {
  global $config;

  $cookie_u = mb_cookie("u");
  $cookie_ph = mb_cookie("ph");

  if($cookie_u != "") {
    if(array_key_exists($cookie_u, $config["admins"])) {
      if($config["admins"][$cookie_u] == $cookie_ph) {
	setcookie("u", $cookie_u, time()+$config["timeout"]);
	setcookie("ph", $cookie_ph, time()+$config["timeout"]);
	return;
      }
    }
  }

  $login = mb_get("login");
  $err_msg = "";

  if($login == "1") {

    sleep(2);

    $username = mb_post("username");
    $password = mb_post("password");

    if(array_key_exists($username, $config["admins"])) {
      $stored_crypto = $config["admins"][$username];
      $entered_crypto = crypt($password, $stored_crypto);
      if($stored_crypto == "" or $entered_crypto == $stored_crypto) {
	setcookie("u", $username, time()+$config["timeout"]);
	setcookie("ph", $stored_crypto, time()+$config["timeout"]);
	mb_redirect();
      }
    }
    $err_msg = '<span style="color: red">Login failed. Try again</span>';
  }

  mb_header();
?>
<div class="art">
<h2>Login</h2>
<?php print $err_msg; ?>
<form class="login" action="?login=1" method="post">
<span>User name:</span><input name="username" type="text" \><br \>
<span>Password:</span><input name="password" type="password" \><br \>
<span></span><input type="submit" value="Login" \><br \>
</form>
</div>
<?php
  mb_footer();
  flush();
  exit(0);
}

function mb_logout() {
  setcookie("u", "", time()-3600);
  setcookie("ph", "", time()-3600);
  mb_header();
?>
<div class="art">
<h2>Goodbye</h2>
<a href=".">Klick here to log in again</a>
</div>
<?php
  mb_footer();
}

function mb_posts_check_data_dir() {
  global $config;

  if(is_dir($config["datadir"])) {
    return;
  }

  $createdatadir = mb_get("createdatadir");
  if($createdatadir == "1") {
    if(mkdir($config["datadir"], 0777, true)) {
      mb_redirect();
    }

    mb_header_menu();
?>
<div class="art">
<h2>Posts</h2>
<span style="color: red">Error: Could not create Data directory! Please check permissions.</span><br />
</div>
<?php
    mb_footer_menu();
    flush();
    exit(0);
  }   

  mb_header_menu();
?>
<div class="art">
<h2>Posts</h2>
<span style="color: red">Error: Data directory "<?php print $config["datadir"]; ?>" does not exist</span><br />
<form action="?createdatadir=1" method="post">
<input type="submit" value="Create it" /></a>
</form>
</div>
<?php
  mb_footer_menu();
  flush();
  exit(0);
}


function mb_title_to_src($title)
{
  return $title;
}

function mb_src_to_title($title)
{
  return $title;
}

function mb_split_src_id($src_id) {
  $date = "";
  $title = "";
  $suffix = "";
  if(preg_match('/^(\d+)\/(.+?)(\.\w+)?$/', $src_id, $matches)) {
    $date = $matches[1];
    $title = mb_src_to_title($matches[2]);
    $suffix = $matches[3];
  }
  return array($date, $title, $suffix);
}

function mb_make_src_id($date, $title, $suffix) {
  return $date."/".mb_title_to_src($title).$suffix;
}

function mb_get_src_file($src_id) {
  global $config;

  return $config["datadir"]."/".$src_id;
}

function mb_get_src_file_dir($src_id) {
  global $config;

  list($date, $title, $suffix) = mb_split_src_id($src_id);
  return $config["datadir"]."/".$date;
}

function mb_title_to_pub($title)
{
  $title = strtolower(trim($title));
  if(strlen($title) == 0) {
    return "";
  }

  $out = "";
  $last = '*';
  $cur = '*';
  for($i=0;$i<strlen($title);$i++) {
    $cur = substr($title, $i, 1);
    if($cur == ' ') {
      if($last == ' ') {
	continue;
      }
      $out .= "-";
      $last = $cur;
      continue;
    }
    if(ord($cur) >= ord('a') && ord($cur) <= ord('z')) {
      $out .= $cur;
      $last = $cur;
      continue;
    }      
    if(ord($cur) >= ord('0') && ord($cur) <= ord('9')) {
      $out .= $cur;
      $last = $cur;
      continue;
    }      
  }
  return $out;
}
/*
function mb_pub_to_title($title)
{
  return str_replace("-", " ", $title);
}

function mb_split_pub_id($pub_id) {
  $date = "";
  $title = "";
  if(preg_match('/^(\d+)\/(.+)\/index.html$/', $pub_id, $matches)) {
    $date = $matches[1];
    $title = mb_pub_to_title($matches[2]);
  }
  return array($date, $title);
}
*/
function mb_make_pub_id($date, $title) {
  return $date."/".mb_title_to_pub($title)."/index.html";
}

function mb_get_pub_file($pub_id) {
  global $config;

  return $config["publishdir"]."/".$pub_id;
}

function mb_get_pub_file_dir($pub_id) {
  return substr(mb_get_pub_file($pub_id), 0, -11);
}

function mb_get_pub_url($pub_id) {
  return substr($pub_id, 0, -10);
}

function mb_src_id_to_pub_id($src_id) {
  list($date, $title, $suffix) = mb_split_src_id($src_id);
  return mb_make_pub_id($date, $title);
}

function mb_is_published($pub_id) {
  if($pub_id == "") {
    return false;
  }

  $pub_file = mb_get_pub_file($pub_id);

  if(!is_file($pub_file)) {
    return false;
  }
  if(!is_readable($pub_file)) {
    return false;
  }
  return true;
}

function mb_get_pub_checksum($pub_id) {
  $pub_file = mb_get_pub_file($pub_id);
  if(!is_readable($pub_file)) {
    return "";
  }
  $f = fopen($pub_file,"rb");
  if($f == false) {
    return "";
  }
  $contents = fread($f, filesize($pub_file));
  fclose($f);

  preg_match('/:mb:checksum:([0-9a-f]{32}):/', $contents, $matches);

  if(!array_key_exists(1,$matches)) {
    return "none";
  }

  return $matches[1];
}

function mb_get_src_contents($src_id) {
  $src_file = mb_get_src_file($src_id);
  if(!is_readable($src_file)) {
    return "";
  }
  $f = fopen($src_file,"rb");
  if($f == false) {
    return "";
  }
  $contents = fread($f, filesize($src_file));
  fclose($f);

  return $contents;
}

function mb_get_src_formatted($src_id) {
  global $parsedown;

  $contents = mb_get_src_contents($src_id);

  list($date, $title, $suffix) = mb_split_src_id($src_id);
  switch($suffix) {
    case ".md":
      if($parsedown) {
	return Parsedown::instance()->parse($contents);
      }
      return "<pre>$contents</pre>";
    case ".html":
      return "$contents";
    default:
      return "<pre>$contents</pre>";
  }
}

function mb_get_src_checksum($src_id) {
  $contents = mb_get_src_contents($src_id);
  if($contents == "") {
    return "";
  }
  return md5($contents);
}

function mb_format_date($date) {
  return preg_replace('/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})/', "$1-$2-$3 $4:$5", $date);
}

function mb_atom_date($date) {
  global $config;

  return preg_replace('/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})/', "$1-$2-$3T$4:$5:00".$config["utcoffset"], $date);
}

function mb_parse_date($datestr) {
  if(preg_match('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})$/', $datestr, $matches)) {
    return $matches[1].$matches[2].$matches[3].$matches[4].$matches[5];
  }
  return "";
}

function mb_atom() {
  global $config;

  $atom_file = $config["publishdir"]."/atom.xml";

  $posts = mb_get_post_listing(false);

  $out = mb_render_atom();
  $out = preg_replace_callback(
    '/\[:mb:articles{:](.*?)\[:mb:}articles:]/s',
    function ($match) use ($posts) {
      $totalout="";
      foreach($posts as $src_id => $post) {

	$contents = mb_get_src_formatted($src_id);
	/*
	$contents = preg_replace('/<.+?>/', "", $contents);
	*/
	if($contents == "")
	  continue;

	$out = $match[1];
	$out = preg_replace('/\[:mb:url:]/', mb_get_pub_url($post["pub_id"]), $out);
	$out = preg_replace('/\[:mb:atom_date:]/', mb_atom_date($post["date"]), $out);
	$out = preg_replace('/\[:mb:title:]/', mb_hesc($post["title"]), $out);
	$out = preg_replace('/\[:mb:contents:]/', $contents, $out);
	$totalout .= $out;
      }
      return $totalout;
    },
    $out
  );
  $out = preg_replace('/\[:mb:main_title:]/', mb_hesc($config["main_title"]), $out);
  $out = preg_replace('/\[:mb:base_abs_url:]/', mb_hesc($config["publishurl"]), $out);
  $out = preg_replace('/\[:mb:atom_abs_url:]/', mb_hesc($config["publishurl"]."/atom.xml"), $out);
  $out = preg_replace('/\[:mb:atom_now:]/', mb_atom_date(date("YmdHi")), $out);

  $f = fopen($atom_file,"wb");
  if($f == false) {
    return "<span style=\"color: red\">Error: Can't create target file: \"$atom_file\". Please check your permissions</span>";
  }
  fwrite($f, $out);
  fclose($f);
  return "";
}

function mb_publish() {
  global $config;

  $front_path = $config["publishdir"];
  $front_file = $front_path."/index.html";

  if(!is_dir($front_path)) {
    if(!mkdir($front_path, 0777, true)) {
      return "<span style=\"color: red\">Error: Can't create target path: \"$front_path\". Please check your permissions</span>";
    }
  }

  $posts = mb_get_post_listing(false);

  if (function_exists("mb_skin_frontpage")) {
    $out = mb_skin_frontpage();
  } else {
    $out = mb_default_frontpage();
  }
  $out = preg_replace_callback(
    '/\[:mb:articles{:](.*?)\[:mb:}articles:]/s',
    function ($match) use ($posts) {
      $totalout="";
      $cnt = 1;
      $len = count($posts);
      foreach($posts as $src_id => $post) {

	if($post["status"] == "Updated") {
	  mb_publish_article($src_id);
	}

	$contents = mb_get_src_formatted($src_id);
	if($contents == "")
	  continue;
	$out = $match[1];
	if($cnt == 1) {
	  $out = preg_replace('/\[:mb:first{:](.*?)\[:mb:}first:]/s', "$1", $out);
	  $out = preg_replace('/\[:mb:!first{:](.*?)\[:mb:}!first:]/s', "", $out);
	} else {
	  $out = preg_replace('/\[:mb:first{:](.*?)\[:mb:}first:]/s', "", $out);
	  $out = preg_replace('/\[:mb:!first{:](.*?)\[:mb:}!first:]/s', "$1", $out);
	}
	if($cnt == $len) {
	  $out = preg_replace('/\[:mb:last{:](.*?)\[:mb:}last:]/s', "$1", $out);
	  $out = preg_replace('/\[:mb:!last{:](.*?)\[:mb:}!last:]/s', "", $out);
	} else {
	  $out = preg_replace('/\[:mb:last{:](.*?)\[:mb:}last:]/s', "", $out);
	  $out = preg_replace('/\[:mb:!last{:](.*?)\[:mb:}!last:]/s', "$1", $out);
	}
	$out = preg_replace('/\[:mb:cnt:]/', "$cnt", $out);

	$out = preg_replace('/\[:mb:url:]/', mb_get_pub_url($post["pub_id"]), $out);
	$out = preg_replace('/\[:mb:date:]/', mb_format_date($post["date"]), $out);
	$out = preg_replace('/\[:mb:title:]/', mb_hesc($post["title"]), $out);
	$out = preg_replace('/\[:mb:contents:]/', $contents, $out);
	$totalout .= $out;
	$cnt+=1;
      }
      return $totalout;
    },
    $out
  );
  $out = preg_replace('/\[:mb:main_title:]/', mb_hesc($config["main_title"]), $out);
  $out = preg_replace('/\[:mb:base_abs_url:]/', mb_hesc($config["publishurl"]), $out);
  $out = preg_replace('/\[:mb:checksum:[0-9a-f]*:?]/', "", $out);

  $f = fopen($front_file,"wb");
  if($f == false) {
    return "<span style=\"color: red\">Error: Can't create target file: \"$front_file\". Please check your permissions</span>";
  }
  fwrite($f, $out);
  fclose($f);

  return mb_atom();
}

function mb_delete_article($src_id) {
  if($src_id == "") {
    return;
  }

  $src_file = mb_get_src_file($src_id);
  if(is_file($src_file)) {
    unlink($src_file);

    $dir = dirname($src_file);
    rmdir($dir);
  }
}

function mb_unpublish_article($src_id) {
  if($src_id == "") {
    return;
  }
  $pub_id = mb_src_id_to_pub_id($src_id);

  $pub_file = mb_get_pub_file($pub_id);
  if(is_file($pub_file)) {
    unlink($pub_file);

    $dir = dirname($pub_file);
    rmdir($dir);

    $dir = dirname($dir);
    rmdir($dir);
  }
}

function mb_publish_article($src_id) {
  global $config;

  list($date, $title, $suffix) = mb_split_src_id($src_id);

  $contents = mb_get_src_formatted($src_id);
  if($contents == "") {
    return "<span style=\"color: red\">Error: Problems reading source data file. Please check your permissions</span>";
  }
  $src_checksum = mb_get_src_checksum($src_id);

  $pub_id = mb_src_id_to_pub_id($src_id);
  $pub_path = mb_get_pub_file_dir($pub_id);
  $pub_file = mb_get_pub_file($pub_id);

  if(!is_dir($pub_path)) {
    if(!mkdir($pub_path, 0777, true)) {
      return "<span style=\"color: red\">Error: Can't create target path: \"$pub_path\". Please check your permissions</span>";
    }
  }

  if (function_exists("mb_skin_article")) {
    $out = mb_skin_article();
  } else {
    $out = mb_default_article();
  }
  $out = preg_replace('/\[:mb:main_title:]/', mb_hesc($config["main_title"]), $out);
  $out = preg_replace('/\[:mb:base_abs_url:]/', mb_hesc($config["publishurl"]), $out);
  $out = preg_replace('/\[:mb:url:]/', mb_get_pub_url($pub_id), $out);
  $out = preg_replace('/\[:mb:date:]/', mb_format_date($date), $out);
  $out = preg_replace('/\[:mb:title:]/', mb_hesc($title), $out);
  $out = preg_replace('/\[:mb:contents:]/', $contents, $out);
  $out = preg_replace('/\[:mb:checksum:[0-9a-f]*:?]/', "[:mb:checksum:$src_checksum:]", $out);

  $f = fopen($pub_file,"wb");
  if($f == false) {
    return "<span style=\"color: red\">Error: Can't create target file: \"$pub_file\". Please check your permissions</span>";
  }
  fwrite($f, $out);
  fclose($f);
}

function mb_get_post_status($src_id, $pub_id) {

    if(!mb_is_published($pub_id)) {
      return "Not published";
    }
    if($src_id == "") {
      return "No source";
    }
    $src_checksum = mb_get_src_checksum($src_id);
    $pub_checksum = mb_get_pub_checksum($pub_id);
    if($src_checksum != $pub_checksum) {
      return "Updated";
    }
    return "Published";
}

function mb_status_to_class($status) {
  return strtolower(str_replace(" ", "", $status));
}

function mb_are_there_updated_posts() {
  $posts = mb_get_post_listing(false);

  $found_src_id = "None";

  foreach($posts as $src_id => $post) {
    if($post["status"] == "Updated") {
      if($found_src_id != "None") {
	return "Multiple";
      }
      $found_src_id = $src_id;
    }
  }
  return $found_src_id;
}

function mb_get_post_listing($all=true, $limit=0, $offset=0) {
  global $config;

  mb_posts_check_data_dir();

  $posts = array();

  $path = $config["datadir"];
  $dh = opendir($path);
  $count = 0;
  while (($dir = readdir($dh)) !== false) {
    if($dir == "." or $dir == "..") {
      continue;
    }
    $path2 = $path."/".$dir;
    if(!is_dir($path2)) {
      continue;
    }
    $dh2 = opendir($path2);
    while (($file = readdir($dh2)) !== false) {
      if($file == "." or $file == "..") {
        continue;
      }
      $path3 = $path2."/".$file;
      if(!is_file($path3)) {
        continue;
      }
      $src_id = $dir."/".$file;
      $posts[$src_id] = array("last" => 0);
    }
    closedir($dh2);
  }
  closedir($dh);

  if(count($posts) == 0) {
    return $posts;
  }

  foreach($posts as $src_id => &$post) {

    list($date, $title, $suffix) = mb_split_src_id($src_id);

    $post["date"] = $date;
    $post["title"] = $title;
    $post["suffix"] = $suffix;

    $post["readable"] = is_readable(mb_get_src_file($src_id));

    $pub_id = mb_src_id_to_pub_id($src_id);
    $post["pub_id"] = $pub_id;

    $post["status"] = mb_get_post_status($src_id, $pub_id);
  }
  unset($post);

  if(!$all) {
    foreach($posts as $src_id => $post) {
      if($post["status"] != "Published" && $post["status"] != "Updated") {
	unset($posts[$src_id]);
      }
    }
  }

  if(count($posts) == 0) {
    return $posts;
  }

  krsort($posts);

  end($posts);
  $posts[key($posts)]["last"] = 1;
  reset($posts);

  if($limit > 0) {
    $posts = array_slice($posts, $offset, $limit);
  } else {
    if($offset > 0) {
      $posts = array_slice($posts, $offset);
    }
  }

  return $posts;
}

function mb_posts() {
  $err_msg = "";

  $new = mb_post("new");
  if($new != "") {
    mb_redirect("?cmd=edit&amp;new=1");
  }

  $do = mb_post("do");
  $do_action = mb_post("do_action");
  if($do != "") {
    switch($do_action) {
    case "publishall":
      $err_msg = mb_publish();
      break;
    case "publish":
      $count = (int)mb_post("count");
      for($i=0; $i<$count; $i++) {
	if(mb_post("chk_$i") == "on") {
	  mb_publish_article(mb_post("src_id_$i"));
	}
      }
      $err_msg = mb_publish();
      break;
    case "unpublish":
      $count = (int)mb_post("count");
      for($i=0; $i<$count; $i++) {
	if(mb_post("chk_$i") == "on") {
	  mb_unpublish_article(mb_post("src_id_$i"));
	}
      }
      $err_msg = mb_publish();
      break;
    case "delete":
      $count = (int)mb_post("count");
      for($i=0; $i<$count; $i++) {
	if(mb_post("chk_$i") == "on") {
	  mb_unpublish_article(mb_post("src_id_$i"));
	  mb_delete_article(mb_post("src_id_$i"));
	}
      }
      $err_msg = mb_publish();
      break;
    }
    if($err_msg == "") {
      mb_redirect();
    }
  }

  $posts = mb_get_post_listing();

  mb_header_menu();

  /*  print_r($posts); */
?>
<div class="art">
<form action="." method="post">
<div style="float: right;">
<input style="margin-right: 14em" type="submit" name="new" value="New post" />
<select name="do_action">
<option>----- Actions -----</option>
<option value="publishall">Publish all updated</option>
<option disabled="disabled">---------------------</option>
<option value="publish">Publish selected</option>
<option value="unpublish">Unpublish selected</option>
<option value="delete">Delete selected</option>
<!--
<option disabled="disabled">---------------------</option>
<option value="delete_all">Delete everything</option>
-->
</select>&nbsp;
<input type="submit" name="do" value="Do" />
</div>
<h2>Posts</h2>
<?php print $err_msg; ?>
<table class="posts" style="width: 100%;">
<tr style="background-color: #e0e0e0;"><th>Date</th><th style="width: 70%">Title</th><th>Status</th><th><!--<input type="checkbox" />--></th></tr>
<?php
  $count = 0;
  foreach($posts as $src_id => $post) {
    $date = $post["date"];
    $title = $post["title"];
    $suffix = $post["suffix"];
    $status = $post["status"];
    $class = mb_status_to_class($status);
?>
  <tr>
    <td style="border-bottom: solid 1px #e0e0e0"><a href="?cmd=edit&amp;src_id=<?php print mb_uesc($src_id); ?>"><?php print mb_hesc($date); ?></a></td>
    <td style="border-bottom: solid 1px #e0e0e0"><a href="?cmd=edit&amp;src_id=<?php print mb_uesc($src_id); ?>"><?php print mb_hesc($title); ?></a></td>
    <td style="border-bottom: solid 1px #e0e0e0; text-align: center;" class="status_<?php print $class; ?>"><?php print $status; ?></td>
    <td style="border-bottom: solid 1px #e0e0e0; text-align: center;">
      <input name="chk_<?php print $count; ?>" type="checkbox" />
      <input type="hidden" name="src_id_<?php print $count; ?>" value="<?php print mb_hesc($src_id); ?>" />
    </td>
  </td>
<?php
    $count++;
  }
?>
<input type="hidden" name="count" value="<?php print $count; ?>" />
</table>
<?php
  if($count == 0) {
    print "<em>&lt;None&gt;</em>\n";
  }
?>
</form>
</div>
<?php
  mb_footer_menu();
}

function mb_edit() {
  $err_msg = "";

  $src_id = "";
  $src_path = "";
  $date = date("Y-m-d H:i");
  $title = "";
  $suffix = "";
  $contents = "";
  $status = "New";
  $class = "new";

  $new = mb_get("new");
  if($new != "1") {
    $src_id = mb_get("src_id");
    list($date, $title, $suffix) = mb_split_src_id($src_id);
    $pub_id = mb_src_id_to_pub_id($src_id);
    $status = mb_get_post_status($src_id, $pub_id);
    $class = mb_status_to_class($status);
  }

  $save = mb_get("save");
  if($save == "1") {
    $contents = mb_post("contents");
    $new_date = mb_post("new_date");
    $new_title = mb_post("new_title");
    $new_suffix = mb_post("new_suffix");
    $contents = str_replace("\r", "", $contents);     /* Posted data always have \r\n as EOL */
    $saveandpublish = mb_post("saveandpublish");

    $parsed_date = mb_parse_date($new_date);
    if($parsed_date == "") {
      $err_msg .= '<span style="color: red">Error: Malformed date.</span>';
    }

    if($err_msg == "") {
      $new_src_id = mb_make_src_id($parsed_date, $new_title, $new_suffix);

      if($new != "1") {
	if($new_src_id != $src_id) {
	  if($status == "Published" || $status == "Updated") {
	    $err_msg .= '<span style="color: red">Error: Date, title or type can not be changed on published posts. Please unpublish first.</span>';
	  }
	}
      }
    }

    if($err_msg == "") {
      $new_src_dir = mb_get_src_file_dir($new_src_id);
      
      if(!is_dir($new_src_dir)) {
	if(!mkdir($new_src_dir, 0777, true)) {
	  $err_msg .= '<span style="color: red">Error: Could not create directory! Please check permissions.</span>';
	}
      }
    }

    if($err_msg == "") {
      $new_src_file = mb_get_src_file($new_src_id);

      $f = fopen($new_src_file,"wb");
      if($f != false) {
	fwrite($f, $contents);
	fclose($f);
      } else {
	$err_msg .= '<span style="color: red">Error: Cant write to content file. Please check your permissions</span>';
      }
    }

    if($err_msg == "") {
      if($new != "1") {
	if($new_src_id != $src_id) {
	  $src_file = mb_get_src_file($src_id);
	  unlink($src_file);

	  $src_dir = mb_get_src_file_dir($src_id);
	  rmdir($src_dir);
	}
      }
    }

    if($err_msg == "") {
      if($saveandpublish != "") {
	$err_msg = mb_publish_article($new_src_id);
	if($err_msg == "") {
	  $err_msg = mb_publish();
	}
      }
    }

    if($err_msg == "") {
      mb_redirect();
    }

  } else {

    if($new != "1") {
      $fail = 1;
      $src_file = mb_get_src_file($src_id);
      if(is_readable($src_file)) {
	$f = fopen($src_file,"rb");
	if($f != false) {
	  $contents = fread($f, filesize($src_file));
	  fclose($f);
	  $fail = 0;
	}
      }

      if($fail) {
	$err_msg .= '<span style="color: red">Error: Cant read content file. Please check your permissions</span>';
      }
    }

    $new_date = $date;
    $new_title = $title;
    $new_suffix = $suffix;
  }

  $readonly = " readonly=\"readonly\" ";
  if($status == "Not published" || $status == "New") {
    $readonly = "";
  }

  $publish_warning = false;
  $res = mb_are_there_updated_posts();
  if($res != "None") {
    if($res == "Multiple" || ($res != "Multiple" && $res != $src_id) ) {
      $publish_warning = true;
    }
  }

  $new_action = "";
  if($new == "1") {
    $new_action = "&amp;new=1";
  }

  mb_header_menu();
?>
<div class="art">
<h2>Edit post</h2>
<?php print $err_msg; ?>
<form class="edit" action="?cmd=edit&amp;src_id=<?php print mb_uesc($src_id); ?>&amp;save=1<?php print $new_action; ?>" method="post">
<table border="0" cellpadding="0" cellspacing="2">
 <tr>
  <td align="right">Date:</td>
  <td><input style="width: 11em;" name="new_date" type="text" value="<?php print mb_format_date($new_date); ?>" <?php print $readonly; ?> \></td>
  <td align="right">&nbsp;Title:</td>
  <td><input style="width: 40em;" name="new_title" type="text" value="<?php print mb_hesc($new_title); ?>" <?php print $readonly; ?> \></td>
 </tr>
 <tr>
  <td align="right">Type:</td>
  <td>
   <select style="width: 11em;" name="new_suffix">
    <option value=".md"<?php if($suffix==".md") print " selected"; ?>>MarkDown (.md)</option>
    <option value=".html"<?php if($suffix==".html") print " selected"; ?>>HTML (.html)</option>
    <option value=".txt"<?php if($suffix==".txt") print " selected"; ?>>Text file (.txt)</option>
   </select>
  </td>
<!--
<span>Data file:</span><input name="src" type="text" value="<?php # print mb_hesc($src_id); ?>" \><br \>
<span>Publish file:</span><input name="pub" type="text" value="<?php # print mb_hesc($pub_id); ?>" \><br \>
-->
  <td align="right">&nbsp;Status:</td>
  <td><input class="status_<?php print $class; ?>" style="width: 9em;" name="status" type="text" value="<?php print $status; ?>" readonly="readonly" \></td>
 </tr>
</table>
<textarea style="margin: 8px 0;" name="contents"><?php print mb_hesc($contents); ?></textarea><br \>
<div style="display: inline-block; width: 20px; text-align: right;"><?php if($publish_warning) { ?><img align="absmiddle" src="<?php print mb_img_warning(); ?>"><?php } ?>
</div><input type="submit" name="saveandpublish" value="Save and publish" \>&nbsp;&nbsp;&nbsp;
<input type="submit" name="saveonly" value="Save only" \>&nbsp;&nbsp;&nbsp;
<input type="reset" value="Revert" \>
<?php if($publish_warning) { ?><div style="float: right"><img align="absmiddle" src="<?php print mb_img_warning(); ?>">&nbsp;<small>All other updated posts will be published</small></div><?php } ?>
</form>
</div>
<?php
  mb_footer_menu();
}


function mb_admins_username($username) {
  global $config;

  $err_msg = "";

  $changename = mb_get("changename");
  if($changename == "1") {
    $newname = mb_post("newname");

    $err_msg = '<span style="color: red">Error: Bad input. Try again</span>';

    if($newname!="") {
      $crypto = $config["admins"][$username];
      unset($config['admins'][$username]);
      $config['admins'][$newname] = $crypto;
      mb_write_config();
      mb_redirect($to="?cmd=admins");
    }
  }

  $setpassword = mb_get("setpassword");
  if($setpassword == "1") {
    $oldpassword = mb_post("oldpassword");
    $password = mb_post("password");
    $password2 = mb_post("password2");

    $err_msg = '<span style="color: red">Error: Bad input. Try again</span>';

    if($password!="" and $password == $password2) {
      $stored_crypto = $config["admins"][$username];
      $entered_crypto = crypt($oldpassword, $stored_crypto);
      if($stored_crypto == "" or $entered_crypto == $stored_crypto) {
	$crypto = crypt($password);
	$config['admins'][$username] = $crypto;
	setcookie("u", $username, time()+$config["timeout"]);
	setcookie("ph", $crypto, time()+$config["timeout"]);
	mb_write_config();
	mb_redirect($to="?cmd=admins");
      }
    }
  }

  mb_header_menu();
?>
<div class="art">
<h2>Admin: <?php print $username; ?></h2>
<?php print $err_msg; ?>
<p>Change username</p>
<form action="?cmd=admins&username=<?php print $username; ?>&changename=1" method="post">
<span>Name:</span><input name="newname" type="text" value="<?php print $username; ?>" \><br \>
<span></span><input type="submit" value="Update name" \><br \>
</form>
<p>Change password</p>
<form action="?cmd=admins&username=<?php print $username; ?>&setpassword=1" method="post">
<span>Old password:</span><input name="oldpassword" type="password" \><br \>
<span>New password:</span><input name="password" type="password" \><br \>
<span>Confirm:</span><input name="password2" type="password" \><br \>
<span></span><input type="submit" value="Change password" \><br \>
</form>
</div>
<?php
  mb_footer_menu();
}

function mb_admins() {
  global $config;

  $username = mb_get("username");
  if(!array_key_exists($username, $config["admins"])) {
    $username = "";
  }

  if($username != "") {
    mb_admins_username($username);
    return;
  }

  mb_header_menu();
?>
<div class="art">
<h2>Admins</h2>
<p>Klick on user name to manage</p>
<ul>
<?php
  foreach(array_keys($config[admins]) as $username) {
?>
    <li><a href="?cmd=admins&username=<?php print $username; ?>"><?php print $username; ?></a></li>
<?php
  }
?>
</ul>
</div>
<?php
  mb_footer_menu();
}

function mb_settings() {
  global $config;

  $err_msg = "";

  $save = mb_get("save");

  $datadir = $config['datadir'];
  $datapattern = $config['datapattern'];
  $publishdir = $config['publishdir'];
  $publishurl = $config['publishurl'];
  $publishpattern = $config['publishpattern'];
  $timeout = $config['timeout'];
  $utcoffset = $config['utcoffset'];
  $main_title = $config['main_title'];

  if($save == "1") {
    $fail = 0;

    $datadir = mb_post("datadir");
    if($datadir != "") {
      $config['datadir'] = $datadir;
    } else {
      $fail = 1;
      $err_msg .= '<span style="color: red">Error: Bad source data directory</span><br />';
    }
    /*
    $datapattern = mb_post("datapattern");
    if($datapattern != "") {
      $config['datapattern'] = $datapattern;
    } else {
      $fail = 1;
      $err_msg .= '<span style="color: red">Error: Bad source directory pattern</span><br />';
    }
    */
    $publishdir = mb_post("publishdir");
    if($publishdir != "") {
      $config['publishdir'] = $publishdir;
    } else {
      $fail = 1;
      $err_msg .= '<span style="color: red">Error: Bad publish directory</span><br />';
    }

    $publishurl = mb_post("publishurl");
    if($publishurl != "") {
      $config['publishurl'] = $publishurl;
    } else {
      $fail = 1;
      $err_msg .= '<span style="color: red">Error: Bad base URL</span><br />';
    }
    /*    
    $publishpattern = mb_post("publishpattern");
    if($publishpattern != "") {
      $config['publishpattern'] = $publishpattern;
    } else {
      $fail = 1;
      $err_msg .= '<span style="color: red">Error: Bad publish directory pattern</span><br />';
    }
    */
    $timeout = mb_post("timeout");
    if($timeout != "") {
      $config['timeout'] = $timeout;
    } else {
      $fail = 1;
      $err_msg .= '<span style="color: red">Error: Bad session timeout</span><br />';
    }

    $utcoffset = mb_post("utcoffset");
    if($utcoffset == "") {
      $utcoffset = "+00:00";
    }
    $config['utcoffset'] = $utcoffset;
    
    $main_title = mb_post("main_title");
    if($main_title != "") {
      $config['main_title'] = $main_title;
    } else {
      $fail = 1;
      $err_msg .= '<span style="color: red">Error: Bad title</span><br />';
    }

    if(!$fail) {
      mb_write_config();
      mb_redirect();
    }
  }

  mb_header_menu();
?>
<div class="art">
<h2>Settings</h2>
<?php print $err_msg; ?>
<form class="wide" action="?cmd=settings&save=1" method="post">
<span>Source data directory:</span><input name="datadir" type="text" value="<?php print $datadir; ?>" \><br \>
<!-- <span>Source dir pattern:</span><input name="datapattern" type="text" value="<?php print $datapattern; ?>" \><br \> -->
<span>Publish directory:</span><input name="publishdir" type="text" value="<?php print $publishdir; ?>" \><br \>
<!-- <span>Publish dir pattern:</span><input name="publishpattern" type="text" value="<?php print $publishpattern; ?>" \><br \> -->
<span>Session timeout:</span><input name="timeout" type="text" value="<?php print $timeout; ?>" \><br \>
<span>UTC offset:</span>
  <select name="utcoffset">
<?php
  $offs = array("+00:00", "+01:00", "+02:00", "+03:00", "+04:00", "+05:00", "+06:00", "+07:00", "+08:00", "+09:00", "+10:00", "+11:00", "+12:00", "+13:00", "+14:00",
		"-01:00", "-02:00", "-03:00", "-04:00", "-05:00", "-06:00", "-07:00", "-08:00", "-09:00", "-10:00", "-11:00", "-12:00");
  foreach($offs as $off) {
    print "<option";
    if($off==$utcoffset) {
      print " selected";
    }
    print ">$off</option>\n";
  }
?>
  </select>
<br \>
<span>Blog title:</span><input name="main_title" type="text" value="<?php print mb_hesc($main_title); ?>" \><br \>
<span>Blog base URL:</span><input name="publishurl" type="text" value="<?php print mb_hesc($publishurl); ?>" \><br \>
<span></span><input type="submit" value="Save" \><br \>
</form>
</div>
<?php
  mb_footer_menu();
}

// -------- MAIN ----------

umask(2);

if(! $config['configured']) {
  mb_installer();
}

mb_login();

$cmd = mb_get("cmd");

switch($cmd) {
  case "logout":
    mb_logout();
    break;

  case "admins":
    mb_admins();
    break;

  case "settings":
    mb_settings();
    break;

  case "edit":
    mb_edit();
    break;

  default:
    mb_posts();
    break;
}
?>
