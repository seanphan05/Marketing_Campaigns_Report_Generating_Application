from mailchimp3 import MailChimp
import pandas as pd
import config


mc_cre = config.mc_log()
API_KEY = mc_cre[0]
USER_NAME = mc_cre[1]
START_DATE = mc_cre[2]
END_DATE = mc_cre[3]

def get_mc():
    client = MailChimp(mc_api=API_KEY, mc_user=USER_NAME)
    # returns all the campaigns
    reports = client.reports.all(count=1000, before_send_time=END_DATE,
                                 since_send_time=START_DATE)
    details = client.campaigns.all(count=1000, before_send_time=END_DATE,
                                 since_send_time=START_DATE)
    report_data = reports['reports']
    detail_data = details['campaigns']
    
    # create list to store metrics
    camp_report = {}
    camp_detail = {}
    
    # store data in metric lists
    
    for i in range(0, len(report_data)):
        temp_metrics = []
        temp_metrics.append(report_data[i]['campaign_title'])
        temp_metrics.append(report_data[i]['emails_sent'])
        temp_metrics.append(report_data[i]['opens']['unique_opens'])
        temp_metrics.append(report_data[i]['opens']['open_rate']*100)
        temp_metrics.append(report_data[i]['clicks']['unique_subscriber_clicks'])
        temp_metrics.append(report_data[i]['clicks']['click_rate']*100)
        num_bounces = report_data[i]['bounces']['hard_bounces'] + report_data[i]['bounces']['soft_bounces']
        temp_metrics.append(num_bounces)
        temp_metrics.append(((report_data[i]['emails_sent'] - num_bounces)/report_data[i]['emails_sent'])*100)
        temp_metrics.append((num_bounces/report_data[i]['emails_sent'])*100)
        temp_metrics.append(report_data[i]['unsubscribed'])
        temp_metrics.append((report_data[i]['unsubscribed']/report_data[i]['emails_sent'])*100)
        temp_metrics.append(report_data[i]['abuse_reports'])
        temp_metrics.append((report_data[i]['abuse_reports']/report_data[i]['emails_sent'])*100)
        camp_report.update({report_data[i]['id']:temp_metrics})
    
    for j in range(0, len(detail_data)):
        temp_detail = []
        temp_detail.append((detail_data[j]['create_time'])[:10])
        temp_detail.append(detail_data[j]['tracking']['google_analytics'])
        if 'variate_settings' in detail_data[j]:
            temp_track = []
            temp_track.append(detail_data[j]['variate_settings']['combinations'][0]['id'] + '-'
                               + detail_data[j]['tracking']['google_analytics'])
            temp_track.append(detail_data[j]['variate_settings']['combinations'][1]['id'] + '-'
                               + detail_data[j]['tracking']['google_analytics'])
            
            # Add missing GA tracking code from JUNE 2019
            if detail_data[j]['tracking']['google_analytics'].find('EMAIL_JUN1_2019_NO_PID_MOST_ACTIVE') != -1:
                temp_track.append('418d924e90-EMAIL_JUN1_2019_NO_PID_MOST_ACTIVE')
            if detail_data[j]['tracking']['google_analytics'].find('EMAIL_JUNE2_SURVEY_2019_MOST_ACTIVE') != -1:
                temp_track.append('de1b13c48a-EMAIL_JUNE2_SURVEY_2019_MOST_ACTIVE')
            
            temp_detail.append(temp_track)
        else:
            temp_detail.append([detail_data[j]['id'] + '-'
                               + detail_data[j]['tracking']['google_analytics']])
        
        camp_detail.update({detail_data[j]['id']:temp_detail})
        
    
    camp_data = {key:(camp_detail[key] + camp_report[key]) for key in camp_detail}
    col_name = ['Launch Date', 'GA Tracking', 'GA Tracking Code', 'Campaign Title','Emails Sent', 'Unique Opens', 
                'Unique Open Rate (%)', 'Unique Clicks', 'Unique Click Rate (%)', 'Bounces', 'Deliverability Rate (%)',
                'Bounce Rate (%)', 'Unsub', 'Unsub Rate (%)', 'Spam', 'Spam Rate (%)']
    camp_df = pd.DataFrame(list(camp_data.values()), columns=col_name)
    
    
    # exculde test-campaign conditions:
    cond1 = camp_df['Campaign Title'].str.contains('test', case=False)
    cond2 = camp_df['Campaign Title'].str.contains('Once & Twice', case=True)
    
    # exclude test campaign
    new_df = pd.concat([camp_df.loc[(~cond1)], camp_df.loc[cond1 & cond2]])
    
    # create tracking list for MC-GA mapping
    track_list = new_df['GA Tracking Code'].to_list()
    ga_track = [item for sublist in track_list for item in sublist]
    return (new_df, ga_track)
