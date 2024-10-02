pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                // Checkout code from GitHub directly to the correct directory
                dir('/var/www/ayasyatech.com/ppob') {
                    checkout scm
                }
            }
        }
        stage('Pull Request') {
            steps {
                dir('/var/www/ayasyatech.com/ppob') {
                    sh 'git pull origin main'
                }
                echo 'Pulling completed'
            }
        }
        stage('Install Dependencies') {
            steps {
                dir('/var/www/ayasyatech.com/ppob') {
                    // Install Composer dependencies
                    sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
                }
            }
        }
        stage('Build') {
            steps {
                dir('/var/www/ayasyatech.com/ppob') {
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
                dir('/var/www/ayasyatech.com/ppob') {
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
