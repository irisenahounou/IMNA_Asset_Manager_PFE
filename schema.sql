PRAGMA foreign_keys = ON;
--Table services
create table if NOT EXISTS Service (
    id_service INTEGER PRIMARY KEY AUTOINCREMENT,
    nom_service VARCHAR(255) NOT NULL
);

--table utilisateurs(table mère)
create Table IF NOT EXISTS Utilisateur (
    id_utilisateur INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) unique NOT NULL,
    mot_passe VARCHAR(255) NOT NULL,
    statut_compte boolean DEFAULT 1 NOT NULL

);

--table employe(table fille)

create table IF NOT EXISTS Employe(
    id_employe INTEGER PRIMARY KEY,
    foreign key (id_employe) REFERENCES Utilisateur(id_utilisateur) on delete CASCADE


);

--table responsable(table fille)

create Table if NOT EXISTS Responsable(
    id_responsable INTEGER PRIMARY KEY,
    disponibilite boolean DEFAULT 1 NOT NULL,
    foreign KEY (id_responsable) REFERENCES Utilisateur(id_utilisateur) on delete CASCADE


);

--table technicien(table fille)

create Table IF NOT EXISTS Technicien(
    id_technicien INTEGER PRIMARY KEY,
    disponibilite boolean DEFAULT 1 NOT NULL,
    foreign KEY (id_technicien) REFERENCES Utilisateur(id_utilisateur) on delete cascade

);

--table materiel

create Table IF NOT EXISTS Materiel(
    id VARCHAR(50) PRIMARY KEY,
    numero_serie VARCHAR(100) unique NOT NULL,
    nom_equipement VARCHAR(50) NOT NULL,
    type VARCHAR(50) NOT NULL,
    date_achat DATETIME NOT NULL,
    etat_operationnel VARCHAR(50) DEFAULT 'fonctionnel' NOT NULL,
    id_service INTEGER not NULL,
    id_responsable INTEGER NOT NULL,
    foreign KEY (id_service) REFERENCES Service(id_service) on delete RESTRICT,
    foreign KEY (id_responsable) REFERENCES Responsable(id_responsable) on delete RESTRICT

);

-- table panne

create table IF NOT EXISTS Panne (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titre VARCHAR(255) NOT null,
    description TEXT NOT NULL,
    date_declaration DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'Ouvert' NOT NULL, --Ouvert, En cours, Résolu
    id_materiel VARCHAR(50) NOT NULL,
    id_employe INTEGER NOT NULL,
    foreign KEY (id_materiel) REFERENCES Materiel(id) on delete CASCADE,
    foreign KEY (id_employe) REFERENCES Employe(id_employe) on delete CASCADE

);

-- table reparation (table mere des interventions)

create Table IF NOT EXISTS Reparation(
    id VARCHAR(50) PRIMARY KEY,
    date_debut DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_fin DATETIME,
    rapport_technique TEXT,
    id_panne INTEGER,
    id_technicien INTEGER NOT NULL,
    foreign KEY (id_panne) REFERENCES Panne(id) on delete SET NULL,
    foreign KEY (id_technicien) REFERENCES Technicien(id_technicien) on delete RESTRICT

);

--table corrective (heritage de reparation)

create TABLE IF NOT EXISTS Corrective(
    id_corrective VARCHAR(50) PRIMARY KEY,
    id_panne INTEGER NOT NULL,
    foreign KEY (id_corrective) REFERENCES Reparation(id) on delete CASCADE,
    foreign KEY (id_panne) REFERENCES Panne(id) on delete CASCADE

);

--table preventive (heritage de raparation)
create TABLE IF NOT EXISTS Preventive(
    id_preventive VARCHAR(50) PRIMARY KEY,
    id_panne INTEGER,
    frequence_jour INTEGER NOT NULL,
    prochaine_rep DATE NOT NULL,
    foreign KEY (id_preventive) REFERENCES Reparation(id) on delete CASCADE,
    foreign KEY (id_panne) REFERENCES Panne(id) on delete SET NULL


);
