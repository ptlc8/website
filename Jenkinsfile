pipeline {
    agent any

    parameters {
        string(name: 'SITEMAP_PATH', defaultValue: params.SITEMAP_PATH ?: null, description: 'Sitemap file path')
        string(name: 'SITE_NAME', defaultValue: params.SITE_NAME ?: null, description: 'Site name')
        string(name: 'SITE_DESCRIPTION', defaultValue: params.SITE_DESCRIPTION ?: null, description: 'Site description')
        string(name: 'SITE_KEYWORDS', defaultValue: params.SITE_KEYWORDS ?: null, description: 'Site keywords')
        string(name: 'SITE_AUTHOR', defaultValue: params.SITE_AUTHOR ?: null, description: 'Site author')
        string(name: 'HCAPTCHA_SITEKEY', defaultValue: params.HCAPTCHA_SITEKEY ?: null, description: 'Hcaptcha site key')
        string(name: 'HCAPTCHA_SECRET', defaultValue: params.HCAPTCHA_SECRET ?: null, description: 'Hcaptcha secret')
    }

    stages {
        stage('Build') {
            steps {
                sh 'docker compose build'
            }
        }
        stage('Deploy') {
            steps {
                sh 'docker compose up --remove-orphans -d'
            }
        }
    }
}
