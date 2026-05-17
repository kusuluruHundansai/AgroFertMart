pipeline {
    agent any

    environment {
        IMAGE_NAME = "hundansai/agrofertmart:v1"
    }

    stages {

        stage('Clone Repository') {
            steps {
                git branch: 'main',
                url: 'https://github.com/kusuluruHundansai/AgroFertMart.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t %IMAGE_NAME% .'
            }
        }

        stage('Push Docker Image') {
            steps {
                sh 'docker push %IMAGE_NAME%'
            }
        }
    }
}