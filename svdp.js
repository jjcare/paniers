// put cool javascript functions here.
//# -*- coding: utf-8 -*-
var niplist = [];
var chk;

function activateButtons (element) {
	var buts = element.down('ul').childElements();
	var acts = $w('add modify delete');
	buts.each ( function (el, index) {
		el.down('img').observe ('click', eval(acts[index]+'Family'));
	});
}

var SelectItem = Class.create ({
	initialize: function ( label, optvals ) {
		this.label = label;
		this.optvals = optvals;
	},

	getElname: function () {
		return this.label;
	},

	getHtml: function() {
		if ( ! this.optvals instanceof Array) {
			this.optvals = new Array (this.optvals);
		}
		var ret = '<select name="' + this.label + '">\n';
		for (i = 0; i < this.optvals.length; i++) {
			ret = ret + '<option name="' + this.optvals[i] + '">' + this.optvals[i] + '</option>\n';
		}
		ret = ret + '</select>';
		return ret;
	}
});  /* SelectItem */

function setAlternateRows (element) {
	var head = $(element).down('thead');
	var body = element.down('tbody');
	if ( head && body ) {
		var rows = body.childElements();
		rows.each(function ( row, index) {
			row[(1 == index % 2 ? 'add' : 'remove' ) + 'ClassName' ]('alternate');
			});
	}
}

var selects = [
	new SelectItem ('relation', ['Demandeur', 'Conjoint', 'Enfant', 'Autre' ] ),
	new SelectItem ('sexe', ['M', 'F'] )
];

function confirmForm (e) {
	var form1 = e.findElement('input');
	// alert(form1.value + ' ' + form1.inspect());
	if (form1.value == 'supprimer' )  {
		var r = confirm ('Voulez-vous réelement supprimer cette famille?');
		if (!r) {
			e.stop();
		}
		return r;
	}
	return true;
}

function checkForm (f, els) {
	// alert (els);
	if (!f.nip.disabled && niplist.indexOf(f.nip.value) >= 0) {
		alert ('Une famille avec ce NIP est déjà présente dans le système.');
		$('nip').addClassName('error');
		$('nip').activate();
	} else if (f.nip.value < 1) {
		alert ('NIP manquant ou invalide.');
		$('nip').activate();
	} else {
		$('nip').removeClassName('error');
	}

}

function submitForm(e) {
	var btn = e.Element();
	if (btn.value == 'Ajouter' || btn.value == 'Modifier') {
		alert ('submit requested: ' + btn.value);
		e.stop;
	}
}

document.observe ('dom:loaded', function() {
			if ( $('list') ) {
				setAlternateRows ($('list'));
				document.observe('click', confirmForm);
			}
			/*activateButtons($('list')); */
			if ($('famform') ) {
				chk = new Form.EventObserver($('famform'), checkForm);
				if (!chk) alert ('Cannot set observer');
				niplist = $w($('nips').value);
				if ($('modform')) {
					$('nip').disable();
				} else {
					$('nip').focus();
				}
			}

		}
	);

/***
 * Excerpted from "Prototype and script.aculo.us",
 * published by The Pragmatic Bookshelf.
 * Copyrights apply to this code. It may not be used to create training material, 
 * courses, books, articles, and the like. Contact us if you are in doubt.
 * We make no guarantees that this code is fit for any purpose. 
 * Visit http://www.pragmaticprogrammer.com/titles/cppsu for more book information.
***/

// Borrowed from script.aculo.us' effects.js...
Element.addMethods({
  collectTextNodes: function(element) {  
    return $A($(element).childNodes).collect( function(node) {
      return (node.nodeType==3 ? node.nodeValue : 
        (node.hasChildNodes() ? Element.collectTextNodes(node) : ''));
    }).flatten().join('');
  } 
});



var TableSorter = Class.create({
  initialize: function(element) {
    this.element = $(element);
    this.sortIndex = -1;
    this.sortOrder = 'asc';
    this.initDOMReferences();
    this.initEventHandlers();
  }, // initialize

  initDOMReferences: function() {
    var head = this.element.down('thead');
    var body = this.element.down('tbody');
    if (!head || !body)
      throw 'Table must have a head and a body to be sortable.';
    this.headers = head.down('tr').childElements(); 
    this.headers.each(function(e, i) { 
      e._colIndex = i;
    });
    this.body = body;
  }, // initDOMReferences

  initEventHandlers: function() {
    this.handler = this.handleHeaderClick.bind(this); 
    this.element.observe('click', this.handler);
  }, // initEventHandlers



  handleHeaderClick: function(e) {
    var element = e.element();
    if (!('_colIndex' in element)) {
      element = element.ancestors().find(function(elt) { 
        return '_colIndex' in elt;
      });
      if (!((element) && '_colIndex' in element))
        return;
    }
    this.sort(element._colIndex);
  }, // handleHeaderClick



  adjustSortMarkers: function(index) {
    if (this.sortIndex != -1)
      this.headers[this.sortIndex].removeClassName('sort-' +
        this.sortOrder);
    if (this.sortIndex != index) {
      this.sortOrder = 'asc';
      this.sortIndex = index;
    } else
      this.sortOrder = ('asc' == this.sortOrder ? 'desc' : 'asc');
    this.headers[index].addClassName('sort-' + this.sortOrder);
  }, // adjustSortMarkers

  sort: function(index) {
    this.adjustSortMarkers(index);
    var rows = this.body.childElements();
    rows = rows.sortBy(function(row) { 
      return row.childElements()[this.sortIndex].collectTextNodes(); 
    }.bind(this));
    if ('desc' == this.sortOrder)
      rows.reverse();
    rows.reverse().each(function(row, index) { 
      if (index > 0)
        this.body.insertBefore(row, rows[index - 1]);
    }.bind(this));
    rows.reverse().each(function(row, index) {
      row[(1 == index % 2 ? 'add' : 'remove') + 'ClassName']('alternate'); 
    });
  } // sort
}); // TableSorter



document.observe('dom:loaded', function() {
  $$('table').each(function(table) { new TableSorter(table); }); 
});


