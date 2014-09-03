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


/*
This is an example skin, the MogBlog default theme but with additional
comments.

A skin for MogBlog contains at least of one file: skin.php (this file),
and it must be placed next to the main engine index.php file.
skin.php should contain at least these two functions: mb_skin_frontpage()
and mb_skin_article().

This example skin is broken down in functions the same way as the default
skin in MogBlog but you are free to structure it any way you like it.

It is recommended that all global variables and functions you define have
'mb_skin_' in the beginning of the name to avoid name clashes with the
MogBlog engine itself and optional extra components loaded by the engine.

The MogBlog engine and admin is designed to be self-contained, so it does
not refer to external images, CSS files, JavaScript libraries etc. You
are completely free to use external files and resources for your skin,
but you have to manage these yourself, upload to the server etc. If you
make a redistributable skin pack for MogBlog, please include external
files and write some documentation about how things should be set up for
it to work. Remember that MogBlog may be installed and run from any
sub directory on a server, you should not assume that it always operate
from the site root directory.
*/

function mb_skin_style() {
  /* This function returns a string with the in-line CSS. */
  return <<<END
body {
  background-color: #F0F0F0;
  background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAAA \
    AACoBHk5AAAAAXNSR0IArs4c6QAAACVJREFUCNcFwTESACAMwzDDZemU/z+zk0eQzluZux2MF \
    IOUjNIPrBoKCWyToNYAAAAASUVORK5CYII=');
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

function mb_skin_header($rooturl=".") {
  /*
   This function returns a string containing the header part of a page,
   including the title bar and menu.

   The $rooturl argument is the relative path to the front page. This makes
   this functon usable from both mb_skin_frontpage() and mb_skin_article().

   This function uses macros in the string, see below for documentation
   of what they do.
 */
  $style = mb_skin_style();
  return <<<END
<html>
  <head>
    <title>[:mb:main_title:]</title>
    <style type="text/css">
$style
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="$rooturl/atom.xml" type="application/atom+xml" rel="alternate"
     title="ATOM Feed" />
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
<!--            <li><a href="$rooturl">About</a></li>-->
          </ul>
        </div>
      </div>
END;
}

function mb_skin_footer() {
  /* Returns the footer */
  return <<<END
    </div>
  </body>
</html>
END;
}

function mb_skin_frontpage() {
  /*
   This is one of the functions actually invoked from MogBlog when publishing
   stuff. It should return a string with HTML for the front page.

   You have some placeholder and iteration macros you can use that expands
   to article data during the publish. All macros begin with the sequence
   "[:mb:" and end with ":]".

   Some macros are only available inside other macros.

   [:mb:main_title:]   - This macro is availabe anywhere on the page and
                         expands to the main title of the blog as configured
		         in the admin settings.
   [:mb:base_abs_url:] - The base URL as configured in the admin settings.
                         Useful for creating absolute paths. Note: there is
                         no trailing slash on this URL.

   The front page string has one top-level macro block pair:
   [:mb:articles{:] and 
   [:mb:}articles:]

   These two marks the beginning and end of the section that is repeated
   for every published post.

   Inside the "articles" block you have these macros defined:

   [:mb:date:]     - The date and time of the post (YYYY-MM-DD hh:mm)
   [:mb:url:]      - Relative URL to link to this specific posts's page
   [:mb:title:]    - The title of the post
   [:mb:contents:] - The contents. MarkDown or other intermediary format is
                     converted to HTML before inserted here.
   [:mb:cnt:]      - An article count, the first post on this page has the
                     number 1

   You also have sub-blocks within the article block. You may use the other
   macros above within these blocks.

   [:mb:first{:],  - This block will be rendered for the first post of the
   [:mb:}first:]     page.

   [:mb:!first{:], - This block will be rendered for all but the first post
   [:mb:}!first:]    of the page.

   [:mb:last{:],   - This block will be rendered for the last post of the
   [:mb:}last:]      page.

   [:mb:!last{:],  - This block will be rendered for all but the last post of
   [:mb:}!last:]     the page.


   The sub-blocks are suited if you for example want a <hr> line strictly
   between posts. Put the <hr> in the end of the article block inside a
   "!last" block, and it will not be drawn for the last post etc.
  */

  $header = mb_skin_header();
  $footer = mb_skin_footer();
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

function mb_skin_article() {
  /*
   This is the other function that is actually invoked from MogBlog when
   publishing stuff. It should return a string with HTML for a page of a
   specific article.

   The macros you can use here are the same as for the front page rendering,
   but you should not use the "articles" block here as this function only
   render a single article. The "first", "!first", "last", "!last"  and 
   "cnt" macros are also useless here.

   You can use these macros:

   [:mb:main_title:]   - This macro expands to the main title of the blog as
                         configured in the admin settings.
   [:mb:base_abs_url:] - The base URL as configured in the admin settings.
                         Useful for creating absolute paths. Note: there is
                         no trailing slash on this URL.
   [:mb:date:]         - The date and time of the post (YYYY-MM-DD hh:mm)
   [:mb:url:]          - Relative URL from the root directory to this
                         specific post.
   [:mb:title:]        - The title of the post
   [:mb:contents:]     - The contents. MarkDown is converted to HTML before
                         inserted here

   !! IMPORTANT !!

   One additional macro MUST be put inside this string/page somewhere:
   [:mb:checksum:]

   This macro is replaced with a checksum and this is used to track if you
   have made changes to the post but not re-published it. If you do not add
   this on the page, posts will never get the green status of "Published" in
   the admin.

   It is totally ok and even recommended to put this macro inside a HTML
   comment like this:  <!-- [:mb:checksum:] --> so it will be hidden by the
   browser.

   After expanding all macros, the final HTML string will be put into a HTML
   file. This file is always created in a specific path. First a directory is
   created based on the date in the post. Inside this a directory another
   directory is created based on the title of the post. Strange characters
   are removed in this name and spaces are repaced by '-' making it search
   engine optimized. The HTML file is put inside this title directory, as
   index.html.

   If you want to use relative links from the posts, you need to have this in
   mind as the article HTML is put two steps down in subdirectories. Either
   you should prepend all URLs with "../../" to get to the same level as the
   front page, or use some other technique like the <base> tag in HTML.
  */

  $header = mb_skin_header("../..");  // compensate relative URLs
  $footer = mb_skin_footer();
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
?>
