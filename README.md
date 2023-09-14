# technical_assessment

## This site is set up to run using ddev.
https://ddev.readthedocs.io/en/latest/users/install/ddev-installation/

It is based on Drupal 10.1.3 and php 8.1.16.

## Setting up the site locally.
Clone the site from Github using: 'git clone git@github.com:nmowat/technical-assessment.git'
cd into /technical-assessment
Run 'ddev config'
Press enter to accept the default Project name and doc root, then enter 'drupal10' for the project type.

On a configuration complete message, you can start the site with 'ddev start'
Run 'git checkout -f flood-report-custom-module'
Then run 'composer install'
You can access the site at https://technical-assessment.ddev.site
You may need to run through the Drupal install
The database created during install is sufficient.
Config can be imported with 'ddev drush cim'

The flood report page is at https://technical-assessment.ddev.site/flood-report
