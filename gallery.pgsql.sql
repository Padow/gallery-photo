-- psql template1 postgres
-- =>  CREATE DATABASE gallery WITH ENCODING 'utf-8';
--
-- psql gallery postgres

CREATE TABLE "about" (
  "id" INTEGER NOT NULL ,
  "content" TEXT NULL ,
  PRIMARY KEY ("id")
);

CREATE TABLE "galleries" (
  "id" SERIAL NOT NULL ,
  "folder" TEXT NULL ,
  "name" TEXT NOT NULL ,
  "subtitle" TEXT NULL ,
  "thumb" TEXT NULL ,
  PRIMARY KEY ("id"),
  UNIQUE ("folder")
);

CREATE TABLE "pictures" (
  "id" SERIAL NOT NULL ,
  "gallery" INTEGER NOT NULL DEFAULT 0,
  "name" TEXT NOT NULL ,
  "info" TEXT NOT NULL ,
  "nbcomment" INTEGER NOT NULL DEFAULT 0,
  "link" TEXT NOT NULL ,
  "thumb" TEXT NULL ,
  PRIMARY KEY ("id"),
  UNIQUE ("link"),
  FOREIGN KEY ("gallery") REFERENCES "galleries" ( "id" ) ON UPDATE RESTRICT ON DELETE RESTRICT
);
CREATE INDEX "pictures_FK__galleries" ON "pictures" ("gallery");

CREATE TABLE "comments" (
  "id" SERIAL NOT NULL ,
  "gallery" INTEGER NOT NULL ,
  "pics" INTEGER NOT NULL ,
  "author" TEXT NOT NULL ,
  "date" TIMESTAMP NOT NULL DEFAULT now(),
  "comment" TEXT NOT NULL ,
  "ip" TEXT NOT NULL ,
  PRIMARY KEY ("id"),
  FOREIGN KEY ("pics") REFERENCES "pictures" ( "id" ) ON UPDATE RESTRICT ON DELETE RESTRICT
);
CREATE INDEX "comments_FK_comments_pictures" ON "comments" ("gallery");
CREATE INDEX "comments_FK_comments_pictures_2" ON "comments" ("pics");
