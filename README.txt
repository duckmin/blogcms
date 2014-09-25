Mongo Branch will port blogcms to Mongo DB instead of MySql

Blog CMS is a way to manage a blog ( or any paginated content ) through a GUI interface.
To get setup you want Mysql 5+ PHP5+ and A web server ( I recommend Apache ).

*Once everything is installed and started you need to create your DB follow the mysql_instructions.txt for all the commands you need 
to set up blog DB and the posts table with some sample data.

*Clone this repo and navigate to the /main/index.php page.
This is the base URL.

*All configs for the application are in /server/configs.php
-find the variable $GLOBALS['base_url'] and change it to the URL you are using to access the index.php page
-set $GLOBALS['db_user'] & $GLOBALS['db_password']  to whichever user pw combo you setup for your DB installation
-example $GLOBALS['base_url'] = "http://localhost:8080/blogcms/main/"

*also set, var base_url = <example>; to the same url you set as base_url in the configs file in the javascript file /scripts/globals.js  

*you can add/edit/delete postings in the manager.php page
users and passwords for this page are set in the configs.php file 
edit that variable to set your username and password used to login to manager page using the examples provided as a template

*postings made are sorted by category allowing you to have posts only show up in one category at a time
 you can add as many categories as you wish through the configs file
-NOTE:  if you remove a category and have postings currently marked as within the deleted category they will not show up until you
recategorize your posting.

*I would recommend before any category is deleted to first 
-switch posts within that category to another using the manage.php interface
-then delete the old category once no posts belong to it.

*I made the default style pretty plain allowing for alot of customization to the style of the blog, feel free to edit 
blog_style.css to adjust the look and feel of the appliction.  ( I do not recommend changing the css of the manager page!! )
