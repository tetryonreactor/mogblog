BUGS
====

Bugs and bad behavior. Put everything in this list, small and big.

  * 2014-09-09: Installation with cookies disabled is failing, you only get
    to the login page after creating the user. Halt and error needed.

  * 2014-09-09: Saving and publishing that is failing on creating the
    directory at the publishing step will still save the article. 
    Correcting the permissions and re-doing save and publish will save
    a duplicate post in the data dir. Permissions should be checked
    before saving.

  * 2014-09-09: Markdown can be selected as a source format even though
    Parsedown.php is not installed.

  * 2014-09-09: Editing a post for a long while will time out the admin
    session. The login page will be reached at saving, but when logging
    in again all that was written is lost. Going back in the browser
    and save again on the editing page may work, but some better method
    is needed. JavaScript may be used to do an emergency save when the
    session is near the timeout. Periodic auto-saves like in Wordpress
    will also work.
