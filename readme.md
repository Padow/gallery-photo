# Gallery Photo

This is a web photo gallery.
You can edit the name and info of the galleries and photos, as you wish.
It allow users to send comments on your pics.
Site admin can remove some comments, if they are inappropriate

## Demo
[http://padow.livehost.fr](http://padow.livehost.fr)

## Version
1.1.0

## Tech

Gallery Photo uses a number of open source projects to work properly:

* [Bootstrap]
* [jQuery]
* [JQuery-ui]
* [Bootstrap-file-input]
* [CKEditor]
* [Blueimp Gallery] "slightly modified"

## Server requirements
* PHP 5.3
* Apache 2.2
* MySQL 5 or PostreSQL 9 

> Also tested with Nginx 1.2.1 and MariaDB 10.1

## Installation

### Copy files to your server FTP
```sh
git clone https://github.com/Padow/gallery-photo.git
```

### Nginx
Ensure that you deny access to the `config` folder in your Nginx configuration:
```conf
location /config {
  deny all;
}
```
> With Apache server, a `.htacess` is defined in the folder.

### Deploy database.

* gallery.mysql.sql

or

* gallery.pgsql.sql -> This is MADNESS

as you wish.

### Configure *.json files.

* config/sql.json --> sets param for BDD
* config/param.json --> sets title of the pages, footer contact link, title of the main page
* config/admin.json --> sets login/password for the admin tools page

## Add new gallery

With an ftp client copy the whole folder containing photographies/images on your FTP into the `photos` folder (avoid using special chars for the folder name).
Then go to your main page of the gallery.
Thumbnails will be automatically generated.

You can remove, or add a pic into a gallery, the database will automatically be updated.

With the admin tools you can modify title/subtitle of each galleries, likewise for each pics.

Pics and galleries informations can be pre-set with the [mgn-meta project] tool.



## Remove gallery

Just delete the folder from the FTP

## Admin access page

`http://<indexURL>/admin/`




[Bootstrap]:http://getbootstrap.com/
[jQuery]:http://jquery.com
[JQuery-ui]:jqueryui.com
[Bootstrap-file-input]:https://github.com/grevory/bootstrap-file-input
[CKEditor]:http://ckeditor.com/
[Blueimp Gallery]:https://github.com/blueimp/Gallery
[mgn-meta project]:https://github.com/Fragan/mgn-meta
