# -*- coding: utf-8 -*-
################################################################################
#
#  PANIERS DE NOËL (paniers2012.py)
#
#       John Carey, 2010, 2011, 2012, 2015

#   Take list (text extracted from Word) and make into useable formats
#
#  - get input document (in current folder) - fairly dirty data (put sep_char as extra column)
#  - create object structure from data, adding geolocation data and amounts
#  - output in desired formats (uses utf-8 for .sql files)
#
#  - added 2011: check for list of foyers - familles. If present and this year's
#    create Foyer class and use to assign foyers to families. Usually this may
#    be run later and the dataset replaced to include the foyers.
#
#  - 2012: total rewrite of program to be more robust with sloppy input (typos)
#          Also gives does lots of error checking and meaningful messages
#
#  - 2015: convert to Python 3 and all unicode
#
#  - 2017: ST-LOUIS comes out as ST-, LOUIS todo: check name processing routines (manually correct) - done
#
#################################################################################

import sys, string, re, urllib.request, json, time, codecs, datetime, os
fname = 'familles.txt'
outfiles = 'famrecs.tab famrecs.sql deprecs.tab deprecs.sql'.split()
three_mos = 60*60*24*28*3  # 3 months in seconds
debug = 0  # print debugging information (0 to go faster
testdata = 0   # when 1, skip location look-up (good for testing the input data)

# amounts for calculating certificates (global values presently)
base_amt = 60      # monoparental amount
couple_deduct = 10 # reduce by this if couple
depend_amt = 10    # amt for each dependent


#    
#  class structures
#


class Foyers: # foyer assignments

    def __init__(self):
        self.foyers = {}
        # chick if last year's list is still there
        if time.time() - os.stat("groceries.txt").st_mtime > three_mos:  # data is old
            print (">>>> Fichier 'foyers-familles.txt' est vieux. Foyers ne sont pas inclus.")
            return
        try:
            print ( "Élaboration des foyers en cours...")
            f = open('foyers-familles.txt','r')
            buf = f.read()
            buf = re.split('\r?\n', buf)
            if buf[0].find('Foyer') != 0:
                print ("Foyer header not found")
                return  # foyer should be first
            buf.pop(0)                            # don't count field names
            # use dict comprehension to populate foyer list (strip preceding 0s)
            self.foyers = {i.split('\t')[0]: list(map(lambda x: x.lstrip('0'),re.split('[^0-9]+',i.split('\t')[1]))) for i in buf if i.strip() != ''}
        except:
            print ("Foyer list failed")
            return

    def addFoyer (self, s):
        if len(s) == 1:
            self.foyers[s[0]] = ""
        else:
            self.foyers[s[0]] = re.split('[^0-9]+', s[1]) # accept any sep char

    def getFoyer (self, fam):
        # search for family number fam in foyer list. If more than one family for foyer, get index (eg. 203a)
        for foyer in self.foyers:
            if fam[0] == '0': fam = fam[1:]
            if fam in self.foyers[foyer] or '0'+fam in self.foyers[foyer]:
                if len(self.foyers[foyer]) == 1:
                    app = ''
                else:
                    app = 'abcde'[self.foyers[foyer].index(fam)]
                return foyer + app
        return ''

    def getAllFoyers (self):
        return sorted([ x for x in self.foyers ])

    def getFamily (self, foyer):
        if self.foyers.has_key(foyer):
            return foyer + ' : ' + ' + '.join(self.foyers[foyer])
        return ''
# end class foyer

