xcow_b
======

xcow_b is the base of the xcow suite. The xcow suite is a set of tools to build *X*ml based *CO*ntent for the *W*eb.

Xcow_b is the first tool to be released in januari 2006. It consists of the base of a websystem. It is centered around a model, view and controller mechanism to display webpages.

The master initialization file is etc/xcow_b.ini.

Control:
There is only one page available on the document root, this is the master controller and is called 'control.php'. The controller takes care of the following functions:
* master logging
* database connection
* processing HTTP requests, like parameters in the query string
* it also processes XML requests (to be used for webservices and the like)
* building a response for a typical model and view (with extensions!)
* session handling
* access control (with anonymous login!)

The mapping of the controller to the model and view is specified in de control/ini.php file. This file determines the actions that the controller executes.

Model:
First control is given to the model. This is where the actual calculations take place. The base model is the directory model/xcow_b. This model takes care of acces control and error handling.

View:
The corresponding base view is the directory view/xcow_b. There are views for html responses, but also for xml responses.


Listing
=======

INSTALL.txt: how to install the xcow_b tool
README.txt: this file
cgi-bin: script directory (is empty)
data: directory with all kinds of information (to be explained later)
htdocs: the documentroot of the webserver, with the 'control.php' file.
license: the license
save: a directory where information from the web is stored, like session infomration and logging

the data directory
------------------

control: the controller actions in the file ini.php
db: this is a directory with database tools to fill an initial database.
etc: a directory with configuration files, like xcow_b.ini
extensions: the directory where extensions on the model, view controller principle can be placed
lib: library files
model: the model, with an xcow_b model and a user model for your stuff
view:, the view, with a xcow_b view and a user view for your stuff


Now What?
=========

- Read the license in 'license'
- Read the documents in 'doc' if you are interest in the working of all things
- install! read INSTALL.txt


have fun :-)

--------------------
Herman van Dompseler
herman@dompseler.nl
