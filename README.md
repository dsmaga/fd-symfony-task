# Symfony application template

## Requirements
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Make](https://www.gnu.org/software/make/)
- [Composer](https://getcomposer.org/) (optional)
- [Git](https://git-scm.com/)

## Features
- Symfony 7.0
- PHP 8.2
- Nginx
- Docker configuration
- Makefile for common tasks
- PHP QA tools (PHPStan, PHPCS, PHPUnit, GrumPHP) + pre-commit hooks

## Installation

### Via `composer create-project`

```bash
composer create-project dsmaga/template example --repository="{\"url\": \"https://github.com/dsmaga/symfony-template.git\", \"type\": \"vcs\"}" --stability=dev
cd example
cp ./compose.override.yml.dist ./compose.override.yml
# Edit ./compose.override.yml to your needs
/usr/bin/make -f ./Makefile -C . create
git init
git add .* *
git commit -m "Initial commit"
```

### Via `git clone`

```bash
git clone https://github.com/dsmaga/symfony-template.git example
cd example
rm -rf .git
git init
cp compose.override.yaml-dist compose.override.yaml
# Edit ./compose.override.yml to your needs
/usr/bin/make -f ./Makefile -C . create
/usr/bin/make -f ./Makefile -C . install
git add .* *
git commit -m "Initial commit"
``` 