class Geo:
    """Keeps track of geolocation values for addresses"""

    geohash = {}
    hashfname = "addresses.txt"
    REQ = "http://maps.googleapis.com/maps/api/geocode/json?address={}+Montreal+Canada&sensor=false"
    TRANS_ACCENTS = str.maketrans('ôöìéèêùàâçÉ', 'ooieeeuaacE')


    def __init__(self):
        # only read in hash table once
        if Geo.geohash: return
        # checks for hashed list of addresses and loads it
        if not os.path.exists(Geo.hashfname):  # create it
            f = open(Geo.hashfname, 'w', 1, 'utf-8')
            json.dump({},f)
            f.close()
        hp = open(Geo.hashfname, "r")
        # deserialize dict from hashes file  
        Geo.geohash = json.load(hp, parse_int=int)
        hp.close()

    def add(self, key, val):
        key = str(key)  # make sure key is string
        if not key in Geo.geohash:
            Geo.geohash[key] = val

    def __get__(self, instance, key):
        return Geo.geohash.get(key)
 
    def geolocate (self, addr):
        # google geolocate api request
        if testdata:
            return '00,00'
        if debug: print ('geolocate: ', addr)
        location = self.getLatLong(addr)
        if not location:
            # try without postcode
            location = self.getLatLong ( re.split(' [A-Z][0-9][A-Z] [0-9][A-Z][0-9]', addr)[0])
            if not location:
                # try just postcode
                location = self.getLatLong ( addr[-7:] )
                if not location:
                    print ('Warning: no result for geocode (%s)' % (addr,))
                    location = ''
        print ('.', end=' ')
        return location            

    def getLatLong (self, addr):
        # get google geolocate data
        addr = '+'.join(addr.split(' ')).translate(Geo.TRANS_ACCENTS)
        req = Geo.REQ.format(addr)
        if debug: print ('\nSearch location = "{}"'.format(addr))

        # check in address location table first
        if addr in Geo.geohash:
            return Geo.geohash[addr]
        else:
            print('+', end ='')
            try:
                ret = urllib.request.urlopen(req)
            except HTTPError:
                print('Geolocate server error. Retry job later.')
                return False
                
            j = json.loads(ret.read().decode())
            if len(j['results']) == 0:
                return False
            # result found, get lat and lng values
            loc = j['results'][0]['geometry']['location']
            lat = loc['lat']
            lng = loc['lng']
            location = str(lat) + ',' + str(lng)
            self.add( addr, location)
            time.sleep(.3)  # wait
            return location

    def writeGeo(self):
        # write out the updated hash table of address locations
        f = open(Geo.hashfname, 'w', encoding='utf-8')
        json.dump(Geo.geohash, f)
        f.close()

    def __delete__(self):
        self.writeGeo()

# end class Geo

class FamilyBuffer: # control the input buffer

    SPLIT_EXP = r'[^$]*'

    def __init__(self, s):
        # split input buffer into families (sep_char used to separate)
        
        self.bufcopy = s                  # backup copy of original buffer for debug
        buf = self.normalize(s)
        if '$' not in buf:
            print ("No split chars ($) found in input file. Terminating.")
            raise SystemError ( "No split chars found in input file.")
        fam = re.findall(FamilyBuffer.SPLIT_EXP, buf, re.DOTALL)  # get family chunks
        
        # sort list and remove empty families
        fam = sorted(map(str.strip, fam))    
        while fam[0] == '':
            fam.pop(0)  

        # remove entries that don't begin with a number
        while not fam[-1].split('\n',1)[0].isdigit():
            #print ("**",fam[-1])  # debug
            fam.pop()

        self.fam = sorted(fam, key=lambda x: int(x.split('\n',1)[0]))

        # do some initial verifying of input data
        if len(self.fam) == 0: raise ValueError ('No families found. Did you forget "$" as separator?')
        
        r = input((u"J'ai trouvé %d familles. Est-ce juste (o/n)? " % (len(self.fam,))))
        if r[0] == 'n':
            sys.exit()  # stop right here if data looks suspicious

    def normalize(self, buf):
        # take care of all normalizations of input (try to correct errors at the source)
        # transformations: 1. use linux newlines; 2. watch out for stray periods(x2); 3. at least 3 spaces before sex of adults;
        #                   4. multiple spaces become \t; 5. reduce double spaces; 6. convert tabs to newlines
        #                  7. eliminate blank lines
        transformations = [ (r'\r\n',r'\n'), (r'\.([A-Z.])',r' \1'),(r'\.([A-Z.])',r' \1'),
                            (r' ([MF]) ?\b', r'    \1'),(r'   +', r'\t'), (r'  ', r' '), (r'\t', r'\n'), (r'\n\n', '\n') ]
        for trans in transformations:
            buf = re.sub(trans[0], trans[1], buf)
        return buf.strip()
    
    def display(self,family):  # for debug on idle
