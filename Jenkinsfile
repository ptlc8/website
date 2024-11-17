pipeline {
    agent any

    parameters {
        string(name: 'HCAPTCHA_SITEKEY', description: 'Hcaptcha site key', defaultValue: '')
        string(name: 'HCAPTCHA_SECRET', description: 'Hcaptcha secret', defaultValue: '')
        string(name: 'SITEMAP_PATH', description: 'Sitemap file path', defaultValue: '')
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
