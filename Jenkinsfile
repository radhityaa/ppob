pipeline {
    agent any

    stages {
        stage('Directory') {
            steps {
                sh 'cd ~/var/www/ayasyatech.com/ppob/'
            }
        }
        stage('Checkout') {
            steps {
                // Checkout code from GitHub
                checkout scm
            }
        }
        stage('Pull Request') {
            steps {
                sh 'git pull origin main'
                echo 'Pulling completed'
            }
        }
        stage('Install Dependencies') {
            steps {
                // Install Composer dependencies
                sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
            }
        }
        stage('Build') {
            steps {
                // Any build steps can go here
                sh """
                npm install
                php artisan migrate --force
                """
                echo 'Build completed'
            }
        }
        stage('Clear Cache') {
            steps {
                // Clear cache
                sh 'php artisan optimize'
                sh 'php artisan optimize:clear'
            }
        }
    }

    post {
        always {
            // Clean workspace after build
            cleanWs()
        }
        success {
            // Notify success
            echo 'Build and deployment successful'
        }
        failure {
            // Notify failure
            echo 'Build or deployment failed'
        }
    }
}
