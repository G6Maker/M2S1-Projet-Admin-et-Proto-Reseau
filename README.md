# M2S1-Projet-Admin-et-Proto-Reseau

## <u> Installation des paquets necessaires </u> 

Pour cela, il suffit de lancer le playbook ansible:
```sh
sudo ansible-playbook -k playbook.yaml
```

## <u> Création du cluster avec terraform </u>

```sh
#initialisation de terraform (installe les providers et ressources nécessaires)
terraform init
#Crée le cluster
terraform apply
```

### Avant de travailler !
Récupérer les modifications:
```sh
git pull
```

### Pour faire un commit sur git
Pour ajouter les fichiers modifiers:
```sh
git add <fichiers>
```

Pour créer le commit:
```sh
git commit -m "<msg>"
```

Pour envoyer le commit:
```sh
git push
```