#        f = family.decode('latin-1').split('\n')
        f = family.split('\n')
        sys.stderr.write('\nFamille no. ' + f[0] + '\n')
        for i in f[1:]: print ('\t', i)

    def showFams(self):
        c= self.genfam()  # get generator to iterate over family list
        print ("showFam - '.' to end, or fam number.\n")
        for i in c:
            x = input('? ')
            if x == '.': break
            if x.isdigit() and int(x) < len(self.fam):
                print (self.display(self.fam[int(x)-1]))
            else: print (self.display(i))

    def genfam(self): # generate the next family (used in iterator situations)
       for fam in self.fam:
           yield fam.strip()
           

#end class Buffer
        
class FamilyRecord:
    """Famille: Famno Person sexe [Person sexe ...] nip street [CND] [RA=...] pcode tel [tel ...] [Child{n} sexe{n} age{n}"""

    # class constant definitions
    UPPER_NAME_PAT = r"[A-ZÉÈÙÂÄÀÇÎÏÔÖ -][']?[A-ZÉÈÙÂÄÀÇÎÏÔÖ -]{2,}\s" # corrected for false results (ST-LOUIS = ST, LOUIS and N'Doube = N', Doube)
    SEX_PAT = r'^([MF])$'
    ADD_PAT = r'^([0-9]+? [^0-9]+( *# ?[0-9]+)?)'
    ADDR_PAT = r'^([0-9]{2,5} ?(?:[A-F] )?) ?([^0-9#]+) *#? ?([0-9]+)?'  # grab all components of address at once
    PCODE_PAT = r'^H\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}$'  # for MTL. For generic: r'^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}$'
    TEL_PAT = r'^(\?|\*?[-0-9]{7,})'
    UPPER_PAT = r"[A-ZÉÈÙÂÄÀÇÎÏÔÖ -][']?[A-ZÉÈÙÂÄÀÇÎÏÔÖ -]{2,}\s"
    
    def __init__(self, f, foyers):
        # expect items on separate lines (min 7 items obligatory)
        fam = f.split('\n')
        if len(fam) < 7:
            print ("FamilyRecord error: \nInput given: ", fam)
            raise IndexError ( "Il faut plus d'items pour une famille.")
        
        if fam[0].isdigit():
            self.num = fam.pop(0)
        
        else:
