pipeline {
    agent any

    environment {
        // Define any environment variables here
        TARGET_DIR = '/www/wwwroot/ayasyatech.store'
    }

    stages {
        stage('Checkout') {
            steps {
                dir("${TARGET_DIR}") {
                    bat "git pull origin main"
                }
            }
        }

        stage('Install Dependencies') {
            steps {
                bat "cd ${TARGET_DIR} && composer install"
            }
        }

        stage('Clear Cache') {
            steps {
                bat "cd ${TARGET_DIR} && php artisan cache:clear"
                bat "cd ${TARGET_DIR} && php artisan config:clear"
                bat "cd ${TARGET_DIR} && php artisan route:clear"
            }
        }
    }

    post {
        success {
            echo 'Build succeeded!'
        }
        failure {
            echo 'Build failed!'
        }
    }
}
