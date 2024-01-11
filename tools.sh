#!/bin/bash

service_name=$1

if [[ $service_name == "argocd" ]]; then
    echo "mot de passe: $(kubectl get secret argocd-initial-admin-secret -n argocd --template={{.data.password}} | base64 -d)"
    kubectl port-forward service/argocd-server 8443:443 -n argocd
elif [[ $service_name == "nginx" ]]; then
    kubectl port-forward service/nginx 9012:80 -n argocd
else
    echo "port-forward du service/$service_name si possible"
    kubectl port-forward service/$service_name 32101:80 -n argocd
fi

