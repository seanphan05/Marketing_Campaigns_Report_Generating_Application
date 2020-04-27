"""Hello Analytics Reporting API V4."""

import argparse
import pandas as pd
from apiclient.discovery import build
import httplib2
from oauth2client import client
from oauth2client import file
from oauth2client import tools
import mailchimp_api
import config

ga_cre = config.ga_log()

SCOPES = ga_cre[0]
CLIENT_SECRETS_PATH = ga_cre[1] # Path to client_secrets.json file.
VIEW_ID = ga_cre[2]
START_DATE = ga_cre[3]
END_DATE = ga_cre[4]


def initialize_analyticsreporting():
  """Initializes the analyticsreporting service object.

  Returns:
    analytics an authorized analyticsreporting service object.
  """
  # Parse command-line arguments.
  parser = argparse.ArgumentParser(
      formatter_class=argparse.RawDescriptionHelpFormatter,
      parents=[tools.argparser])
  flags = parser.parse_args([])

  # Set up a Flow object to be used if we need to authenticate.
  flow = client.flow_from_clientsecrets(
      CLIENT_SECRETS_PATH, scope=SCOPES,
      message=tools.message_if_missing(CLIENT_SECRETS_PATH))

  # Prepare credentials, and authorize HTTP object with them.
  # If the credentials don't exist or are invalid run through the native client
  # flow. The Storage object will ensure that if successful the good
  # credentials will get written back to a file.
  storage = file.Storage('analyticsreporting.dat')
  credentials = storage.get()
  if credentials is None or credentials.invalid:
    credentials = tools.run_flow(flow, storage, flags)
  http = credentials.authorize(http=httplib2.Http())

  # Build the service object.
  analytics = build('analyticsreporting', 'v4', http=http)

  return analytics

def get_report(analytics):
  # Use the Analytics Service Object to query the Analytics Reporting API V4.
  return analytics.reports().batchGet(
      body={
        'reportRequests': [
        {
          'viewId': VIEW_ID,
          'dateRanges': [{'startDate': START_DATE, 'endDate': END_DATE}],
          'dimensions': [{'name':'ga:campaign'}],
          'dimensionFilterClauses': [
                  {'filters': [
                          {'dimensionName': 'ga:campaign',
                           'operator': 'IN_LIST',
                           'expressions': mailchimp_api.get_mc()[1]}]}],
          'metrics': [{'expression': 'ga:users'},
                      {'expression': 'ga:sessions'},
                      {'expression': 'ga:bounceRate'},
                      {'expression': 'ga:transactionsPerSession'},
                      {'expression': 'ga:transactions'},
                      {'expression':'ga:transactionRevenue'}]
        }]
      }
  ).execute()

def get_ga():
    # Create  empty lists that will hold our dimentions and sessions data
    dim = [] # dimensions
    val = []
    met = [] # metrics
    
    analytics = initialize_analyticsreporting()
    response = get_report(analytics)
    """Parses and prints the Analytics Reporting API V4 response"""
    for report in response.get('reports', []):
        columnHeader = report.get('columnHeader', {})
        dimensionHeaders = columnHeader.get('dimensions', [])
        metricHeaders = columnHeader.get('metricHeader', {}).get('metricHeaderEntries', [])
        rows = report.get('data', {}).get('rows', [])
    
    for row in rows:
      dimensions = row.get('dimensions', [])
      dateRangeValues = row.get('metrics', [])
    
      for header, dimension in zip(dimensionHeaders, dimensions):
        dim.append(dimension)
    
      for i, values in enumerate(dateRangeValues):
        for metricHeader, value in zip(metricHeaders, values.get('values')):
          val.append(round(float(value),2))
    met = [val[x:x+6] for x in range(0, len(val), 6)]
    
    df1 = pd.DataFrame(met, columns=['Users', 'Sessions', 'Bounce Rate', 'ECR (%)','Transactions','Revenue ($)']) 
    df1.insert(0,'GA Tracking Code', dim, True)
    return (df1)
