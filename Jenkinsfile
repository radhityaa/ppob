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

        stage('Build Assets') {
            steps {
                // Compile assets menggunakan npm/yarn
                sh 'npm install'
                sh 'npm run build'
            }
        }

        stage('Deploy') {
            steps {
                // Salin kode ke direktori deployment
                sh "cp -R * ${DEPLOY_DIR}/"
                sh "cd ${DEPLOY_DIR} && composer install --no-interaction --prefer-dist --optimize-autoloader"
            }
        }

        stage('Migrate Database') {
            steps {
                // Jalankan migrasi di direktori deployment
                sh "cd ${DEPLOY_DIR} && php artisan migrate --force"
            }
        }

        stage('Restart Server') {
            steps {
                // Restart server (misalnya Nginx atau Apache) setelah deployment
                sh 'sudo systemctl restart nginx' // Sesuaikan jika menggunakan Apache
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
