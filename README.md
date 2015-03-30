#Getting Started
1. Set up apache ( or nginx ), php, mongo db
2. php must have "mongo" module
3. Clone project into folder
4. change your apache conf ( or vhost ) documentroot to be the "main" ( same directory of index.php ) folder of the project 
5. this application routes all urls through "index.php" file with using apaches mod rewrite rules for the /main directory should look like

    <VirtualHost *:80>
	
        ServerName www.blog.local
		  DocumentRoot /opt/blogcms/main
		
        <Directory "/opt/blogcms/main">
            RewriteEngine On
			   RewriteBase /
			   RewriteCond %{REQUEST_FILENAME} !-f
			   RewriteCond %{REQUEST_FILENAME} !-d
			   RewriteRule ^(.*)$ index.php
			   
			   Options -Indexes +FollowSymLinks
			   AllowOverride None
			   Order allow,deny
			   Allow from all
        </Directory>
	
    </VirtualHost>


6. in /server/configs.php you will find many configuration settings most are obvious as to what they are and can be changed easily 
**except** categories
7. set the $GLOBALS['post_categories'] array to the categories you wish to start making postings under ( do not put spaces in category name ) **if you wish to edit a category name or remove a category read the warning in the 'things to note' section before doing so**
8. In mongo db console create a DB named "blog"
9. In the /server/includes/logins.json file set a user name and password used to enter the manager.php page ( use same format as examples ) **ATM json property "level" is not used leave at 1**
10. Navigate to { host }/manager.php in your browser and log in with credentials
11.  Start creating posts!  

##Things to note
- make sure permissions on the /pics folder and sub folders giv full permissions to the user server is running as
- in order for search to work correctly a text index must be set in the blog->posts collection in Mongo Db the command in mongo_instructions.txt must be ran in the mongo console
- until a category created in the "confings.php" file has any URLS that belong to it the category will bring up an error page.  this is expected just create a post in the category for the error not to show
- if you remove a category in the "configs.php" file and there are posts associated with that category in Mongo they will not show up on any page,  use the "Posts" posts tab on the manager page to change the category of any posts belonging the the category you intend to delete first
- if you "edit" a post form the "Posts" tab on the manager page, and wish to cancel an edit.  You must click cancel from the "Template" tab, this will exit edit mode, and allow you to start creating new posts again ( editing a post and then saving the edit will also make you exit edit mode ).  
- I can not explain what every button on manager.php does, just click around on every icon and read the messages to find out what they do!