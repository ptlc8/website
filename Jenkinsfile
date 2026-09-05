pipeline {
    agent any

    parameters {
        string(name: 'SITEMAP_PATH', defaultValue: params.SITEMAP_PATH ?: null, description: 'Sitemap file path')
        string(name: 'AUTH_URL', defaultValue: params.AUTH_URL ?: null, description: 'Authentication server URL')
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
