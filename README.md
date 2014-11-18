#Getting Started
1. Set up apache, php, mongo db
2. Clone project into folder
3. Either change your apache configs documentroot to be the "main" ( same directory of index.php ) folder of the project 
**OR**
edit the configs.php & globals.js "base_url" variable to use the url address that points to "main/index.php"
4. this application routes all urls through "index.php" file with using apaches mod rewrite rules for the /main directory should look like

  Directory "/var/www/html/main/"
  
    RewriteEngine On
    
    RewriteBase /
    
    RewriteCond %{REQUEST_FILENAME} !-f
    
    RewriteCond %{REQUEST_FILENAME} !-d
    
    RewriteRule ^(.*)$ index.php
    
  /Directory

5. In mongo db console create a DB named "blog"
6. In the configs.php file set a user name and password used to enter the manager.php page, you can also make categories that are used to organize your posts and decide which items will show up in the header of your page
7. Navigate to /manager.php in your browser and log in with credentials
8.  Start creating posts!  

##Things to note
- make sure permissions on the /pics folder and sub folders have correct permissions
- the folders that show up with in the pics folder on the "Pictures" tab in the manager page are dynamic. Folders can just be added at the server level and they will show up in the "Pictures" tab
- in order for search to work correctly a text index must be set in the blog->posts collection in Mongo Db the command in mongo_instructions.txt must be ran in the mongo console
- until a category created in the "confings.php" file has any URLS that belong to it the category will bring up an error page.  this is expected just create a post in the category for the error not to show
- if you remove a category in the "configs.php" file and there are posts associated with that category in Mongo they will not show up on any page,  use the "Posts" posts tab on the manager page to change the category of any posts belonging the the category you intend to delete first
- if you "edit" a post form the"Posts" tab on the manager page, and wish to cancel an edit.  You must first preview the post, then click cancel this will allow you to create new posts again and you will exit edit mode 
