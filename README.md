epub_generator
==============
which is a tool that generates fixed layout epub files, (at the moment) optimized for the ipad.


**INSTALLATION**

- copy files to server
- create database, note settings, add these to system/connect.php
- use included database structure.sql to create tables

READY

- open browser > visit installation location 
- add pages. Note that pages are added per two. Like in a real book.
- when done, use 'generate epub' link to generate your epub structure.
- use ftp to visit installation, generated files are in system/ folder, named 'test-HH-MM-SS'
- you might now want to check out the package.opf file, and set the title, language, author and other metatags
- if a page has a background image, inclide it in the OEBPS/images folder, using the page001.jpg naming convention.

- right now all the templates for the xhtml files are in system/generate.php, so check that out if you want to change them beforehand. 
- when content, zip files (the order is important, 'mimetype' should be the first file) rename .zip to .epub, run epubcheck (https://code.google.com/p/epubcheck/) and load onto ipad.
