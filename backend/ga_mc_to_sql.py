
# -*- coding: utf-8 -*-
"""
Created on Fri Apr  3 12:48:41 2020

@author: SEAN  PHAN
"""

#!/usr/bin/env python
import pandas as pd
import mailchimp_api
import ga_api
import config
import pymysql
from sqlalchemy import create_engine
import data_cleanup
try:
    # get GA data
    ga_data = ga_api.get_ga()
    ga_data['GA Tracking Code'] = ga_data['GA Tracking Code'].str.lower()
    ga_data.insert(0, 'GA Tracking', ga_data['GA Tracking Code'].str[11:], True)
    ga_data1 = ga_data.groupby('GA Tracking')['Users', 'Sessions', 'Bounce Rate', 'ECR (%)','Transactions','Revenue ($)'].sum().reset_index()
    
    # get MC data
    mc_data = mailchimp_api.get_mc()[0]
    mc_data = mc_data[mc_data['Campaign Title'].str.contains('CAS', case=True)]
    mc_data['GA Tracking'] = mc_data['GA Tracking'].str.lower()
    
    # combine and modify data
    # merger 2 data source 
    com_data = pd.merge(mc_data, ga_data1, on='GA Tracking', how='inner')
    # reduce unsued columns (GA Tracking, GA Tracking Code, Users, Bounce Rate)
    com_data = com_data.drop(['GA Tracking','GA Tracking Code','Users','Bounce Rate'], axis=1)
    
    # Add Months, Promo Name, RPE (Revenue Per Emails Sent) columns
    com_data.insert(0, 'Months', com_data['Campaign Title'].str[:3], True)
    com_data.insert(1, 'Years', com_data['Campaign Title'].apply(lambda st: st[st.find('20'):st.find('20')+4]))
    com_data.insert(2, 'Promo Name', com_data['Campaign Title'].apply(lambda st: st[st.find('Promo'):st.find('20')+4]))
    com_data.insert(3, 'Segment Name', com_data['Campaign Title'].apply(lambda st: st[st.find('CAS'):]))
    
    # clean 2019 data
    clean_data = data_cleanup.clean(com_data)
    
    #com_data.loc[com_data['Campaign Title'].str.contains('Reminder', case=True), 'Promo Name'] = com_data['Promo Name'].astype(str) + '- Reminder'
    clean_data['RPE ($)'] = clean_data['Revenue ($)']/clean_data['Emails Sent']
    clean_data = clean_data[['Months', 'Years', 'Promo Name', 'Segment Name', 'Segments', 'Launch Date', 'Campaign Title', 'Emails Sent', 'Unique Opens', 
                    'Unique Open Rate (%)', 'Unique Clicks', 'Unique Click Rate (%)', 'Bounces', 'Deliverability Rate (%)',
                    'Bounce Rate (%)', 'Unsub', 'Unsub Rate (%)', 'Spam', 'Spam Rate (%)', 'Sessions', 'ECR (%)','Transactions','Revenue ($)', 'RPE ($)']]
    
    # Push data on MySQL
    # get SQL credentials
    sql_cre = config.sql_log()
    user = sql_cre[0]
    passw = sql_cre[1]
    host = sql_cre[2]
    port = sql_cre[3]
    database = sql_cre[4]
    
    # connect to MySQL
    engine = create_engine('mysql+pymysql://' + user + ':' + passw + '@' + host + ':' + port + '/' + database , echo=False)
    # write db to MySQL
    clean_data.to_sql(name='temp_mc_ga', con=engine, if_exists = 'replace', index=False)
    
    if (engine.dialect.has_table(engine.connect(), "mc_ga")):
        with engine.begin() as conn:
            conn.execute('ALTER TABLE mc_ga RENAME TO old_mc_ga')
            conn.execute('ALTER TABLE temp_mc_ga RENAME TO mc_ga')
            conn.execute('DROP TABLE old_mc_ga')
            print('success')
    elif (engine.dialect.has_table(engine.connect(), "old_mc_ga")):
        with engine.begin() as conn:
            conn.execute('ALTER TABLE temp_mc_ga RENAME TO mc_ga')
            conn.execute('DROP TABLE old_mc_ga')
            print('success')
    else:
        with engine.begin() as conn:
            conn.execute('ALTER TABLE temp_mc_ga RENAME TO mc_ga')
            print('success')

except Exception as err:
    print (err)
