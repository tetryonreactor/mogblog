MogBlog
=======

A small PHP based blog engine rendering static content.

Goals
-----

  * Generate static content
  * Web admin on-line, no custom tools, deploy scripts etc.
  * Built in PHP, as it is available everywhere
  * The whole thing as a single PHP file
  * No SQL database
  * Simple yet fully usable
  * Customisable

Requirements
------------

  * A web server site that can run PHP.
  * FTP access or other method to initially create the admin directory on the
    site and to upload the index.php file.
  * PHP scripts must be allowed to browse and modify files and directories
    within the site.

Features
--------
  * Blog posts can be created, edited and deleted
  * Posts can be worked on internally and later be published
  * Support for creating posts as plain text or HTML (no fancy editor yet)
  * Markdown can be used if [parsedown.php](http://www.parsedown.org/) is downloaded and put into the
    admin directory next to index.php.
  * Primitive Atom (RSS) feed

Installation
------------

  1. Log into the site with FTP or via some other transfer method.
  2. Create your admin directory. You should give it a name that is not easy
     for a potential hacker to guess, but a name you will remember. The
     admin page will also be protected by password, but the best way to
     keep hacking attacks away is that the path to admin is never guessed.
  3. Upload index.php into your admin directory.
  4. Open your browser and surf into your site and the admin directory
     you created. E.g. http://mycoolsite.com/longsecretadmindirectory/
  5. Follow the installation wizard

Known issues and missing stuff
------------------------------

  * No support yet for uploading images or other files. You need to upload
    such things manually for now. File handling feature is planned.
  * Custom skinning requires modifying the HTML and style inside the PHP
    file. Pluggable skins are also planned.
  * Only one admin user can be configured.
  * No paging or "archives". All posts are listed on the front page as of now.
  * Changing time zone will make all posts appear as new in the Atom feed.
  * No auto-saving or revision handling (backup) of changes.
  * Better documentation
