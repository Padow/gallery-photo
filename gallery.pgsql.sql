CREATE TABLE "about" (  "id" INTEGER NOT NULL ,
  "content" TEXT NULL ,
  PRIMARY KEY ("id")
); 

CREATE TABLE "galleries" (  "id" SERIAL NOT NULL ,
  "folder" VARCHAR(255) NULL ,
  "name" VARCHAR(255) NOT NULL ,
  "subtitle" VARCHAR(255) NULL ,
  PRIMARY KEY ("id"),
  UNIQUE ("folder")
); 

CREATE TABLE "pictures" (  "id" SERIAL NOT NULL ,
  "gallery" INTEGER NOT NULL DEFAULT 0,
  "name" VARCHAR(255) NOT NULL ,
  "info" TEXT NOT NULL ,
  "nbcomment" INTEGER NOT NULL DEFAULT 0,
  "link" VARCHAR(255) NOT NULL ,
  "thumb" VARCHAR(255) NULL ,
  PRIMARY KEY ("id"),
  UNIQUE ("link"),FOREIGN KEY ("gallery") REFERENCES "galleries" ( "id" ) ON UPDATE RESTRICT ON DELETE RESTRICT
); 
CREATE INDEX "pictures_FK__galleries" ON "pictures" ("gallery");

CREATE TABLE "comments" (  "id" SERIAL NOT NULL ,
  "gallery" INTEGER NOT NULL ,
  "pics" INTEGER NOT NULL ,
  "author" VARCHAR(50) NOT NULL ,
  "date" TIMESTAMP NOT NULL DEFAULT now(),
  "comment" TEXT NOT NULL ,
  "ip" VARCHAR(50) NOT NULL ,
  PRIMARY KEY ("id"),FOREIGN KEY ("pics") REFERENCES "pictures" ( "id" ) ON UPDATE RESTRICT ON DELETE RESTRICT
); 
CREATE INDEX "comments_FK_comments_pictures" ON "comments" ("gallery");
CREATE INDEX "comments_FK_comments_pictures_2" ON "comments" ("pics");

