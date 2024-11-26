pipeline {
    agent any

    parameters {
        string(name: 'HCAPTCHA_SITEKEY', defaultValue: params.HCAPTCHA_SITEKEY ?: null, description: 'Hcaptcha site key')
        string(name: 'HCAPTCHA_SECRET', defaultValue: params.HCAPTCHA_SECRET ?: null, description: 'Hcaptcha secret')
        string(name: 'SITEMAP_PATH', defaultValue: params.SITEMAP_PATH ?: null, description: 'Sitemap file path')
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
