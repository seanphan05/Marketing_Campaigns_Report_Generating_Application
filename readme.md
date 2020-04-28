Setup Requirement 
==================
## Initial settings
1. Repository clone: https://team.compandsave.com/stash/scm/csp/campaignreports.git


2. Python installation and packages:  
Python and interpreter: Anaconda  
Anaconda Environment includes Python, interpreter, and necessary science packages, refer to https://www.anaconda.com/products/individual  
Python version: this project use Python 3 syntax.  
All required external libraries include: mailchimp3, httplib2, oauth2client, google-api-python-client, SQLAlchemy.  
To install libraries run these commands in Command Prompt:  
pip install mailchimp3  
pip install httplib2  
pip install oauth2client  
pip install SQLAlchemy  
pip install --upgrade google-api-python-client --ignore-installed six


## Add credentials at config.py
1. Date Input section: add START_DATE field for when do you want the campaign data start from. Date format should be YYYY-MM-DD


2. Google Analytics Credentials:  
Use Scope: 'https://www.googleapis.com/auth/analytics.readonly'  
Get client_secrets.json file: refer to https://console.developers.google.com/  
Set client_secrets path: path to your client secret file  
View ID: GA view ID can be found at view settings on GA web


3. Mailchimp Credentials:  
API Key: add API key  
User Name: add a Mailchimp username


4. MySQL Credentials:  
User: SQL username  
Passw: SQL password  
host: localhost or 127.0.0.1  
port: 3306 by default, can be change to another port  
database: mc_ga_db


## Change file path in refreshData.php
In line 2, change the command to 'python path\to\your\ga_mc_to_sql.py' to get PHP triggered refreshing database command.

        
