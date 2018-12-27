# Gallery Photo

This is a web photo gallery.
You can edit the name and info of the galleries and photos, as you wish.
It allow users to send comments on your pics.
Site admin can remove some comments, if they are inappropriate

## Demo
[http://padow.livehost.fr](http://padow.livehost.fr)

## Version
1.2.0

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
* MySQL 5, PostreSQL 9 or SQLite 3

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

* gallery.pgsql.sql

or

* `cat gallery.sqlite3.sql | sqlite3 gallery.db` (`<dbname>.db`)

as you wish.

### Configure `*.json` files.

* config/sql.json --> sets param for BDD
* config/param.json --> sets title of the pages, footer contact link, title of the main page
* config/admin.json --> sets login/password for the admin tools page

## Docker

- Build the Docker's image:

```sh
docker build -t gallery .
```

- Edit the `TODO` from the `docker-compose.yml`

- Run the application:

```sh
docker-compose up
# or
docker-compose up -d
```

- Run database migration:

```
docker exec -it gallery-photo_postgres_1 ash

/ # psql template1 postgres
template1=# CREATE DATABASE gallery WITH ENCODING 'utf-8';
CREATE DATABASE
template1-# \q

/ # psql gallery postgres
====> Paste the `gallery.pgsql.sql` content
```

## Add new gallery

With an ftp client copy the whole folder containing photographies/images on your FTP into the `galleries` folder (avoid using special chars for the folder name).
Then go to the admin page and click on update button.
Thumbnails will be automatically generated.

You can remove, or add a pic into a gallery, the database will automatically be updated.

With the admin tools you can modify title/subtitle of each galleries, likewise for each pics.

Pics and galleries informations can be pre-set with the [mgn-meta project] tool.

<details>
<summary>metadata.json example</summary>


```json
{
  "title": "BATEAUX en DÉTAILS I",
  "description": "",
  "images": [
    {
      "filename": "IMG_01.jpg",
      "title": "PARE-BATTAGE VINTAGE",
      "description": ""
    },
    {
      "filename": "IMG_02.jpg",
      "title": "FRANCE ",
      "description": "La TRINITE sur MER"
    }
  ]
}
```


</details>

> Special note: if the gallery already contains a `thumbs` folder, the gallery won't be added.

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
