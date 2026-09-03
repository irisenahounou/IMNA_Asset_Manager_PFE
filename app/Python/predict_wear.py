import sqlite3
import pandas as pd
import json
import os
import sys
from datetime import datetime


def analyser_et_sauvegarder_materiel(db_path):
    if not os.path.exists(db_path):
        print(json.dumps({"error": "Base de données introuvable au chemin : " + db_path}))
        return

    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    
    # Requête pour récupérer le matériel, le nombre de pannes et les correctives
    query = """
        SELECT 
            m.id as id_materiel, 
            m.nom_equipement, 
            m.date_achat,
            COUNT(DISTINCT p.id) as nombre_pannes,
            COUNT(DISTINCT c.id_corrective) as interventions_correctives
        FROM Materiel m
        LEFT JOIN Panne p ON m.id = p.id_materiel
        LEFT JOIN Corrective c ON p.id = c.id_panne
        GROUP BY m.id, m.nom_equipement, m.date_achat
    """
    
    try:
        df = pd.read_sql_query(query, conn)
    except Exception as e:
        print(json.dumps({"error": "Erreur SQL : " + str(e)}))
        conn.close()
        return

    resultats = []
    current_year = datetime.now().year

    for index, row in df.iterrows():
        id_materiel = row['id_materiel']
        nb_pannes = row['nombre_pannes']
        nb_correctives = row['interventions_correctives']
        
        # Calcul de l'âge du matériel
        try:
            annee_achat = int(str(row['date_achat'])[:4])
            age = current_year - annee_achat
        except:
            age = 0

        # Algorithme d'indice d'usure
        score_usure = (age * 5) + (nb_pannes * 20) + (nb_correctives * 10)
        score_usure = min(float(score_usure), 100.0)
        
        # Logique de recommandation / statut
        statut_recommande = "Bon état"
        if score_usure >= 75:
            statut_recommande = "Critique - Remplacement conseillé"
        elif score_usure >= 50:
            statut_recommande = "Usure modérée - Maintenance préventive requise"

        # --- SAUVEGARDE DIRECTE DANS LA BASE DE DONNÉES ---
        cursor.execute("""
            UPDATE Materiel 
            SET score_usure = ?, statut_usure = ? 
            WHERE id = ?
        """, (score_usure, statut_recommande, id_materiel))

        resultats.append({
            "id_materiel": str(id_materiel),
            "nom_equipement": str(row['nom_equipement']),
            "nombre_pannes": int(nb_pannes),
            "score_usure": score_usure,
            "recommandation": statut_recommande
        })

    # Valider les modifications en base et fermer la connexion
    conn.commit()
    conn.close()

    # Renvoyer le JSON (utilisé par la commande Artisan)
    print(json.dumps(resultats))

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Chemin de la base de données non fourni en argument."}))
        sys.exit(1)
    analyser_et_sauvegarder_materiel(sys.argv[1])