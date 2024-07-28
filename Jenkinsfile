pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                // Checkout code from GitHub
                checkout scm
            }
        }
        stage('Install Dependencies') {
            steps {
                // Install Composer dependencies
                sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
            }
        }
        stage('Clear Cache') {
            steps {
                // Clear Laravel cache
                sh 'php artisan config:cache'
                sh 'php artisan route:cache'
                sh 'php artisan view:cache'
            }
        }
        stage('Build') {
            steps {
                // Any build steps can go here
                echo 'Build completed'
            }
        }
        stage('Deploy') {
            steps {
                // Deploy steps can go here
                echo 'Deployment completed'
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
