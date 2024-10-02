pipeline {
    agent any

    environment {
        // Define any environment variables here
        TARGET_DIR = '/var/www/ayasyatech.com/ppob'
    }

    stages {
        stage('Pull Request') {
            steps {
                dir("${TARGET_DIR}") {
                    sh 'git pull origin main'
                }
                echo 'Pulling completed'
            }
        }
        stage('Install Dependencies') {
            steps {
                dir("${TARGET_DIR}") {
                    // Install Composer dependencies
                    sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
                }
            }
        }
        stage('Build') {
            steps {
                dir("${TARGET_DIR}") {
                    // Any build steps can go here
                    sh """
                    npm install
                    php artisan migrate --force
                    """
                    echo 'Build completed'
                }
            }
        }
        stage('Clear Cache') {
            steps {
                dir("${TARGET_DIR}") {
                    // Clear cache
                    sh 'php artisan optimize'
                    sh 'php artisan optimize:clear'
                }
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
