db.trajets_geolocalisation.drop();

db.createCollection("trajets_geolocalisation");

// Creation d'index pour les performances
db.trajets_geolocalisation.createIndex({"covoiturage_id": 1}, {unique: true});
db.trajets_geolocalisation.createIndex({"point_depart": "2dsphere"});
db.trajets_geolocalisation.createIndex({"point_arrivee": "2dsphere"});
db.trajets_geolocalisation.createIndex({"date_creation": -1});