#            raise ValueError, 'Famille id non trouvé'.decode('latin-1')
            raise ValueError ('Famille id non trouvé')
            
        self.ord = ''
        self.montant = 0
        self.setMembers(fam)
        self.setNIP(fam)
        self.setAddress(fam)
        self.setRA(fam)
        self.setTels(fam)
        self.setChildren(fam)
        self.setMontant()
        self.setFoyer(foyers.getFoyer(self.num))  # if available, include foyer 

    def setMembers(self, fam):
        # sets the Demandeur and other adults
        if self.ord != '': raise SystemError ("setMembers: Wrong call order in FamilyRecord. Previous: %s" % (self.ord,))
        self.members = []
        name, sex = self.getNextMember(fam)
        
        while name:
            
            if len(self.members) == 0:   # demandeur is always first name
                rel = 'Demandeur'
            elif re.search('\d{2}', name):  # age given in name indicates other person
                rel = 'Autre'
                if not (20 < int(re.search('\d{2}', name).group(0)) < 60): self.montant += depend_amt
            else:
                rel = 'Conjoint'
                self.montant -= couple_deduct
                
            nom, prenom = self.getNameParts(name)
            self.members.append({ 'nom': nom, 'prenom': prenom, 'sexe': sex, 'relation':rel })
            name, sex = self.getNextMember(fam)
        
        self.ord = sys._getframe().f_code.co_name # chain function name


    def getNextMember(self, fam):
        # returns a tuple of name, sex
               
        if re.match(FamilyRecord.UPPER_NAME_PAT, fam[0],re.IGNORECASE) and re.match(FamilyRecord.SEX_PAT, fam[1].strip()):
            return (fam.pop(0).strip(), fam.pop(0).strip())
        return (None, None)


    def getNameParts(self, name):
        w = re.match(FamilyRecord.UPPER_PAT, name)
        if not w:
            if ' ' in name:
                nom, prenom = name.split(' ',1) # consider first word as name
                nom = nom.upper()   # todo: make this international (locale dependent)
            else:
                nom = name
                prenom = ''
        else:
            nom = w.group(0).strip()
            prenom = name[w.end():]
        return (nom, prenom)


    def setNIP(self, fam):
        # parses and sets the street address
        if self.ord != 'setMembers': raise SystemError ("setNIP: Wrong call order in FamilyRecord. Previous: %s" % (self.ord,))
        if not fam[0].isdigit(): raise ValueError ('NIP not numeric: %s' % (fam[0],))
        self.nip = fam.pop(0)

        self.ord = sys._getframe().f_code.co_name # chain function name


    def setAddress(self, fam):
        # parses and sets the street address
        if self.ord != 'setNIP': raise SystemError ( "setAddress: Wrong call order in FamilyRecord. Previous: %s" % (self.ord,))

        if not fam[0][:3].strip().isdigit(): raise ValueError ("Address expected, not found: %s" % (fam[0],))

        w = re.match(FamilyRecord.ADDR_PAT, fam[0].strip())
        if w:
            adparts = w.groups()
            addr = {}
            addr['number'] = adparts[0].strip()
            rue = adparts[1].strip()
            addr['rue'] = rue if rue[-1] != '-' else rue[:-1].strip()
            if 'app.' in addr['rue'][-4:]: addr['rue'] = addr['rue'][:-4].strip()
            addr['appt'] = adparts[2].strip() if adparts[2] else 'null'
        else:
            raise ValueError ('Invalid address: %s' % (fam[0],))

        fam.pop(0)

        self.address = addr

        for i in range(len(fam)):
            if re.match(FamilyRecord.PCODE_PAT, fam[i].upper()):
                self.pcode = fam[i].upper()
                del fam[i]
                break
        
        geo = Geo()  # get a Geo instance 
        self.location = geo.geolocate(' '.join([addr['number'],addr['rue'],self.pcode]))

        self.ord = sys._getframe().f_code.co_name # chain function name



    def setRA(self,fam):
        # set RA (restrictions alimentaires)
        if self.ord != 'setAddress': raise SystemError ("setRA: Wrong call order in FamilyRecord. Previous: %s" % (self.ord,))

        RA_PAT = r'RA *= *.+'
        if fam[0].strip().upper() == 'CND': fam.pop(0)  # skip CND
        ra = re.search(RA_PAT, fam[0])
        if ra:
            self.ra = ra.group(0)
            fam.pop(0)
        else:
            self.ra = ''
            
        self.ord = sys._getframe().f_code.co_name # chain function name


    def setTels(self,fam):
        # sets the telephone numbers
        if self.ord != 'setRA': raise SystemError ("setTels: Wrong call order in FamilyRecord. Previous: %s" % (self.ord,))
        self.tel = []
        while re.match(FamilyRecord.TEL_PAT, fam[0].strip()):
            self.tel.append(self.formatTelno(fam.pop(0)))

        if len(self.tel) == 0 : raise SystemError ("setTels: No telephone numbers found in family #%s: %s" % (self.num, str(fam)))
        
        self.ord = sys._getframe().f_code.co_name # chain function name


    def formatTelno(self, t):
        if t[0] == '*': t = '438' + t[1:]
        t = re.sub('[^0-9]', '', t)
        if len(t) < 10: t = '514' + t
        return format(int(t[:-1]), ",").replace(",", "-") + t[-1]  # not mine - can't think of better just now


    def setChildren(self,fam):
        # parse and set children, sexes and ages
        if self.ord != 'setTels': raise SystemError ("setChildren: Wrong call order in FamilyRecord. Previous: %s" % (self.ord,))

        self.children = []
        if ' '.join(fam).strip() != '':
            cnames, sexes, ages = [],[],[]
            try:
                while not re.match(FamilyRecord.SEX_PAT, fam[0].strip()):
                    cnames.append(fam.pop(0))
                while re.match(FamilyRecord.SEX_PAT, fam[0].strip()):
                    sexes.append(fam.pop(0))
                while len(fam):
                    age = fam.pop(0)
                    if age[0] == "0":  # don't use leading zero
                         age = age[1:]
                    ages.append(format_age(age))
            except:
                pass
            if len(cnames) != len(sexes) != len(ages):
                raise IndexError ("setChildren: unmatched child records in family %s.\n%s" % (self.num,cnames[-1]))

            # finally, assign the found children to the family record
            for i in range(len(cnames)):
                 self.children.append( { 'prenom':cnames[i], 'sexe':sexes[i], 'age':ages[i], 'relation': 'Enfant'})
 
        self.ord = sys._getframe().f_code.co_name # chain function name


    def setFoyer(self, num):
        # set foyer
        self.foyer = num


    def setMontant(self):
        # based on family membership, calculate amount to be given
        if self.ord != 'setChildren': raise SystemError ("setMontant: Wrong call order in FamilyRecord. Previous: %s" % (self.ord,))
        self.montant += base_amt + (depend_amt * len(self.children))
        del self.ord  # finished with order control


    # other methods to display, get and transform family records


    def showFamily (self):
        sys.stderr.write( '\n' + self.num + '  ---------------------\n') # colour heading
        for i in 'members nip showAddress() pcode location tel ra showChildren() foyer montant'.split():
            print ('\t' + i.capitalize() + ':', eval('self.' + i))

    def showAddress (self):
        ad = self.address
        pd = '#' if ad['appt'] != 'null' else ''
        return '%s %s %s%s' % (ad['number'], ad['rue'], pd, ad['appt'])

    def showChildren (self):
        ret = '\n'
        for child in self.children:
            ret = ret + ('\t\t%s\t%s  %s ans\n' % (child['prenom'],child['sexe'],child['age']))
        return ret
        
    def getFamilyRec(self):
        addr = '%(number)s\t%(rue)s\t%(appt)s' % self.address
        tels = self.tel[0] + '\t' + ','.join(self.tel[1:])
        return '%s\t%s\t%s\t%s\t%s\t%s\t%d\t%s\t%s\n' % (self.num, self.nip, addr, self.pcode, tels, self.ra, self.montant, self.foyer, self.location)

    def getFamilySql(self):
        addr = '"%(number)s","%(rue)s","%(appt)s"' % self.address
        if len(self.tel) == 1:
            self.tel.append("")
        tels = self.tel[0] + '","' + ','.join(self.tel[1:])
        return '(%s,%s,%s,"%s","%s","%s","%d", "%s", "%s"),\n' % (self.num, self.nip, addr, self.pcode, tels, self.ra, self.montant, self.foyer, self.location)
        
    def getDependentsRec(self):
        rec = ''
        for i in self.members:
            rec = rec + self.nip + '\t'
            rec = rec + '%(relation)s\t%(nom)s\t%(prenom)s\t\t%(sexe)s\n' % i
        for i in self.children:
            rec = rec + self.nip + '\t'
            rec = rec + '%(relation)s\t\t\t%(prenom)s\t%(age)s\t%(sexe)s\n' % i
        return rec

    def getDependentsSql(self):
        rec = '('
        for i in self.members:
            rec = rec + self.nip + ','
            rec = rec + '"%(relation)s","%(nom)s","%(prenom)s",null,"%(sexe)s"),\n(' % i
        for i in self.children:
            rec = rec + self.nip + ','
            rec = rec + '"%(relation)s",null,"%(prenom)s","%(age)s","%(sexe)s"),\n(' % i
        return rec[:-1]

