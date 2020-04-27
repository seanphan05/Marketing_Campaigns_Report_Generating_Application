# -*- coding: utf-8 -*-
"""
Created on Thu Apr 16 11:50:14 2020

@author: SEAN  PHAN
"""
import numpy as np

def clean(data):
    jan1 = (data['Months']=='Jan') & (data['Years']=='2019')
    feb2 = (data['Months']=='Feb') & (data['Years']=='2019')
    mar3 = (data['Months']=='Mar') & (data['Years']=='2019')
    apr4 = (data['Months']=='Apr') & (data['Years']=='2019')
    may5 = (data['Months']=='May') & (data['Years']=='2019')
    jun6 = (data['Months']=='Jun') & (data['Years']=='2019')
    jul7 = (data['Months']=='Jul') & (data['Years']=='2019')
    aug8 = (data['Months']=='Aug') & (data['Years']=='2019')
    sep9 = (data['Months']=='Sep') & (data['Years']=='2019')
    oct10 = (data['Months']=='Oct') & (data['Years']=='2019')
    nov11 = (data['Months']=='Nov') & (data['Years']=='2019')
    dec12 = (data['Months']=='Dec') & (data['Years']=='2019')
 
   
    # Conditions
    seg_less = data['Segment Name'].str.contains('Less Active', case=True)
    seg_remind = (data['Segment Name'].str.contains('Reminder', case=True))
    seg_most = data['Segment Name'].str.contains('Most Active', case=True)
    seg_once = data['Segment Name'].str.contains('Once', case=True)
    seg_twice = data['Segment Name'].str.contains('Twice', case=True)
    seg_inactive = data['Segment Name'].str.contains('Inactive', case=True)
    seg_no_pid = data['Segment Name'].str.contains('No PID', case=True)
    jun_has_pid = data['Campaign Title'].str.contains('With', case=True)
    nov_promo = data['Campaign Title'].str.contains('November 2 Black Friday 2019', case=True)
    dec_promo = data['Campaign Title'].str.contains('December Cyber Week 2019', case=True)
      
    
    # January   
    data.loc[jan1 & seg_less, 'Segments'] = 'Less Active List'
    data.loc[jan1 & seg_remind & (~seg_less), 'Segments'] = 'Most Active List-Reminder'
    data.loc[jan1 & seg_most & (~seg_remind), 'Segments'] = 'Most Active List'
    data.loc[jan1 & seg_once, 'Segments'] = 'Once List'
    data.loc[jan1 & seg_twice, 'Segments'] = 'Twice List'
    
    

    # February
    data.loc[feb2 & seg_less & (~seg_remind), 'Segments'] = 'Less Active List'
    data.loc[feb2 & seg_remind & (~seg_less), 'Segments'] = 'Most Active List-Reminder'
    data.loc[feb2 & seg_remind & seg_less, 'Segments'] = 'Less Active List-Reminder'
    data.loc[feb2 & seg_most & (~seg_remind), 'Segments'] = 'Most Active List'
    data.loc[feb2 & seg_once, 'Segments'] = 'Once List'
    data.loc[feb2 & seg_twice, 'Segments'] = 'Twice List'

 
    # March
    data.loc[mar3 & seg_less & (~seg_remind), 'Segments'] = 'Less Active List'
    data.loc[mar3 & seg_remind & seg_less, 'Segments'] = 'Less Active List-Reminder'
    data.loc[mar3 & seg_remind & seg_most, 'Segments'] = 'Most Active List-Reminder'
    data.loc[mar3 & seg_most & (~seg_remind), 'Segments'] = 'Most Active List'
    data.loc[mar3 & seg_once, 'Segments'] = 'Once List'
    data.loc[mar3 & seg_twice, 'Segments'] = 'Twice List'
    

    # April
    data.loc[apr4 & seg_less, 'Segments'] = 'Less Active List'
    data.loc[apr4 & seg_most, 'Segments'] = 'Most Active List'
    data.loc[apr4 & seg_once, 'Segments'] = 'Once List'
    data.loc[apr4 & seg_twice, 'Segments'] = 'Twice List'
    

    # May
    data.loc[may5 & seg_remind & seg_less, 'Segments'] = 'Less Active List-Reminder'
    data.loc[may5 & seg_remind & (~seg_less), 'Segments'] = 'Most Active List-Reminder'
    data.loc[may5 & seg_less & (~seg_remind), 'Segments'] = 'Less Active List'
    data.loc[may5 & seg_most, 'Segments'] = 'Most Active List'
    
      
    # June
    data.loc[jun6 & jun_has_pid & seg_less & seg_most, 'Promo Name'] = 'Promo 1 2019'
    data.loc[jun6 & jun_has_pid & seg_less, 'Segments'] = 'Has PID-Most & Less Active List'   
    data.loc[jun6 & seg_inactive, 'Segments'] = 'Has PID-Inactive List'
    data.loc[jun6 & seg_once & seg_twice, 'Segments'] = 'Has PID-Once & Twice List'
    data.loc[jun6 & (~jun_has_pid) & seg_remind & seg_most, 'Segments'] = 'No PID-Most Active List-Reminder'
    data.loc[jun6 & (~jun_has_pid) & seg_remind & seg_less, 'Segments'] = 'No PID-Less Active List-Reminder'
    data.loc[jun6 & (~jun_has_pid) & seg_inactive, 'Segments'] = 'No PID-Inactive'
    data.loc[jun6 & (~jun_has_pid) & (~seg_remind) & seg_most, 'Segments'] = 'No PID-Most Active List'
    data.loc[jun6 & (~jun_has_pid) & (~seg_remind) & seg_less, 'Segments'] = 'No PID-Less Active List'
    data.loc[jun6 & (~jun_has_pid) & seg_once & seg_twice, 'Segments'] = 'No PID-Once & Twice List'

     
    # July
    data.loc[jul7 & seg_no_pid & seg_most, 'Segments'] = 'No PID-Most Active List'
    data.loc[jul7 & seg_no_pid & seg_less, 'Segments'] = 'No PID-Less Active List'
    data.loc[jul7 & (~seg_no_pid), 'Segments'] = 'Has PID-Most & Less Active List'
    
             
    # August
    data.loc[aug8, 'Segments'] = data.loc[aug8, 'Segment Name']
    data.loc[aug8, 'Segments'] = data.loc[aug8, 'Segments'].replace('[\(\)]', '', regex=True).astype(str)
    data.loc[aug8, 'Segments'] = data.loc[aug8, 'Segments'].str.replace('CAS SKU ', 'Has SKU-')
    data.loc[aug8, 'Segments'] = data.loc[aug8, 'Segments'].str.replace('CAS No PID ', 'No PID-')
    data.loc[aug8, 'Segments'] = data.loc[aug8, 'Segments'].str.replace('CAS Has PID ', 'Has PID-')
    data.loc[aug8, 'Segments'] = data.loc[aug8, 'Segments'].astype(str) + ' List'
    
    
    # September
    
    data.loc[sep9, 'Segments'] = data.loc[sep9, 'Segment Name']
    data.loc[sep9, 'Segments'] = data.loc[sep9, 'Segments'].replace('[\(\)]', '', regex=True).astype(str)
    data.loc[sep9, 'Segments'] = data.loc[sep9, 'Segments'].str.replace('CAS SKU ', 'Has SKU-')
    data.loc[sep9, 'Segments'] = data.loc[sep9, 'Segments'].str.replace('CAS no PID ', 'No PID-')
    data.loc[sep9, 'Segments'] = data.loc[sep9, 'Segments'].str.replace('CAS Has PID ', 'Has PID-')
    data.loc[sep9, 'Segments'] = data.loc[sep9, 'Segments'].str.replace('CAS Regular ', 'No PID-')
    data.loc[sep9, 'Segments'] = data.loc[sep9, 'Segments'].str.replace(' - AB Testing', '')
    data.loc[sep9, 'Segments'] = data.loc[sep9, 'Segments'].astype(str) + ' List'
  
    
    # October
    data.loc[oct10, 'Segments'] = data.loc[oct10, 'Segment Name']
    data.loc[oct10, 'Segments'] = data.loc[oct10, 'Segments'].replace('[\(\)]', '', regex=True).astype(str)
    data.loc[oct10, 'Segments'] = data.loc[oct10, 'Segments'].str.replace('CAS SKU Email ', 'Has SKU-')
    data.loc[oct10, 'Segments'] = data.loc[oct10, 'Segments'].str.replace('CAS PID Email ', 'Has PID-')
    data.loc[oct10, 'Segments'] = data.loc[oct10, 'Segments'].str.replace('CAS Regular ', 'No PID-')
    data.loc[oct10, 'Segments'] = data.loc[oct10, 'Segments'].astype(str) + ' List'
    
    
    # November
    data.loc[nov11 & nov_promo,'Promo Name'] = 'Promo 2 2019'
    data.loc[nov11 & nov_promo & data['Segment Name'].str.contains('Has SKU', case=True),'Segments'] = 'Has SKU-Most & Less Active List'
    data.loc[nov11 & nov_promo & data['Segment Name'].str.contains('Has PID', case=True),'Segments'] = 'Has PID-Most & Less Active List'
    data.loc[nov11 & nov_promo & data['Segment Name'].str.contains('Regular', case=True),'Segments'] = 'No PID-Most & Less Active List'
    data.loc[nov11 & (~nov_promo), 'Segments'] = data.loc[nov11 & (~nov_promo), 'Segment Name']
    data.loc[nov11 & (~nov_promo), 'Segments'] = data.loc[nov11 & (~nov_promo), 'Segments'].replace('[\(\)]', '', regex=True).astype(str)
    data.loc[nov11 & (~nov_promo), 'Segments'] = data.loc[nov11 & (~nov_promo), 'Segments'].str.replace('CAS SKU Email ', 'Has SKU-')
    data.loc[nov11 & (~nov_promo), 'Segments'] = data.loc[nov11 & (~nov_promo), 'Segments'].str.replace('CAS PID Email ', 'Has PID-')
    data.loc[nov11 & (~nov_promo), 'Segments'] = data.loc[nov11 & (~nov_promo), 'Segments'].str.replace('CAS Regular ', 'No PID-')
    data.loc[nov11 & (~nov_promo), 'Segments'] = data.loc[nov11 & (~nov_promo), 'Segments'].astype(str) + ' List'
        
        
    # December
    data.loc[dec12 & dec_promo,'Promo Name'] = 'Promo 1 2019'
    data.loc[dec12 & seg_remind, 'Segments'] = 'No PID-Reminder Most Active List'
    data.loc[dec12 & (~seg_remind), 'Segments'] = data.loc[dec12 & (~seg_remind), 'Segment Name']
    data.loc[dec12 & (~seg_remind), 'Segments'] = data.loc[dec12 & (~seg_remind), 'Segments'].replace('[\(\)]', '', regex=True).astype(str)
    data.loc[dec12 & (~seg_remind), 'Segments'] = data.loc[dec12 & (~seg_remind), 'Segments'].str.replace('CAS SKU Email ', 'Has SKU-')
    data.loc[dec12 & (~seg_remind), 'Segments'] = data.loc[dec12 & (~seg_remind), 'Segments'].str.replace('CAS Has PID ', 'Has PID-')
    data.loc[dec12 & (~seg_remind), 'Segments'] = data.loc[dec12 & (~seg_remind), 'Segments'].str.replace('CAS Regular Incentive ', 'No PID-')
    data.loc[dec12 & (~seg_remind), 'Segments'] = data.loc[dec12 & (~seg_remind), 'Segments'].astype(str) + ' List'
    
    # Add reminder to Promo Name
    data['Promo Name'] = np.where(seg_remind, data['Promo Name']+' Reminder', data['Promo Name'])
    data['Promo Name'] = np.where(jun6 & data['Segment Name'].str.contains('Survey', case=True), data['Promo Name']+' Survey', data['Promo Name'])
    data['Promo Name'] = np.where(jun6 & data['Segment Name'].str.contains('With PID', case=True), data['Promo Name']+' Has PID', data['Promo Name'])
    
    return data
    
    
