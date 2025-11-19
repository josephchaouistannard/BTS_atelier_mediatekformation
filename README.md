# Mediatekformation

Ce projet fait partie d'un atelier de professionnalisation dans le cadre de mon BTS SIO SLAM.
Vous pouvez voir dans le [dépôt d'origine](https://github.com/CNED-SLAM/mediatekformation) l'application existante avant je l'ai fait évoluer.

Le site est disponbible en ligne à [mediatekformation.josephchaouistannard.com](https://mediatekformation.josephchaouistannard.com).

La documentation est disponbible à [mediatekformationdocs.josephchaouistannard.com](https://mediatekformationdocs.josephchaouistannard.com).

## Présentation
Ce site, développé avec Symfony 6.4, permet d'accéder aux vidéos d'auto-formation proposées par une chaîne de médiathèques et qui sont aussi accessibles sur YouTube.<br> 
Elle contient les fonctionnalités globales suivantes :<br>
![cas d&#39;utilisation](https://github.com/user-attachments/assets/02098210-e844-4474-ae57-ab07c4824dc0)
## Les différentes pages
Voici les 11 pages correspondant aux différents cas d’utilisation.
### Page 1 : l'accueil
Cette page présente le fonctionnement du site et les 2 dernières vidéos mises en ligne.<br>
La partie du haut contient une bannière (logo, nom et phrase présentant le but du site) et le menu permettant d'accéder aux 3 pages principales (Accueil, Formations, Playlists).<br>
Le centre contient un texte de présentation avec, entre autres, les liens pour accéder aux 2 autres pages principales.<br>
La partie basse contient les 2 dernières formations mises en ligne. Cliquer sur une image permet d'accéder à la page 3 de présentation de la formation.<br>
Le bas de page contient un lien pour accéder à la page des CGU : ce lien est présent en bas de chaque page excepté la page des CGU.<br>
![img2](https://github.com/user-attachments/assets/523b4233-3505-4b8c-9db0-5e7b72965bc6)
### Page 2 : les formations
Cette page présente les formations proposées en ligne (accessibles sur YouTube).<br>
La partie haute est identique à la page d'accueil (bannière et menu).<br>
La partie centrale contient un tableau composé de 5 colonnes :<br>
•	La 1ère colonne ("formation") contient le titre de chaque formation.<br>
•	La 2ème colonne ("playlist") contient le nom de la playlist dans laquelle chaque formation se trouve.<br>
•	La 3ème colonne ("catégories") contient la ou les catégories concernées par chaque formation (langage…).<br>
•	La 4ème colonne ("date") contient la date de parution de chaque formation.<br>
•	LA 5ème contient la capture visible sur YouTube, pour chaque formation.<br>
Au niveau des colonnes "formation", "playlist" et "date", 2 boutons permettent de trier les lignes en ordre croissant ("<") ou décroissant (">").<br>
Au niveau des colonnes "formation" et "playlist", il est possible de filtrer les lignes en tapant un texte : seuls les lignes qui contiennent ce texte sont affichées. Si la zone est vide, le fait de cliquer sur "filtrer" permet de retrouver la liste complète.<br> 
Au niveau de la catégorie, la sélection d'une catégorie dans le combo permet d'afficher uniquement les formations qui ont cette catégorie. Le fait de sélectionner la ligne vide du combo permet d'afficher à nouveau toutes les formations.<br>
Par défaut la liste est triée sur la date par ordre décroissant (la formation la plus récente en premier).<br>
Le fait de cliquer sur une miniature permet d'accéder à la troisième page contenant le détail de la formation.<br>
![img3](https://github.com/user-attachments/assets/bc033cf9-41a5-4cad-a268-8abb400965c5)
### Page 3 : détail d'une formation
Cette page n'est pas accessible par le menu mais uniquement en cliquant sur une miniature dans la page "Formations" ou une image dans la page "Accueil".<br>
La partie haute est identique à la page d'accueil (bannière et menu).<br>
La partie centrale est séparée en 2 parties :<br>
•	La partie gauche contient la vidéo qui peut être directement visible dans le site ou sur YouTube.<br>
•	La partie droite contient la date de parution, le titre de la formation, le nom de la playlist, la liste des catégories et sa description détaillée.<br>
![img4](https://github.com/user-attachments/assets/f41d05d8-5980-4dc4-9eb7-58d1c31b8a25)
### Page 4 : les playlists
Cette page présente les playlists.<br>
La partie haute est identique à la page d'accueil (bannière et menu).<br>
La partie centrale contient un tableau composé de 3 colonnes :<br>
•	La 1ère colonne ("playlist") contient le nom de chaque playlist.<br>
•	La 2ème colonne ("catégories") contient la ou les catégories concernées par chaque playlist (langage…).<br>
•	La 3ème contient le nombre de formations présent dans chaque playlist.<br>
•	La 4ème contient un bouton pour accéder à la page de présentation de la playlist.<br>
Au niveau de la colonne "playlist", 2 boutons permettent de trier les lignes en ordre croissant ("<") ou décroissant (">"). Il est aussi possible de filtrer les lignes en tapant un texte : seuls les lignes qui contiennent ce texte sont affichées. Si la zone est vide, le fait de cliquer sur "filtrer" permet de retrouver la liste complète.<br> 
Au niveau de la catégorie, la sélection d'une catégorie dans le combo permet d'afficher uniquement les playlists qui ont cette catégorie. Le fait de sélectionner la ligne vide du combo permet d'afficher à nouveau toutes les playlists.<br>
Au niveau du nombre de formation 2 boutons permettent de trier les lignes en ordre croissant ("<") ou décroissant (">").<br>
Par défaut la liste est triée sur le nom de la playlist.<br>
Cliquer sur le bouton "voir détail" d'une playlist permet d'accéder à la page 5 qui présente le détail de la playlist concernée.<br>
<img width="1317" height="667" alt="page playlists" src="https://github.com/user-attachments/assets/96c905f3-037b-4dd5-9956-5311f955bf09" />

### Page 5 : détail d'une playlist
Cette page n'est pas accessible par le menu mais uniquement en cliquant sur un bouton "voir détail" dans la page "Playlists".<br>
La partie haute est identique à la page d'accueil (bannière et menu).<br>
La partie centrale est séparée en 2 parties :<br>
•	La partie gauche contient les informations de la playlist (titre, liste des catégories, nombre de formations, description).<br>
•	La partie droite contient la liste des formations contenues dans la playlist (miniature et titre) avec possibilité de cliquer sur une formation pour aller dans la page de la formation.<br>
<img width="1315" height="799" alt="page playlist" src="https://github.com/user-attachments/assets/cb700613-8916-48e2-a373-4ee216d6766d" />

### Page 6 : la gestion des formations
Cette page est comme la pages pour consulter les formations sauf:
- Une bouton pour ajouter une nouvelle formation à été ajouté en haut à gauche.
- Le lien pour voir le détail d'une formation à été remplacé par des boutons pour modifier ou supprimer chaque formation.
<img width="1321" height="735" alt="adminformations" src="https://github.com/user-attachments/assets/786ef976-11e5-47f7-9b52-edcbaea159cb" />

### Page 7 : le formulaire d'ajout/modification d'une formation
<img width="1326" height="938" alt="formformation" src="https://github.com/user-attachments/assets/25e85a25-cff6-4c64-96db-c72af3d8b69d" />

### Page 8 : la gestion des playlists
Cette page est comme la pages pour consulter les playlists sauf:
- Une bouton pour ajouter une nouvelle playlist à été ajouté en haut à gauche.
- Le lien pour voir le détail d'une playlist à été remplacé par des boutons pour modifier ou supprimer chaque playlist.
Il est seulement possible de supprimer une playlist quand il ne contient pas de formations.
<img width="1311" height="761" alt="adminplaylists" src="https://github.com/user-attachments/assets/fb812eb8-72b5-4f17-976c-700e95432484" />

### Page 9 : le formulaire d'ajout/modification d'une playlist
<img width="1331" height="619" alt="formplaylists" src="https://github.com/user-attachments/assets/e3a57c08-8549-4d57-93b4-357a4301b1e4" />

### Page 10 : la gestion des categories
Cette page liste les catégories, avec pour chaque son nom, le nombre de formations et un bouton pour la suprimmer.<br>
On peut trier et filter les noms des catégories comme pour les formations et playlists, et on peut trier le nombre de formations.<br>
Il est seulement possible de supprimer une catégorie quand il ne contient pas de formations.
<img width="1323" height="1040" alt="admincategories" src="https://github.com/user-attachments/assets/07fc2d97-dad0-4af6-916c-b49a30ba07ed" />

### Page 11 : connexion
Ce formulaire de connexion s'affiche quand on accède à '/admin'. Il permet de se connecter à la partie gestion.
<img width="1320" height="238" alt="login" src="https://github.com/user-attachments/assets/e3ca772e-0013-434a-99ef-235409a8198e" />


## La base de données
La base de données exploitée par le site est au format MySQL.
### Schéma conceptuel de données
Voici le schéma correspondant à la BDD.<br>
![img7](https://github.com/user-attachments/assets/f3eca694-bf96-4f6f-811e-9d11a7925e9e)
<br>video_id contient le code YouTube de la vidéo, qui permet ensuite de lancer la vidéo à l'adresse suivante :<br>
https://www.youtube.com/embed/<<<video_id>>>
### Relations issues du schéma
<code><strong>formation (id, published_at, title, video_id, description, playlist_id)</strong>
id : clé primaire
playlist_id : clé étrangère en ref. à id de playlist
<strong>playlist (id, name, description)</strong>
id : clé primaire
<strong>categorie (id, name)</strong>
id : clé primaire
<strong>formation_categorie (id_formation, id_categorie)</strong>
id_formation, id_categorie : clé primaire
id_formation : clé étrangère en ref. à id de formation
id_categorie : clé étrangère en ref. à id de categorie</code>

Remarques : 
Les clés primaires des entités sont en auto-incrémentation.<br>
Le chemin des images (des 2 tailles) n'est pas mémorisé dans la BDD car il peut être fabriqué de la façon suivante :<br>
"https://i.ytimg.com/vi/" suivi de, soit "/default.jpg" (pour la miniature), soit "/hqdefault.jpg" (pour l'image plus grande de la page d'accueil).
<br><br>
**Ce schema n'a pas été modifie au cours des évolutions. Il y a juste une table 'User' en plus qui contient les comptes administrateurs.**

## Test de l'application en local
- Vérifier que Composer, Git et Wamserver (ou équivalent) sont installés sur l'ordinateur.
- Télécharger le code et le dézipper dans www de Wampserver (ou dossier équivalent) puis renommer le dossier en "mediatekformation".<br>
- Ouvrir une fenêtre de commandes en mode admin, se positionner dans le dossier du projet et taper "composer install" pour reconstituer le dossier vendor.<br>
- Dans phpMyAdmin, se connecter à MySQL en root sans mot de passe et créer la BDD 'mediatekformation'.<br>
- Récupérer le fichier mediatekformationfinale.sql en racine du projet et l'utiliser pour remplir la BDD (si vous voulez mettre un login/pwd d'accès, il faut créer un utilisateur, lui donner les droits sur la BDD et il faut le préciser dans le fichier ".env" en racine du projet).<br>
- De préférence, ouvrir l'application dans un IDE professionnel. L'adresse pour la lancer est : http://localhost/mediatekformation/public/index.php<br>
