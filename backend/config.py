# -*- coding: utf-8 -*-
"""
Created on Mon Apr  6 01:26:16 2020

@author: SEAN  PHAN
"""
# Date Range
from datetime import date


START_DATE = '' # input start date
END_DATE = str(date.today())
MC_START_DATE = START_DATE + 'T00:00:00+00:00'
MC_END_DATE = END_DATE + 'T00:00:00+00:00'

# Google Analytics Credentials
SCOPES = ['https://www.googleapis.com/auth/analytics.readonly']
CLIENT_SECRETS_PATH = '' # input path to client_secrets.json file.
VIEW_ID =  '' # input view_id


# Mailchimp Credentials
API_KEY = '' # input API key
USER_NAME = '' # input username


# MySQL Credentials
user = ''
passw = ''
host =  ''
port = ''
database = ''


# functions
def ga_log():
    ga_cre = [SCOPES, CLIENT_SECRETS_PATH, VIEW_ID, START_DATE, END_DATE]
    return (ga_cre)

def mc_log():
    mc_cre = [API_KEY, USER_NAME, MC_START_DATE, MC_END_DATE]
    return (mc_cre)

def sql_log():
    sql_cre = [user, passw, host, port, database]
    return (sql_cre)