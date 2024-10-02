pipeline {
    agent any

    stages {
        stage('Clone Repository') {
            steps {
                git branch: 'main', url: 'https://github.com/radhityaa/ppob.git'
            }
        }
        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
            }
        }
        stage('Build') {
            steps {
                sh 'export PATH=$PATH:/usr/local/bin && npm install'
                sh 'npm run build'
            }
        }
        stage('Deploy to Local VPS') {
            steps {
                // Jalankan perintah deploy langsung di VPS
                sh """
                cd /var/www/ayasyatech.com/ppob
                git pull origin main
                composer install --no-interaction --prefer-dist --optimize-autoloader
                npm install
                php artisan migrate --force
                """
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