# end class FamilyRecord

def buildFamilies(buf, foyers):  # pass through buf, building array of family instances (2012: vastly simplified)
    fams = []
    g = Geo()
    flist = buf.genfam()  # get iterator (generator)
    for f in flist:
        fams.append(FamilyRecord(f, foyers))
        if debug:
            fams[-1].showFamily()  # print for debugging purposes
    g.writeGeo()
    return fams

def doGroceries():  # get the grocery list and process
    if time.time() - os.stat("groceries.txt").st_mtime <= three_mos:  # data is recent
        groceries = open("groceries.txt").read().split('\n')
        outbuf = "use shalom_cnd;\nSET character_set_client = utf8;\nTRUNCATE TABLE groceries;\nINSERT INTO groceries (`foyer`, `item1`, `item2`) VALUES "
        for grocery in groceries:
            if grocery.strip():
                foyer, item1, item2 = grocery.split('\t')
                outbuf += '\n({},"{}","{}"),'.format(foyer,item1,item2)
        outbuf = outbuf[:-1] + ';'
        f = open("groceries.sql","w")
        f.write(outbuf)
        f.close()

# for age conversion
conv = {'¾': 0.75, '½': 0.5, '¼': 0.25, '⅓': 0.33}    

def format_age(a):
    # format_age: convert ½, ¼, ¾, ⅓, etc to month values
    # return age else convert to month values if fractions present (unless over 2yrs)
    if a[-1] not in conv: return a
    comp = list(a)
    frac = round(conv.get(comp.pop()) * 12)
    units = '0' + ''.join(comp)
    if int(units) > 2:
        return str(int(units) + frac)
    return '{} mos'.format( round((int(units) + frac) * 12))
    
