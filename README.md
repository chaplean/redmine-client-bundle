# Getting Started With ChapleanRedmineClientBundle

![Codeship Status for chaplean/redmine-invoice-client-bundle](https://app.codeship.com/projects/8f675b50-97b1-0135-385d-161fd251d857/status?branch=master)

# Prerequisites

This version of the bundle requires Symfony 2.8+.

# Installation

## 1. Composer

```bash
composer require chaplean/redmine-client-bundle
```

## 2. AppKernel.php

Add

```php
new Chaplean\Bundle\RedmineClientBundle\ChapleanRedmineClientBundle(),
```

# Configuration

## 1. config.yml

```yml
imports:
    - { resource: '@ChapleanRedmineClientBundle/Resources/config/config.yml' }
```

## 2. paramters.yml

```yml
chaplean_redmine_client.url: 'your redmine url'
chaplean_redmine_client.access_token: 'your access token'
```

#Available functions:

* Projects
	* getProjects()
	* getProject()
	* putProjects()
	* deleteProjects()

* Users
	* getUsers()
	* getUser()
	* postUsers()
	* putUsers()
	* deleteUsers()

* Issues
	* getIssues()
	* getIssue()
	* postIssues()
	* putIssues()
	* deleteIssues()

* Times
	* getTimes()
	* getTime()
	* postTimes()
	* putTimes()
	* deleteTimes()
