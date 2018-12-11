# -*- coding: utf-8 -*-
#
# getmap.py
#
# produce map with directions from CND to location and save as png image
#  - 2 requests must be made: one for the directions (path) and another for
#    the map graphic and path overlay.
#
#
#  John Carey, 2016

import urllib.parse
import json, re
import sys, time
import unicodedata
from urllib import request


locationiq_token = '7e46d492aae59f'

mapquestkey = 'gc6PFJn0kE9Etcg78XviilafHjbyTc4e'
cndlocation = "45.493415,-73.620506" # 3791 Queen Mary

# using Mapquest (openmap) as map source (15000 requests per month free)

openmapUrl = "http://open.mapquestapi.com/staticmap/v5/map"

map_values = { 'key' : mapquestkey,
               #'zoom' : '15',
               'start': cndlocation + '|marker-start',
               'end': '',
               'size' : '650,700',
               'format' : 'png',
               'type' : 'light',
               'scalebar' : 'false',
               #'routeColor' : '99ff6688',
               'routeColor' : 'FFFF0088',
               'routeWidth' : '6',
               'session' : ''
               }


openmapDirectionReq = "http://www.mapquestapi.com/directions/v2/route"

dir_values = { 'key': mapquestkey,
               'from': cndlocation,
               'to': '',
               'outFormat': 'json',
               'locale': 'fr_CA',
               'ambiguities': 'ignore',
               'routeType': 'shortest',
               'unit': 'k',
               'doReverseGeocode': 'false',
               'enhancedNarrative': 'false',
               'avoidTimedConditions': 'true',
               'sideOfStreetDisplay': 'false' }

def strip_accents(s):
   return u''.join(c for c in unicodedata.normalize('NFD', s)
                  if unicodedata.category(c) != 'Mn') 

def makeMap(addr, longlat):
    # generate and save the map of given address and coordinates
    
    dir_values['to'] = longlat  # set destination
    data = urllib.parse.urlencode(dir_values) # pack params for get request
    # send directions request and retrieve data (only need sessionId)
    ret = request.urlopen(openmapDirectionReq + '?' + data)
    page = ret.read()
    # returned data is json - load json as dict
    jdata = json.loads(page.decode())

    # check return data present
    if not jdata['route'].get('sessionId'):
        print ('No map found for "{}" --> {}.\n'.format(addr, jdata['route']))
        return

    # set params for map request (sessionId needed to get directions path)
    bb = jdata['route'].get('boundingBox')
    if bb:
       map_values['boundingBox'] = ','.join(['{},{}'.format(bb[x].get('lat'),bb[x].get('lng')) for x in bb])
   
    map_values['end'] = longlat + "|marker-end"
    #map_values['session'] = jdata['route']['sessionId']

    mdata = urllib.parse.urlencode(map_values)

    ret = request.urlopen(openmapUrl + '?' + mdata)
    img = ret.read()

    # written directions:
    #    for i in jdata['route']['legs'][0]['maneuvers']:
    #        print('- {0} ({1:0.1f}km)'.format(i['narrative'], i['distance']))

    # maps dir is in web level above
    fname = '../maps/{}.png'.format(strip_accents(''.join(addr.split())))
    
    with open(fname, 'wb') as f:
        f.write(img)
    print ('Image {} sauvegardée.'.format(fname))
    return

def extractAddress (famrec):
    try:
        items = famrec.split('\t')
        return (' '.join(items[2:4]), items[-1])
    except:
        print('Adresse introuvable : {}'.format(famrec))
        return False

def geolocate (address):
   google_api_key = 'AIzaSyCqJgDfAWRHX4iGg4Jbd3KU0uScQQps_lA'
   google_url = 'https://maps.googleapis.com/maps/api/geocode/json?address={},+Montreal,+Quebec,+Canada&key={}'
   response = request.urlopen(google_url.format(address.replace(' ','+'), google_api_key))
   j = json.loads(response.read().decode())
   if len(j) and 'error' not in j:
      lat = j['results'][0]['geometry']['location']['lat']
      lon = j['results'][0]['geometry']['location']['lng']
      return '{},{}'.format(lat,lon)
   return ''

################################################
###                 M A I N                  ###
###                                          ###

print('Production de cartes avec directions\n\n')


try:
    
    mode = input('Recherche (s)imple ou (t)ous? ')
    if mode == '': exit(0)
    if mode[0].lower() == 's':
        addr = input("Saisir l'adresse : ")
        while addr != '':
            makeMap (addr, geolocate(addr))
            addr = input("Saisir l'adresse suivante : ")
        exit(0)
except:
    exit(0)


# pass through all adresses and make fresh maps

try:
    
    with open('famrecs.tab',encoding='utf-8') as f:
        famrecs = f.read().split('\n')

    for famrec in famrecs:
        if famrec.strip() == '': continue
        addr, longlat = extractAddress (famrec)
        print('Finding',addr, ':', longlat)
        makeMap(addr, longlat)
        time.sleep(.15)  # timeout counter for rate limits at google


except Exception as e:
    print('Problème rencontré. Terminant...\n{}'.format(str(e)))
    print(sys.exc_info())

else:
    print('\n*** Fin de programme. {:d} cartes produites.\n'.format(len([f for f in famrecs if f.strip()])))

print ('Time:', time.strftime('%X - %x', time.localtime()))




