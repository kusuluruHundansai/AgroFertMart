pipeline {
    agent any

    environment {
        IMAGE_NAME = "hundansai/agrofertmart:v1"
    }

    stages {

        stage('Clone Repository') {
            steps {
                git 'https://github.com/kusuluruHundansai/AgroFertMart.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                bat 'docker build -t %IMAGE_NAME% .'
            }
        }

        stage('Push Docker Image') {
            steps {
                bat 'docker push %IMAGE_NAME%'
            }
        }
    }
}