provider "aws" {
  region = "ap-southeast-2"
}

resource "aws_instance" "my_ec2" {
  ami           =  "ami-0a59248a6294cece2"
  instance_type = "t3.micro"

  tags = {
    Name = "Terraform-EC2"
  }
}