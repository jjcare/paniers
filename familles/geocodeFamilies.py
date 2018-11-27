# -*- coding: utf-8 -*-
#
# geocode Families
#  uses data from locationiq.com (slow)
#
#   2018, John Carey


import json, string, time
import requests
import unicodedata
from urllib import request

# titles = [ 'famno', 'NIP', 'no', 'street', 'apt', 'pcode', 'telh', 'telalt', 'note' ]


def request2loc (address):
    url = "https://us1.locationiq.com/v1/search.php"
    data = {
        'key': '7e46d492aae59f',
        'q': address + ',Montreal,Quebec,Canada', # add query here : structured format also available (to research)
        'format':'json'
        }
    response = requests.get(google_url, params=data)
    j = json.loads(response.text)
    response.close()
    if 'error' in j:
        print('Response gives error: {}\n'.format(j['error']))

    # responses rated by importance. Find highest match and use it
    num,street = (address.split(' ')[0], address.split(' ')[-1].split(',')[0])
    candidates = [x for x in j if num in x['display_name'] and street in x['display_name']]
    if not candidates:
        print('No candidates found')
        return {}
    maximportance = max([x['importance'] for x in candidates])
    best = [x for x in candidates if x['importance'] == maximportance][0]

    return {'lat':best['lat'], 'lon':best['lon']}

def google2loc (address):
    google_api_key = 'AIzaSyCqJgDfAWRHX4iGg4Jbd3KU0uScQQps_lA'
    google_url = 'https://maps.googleapis.com/maps/api/geocode/json?address={},+Montreal,+Quebec,+Canada&key={}'
    response = request.urlopen(google_url.format(address.replace(' ','+'), google_api_key))
    j = json.loads(response.read().decode())
    if len(j) and 'error' not in j:
        lat = j['results'][0]['geometry']['location']['lat']
        lon = j['results'][0]['geometry']['location']['lng']
        return {'lat':lat, 'lon':lon}
    return {}

def strip_accents(s):
   return u''.join(c for c in unicodedata.normalize('NFD', s)
                  if unicodedata.category(c) != 'Mn') 
def F (x, y):
    try:
        return int(x) - int(y)
    except:
        return 0

def show():
    k = sorted(families.keys())
    for f in k:
        y = families[f]
        if 'lat' in y:
            print ('{}\t{}[{},{}]'.format(f, y['q'],y['lat'], y['lon']))

indata = open ('famrecs.tab', 'r', encoding='utf8').read()
# remove all accented characters
buf = strip_accents(indata)
buf = buf.split('\n')[:-1]

fams = []

for i in buf:
    t = i.split('\t')
    fams.append([t[0], '{} {}'.format(t[2],t[3])])  # number and street

fams = [x for x in fams if len(x)]

families = {}

service = 'google'  # or 'locationiq'

for fam in fams:
    q = fam[1]
    print('Checking {}'.format(q))
    if service == 'google':
        loc = google2loc(q)
    else:
        loc = request2loc(q)
    
    families[fam[0]] = {'q':q, 'lat':loc['lat'], 'lon':loc['lon']}
        
    time.sleep(.55)  # timeout counter for rate limits at google
    #print ('.', end='')
print ('done.\n')
show()


