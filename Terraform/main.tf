terraform {
  required_providers {
    #Kubernetes IN Docker = KIND
    #This is the maintain provider for KIND
    #https://github.com/tehcyx/terraform-provider-kind
    kind = {
      source = "tehcyx/kind"
      version = "0.2.1"
    }
    #The provider for kubectl is for manage the cluster using yaml file
    kubectl = {
      source  = "gavinbunney/kubectl"
      version = "1.14.0"
    }
  }
}

provider "kind" {}

resource "kind_cluster" "default" {
  name = "proj-1"
  wait_for_ready = true
  kind_config {
    kind = "Cluster"
    api_version = "kind.x-k8s.io/v1alpha4"

    node {
      role = "control-plane"
      extra_port_mappings {
        container_port = 80
        host_port      = 8080
      }
    }

    #https://kind.sigs.k8s.io/docs/user/quick-start/
    node {
      role = "worker"
      image = "kindest/node:v1.27.1"
    }

    node {
      role = "worker"
      image = "kindest/node:v1.27.1"
    }

    node {
      role = "worker"
      image = "kindest/node:v1.27.1"
    }
  }
}

provider "helm" {
  kubernetes {
    host = kind_cluster.default.endpoint
    cluster_ca_certificate = kind_cluster.default.cluster_ca_certificate
    client_certificate = kind_cluster.default.client_certificate
    client_key = kind_cluster.default.client_key
  }
}

resource "time_sleep" "wait_150_seconds" {
  create_duration = "150s"
}

resource "helm_release" "argocd" {
  name  = "argocd"
  depends_on = [time_sleep.wait_150_seconds]

  repository       = "https://argoproj.github.io/argo-helm"
  chart            = "argo-cd"
  namespace        = "argocd"
  version          = "5.52.1"
  create_namespace = true

}

resource "helm_release" "argocd-apps" {
  name  = "argocd-apps"

  repository       = "https://argoproj.github.io/argo-helm"
  chart            = "argocd-apps"
  namespace        = "argocd"
  version          = "1.4.1"

#  values = [
#    file("argocd/application.yaml")
#  ]

  depends_on = [helm_release.argocd]
}