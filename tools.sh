#!/bin/bash

read -p "Enter 'service name' to execute port forwarding: " input

if [[ $input == "argocd" ]]; then
    echo "mot de passe: $(kubectl get secret argocd-initial-admin-secret -n argocd --template={{.data.password}} | base64 -d)"
    kubectl port-forward service/argocd-server 8443:443 -n argocd
elif [[ $input == "nginx" ]]; then
    kubectl port-forward service/nginx 9012:80 -n argocd
else
    echo "port-forward du service/$input si possible"
    kubectl port-forward service/$input 32101:80 -n argocd
fi
    