def toc():
    return datetime.datetime.utcnow()

def elapsed(td):
    m,s = divmod(td.seconds,60)
    return '%d minutes %d seconds' % (m,s)


#########################################################################################
#
#                            main program logic
#
#########################################################################################
t_start = toc()

doGroceries()

buf = FamilyBuffer(open(fname,'r', encoding='utf-8').read())


foyers = Foyers()              # get foyer list - if present

families = buildFamilies(buf, foyers)  # all the work done here

# output results in various formats

out = [ '','','','']
total = 0

# sql famrecs.sql  deprecs.sql
insertcmd = {'famrecs.sql':'USE shalom_cnd;\nSET character_set_client = utf8;\nTRUNCATE TABLE families;\nINSERT INTO families (`famno`, `nip`, `number`, `street`, `appt`, `code`, `tel_h`, `tel_alt`, `note`, `montant`, `foyer`, `location`) VALUES ',
             'deprecs.sql':'USE shalom_cnd;\nSET character_set_client = utf8;\nTRUNCATE TABLE dependents;\nINSERT INTO dependents (`nip`, `relation`, `nom`, `prenom`, `age`, `sexe`) VALUES ' }


for f in families:
    out[0] += f.getFamilyRec()
    out[1] += f.getFamilySql()
    out[2] += f.getDependentsRec()
    out[3] += f.getDependentsSql()
    total += f.montant

for i in range(len(out)):
    f = open(outfiles[i], 'w', 1, 'utf-8')
    if 'sql' in outfiles[i]:
        out[i] = insertcmd[outfiles[i]] + out[i][:-2] + ';'
    f.write(out[i])
    f.close()

print ('\n\nDone. Outputs are in', ', '.join(outfiles), '.\n')
print ('Total needed for this year\'s collection: %0.2d $\n' % (total,))
print ('Time:', time.strftime('%X - %x', time.localtime()))
# get the elapsed time
t_stop = toc()
print (elapsed(t_stop - t_start))



