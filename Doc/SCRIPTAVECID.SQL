DROP DATABASE IF EXISTS MLR1;

CREATE DATABASE IF NOT EXISTS MLR1;
USE MLR1;
# -----------------------------------------------------------------------------
#       TABLE : EURO_ELEVE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS EURO_ELEVE
 (
   ELEV_NUM INTEGER NOT NULL  ,
   ID_NUM INTEGER NOT NULL  ,
   CLASSE_NUM INTEGER NOT NULL  ,
   GROU_NUM INTEGER NOT NULL  ,
   ELEV_NOM CHAR(32) NULL  ,
   ELEV_PRENOM CHAR(32) NULL  ,
   ELEV_MDP CHAR(60) NULL  ,
   ELEV_CLASSE CHAR(32) NULL  
   , PRIMARY KEY (ELEV_NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE EURO_ELEVE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_EURO_ELEVE_EURO_ID
     ON EURO_ELEVE (ID_NUM ASC);

CREATE  INDEX I_FK_EURO_ELEVE_EURO_CLASSE
     ON EURO_ELEVE (CLASSE_NUM ASC);

CREATE  INDEX I_FK_EURO_ELEVE_EURO_GROUPE
     ON EURO_ELEVE (GROU_NUM ASC);

# -----------------------------------------------------------------------------
#       TABLE : EURO_ID
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS EURO_ID
 (
   ID_NUM INTEGER NOT NULL  ,
   ID_LIBELLE CHAR(32) NULL  ,
   ID_NB INTEGER NULL  
   , PRIMARY KEY (ID_NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : EURO_GROUPE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS EURO_GROUPE
 (
   GROU_NUM INTEGER NOT NULL  
   , PRIMARY KEY (GROU_NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : EURO_NOTE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS EURO_NOTE
 (
   NOTE_NUM INTEGER NOT NULL  ,
   NOTE_TOTAL DECIMAL(4,2) NULL  ,
   NOTE_SOUSNOTE1 DECIMAL(4,2) NULL  ,
   NOTE_SOUSNOTE2 DECIMAL(4,2) NULL  ,
   NOTE_SOUSNOTE3 DECIMAL(4,2) NULL  ,
   NOTE_SOUSNOTE4 DECIMAL(4,2) NULL  ,
   NOTE_BONUS DECIMAL(4,2) NULL  ,
   NOTE_OBSERVATION CHAR(32) NULL  
   , PRIMARY KEY (NOTE_NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : EURO_CLASSE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS EURO_CLASSE
 (
   CLASSE_NUM INTEGER NOT NULL  ,
   PROF_NUM INTEGER NOT NULL  ,
   CLASSE_NIVEAU CHAR(32) NULL  ,
   CLASSE_NB_ELEVE INTEGER NULL  
   , PRIMARY KEY (CLASSE_NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE EURO_CLASSE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_EURO_CLASSE_EURO_PROF
     ON EURO_CLASSE (PROF_NUM ASC);

# -----------------------------------------------------------------------------
#       TABLE : EURO_PRESTATION
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS EURO_PRESTATION
 (
   PRES_NUM INTEGER NOT NULL  ,
   GROU_NUM INTEGER NOT NULL  ,
   ACTI_NUM INTEGER NOT NULL  ,
   PRES_INTITULE CHAR(32) NULL  ,
   PRES_DUREE INTEGER NULL  
   , PRIMARY KEY (PRES_NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE EURO_PRESTATION
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_EURO_PRESTATION_EURO_GROUPE
     ON EURO_PRESTATION (GROU_NUM ASC);

CREATE  INDEX I_FK_EURO_PRESTATION_EURO_ACTIVITE
     ON EURO_PRESTATION (ACTI_NUM ASC);

# -----------------------------------------------------------------------------
#       TABLE : EURO_PROF
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS EURO_PROF
 (
   PROF_NUM INTEGER NOT NULL  ,
   PROF_NOM CHAR(32) NULL  ,
   PROF_PRENOM CHAR(32) NULL  ,
   PROF_MDP CHAR(60) NULL  
   , PRIMARY KEY (PROF_NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : EURO_ACTIVITE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS EURO_ACTIVITE
 (
   ACTI_NUM INTEGER NOT NULL  ,
   PROF_NUM INTEGER NOT NULL  ,
   ACTI_INTITULE CHAR(32) NULL  
   , PRIMARY KEY (ACTI_NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE EURO_ACTIVITE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_EURO_ACTIVITE_EURO_PROF
     ON EURO_ACTIVITE (PROF_NUM ASC);

# -----------------------------------------------------------------------------
#       TABLE : REL_1
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS REL_1
 (
   PRES_NUM INTEGER NOT NULL  ,
   NOTE_NUM INTEGER NOT NULL  
   , PRIMARY KEY (PRES_NUM,NOTE_NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE REL_1
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_REL_1_EURO_PRESTATION
     ON REL_1 (PRES_NUM ASC);

CREATE  INDEX I_FK_REL_1_EURO_NOTE
     ON REL_1 (NOTE_NUM ASC);


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE EURO_ELEVE 
  ADD FOREIGN KEY FK_EURO_ELEVE_EURO_ID (ID_NUM)
      REFERENCES EURO_ID (ID_NUM) ;


ALTER TABLE EURO_ELEVE 
  ADD FOREIGN KEY FK_EURO_ELEVE_EURO_CLASSE (CLASSE_NUM)
      REFERENCES EURO_CLASSE (CLASSE_NUM) ;


ALTER TABLE EURO_ELEVE 
  ADD FOREIGN KEY FK_EURO_ELEVE_EURO_GROUPE (GROU_NUM)
      REFERENCES EURO_GROUPE (GROU_NUM) ;


ALTER TABLE EURO_CLASSE 
  ADD FOREIGN KEY FK_EURO_CLASSE_EURO_PROF (PROF_NUM)
      REFERENCES EURO_PROF (PROF_NUM) ;


ALTER TABLE EURO_PRESTATION 
  ADD FOREIGN KEY FK_EURO_PRESTATION_EURO_GROUPE (GROU_NUM)
      REFERENCES EURO_GROUPE (GROU_NUM) ;


ALTER TABLE EURO_PRESTATION 
  ADD FOREIGN KEY FK_EURO_PRESTATION_EURO_ACTIVITE (ACTI_NUM)
      REFERENCES EURO_ACTIVITE (ACTI_NUM) ;


ALTER TABLE EURO_ACTIVITE 
  ADD FOREIGN KEY FK_EURO_ACTIVITE_EURO_PROF (PROF_NUM)
      REFERENCES EURO_PROF (PROF_NUM) ;


ALTER TABLE REL_1 
  ADD FOREIGN KEY FK_REL_1_EURO_PRESTATION (PRES_NUM)
      REFERENCES EURO_PRESTATION (PRES_NUM) ;


ALTER TABLE REL_1 
  ADD FOREIGN KEY FK_REL_1_EURO_NOTE (NOTE_NUM)
      REFERENCES EURO_NOTE (NOTE_NUM) ;

