# Système d'invitation (le processus complet)
1. L'invité clique sur le lien d'invitation dans la boîte mail
Ce qu'on a fait :
Le lien d'invitation contient un token qui permet de retrouver l'invitation et de vérifier si elle est valide.
Le lien redirige vers une route spécifique (/invite/{token}).

2. S'il n'est pas connecté, le forcer à se connecter
Ce qu'il reste à faire :
Vérifier si l'utilisateur est connecté. Si non, le rediriger vers la page de connexion.
Si l'utilisateur est déjà connecté, vérifier que l'adresse email correspond bien à celle utilisée pour l'invitation.

3. Vérifier s'il s'est bien connecté avec l'adresse mail sur laquelle il a reçu l'invitation
Ce qu'il reste à faire :
Après la connexion, il faut s'assurer que l'adresse e-mail de l'utilisateur connecté correspond à celle de l'invitation.
Si ce n'est pas le cas, afficher un message d'erreur ou rediriger vers une page spécifique.

4. Le diriger vers une page, dans laquelle il choisit le personnage qu'il veut utiliser pour la campagne, ou lui proposer d'en créer un autre
Ce qu'il reste à faire :
Une fois l'utilisateur connecté et validé, l'envoyer vers une page où il peut choisir un personnage existant ou en créer un nouveau.
Cette page pourrait être un formulaire qui affiche tous les personnages disponibles (qu'ils aient été créés par l'utilisateur ou par un autre).

5. Il sélectionne et valide son choix
Ce qu'il reste à faire :
Dans cette page de sélection, l'utilisateur choisit un personnage ou en crée un nouveau.
Lors de la soumission du formulaire, il faut associer le personnage à la campagne.

6. Une fois validé, le personnage du joueur a rejoint la campagne
Ce qu'il reste à faire :
Une fois que l'utilisateur a validé le choix de son personnage, il faut l'ajouter à la campagne.
Tu peux créer une méthode pour lier ce personnage à la campagne du joueur dans la base de données.

7. Le joueur peut consulter les détails de la campagne
Ce qu'il reste à faire :
Une fois l'ajout effectué, l'utilisateur est redirigé vers la page de la campagne où il peut voir les détails de la campagne, tels que les autres joueurs, les objectifs, etc.
Plan de l'implémentation :
Route d'invitation : Comme mentionné, tu as déjà une route pour gérer l'invitation avec le token. Il faut ajouter la logique de redirection en cas de non-connexion et validation du mail.

Page de sélection du personnage : Cette page doit être un formulaire qui présente les personnages disponibles ou offre la possibilité de créer un nouveau personnage. Une fois que l'utilisateur a choisi ou créé un personnage, il faut l'associer à la campagne.

Ajout du personnage à la campagne : Une fois le personnage sélectionné, tu mettras à jour la base de données pour associer ce personnage à la campagne du joueur.

Ce qu'on a déjà fait :
L'envoi de l'invitation par email avec le lien contenant le token.
La route qui vérifie le token de l'invitation.
La redirection si l'utilisateur n'est pas connecté.
Ce qu'il reste à faire :
Gérer la connexion de l'utilisateur dans la route d'invitation.
Créer la page de sélection du personnage.
Associer le personnage à la campagne.
Rediriger l'utilisateur vers la page de la campagne après ajout du personnage.