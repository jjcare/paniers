# -*- coding: cp1252 -*-
#
#  Paniers de Noël
#
#   Take list (text extraction from Word) - make useable format
#
#  - get input document (in current folder) - fairly dirty data
#  - create object structure from data
#  - output in desired format
#
import sys, string, re
fname = 'familles.txt'
# patterns
tel_pat = r'^(\?|\*?[0-9]{3}-[0-9]{4})'
add_pat = r'^([0-9]+? [^0-9]+( *# ?[0-9]+)?)'
nam_pat = r'^([^0-9]+? .+? +[MF])'
sex_pat = r'^([MF])$'
# sql
insert_families = 'USE shalom_cnd;\nSET character_set_client = utf8;\nINSERT INTO families (`famno`, `nip`, `number`, `street`, `appt`, `code`, `tel_h`, `tel_alt`, `note`) VALUES '
insert_dependents = 'USE shalom_cnd;\nSET character_set_client = utf8;\nINSERT INTO dependents (`nip`, `relation`, `nom`, `prenom`, `age`, `sexe`) VALUES '
#
def curdirfile(fname, mode):
    #print sys.argv
    if 'win' in sys.platform:
        sep = '\\'
    else:
        sep = '/'
    args = string.split(sys.argv[0], sep)
    mydir = string.joinfields(args[:-1], sep) + sep
    print mydir
    return open(mydir+fname, mode)

#    
#  class structure
#
class Family:
    def __init__(self, num):
        self.num = num
        self.members = []
        self.tel = []
        self.children = []
        self.ra = ''

    def addMember(self, s):
        w = s.split()
        if len(self.members) == 0:
            rel = 'Demandeur'
        elif len(self.members) == 1:
            rel = 'Conjoint'
        else:
            rel = 'Autre'
        name = { 'nom': string.joinfields(w[:-2]), 'prenom': w[-2], 'sexe': w[-1], 'relation':rel }
        self.members.append(name)

    def setAddress(self, s):
        w = s.split()
        addr = {}
        try:
            del w[w.index('#')]
            addr['appt'] = w[-1]
            del w[-1]
        except:
            addr['appt'] = 'null'
        addr['number'] = w[0]
        addr['rue'] = string.joinfields(w[1:])
        self.address = addr
        
    def addTel (self, s):
        self.tel.append(s)

    def addChild (self, s):
        child = { 'prenom':s, 'sexe':None, 'age':None, 'relation': 'Enfant'}
        self.children.append(child)

    def showFamily (self):
        print self.num
        print '\t', self.members
        print '\tNIP: ', self.nip
        print '\t', self.address
        print '\t', self.pcode
        print '\t', self.tel
        print '\t', self.children

    def getFamilyRec(self):
        addr = '%(number)s\t%(rue)s\t%(appt)s' % self.address
        tels = string.joinfields(self.tel, '\t')
        return '%s\t%s\t%s\t%s\t%s\t%s\n' % (self.num, self.nip, addr, self.pcode, tels, self.ra)

    def getFamilySql(self):
        addr = '"%(number)s","%(rue)s","%(appt)s"' % self.address
        if len(self.tel) == 1:
            self.tel.append("")
        tels = string.joinfields(self.tel, '","')
        return '(%s,%s,%s,"%s","%s","%s"),\n' % (self.num, self.nip, addr, self.pcode, tels, self.ra)
        
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


class Buffer: # control the input buffer

    def __init__(self, s):
        self.buf = s.strip()
        self.buf = re.sub('\t', '\n', self.buf)
        self.buf = self.buf.split('\n')
        self.index = 0

    def next(self):
        try:
            while self.buf[self.index].strip() == '':
                self.index = self.index + 1
        except:
            return False
        n = self.buf[self.index]
        self.index = self.index + 1
        return n
        
def buildFamilies(buf):  # pass through buf, building array of family instances
    fams = []
    x = buf.next()
    while x:
        fam = Family(x)
        # names next (1 or 2)
        x = buf.next()
        while re.search( nam_pat, x):
            fam.addMember(x)
            x = buf.next()
        fam.nip = x  # nip is next
        fam.setAddress( buf.next() )
        x = buf.next()
        if x[0:2] == 'RA':  # restrictions
            fam.ra = x
            x = buf.next()
        fam.pcode = x
        x = buf.next()
        while re.search(tel_pat, x):
            fam.addTel (x)
            x = buf.next()
        while not re.search(sex_pat, x):
            fam.addChild(x)
            x = buf.next()
        numchildren = range(len(fam.children))
        for i in numchildren:
            fam.children[i]['sexe'] = x
            x = buf.next()
        for i in numchildren:
            fam.children[i]['age'] = x
            try:
                x = buf.next()
            except:
                pass
        fams.append(fam)
        # fam.showFamily()
    return fams


#
# main program
#
f = curdirfile(fname,'r')
buf = Buffer(f.read())
f.close()

families = buildFamilies(buf)
familyrecs = ''
familysql = ''
dependrecs = ''
dependsql = ''

for f in families:
    familyrecs = familyrecs + f.getFamilyRec()
    familysql = familysql + f.getFamilySql()
    dependrecs = dependrecs + f.getDependentsRec()
    dependsql = dependsql + f.getDependentsSql()

f = curdirfile('famrecs.tab', 'w')
f.write(familyrecs)
f.close()
f = curdirfile('deprecs.tab', 'w')
f.write(dependrecs)
f.close()

familysql = insert_families + familysql[:-2] + ';'
f = curdirfile('famrecs.sql', 'w')
f.write(familysql)
f.close()
dependsql = insert_dependents + dependsql[:-2] + ';'
f = curdirfile('deprecs.sql', 'w')
f.write(dependsql)
f.close()


