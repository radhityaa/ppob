pipeline {
    agent any

    environment {
        // Define environment variables if needed
        DEPLOY_DIR = '/var/www/ayasyatech.com/ppob' // Ganti dengan direktori deployment Anda
    }

    stages {
        stage('Checkout Code') {
            steps {
                // Tarik kode dari repositori git
                git branch: 'main', url: 'https://github.com/radhityaa/ppob.git'
            }
        }

        stage('Install Dependencies') {
            steps {
                // Install composer dependencies
                sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
            }
        }

        stage('Deploy') {
            steps {
                // Salin kode ke direktori deployment
                sh "cp -R * ${DEPLOY_DIR}/"
                sh "cd ${DEPLOY_DIR} && composer install --no-interaction --prefer-dist --optimize-autoloader"
            }
        }

        stage('Clear Cache') {
            steps {
                sh "php artisan optimize"
                sh "php artisan optimize:clear"
            }
        }

        stage('Migrate Database') {
            steps {
                // Jalankan migrasi di direktori deployment
                sh "cd ${DEPLOY_DIR} && php artisan migrate --force"
            }
        }
    }

    post {
        success {
            echo 'Deployment successful!'
        }
        failure {
            echo 'Deployment failed!'
        }
    }
}
