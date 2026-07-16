-- Activation des clés étrangères
PRAGMA foreign_keys = ON;

-- 1. Insertion d'un Service
INSERT INTO Service (nom_service) VALUES ('Direction des Systèmes d''Information (DSI)');

-- 2. Insertion des Utilisateurs (Table Mère)
-- Mot de passe fictif 'password123' (sera hashé plus tard en Laravel)
INSERT INTO Utilisateur (id, nom, prenom, email, mot_passe, statut_compte) VALUES 
(1, 'Nahounou', 'Irise', 'irise.responsable@imna.com', 'password123', 1),
(2, 'Dupont', 'Jean', 'jean.technicien@imna.com', 'password123', 1),
(3, 'Martin', 'Alice', 'alice.employe@imna.com', 'password123', 1);

-- 3. Liaisons d'héritage (Tables Filles)
INSERT INTO Responsable (id_responsable, id_utilisateur) VALUES (1, 1);
INSERT INTO Technicien (id_technicien, disponibilite, id_utilisateur) VALUES (2, 1, 2);
INSERT INTO Employe (id_employe, id_utilisateur) VALUES (3, 3);

-- 4. Insertion de Matériels (Liés au Service 1 et au Responsable 1)
INSERT INTO Materiel (id, numero_serie, nom_equipement, type, date_achat, etat_operationnel, id_service, id_responsable) VALUES 
('MAT-402', 'SN-SRV-99283', 'Serveur Rack 04', 'Infrastructure', '2025-01-15 08:00:00', 'Fonctionnel', 1, 1),
('MAT-305', 'SN-IMP-11029', 'Imprimante 3D Ultimaker', 'Périphérique', '2025-06-10 10:30:00', 'Fonctionnel', 1, 1);

-- 5. Insertion d'une Panne (Déclarée par l'employé 3 sur le matériel 'MAT-402')
INSERT INTO Panne (id, titre, description, date_declaration, statut, id_materiel, id_employe) VALUES 
(1, 'Surchauffe Processeur', 'Le serveur Rack 04 s''éteint de manière intempestive suite à une alerte de température.', '2026-07-16 14:00:00', 'Ouvert', 'MAT-402', 3);

-- 6. Insertion d'une Réparation Corrective (Assignée au Technicien 2 pour la Panne 1)
INSERT INTO Reparation (id, date_debut, date_fin, rapport_technique, id_panne, id_technicien) VALUES 
('REP-2026-001', '2026-07-16 15:30:00', NULL, NULL, 1, 2);

INSERT INTO Corrective (id_corrective, id_panne) VALUES 
('REP-2026-001', 1);